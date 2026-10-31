<?php die();?>
<div class="dock">
    <ul class="icons">
        <li class="up"><i></i></li>
        {if $zbp->Config('NuliBlog')->NuliBlog_telnum!=''}
        <li class="tel">
            <i></i><p>咨询电话<br />{php} echo $zbp->Config('NuliBlog')->NuliBlog_telnum {/php}</p>
        </li>
        {/if}
        {if $zbp->Config('NuliBlog')->NuliBlog_qqnum!=''}
        <li class="im">
            <i></i><p><a href="http://wpa.qq.com/msgrd?v=3&uin={php} echo $zbp->Config('NuliBlog')->NuliBlog_qqnum {/php}&site=qq&menu=yes" target="_blank">点击QQ咨询</a></p>
        </li>
        {/if}
    </ul>
</div>
<footer>
    <a href="{$host}">{$name}</a><br /><u>{php}echo $copyright;{/php}<a href="http://zgc261.com/zb_system/login.php">©</a></u><br />
</footer>
{$footer}
</body>
</html>