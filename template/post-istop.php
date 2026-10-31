<?php die();?>
<li class="post-entry toplist">
  <div class="right post-entry-body">
    <span class="pin-badge"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M8 3h8l-1 6 4 4v2H5v-2l4-4-1-6zM12 15v7"/></svg>置顶精选</span>
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
