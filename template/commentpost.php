<?php die();?>
<div class="post" id="divCommentPost">
	<div class="posttop"><span class="posti">发表评论</span><span class="comment-form-note">让交流带来新的灵感</span><a rel="nofollow" id="cancel-reply" href="#divCommentPost" style="display:none;">取消回复</a></div>
	<form id="frmSumbit" target="_self" method="post" action="{$article.CommentPostUrl}" >
	<input type="hidden" name="inpId" id="inpId" value="{$article.ID}" />
	<input type="hidden" name="inpRevID" id="inpRevID" value="0" />
{if $user.ID>0}
	<input type="hidden" name="inpName" id="inpName" value="{$user.Name}" />
	<input type="hidden" name="inpEmail" id="inpEmail" value="{$user.Email}" />
	<input type="hidden" name="inpHomePage" id="inpHomePage" value="{$user.HomePage}" />
{else}
<div class="comment-fields">
	<p><i class="glyphicon glyphicon-user"></i><input type="text" name="inpName" id="inpName" class="text" value="{$user.Name}" size="25" tabindex="1" placeholder="昵称（必填）" autocomplete="nickname" aria-label="昵称（必填）" /><label for="inpName"></label></p>
	<p><i class="glyphicon glyphicon-envelope"></i><input type="text" name="inpEmail" id="inpEmail" class="text" value="{$user.Email}" size="25" tabindex="2" placeholder="邮箱" autocomplete="email" inputmode="email" aria-label="邮箱" /><label for="inpEmail"></label></p>
	<p><i class="glyphicon glyphicon-link"></i><input type="text" name="inpHomePage" id="inpHomePage" class="text" value="{$user.HomePage}" size="25" tabindex="3" placeholder="个人网站（选填）" autocomplete="url" aria-label="个人网站（选填）" /><label for="inpHomePage"></label></p>

</div>
{if $option['ZC_COMMENT_VERIFY_ENABLE']}
	<p class="vcode"><input type="text" name="inpVerify" id="inpVerify" class="text" value="" size="22" tabindex="4" placeholder="验证码" aria-label="验证码" /><label for="inpVerify"></label>
	<img style="cursor:pointer;" src="{$article.ValidCodeUrl}" title="点击刷新" onclick="javascript:this.src='{$article.ValidCodeUrl}&amp;tm='+Math.random();"/>
	</p>
{/if}

{/if}
	<div class="comment-composer">
	<textarea name="txaArticle" id="txaArticle" class="text" cols="50" rows="3" tabindex="5" aria-label="评论内容" placeholder="分享你的经验、问题，或此刻的想法…"></textarea>
	</div>
	<div class="cpost"><span class="comment-submit-note">友善表达，认真交流</span><i class="glyphicon glyphicon-plus" title="加高"></i><i class="glyphicon glyphicon-edit" title="重写"></i><input name="sumbit" type="submit" tabindex="6" value="发表评论" onclick="return zbp.comment.post()" class="button" /></div>
	</form>
</div>