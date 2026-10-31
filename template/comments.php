<?php die();?>
{if $socialcomment}
	{$socialcomment}
{else}
	{template:commentpost}
	{if $article.CommNums>0}
<ul class="msg msghead">
	<li class="tbname">评论<span>{$article.CommNums}</span></li>
</ul>
	{/if}
<label id="AjaxCommentBegin"></label>
<!--评论输出-->
{foreach $comments as $key => $comment}
{template:comment}
{/foreach}
<!--评论翻页条输出-->
<div id="pagenavi" class="commentpagebar">
{template:pagebar}
</div>
<label id="AjaxCommentEnd"></label>
{/if}