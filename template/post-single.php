    <section class="toolbar">
        <a class="mobile-brand" href="{$host}" aria-label="返回首页"><img src="{$host}zb_users/theme/{$theme}/images/logo.png" alt="{$name}" width="36" height="36" /></a>
    	<a class="homelink"><span class="switch glyphicon glyphicon-th-list"></span></a>
        <div class="btnList">
            <div class="link btn-group toolbar-home">
        	    <a href="{$host}" class="btn btn-default">{$name}</a>
            </div>
            {if $article.Prev}
            <div class="link btn-group">
        	    <a href="{$article.Prev.Url}" class="btn btn-default" title="{$article.Prev.Title}" aria-label="上一篇"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span>上一篇</span></a>
            </div>
            {/if}
            {if $article.Next}
            <div class="link btn-group">
        	    <a href="{$article.Next.Url}" class="btn btn-default" title="{$article.Next.Title}" aria-label="下一篇"><span>下一篇</span><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>
            </div>
            {/if}
        </div>
    </section>
    <section class="artshow content">
        <div class="box"  style="max-width:1000px">
            <div class="post-page-title">
        	<h1>{$article.Title}</h1>
            <span class="rel"><span class="meta-item meta-author"><img src="{$article.Author.Avatar}" alt="" width="20" height="20" />{$name}</span><span class="meta-item"><svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v4l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0"/></svg><span>{$article.Time('Y-m-d')}</span></span><span class="meta-item"><svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12zM15 12a3 3 0 11-6 0 3 3 0 016 0"/></svg><span>阅读 {$article.ViewNums}</span></span><span class="meta-item"><svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 5h7l2 3h9v12H3z"/></svg><a href="{$article.Category.Url}">{$article.Category.Name}</a></span></span>
            </div>
            <div class="words">
                {$article.Content}
            </div>
            {if count($article.Tags)>0}
            <dl class="tags">
              <dt><svg class="tag-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13l-7 7-10-10V3h7z"/><circle cx="7.5" cy="7.5" r="1"/></svg><span>文章关键词</span></dt>
              	{foreach $article.Tags as $tag}
					<dd><a href="{$tag.Url}">{$tag.Name}</a></dd>
				{/foreach}
            </dl>
            {/if}

{if !$article.IsLock}
{template:comments}
{/if}
        </div>
    </section>