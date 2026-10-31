(function () {
    'use strict';
    function init() {
        var article = document.querySelector('.artshow.content');
        var words = article && article.querySelector('.words');
        if (!words || article.querySelector('.article-toc-rail')) return;
        var headings = Array.from(words.querySelectorAll('h2,h3')).filter(function (h) {
            return h.textContent.trim() && !h.closest('pre,code,.mermaid,.katex,[hidden]');
        });
        if (!headings.length) return;
        var reserved = new Set(Array.from(document.querySelectorAll('[id]')).map(function (el) { return el.id; }));
        var serial = 0;
        headings.forEach(function (h) {
            if (h.id && document.getElementById(h.id) === h) return;
            var id;
            do { id = 'NuliBlog-section-' + (++serial); } while (reserved.has(id));
            h.id = id;
            reserved.add(id);
        });
        function uniqueId(base) {
            var id = base, n = 0;
            while (reserved.has(id) || document.getElementById(id)) id = base + '-' + (++n);
            reserved.add(id);
            return id;
        }
        var icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M9 6h12M9 12h12M9 18h12M3 6h.01M3 12h.01M3 18h.01"/></svg>';
        var rail = document.createElement('div');
        rail.className = 'article-toc-rail';
        var panel = document.createElement('div');
        panel.className = 'article-toc-panel';
        panel.id = uniqueId('NuliBlog-article-toc');
        var labelId = uniqueId('NuliBlog-toc-label');
        panel.setAttribute('aria-labelledby', labelId);
        panel.innerHTML = '<div class="article-toc-heading">' + icon + '<strong>文章目录</strong><span class="article-toc-count"></span><button type="button" class="article-toc-close" aria-label="关闭文章目录">×</button></div><nav class="article-toc-listwrap" aria-label="文章章节"><ol class="article-toc-list"></ol></nav>';
        panel.querySelector('.article-toc-heading strong').id = labelId;
        panel.querySelector('.article-toc-count').textContent = headings.length + ' 节';
        var list = panel.querySelector('.article-toc-list');
        var scrollBox = panel.querySelector('.article-toc-listwrap');
        var parent = null, sublist = null;
        var links = headings.map(function (h) {
            var item = document.createElement('li');
            var link = document.createElement('a');
            link.className = 'article-toc-link' + (h.tagName === 'H2' ? ' article-toc-link-h2' : '');
            link.href = '#' + encodeURIComponent(h.id);
            link.textContent = h.textContent.replace(/\s+/g, ' ').trim();
            if (h.tagName === 'H3' && parent) {
                if (!sublist) { sublist = document.createElement('ol'); sublist.className = 'article-toc-sublist'; parent.appendChild(sublist); }
                sublist.appendChild(item);
            } else {
                list.appendChild(item);
                if (h.tagName === 'H2') { parent = item; sublist = null; }
            }
            item.appendChild(link);
            link.addEventListener('click', function (e) {
                e.preventDefault();
                if (!wide.matches) setOpen(false, false);
                try { history.pushState(null, '', '#' + encodeURIComponent(h.id)); } catch (ignore) { /* Scrolling remains available. */ }
                if (!h.hasAttribute('tabindex')) h.setAttribute('tabindex', '-1');
                h.focus({ preventScroll: true });
                jump(h, !window.matchMedia('(prefers-reduced-motion: reduce)').matches);
            });
            return link;
        });
        rail.appendChild(panel);
        article.appendChild(rail);
        article.classList.add('has-article-toc');
        var toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'article-toc-toggle';
        toggle.innerHTML = icon + '<span>关闭目录</span>';
        toggle.setAttribute('aria-controls', panel.id);
        toggle.setAttribute('aria-expanded', 'false');
        var backdrop = document.createElement('button');
        backdrop.type = 'button';
        backdrop.className = 'article-toc-backdrop';
        backdrop.setAttribute('aria-label', '关闭文章目录');
        backdrop.tabIndex = -1;
        backdrop.hidden = true;
        document.body.appendChild(backdrop);
        var toolbarElement = document.querySelector('section.toolbar');
        (toolbarElement || document.body).appendChild(toggle);
        document.body.classList.add('article-toc-enabled');
        var wide = window.matchMedia('(min-width:1101px)');
        var layoutState = window.NuliBlogLayoutState;
        var open = false, active = -1, positions = [], frame = 0;
        function offset() {
            var toolbar = document.querySelector('section.toolbar');
            return (toolbar ? toolbar.getBoundingClientRect().height : 52) + 20;
        }
        function jump(h, smooth) {
            window.scrollTo({ top: Math.max(0, h.getBoundingClientRect().top + window.scrollY - offset()), behavior: smooth ? 'smooth' : 'auto' });
        }
        function collapseNavigation() {
            // Explicitly showing the directory updates only the article-page menu state.
            document.documentElement.classList.remove('nav-expanded');
            document.body.classList.remove('nav-open');
            document.dispatchEvent(new CustomEvent('NuliBlog:nav-set', { detail: { open: false, persist: true } }));
        }
        function setOpen(value, restore, focusPanel, restoring) {
            var wasOpen = open;
            open = Boolean(value);
            if (!restoring && layoutState) layoutState.setToc(open);
            panel.hidden = !open;
            rail.hidden = !open;
            article.classList.toggle('article-toc-visible', open);
            document.body.classList.toggle('article-toc-visible', open);
            backdrop.hidden = !open || wide.matches;
            toggle.hidden = false;
            toggle.setAttribute('aria-expanded', String(open));
            toggle.querySelector('span').textContent = open ? '关闭目录' : '显示目录';
            toggle.setAttribute('aria-label', open ? '关闭文章目录' : '显示文章目录');
            if (open && wide.matches && !wasOpen && !restoring) collapseNavigation();
            if (open && !wide.matches) {
                panel.setAttribute('role', 'dialog');
                panel.setAttribute('aria-modal', 'true');
                if (focusPanel !== false) (links[Math.max(0, active)] || panel.querySelector('button')).focus({ preventScroll: true });
            } else {
                panel.removeAttribute('role');
                panel.removeAttribute('aria-modal');
            }
            if (restore) toggle.focus({ preventScroll: true });
            measure();
        }
        function highlight() {
            frame = 0;
            var y = window.scrollY + offset() + 4, index = 0;
            for (var i = 0; i < positions.length; i++) { if (positions[i] <= y) index = i; else break; }
            if (active === index) return;
            if (links[active]) links[active].removeAttribute('aria-current');
            active = index;
            var current = links[index];
            current.setAttribute('aria-current', 'location');
            if (!panel.hidden) {
                var a = current.getBoundingClientRect(), b = scrollBox.getBoundingClientRect();
                if (a.top < b.top + 8) scrollBox.scrollTop += a.top - b.top - 8;
                else if (a.bottom > b.bottom - 8) scrollBox.scrollTop += a.bottom - b.bottom + 8;
            }
        }
        function queue() { if (!frame) frame = requestAnimationFrame(highlight); }
        function measure() {
            positions = headings.map(function (h) { return h.getBoundingClientRect().top + window.scrollY; });
            queue();
        }
        function followHash() {
            var id;
            try { id = decodeURIComponent(location.hash.slice(1)); } catch (ignore) { return; }
            var h = headings.find(function (node) { return node.id === id; });
            if (h) jump(h, false);
        }
        toggle.addEventListener('click', function () { setOpen(!open, false); });
        backdrop.addEventListener('click', function () { setOpen(false, true); });
        panel.querySelector('.article-toc-close').addEventListener('click', function () { setOpen(false, true); });
        document.addEventListener('keydown', function (e) {
            if (!open || wide.matches) return;
            if (e.key === 'Escape') { e.preventDefault(); setOpen(false, true); }
            if (e.key === 'Tab') {
                var focusable = Array.from(panel.querySelectorAll('button,a[href]'));
                var first = focusable[0], last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        });
        function breakpoint() {
            var hadFocus = panel.contains(document.activeElement);
            setOpen(open, !open && hadFocus, false, true);
            // Resizing must not undo a manually expanded menu.
        }
        if (wide.addEventListener) wide.addEventListener('change', breakpoint); else wide.addListener(breakpoint);
        window.addEventListener('scroll', queue, { passive: true });
        window.addEventListener('resize', measure);
        window.addEventListener('hashchange', followHash);
        window.addEventListener('popstate', followHash);
        if (window.ResizeObserver) {
            var observer = new ResizeObserver(measure);
            observer.observe(article.querySelector('.box'));
            var toolbar = document.querySelector('section.toolbar');
            if (toolbar) observer.observe(toolbar);
        }
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(measure);
        window.addEventListener('load', function () { measure(); followHash(); }, { once: true });
        setOpen(layoutState ? layoutState.getToc() : true, false, false, true);
        measure();
        if (document.readyState === 'complete') followHash();
    }
    function start() {
        var words = document.querySelector('.artshow.content .words');
        if (!words) return;
        init();
        if (document.querySelector('.article-toc-rail') || !window.MutationObserver) return;
        // Markdown editors may replace a hidden textarea with HTML after DOM ready,
        // including after window.load when renderer dependencies arrive late.
        // Observe content changes instead of polling or assuming a fixed delay.
        var timer = 0;
        var observer = new MutationObserver(function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                if (!words.querySelector('h2,h3')) return;
                init();
                if (document.querySelector('.article-toc-rail')) observer.disconnect();
            }, 120);
        });
        observer.observe(words, { childList: true, subtree: true, characterData: true });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
