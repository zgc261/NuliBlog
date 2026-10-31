<?php die();?>
<li class="post-entry">
  <div class="right post-entry-body">
    <h2 class="title"><a href="{$article.Url}" target="_blank" rel="noopener">{$article.Title}</a></h2>
    {php}$NuliBlog_list_summary = NuliBlog_ListSummary($article);{/php}
    {if $NuliBlog_list_summary!=''}<p class="summary post-excerpt">{$NuliBlog_list_summary}</p>{/if}
    <div class="rel post-entry-meta">
      <span class="meta-item post-date"><time>{$article.Time('Y-m-d')}</time></span>
      <span class="meta-item post-category"><a href="{$article.Category.Url}">{$article.Category.Name}</a></span>
      <span class="meta-item post-views">阅读 {$article.ViewNums}</span>
    </div>
  </div>
</li>
