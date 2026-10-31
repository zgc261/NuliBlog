<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();

$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('NuliBlog')) {$zbp->ShowError(48);die();}

$blogtitle="NuliBlog主题配置";
$act = "";
if ($_GET['act']){
$act = $_GET['act'] == "" ? 'config' : $_GET['act'];
}
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
//基本设置
if(isset($_POST['NuliBlog_qrtext'])){
	$zbp->Config('NuliBlog')->NuliBlog_qrtext = $_POST['NuliBlog_qrtext'];
	$zbp->Config('NuliBlog')->NuliBlog_tagnum = $_POST['NuliBlog_tagnum'];
	$zbp->Config('NuliBlog')->NuliBlog_telnum = $_POST['NuliBlog_telnum'];
	$zbp->Config('NuliBlog')->NuliBlog_qqnum = $_POST['NuliBlog_qqnum'];
	$zbp->SaveConfig('NuliBlog');
	$zbp->ShowHint('good');
}
//SEO设置
if(isset($_POST['NuliBlog_description'])){
	$zbp->Config('NuliBlog')->NuliBlog_description = $_POST['NuliBlog_description'];
	$zbp->Config('NuliBlog')->NuliBlog_keywords = $_POST['NuliBlog_keywords'];
	$zbp->Config('NuliBlog')->NuliBlog_ftnf = $_POST['NuliBlog_ftnf'];
	$zbp->SaveConfig('NuliBlog');
	$zbp->ShowHint('good');
}
?>
<div id="divMain">
	<div class="divHeader"><?php echo $blogtitle;?></div>
	<div class="SubMenu">
	<?php NuliBlog_SubMenu($act);?>
    </div>
	<div id="divMain2">
<?php if ($act == 'config') { ?>
<form enctype="multipart/form-data" method="post" action="save.php?type=logo">
<table width="100%" class="tableBorder">

    <tr height="50">
        <td align="right">LOGO图片</td>
        <td><input name="logo.png" type="file"/><input name="" type="Submit" class="button" value="上传" /><label for="logo.png"> 建议尺寸为162*60像素 </label></td>
    </tr>
</table>
</form>
<form enctype="multipart/form-data" method="post" action="save.php?type=qrcode">
<table width="100%" class="tableBorder">

    <tr height="50">
        <td align="right">二维码图片</td>
        <td><input name="qrcode.png" type="file"/><input name="" type="Submit" class="button" value="上传" /><label for="qrcode.png"> 建议尺寸为130*130像素 </label></td>
    </tr>
</table>
</form>
<form id="form1" name="form1" method="post"> 
<table width="100%" class="tableBorder">
    <tr height="50"><td width="220" align="right">二维码图文字</td><td><input type="text" value="<?php echo $zbp->Config('NuliBlog')->NuliBlog_qrtext;?>" id="NuliBlog_qrtext" name="NuliBlog_qrtext" /></td></tr>
    <tr height="50"><td align="right">顶部标签显示数量</td><td><input type="text" value="<?php echo $zbp->Config('NuliBlog')->NuliBlog_tagnum;?>" id="NuliBlog_tagnum" name="NuliBlog_tagnum" /></td></tr>
    <tr height="50"><td align="right">联系电话</td><td><input type="text" value="<?php echo $zbp->Config('NuliBlog')->NuliBlog_telnum;?>" id="NuliBlog_telnum" name="NuliBlog_telnum" /><span style="color:#999;margin-left:10px">注：此项为空则不显示对应按钮，下同</span></td></tr>
    <tr height="50"><td align="right">QQ号码</td><td><input type="text" value="<?php echo $zbp->Config('NuliBlog')->NuliBlog_qqnum;?>" id="NuliBlog_qqnum" name="NuliBlog_qqnum" /></td></tr>
    <tr height="45">
    	<td colspan="2">NuliBlog 主题设置说明：保存设置后刷新前台页面查看效果；更新模板文件后，请在后台更新缓存。菜单与文章目录的显示状态由当前浏览器分别记忆。</td>
    </tr>
</table>
<input name="" type="Submit" class="button" value="保存" /><br /><br />
</form>
<?php } if ($act == 'seo') { ?>
<form id="form2" name="form2" method="post">
<table width="100%" class="tableBorder">
    <tr>
        <td width="220" height="50" align="right">网站描述</td>
        <td width="500"><textarea cols="62" rows="8" id="NuliBlog_description" name="NuliBlog_description" /><?php echo $zbp->Config('NuliBlog')->NuliBlog_description;?></textarea></td>
        <td><span style="color:#999;margin-left:10px">注：一段概括网站主题内容的简要文字，一般不超过100个汉字</span></td>
    </tr>
    <tr>
        <td height="50" align="right">网站关键词</td>
        <td><textarea cols="62" rows="8" id="NuliBlog_keywords" name="NuliBlog_keywords" /><?php echo $zbp->Config('NuliBlog')->NuliBlog_keywords;?></textarea></td>
        <td><span style="color:#999;margin-left:10px">注：多个关键词用半角逗号隔开，一般不超过50个汉字</span></td>
    </tr>
    <tr>
        <td height="50" align="right">页脚作者外链</td>
        <td>
            <select name="NuliBlog_ftnf">
                <option value="1" <?php echo($zbp->Config('NuliBlog')->NuliBlog_ftnf=='1')?'selected="selected"':'';?>>保持默认状态</option>
                <option value="2" <?php echo($zbp->Config('NuliBlog')->NuliBlog_ftnf=='2')?'selected="selected"':'';?>>使用nofollow标记</option>
            </select>
        </td>
        <td><span style="color:#999;margin-left:10px">注：若无专业需求或不清楚该项作用，请保持默认</span></td>
    </tr>
</table>
<input name="" type="Submit" class="button" value="保存"/>
</form>	
<?php   }  ?>
	</div>
</div>
<script type="text/javascript">ActiveTopMenu("topmenu_NuliBlog");</script> 
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>