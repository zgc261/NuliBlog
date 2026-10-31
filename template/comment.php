<?php die();?>
<ul class="msg" id="cmt{$comment.ID}">
  <li class="msgname">
    <img class="avatar" src="{$comment.Author.Avatar}" alt="" width="34" height="34" loading="lazy" />
    <span class="commentname">{if $comment.Author.HomePage}<a href="{$comment.Author.HomePage}" rel="nofollow noopener" target="_blank" class="mcolor">{$comment.Author.StaticName}</a>{else}<span class="mcolor">{$comment.Author.StaticName}</span>{/if}</span>
    {if $comment.Author.ID==$article.Author.ID}<span class="comment-author-badge">作者</span>{/if}
    <small>{$comment.Time()}</small>
  </li>
  <li class="msgarticle">
    <div class="comment-body">{$comment.Content}</div>
    <div class="comment-actions">
      <a href="#divCommentPost" onclick="zbp.comment.reply('{$comment.ID}');return false;" title="回复该评论" class="comment-reply-link"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M9 5l-6 6 6 6M3 11h11a6 6 0 016 6v2"/></svg>回复</a>
      <a href="#cmt{$comment.ID}" class="comment-permalink" aria-label="此条评论的链接"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M10 13a5 5 0 007 0l3-3a5 5 0 00-7-7l-2 2M14 11a5 5 0 00-7 0l-3 3a5 5 0 007 7l2-2"/></svg>链接</a>
    </div>
    {foreach $comment.Comments as $comment}
    {template:comment}
    {/foreach}
  </li>
</ul>
