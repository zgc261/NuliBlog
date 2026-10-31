<?php die();?>
{template:header}
    <section class="toolbar">
        <a class="mobile-brand" href="{$host}" aria-label="返回首页"><img src="{$host}zb_users/theme/{$theme}/images/logo.png" alt="{$name}" width="36" height="36" /></a>
    	<a class="homelink"><span class="switch glyphicon glyphicon-th-list"></span></a>
        <div class="category btn-group" role="navigation" aria-label="文章标签" tabindex="0">
        	<a href="javascript:;" class="btn btn-default">Tags</a>
            {NuliBlog_GethotTags($zbp->Config('NuliBlog')->NuliBlog_tagnum)}
        </div>
    </section>
    <section class="artlist content{if $type=='index'} home-feed{/if}">
        <div class="article-feed-heading">
            <h1>{if $type=='index'}最新文章{else}{$title}{/if}</h1>
            <span>技术探索与生活记录</span>
        </div>
        <ul class="items">
{php}$articles = NuliBlog_HomeArticles($articles, $type, isset($page) ? $page : 1);{/php}
{foreach $articles as $article}
{if $article.IsTop}
{template:post-istop}
{else}
{template:post-multi}
{/if}
{/foreach}
        </ul>
        <div id="Paging" class="paging"><div class="qzPagination">{template:pagebar}</div></div>
    </section>
{template:footer}