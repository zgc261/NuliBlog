<?php die();?>
<!DOCTYPE HTML>
<html data-nuliblog-page-group="{if $type=='article' || $type=='page'}article{else}listing{/if}">
<head>
<script>
(function() {
 var root = document.documentElement;
 var group = root.getAttribute('data-nuliblog-page-group') === 'article' ? 'article' : 'listing';
 var menuKey = group === 'article' ? 'NuliBlog-article-nav-open' : 'NuliBlog-list-nav-open';
 var tocKey = 'NuliBlog-article-toc-open';
 // Migrate compatible browser preferences without retaining a former namespace.
 try {
  var savedKeys = [], prefixes = {};
  for (var i = 0; i < localStorage.length; i++) savedKeys.push(localStorage.key(i));
  ['article-nav-open', 'list-nav-open', 'article-toc-open'].forEach(function(suffix) {
   var target = 'NuliBlog-' + suffix;
   var matches = savedKeys.filter(function(key) {
    return key !== target && new RegExp('^[A-Za-z]+-' + suffix + '$').test(key);
   });
   if (matches.length !== 1) return;
   var source = matches[0], value = localStorage.getItem(source);
   if (value !== '0' && value !== '1') return;
   if (localStorage.getItem(target) === null) localStorage.setItem(target, value);
   prefixes[source.slice(0, -suffix.length)] = true;
   localStorage.removeItem(source);
  });
  Object.keys(prefixes).forEach(function(prefix) { localStorage.removeItem(prefix + 'nav-open'); });
 } catch (ignore) {}
 var memory = {};
 function read(key, fallback) {
  var value = memory[key];
  try { var stored = localStorage.getItem(key); if (stored !== null) value = stored; } catch (ignore) {}
  return value === '1' ? true : value === '0' ? false : fallback;
 }
 function write(key, value) {
  memory[key] = value ? '1' : '0';
  try { localStorage.setItem(key, memory[key]); } catch (ignore) {}
 }
 window.NuliBlogLayoutState = {
  group: group,
  getMenu: function() {
   var desktop = window.matchMedia('(min-width:1101px)').matches;
   return read(menuKey, desktop && (group !== 'article' || !read(tocKey, true)));
  },
  setMenu: function(value) { write(menuKey, value); },
  getToc: function() { return read(tocKey, true); },
  setToc: function(value) { write(tocKey, value); }
 };
 root.classList.toggle('nav-expanded', window.NuliBlogLayoutState.getMenu());
})();
</script>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="Content-Language" content="{$language}" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
{if $type=='article'}
<title>{$title}-{$article.Category.Name}-{$name}</title>
<meta name="keywords" content="{foreach $article.Tags as $tag}{$tag.Name},{/foreach}" />
{php}$description = preg_replace('/[\r\n\s]+/', ' ', trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),100)).'...');{/php}
<meta name="description" content="{$description}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{$title}" />
<meta property="og:description" content="{$description}" />
{elseif $type=='page'}
<title>{$title}-{$name}</title>
<meta name="keywords" content="{$title},{$name}"/>
{php}$description = preg_replace('/[\r\n\s]+/', ' ', trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),100)).'...');{/php}
<meta name="description" content="{$description}"/>
<meta property="og:type" content="website" />
<meta property="og:title" content="{$title}" />
<meta property="og:description" content="{$description}" />
<meta name="author" content="{$article.Author.StaticName}">
{elseif $type=='index'}
<title>{$name}{if $page>'1'}-第{$pagebar.PageNow}页{/if}-{$subname}</title>
<meta name="Keywords" content="{$zbp->Config('NuliBlog')->NuliBlog_keywords}">
<meta name="description" content="{$zbp->Config('NuliBlog')->NuliBlog_description}">
{elseif $type=='category'}
<title>{$title}-{$name}</title>
<meta name="Keywords" content="{$title},{$name}">
<meta name="description" content="{$category.Intro}">
{else}
<title>{$title}-{$name}</title>
<meta name="Keywords" content="{$zbp->Config('NuliBlog')->NuliBlog_keywords}">
<meta name="description" content="{$zbp->Config('NuliBlog')->NuliBlog_description}">
{/if}
<link rel="icon" href="{$host}zb_users/theme/{$theme}/images/favicon.ico" type="image/x-icon" /> 
<link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/style/bootstrap.min.css" type="text/css" media="all"/>
<link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/style/NuliBlog.css?v=20261031-nuliblog1" type="text/css" media="all"/>
<link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/style/misans-subsets.css?v=20261031-nuliblog1" type="text/css" media="all"/>
<link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/style/responsive.css?v=20261031-nuliblog1" type="text/css" media="all"/>
{if $type=='article' || $type=='page'}
<link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/style/article-toc.css?v=20261031-nuliblog1" type="text/css" media="all"/>
<script src="{$host}zb_users/theme/{$theme}/js/article-toc.js?v=20261031-nuliblog1" defer></script>
{/if}
{if $type=="index" && $page=="1"}
<link rel="alternate" type="application/rss+xml" href="{$feedurl}" title="{$name}" />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="{$host}zb_system/xml-rpc/?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="{$host}zb_system/xml-rpc/wlwmanifest.xml" />
{/if}
<script src="{$host}zb_system/script/jquery-2.2.4.min.js" type="text/javascript"></script>
<script src="{$host}zb_system/script/zblogphp.js" type="text/javascript"></script>
<script src="{$host}zb_system/script/c_html_js_add.php" type="text/javascript"></script>
<script src="{$host}zb_users/theme/{$theme}/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{$host}zb_users/theme/{$theme}/js/inner.min.js?v=20261031-nuliblog1" type="text/javascript"></script>


<!--[if lt IE 9]>
<script src="{$host}zb_users/theme/{$theme}/js/html5shiv.min.js"></script>
<![endif]-->
{$header}
</head>
<body>
<header>
	<div class="box">
    	<a class="logo" href="{$host}" title="{$title}"><img src="{$host}zb_users/theme/{$theme}/images/logo.png" alt="{$name}" /></a>
        <div class="title">{$name}</div>
        <div class="subtitle">
        	<p>{php} echo $subname {/php}<i></i></p>
        </div>
        <nav>
          <ul>
            {php}if (isset($modules['navbar'])) echo NuliBlog_Navbar($modules['navbar']->Content);{/php}
          </ul>
        </nav>
    </div>
</header>
