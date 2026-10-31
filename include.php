<?php
#注册插件
RegisterPlugin("NuliBlog","ActivePlugin_NuliBlog");

function ActivePlugin_NuliBlog() {
    NuliBlog_InitializeConfig();
	Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu','NuliBlog_AddMenu');
}

function NuliBlog_AddMenu(&$m){
	global $zbp;
	array_unshift($m,MakeTopMenu("root",'NuliBlog配置',$zbp->host . "zb_users/theme/NuliBlog/main.php?act=config","","topmenu_NuliBlog"));
}

function NuliBlog_SubMenu($id){
	$arySubMenu = array(
		0 => array('基本设置', 'config', 'left', false),
		1 => array('SEO设置', 'seo', 'left', false),
	);
	foreach($arySubMenu as $k => $v){
		echo '<a href="?act='.$v[1].'" '.($v[3]==true?'target="_blank"':'').'><span class="m-'.$v[2].' '.($id==$v[1]?'m-now':'').'">'.$v[0].'</span></a>';
	}
}

/** Initialize once; identify prior settings by their field signature, not a former theme ID. */
function NuliBlog_InitializeConfig() {
    global $zbp;
    $config = $zbp->Config('NuliBlog');
    if ($config->HasKey('SchemaVersion') && (int)$config->SchemaVersion >= 1) return;
    $fields = array('qrtext', 'tagnum', 'telnum', 'qqnum', 'description', 'keywords', 'ftnf');
    $candidates = array();
    foreach ($zbp->configs as $name => $source) {
        if ($name === 'NuliBlog' || !is_object($source) || !method_exists($source, 'GetData')) continue;
        $data = $source->GetData();
        if (!is_array($data)) continue;
        foreach ($data as $key => $unused) {
            if (!is_string($key) || !preg_match('/^([A-Za-z][A-Za-z0-9]*)_qrtext$/D', $key, $match)) continue;
            $prefix = $match[1] . '_';
            foreach ($fields as $field) { if (!array_key_exists($prefix . $field, $data)) continue 2; }
            $candidates[] = array('prefix' => $prefix, 'data' => $data);
        }
    }
    if (count($candidates) === 1) {
        $source = $candidates[0];
        foreach ($source['data'] as $key => $value) {
            if (strpos($key, $source['prefix']) !== 0) continue;
            $newKey = 'NuliBlog_' . substr($key, strlen($source['prefix']));
            if (!$config->HasKey($newKey)) $config->$newKey = $value;
        }
        $config->MigrationStatus = 'migrated';
    } else {
        $config->MigrationStatus = count($candidates) ? 'ambiguous' : 'new';
    }
    $defaults = array('qrtext' => '手机扫描二维码', 'tagnum' => '5', 'telnum' => '', 'qqnum' => '',
        'description' => '网站描述', 'keywords' => '网站关键词', 'ftnf' => '1');
    foreach ($defaults as $field => $value) {
        $key = 'NuliBlog_' . $field;
        if (!$config->HasKey($key)) $config->$key = $value;
    }
    $config->Version = '1.0.0';
    $config->SchemaVersion = 1;
    $zbp->SaveConfig('NuliBlog');
}
function InstallPlugin_NuliBlog() {
    NuliBlog_InitializeConfig();
}
//自定义函数：友好时间显示
function NuliBlog_TimeAgo( $ptime ){
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return '刚刚';
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  '年前',
        30 * 24 * 60 * 60       =>  '个月前',
        7 * 24 * 60 * 60        =>  '周前',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}
//hot tags
function NuliBlog_GethotTags($num){
	global $zbp;
	$str = '';
	$tagArray = $zbp->GetTagList('','',array('tag_Count'=>'DESC'),array($num),'');	
	foreach ($tagArray as $tag) {
		$str .= '<a href="'.$tag->Url.'" title="'.$tag->Count.'" class="btn btn-default">'.$tag->Name.'</a>';
	}
	return $str;
}
//卸载主题
function UninstallPlugin_NuliBlog(){
	global $zbp;
	//$zbp->DelConfig('NuliBlog');
}

// Homepage pins are resolved from saved article flags, independently of the top-post cache.
function NuliBlog_HomeArticles($articles, $type, $page) {
    global $zbp;
    if ($type != 'index' || (int)$page != 1) {
        return $articles;
    }
    $pins = $zbp->GetArticleList(
        null,
        array(array('=', 'log_Status', 0), array('IN', 'log_IsTop', array(1, 2))),
        array('log_IsTop' => 'ASC', 'log_PostTime' => 'DESC'),
        null,
        null
    );
    $result = array();
    $seen = array();
    foreach ($pins as $post) {
        $result[] = $post;
        $seen[$post->ID] = true;
    }
    foreach ($articles as $post) {
        if (!isset($seen[$post->ID])) {
            $result[] = $post;
            $seen[$post->ID] = true;
        }
    }
    return $result;
}

// Render navigation icons with the page so links do not reflow after JavaScript starts.
function NuliBlog_Navbar($content) {
    $paths = array(
        'globe' => 'M21 12a9 9 0 11-18 0 9 9 0 0118 0M3 12h18M12 3c4 5 4 13 0 18-4-5-4-13 0-18',
        'home' => 'M3 10l9-7 9 7M5 9v12h5v-7h4v7h5V9',
        'archive' => 'M4 4h16v5H4zM6 9v12h12V9M10 13h4',
        'book' => 'M12 5v16M12 5C8 2 4 3 2 4v15c4-2 7-1 10 2 3-3 6-4 10-2V4c-2-1-6-2-10 1z',
        'code' => 'M8 6l-6 6 6 6M16 6l6 6-6 6M14 3l-4 18',
        'radio' => 'M4 8h16v13H4zM5 8l13-6M7 12h6M7 16h3M17 14v3',
        'link' => 'M10 13a5 5 0 007 0l4-4a5 5 0 00-7-7l-3 3M14 11a5 5 0 00-7 0l-4 4a5 5 0 007 7l3-3',
        'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0M4 21v-3a8 8 0 0116 0v3',
        'message' => 'M3 3h18v14H9l-6 4zM7 7h10M7 12h7',
        'camera' => 'M3 7h4l2-3h6l2 3h4v14H3zM16 14a4 4 0 11-8 0 4 4 0 018 0',
        'coffee' => 'M4 8h12v8a5 5 0 01-10 0V8M16 9h2a3 3 0 010 6h-2M3 22h16M8 2v3M12 2v3',
        'car' => 'M3 14l3-8h12l3 8v6h-3v-3H6v3H3zM3 13h18M7 15h1M16 15h1',
        'grid' => 'M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z',
    );
    $names = array('好好上网'=>'globe', '业余无线电'=>'radio', '软硬件知识'=>'code',
        '新能源汽车'=>'car', '新能源分享'=>'car', 'S1:短波频率'=>'radio',
        'S2:摄影站'=>'camera', '关于'=>'user');
    $routes = array('category-3.html'=>'globe', 'category-5.html'=>'radio',
        'category-6.html'=>'code', 'category-9.html'=>'car', 'sw.html'=>'radio', 'about.html'=>'user');
    return preg_replace_callback('/<a\b([^>]*)>(.*?)<\/a>/is', function($m) use ($paths, $names, $routes) {
        $label = trim(html_entity_decode(strip_tags($m[2]), ENT_QUOTES, 'UTF-8'));
        $name = preg_replace('/\s+/u', '', str_replace('：', ':', $label));
        $key = isset($names[$name]) ? $names[$name] : '';
        if (!$key) {
            foreach ($routes as $route => $icon) {
                if (strpos($m[1], $route) !== false) { $key = $icon; break; }
            }
        }
        if (!$key) {
            $rules = array('首页|home'=>'home','归档|archive'=>'archive','关于|about'=>'user',
                '留言|评论|联系|contact'=>'message','链接|友链|link'=>'link',
                '电台|广播|无线电|短波|播客|radio'=>'radio','技术|代码|开发|软硬件|编程|tech'=>'code',
                '摄影|相册|照片|photo'=>'camera','闲扯|生活|随笔|life'=>'coffee',
                '汽车|座舱|车机|新能源|IVI'=>'car','学习|笔记|阅读|book'=>'book','上网|网络|网站|web'=>'globe');
            foreach ($rules as $rule => $icon) {
                if (preg_match('/'.$rule.'/iu', $label)) { $key = $icon; break; }
            }
        }
        if (!$key) $key = 'grid';
        $text = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $attrs = preg_replace('/\s+(?:title|aria-label)\s*=\s*([\x22\x27]).*?\1/is', '', $m[1]);
        return '<a'.$attrs.' data-nav-icon="'.$key.'" title="'.$text.'" aria-label="'.$text.'">'
            .'<svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'.$paths[$key].'"/></svg>'
            .'<span class="nav-label">'.$text.'</span></a>';
    }, $content);
}

// Plain, escaped summaries shared by featured and regular article list entries.
function NuliBlog_ListSummary($article) {
    foreach (array($article->Intro, $article->Content) as $source) {
        $source = preg_replace('/<(script|style|pre|svg|textarea)\b[^>]*>.*?<\/\1>/is', ' ', $source);
        $source = preg_replace('/<(?:br|\/p|\/div|\/li|\/h[1-6])\b[^>]*>/i', ' ', $source);
        $text = html_entity_decode(strip_tags($source), ENT_QUOTES, 'UTF-8');
        $text = trim(preg_replace('/[\s\x{00a0}\x{3000}]+/u', ' ', $text));
        if ($text !== '') {
            $excerpt = SubStrUTF8($text, 160);
            return htmlspecialchars($excerpt . ($excerpt !== $text ? '…' : ''), ENT_QUOTES, 'UTF-8');
        }
    }
    return '';
}
