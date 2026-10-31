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
        <div class="box" style="max-width:1000px">
            <div class="post-page-title">
        	<h1>{$article.Title}</h1>
            <span class="rel"><span class="meta-item meta-author">{$name}</span><span class="meta-item"><svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v4l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0"/></svg><span>{$article.Time('Y-m-d')}</span></span><span class="meta-item"><svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12zM15 12a3 3 0 11-6 0 3 3 0 016 0"/></svg><span>阅读 {$article.ViewNums}</span></span></span>
            </div>
            <div class="words">
                {$article.Content}
            </div>
            <div class="bdsharebuttonbox">
            	<a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_douban" data-cmd="douban" title="分享到豆瓣网"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧"></a><a href="#" class="bds_mail" data-cmd="mail" title="邮件分享"></a>
            </div>
<script>
window._bd_share_config = { "common": { "bdSnsKey": {}, "bdText": "{$article.Title}", "bdMini": "2", "bdMiniList": false, "bdPic": "", "bdStyle": "0", "bdSize": "32" }, "share": {} }; with (document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
</script>
{if !$article.IsLock}
{template:comments}
{/if}
        </div>
    </section>