<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## Error Reporting ##
if(!defined('DEBUG_LOADER'))
    exit('<b>Die Debug-Console wurde nicht geladen!<p>
    Bitte überprüfen Sie ob die index.php einen "include(basePath."/inc/debugger.php");" Eintrag hat.</b>');

## INCLUDES/REQUIRES ##
require_once(basePath.'/inc/secure.php');
require_once(basePath.'/inc/_version.php');
require_once(basePath.'/inc/pop3.php');
require_once(basePath.'/inc/smtp.php');
require_once(basePath.'/inc/phpmailer.php');
require_once(basePath.'/inc/server_query/_functions.php');
require_once(basePath."/inc/teamspeak_query.php");
require_once(basePath."/inc/phpfastcache/phpfastcache.php");

## Is AjaxJob ##
$ajaxJob = (!isset($ajaxJob) ? false : $ajaxJob);

## FUNCTIONS ##
//-> Legt die UserID desRootadmins fest
//-> (dieser darf bestimmte Dinge, den normale Admins nicht duerfen, z.B. andere Admins editieren)
$rootAdmin = 1;

//Cache
$config_cache['htaccess'] = true;
$config_cache['fallback'] = array( "memcache" => "apc", "memcached" =>  "apc", "apc" =>  "sqlite", "sqlite" => "files");
$config_cache['path'] = basePath."/inc/_cache_";

if(!is_dir($config_cache['path'])) //Check cache dir
    mkdir($config_cache['path'], 0777, true);

$config_cache['securityKey'] = settings('prev',false);
phpFastCache::setup($config_cache);
$cache = phpFastCache();

//-> Settingstabelle auslesen * Use function settings('xxxxxx');
if(!dbc_index::issetIndex('settings')) {
    $get = db("SELECT * FROM ".$db['settings'],false,true);
    dbc_index::setIndex('settings', $get);
}

//-> Configtabelle auslesen * Use function config('xxxxxx');
if(!dbc_index::issetIndex('config')) {
    $config = db("SELECT * FROM ".$db['config'],false,true);
    dbc_index::setIndex('config', $config);
}

//-> DZCP Cookie Prefix
$prev = settings('prev').'_';

//-> Language auslesen
$language = (isset($_COOKIE[$prev.'language']) ? (file_exists(basePath.'/inc/lang/languages/'.$_COOKIE[$prev.'language'].'.php') ? $_COOKIE[$prev.'language'] : settings('language')) : settings('language'));

//einzelne Definitionen
$isSpider = isSpider();
$subfolder = basename(dirname(dirname($_SERVER['PHP_SELF']).'../'));
$httphost = $_SERVER['HTTP_HOST'].(empty($subfolder) ? '' : '/'.$subfolder);
$domain = str_replace('www.','',$httphost);
$pagetitle = settings('pagetitel');
$sdir = settings('tmpdir');
$useronline = 1800;
$reload = 3600 * 24;
$datum = time();
$today = date("j.n.Y");
$picformat = array("jpg", "gif", "png");
$userip = visitorIp();
$maxpicwidth = 90;
$maxadmincw = 10;
$maxfilesize = @ini_get('upload_max_filesize');

//-> Global
$action = isset($_GET['action']) ? $_GET['action'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$do = isset($_GET['do']) ? $_GET['do'] : '';
$index = ''; $show = '';

//-> Auslesen der Cookies und automatisch anmelden
if(isset($_COOKIE[$prev.'id']) && isset($_COOKIE[$prev.'pkey']) && empty($_SESSION['id']) && !checkme()) {
    ## User aus der Datenbank suchen ##
    $sql = db_stmt("SELECT id,user,nick,pwd,email,level,time,pkey FROM ".$db['users']." WHERE id = ? AND pkey = ? AND level != '0'",array('is', $_COOKIE[$prev.'id'], $_COOKIE[$prev.'pkey']));
    if(_rows($sql)) {
        $get = _fetch($sql);

        ## Generiere neuen permanent-key ##
        $permanent_key = md5(mkpwd(8));
        set_cookie($prev."pkey",$permanent_key);

        ## Schreibe Werte in die Server Sessions ##
        $_SESSION['id']         = $get['id'];
        $_SESSION['pwd']        = $get['pwd'];
        $_SESSION['lastvisit']  = $get['time'];
        $_SESSION['ip']         = visitorIp();

        if(data("ip",$get['id']) != $_SESSION['ip'])
            $_SESSION['lastvisit'] = data("time",$get['id']);

        if(empty($_SESSION['lastvisit']))
            $_SESSION['lastvisit'] = data("time",$get['id']);

        ## Aktualisiere Datenbank ##
        db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$_SESSION['ip']."', `pkey` = '".$permanent_key."' WHERE id = '".$get['id']."'");

        ## Aktualisiere die User-Statistik ##
        db("UPDATE ".$db['userstats']." SET `logins` = logins+1 WHERE user = '".$get['id']."'");
        unset($get,$permanent_key);
    } else {
        $_SESSION['id']        = '';
        $_SESSION['pwd']       = '';
        $_SESSION['ip']        = '';
        $_SESSION['lastvisit'] = '';
        $_SESSION['pkey']      = '';
    }

    unset($sql);
}

lang($language); //Lade Sprache
$userid = userid();
$chkMe = checkme();
if(!$chkMe) {
    $_SESSION['id']        = '';
    $_SESSION['pwd']       = '';
    $_SESSION['ip']        = '';
    $_SESSION['lastvisit'] = '';
}

/**
* Gibt die IP des Besuchers / Users zurück
* Forwarded IP Support
*/
function visitorIp() {
    $TheIp=$_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        $TheIp = $_SERVER['HTTP_X_FORWARDED_FOR'];

    if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))
        $TheIp = $_SERVER['HTTP_CLIENT_IP'];

    if(isset($_SERVER['HTTP_FROM']) && !empty($_SERVER['HTTP_FROM']))
        $TheIp = $_SERVER['HTTP_FROM'];

    $TheIp_X = explode('.',$TheIp);
    if(count($TheIp_X) == 4 && $TheIp_X[0]<=255 && $TheIp_X[1]<=255 && $TheIp_X[2]<=255 && $TheIp_X[3]<=255 && preg_match("!^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$!",$TheIp))
        return trim($TheIp);

    return '0.0.0.0';
}

/**
 * Funktion um notige Erweiterungen zu prufen
 *
 * @return boolean
 **/
function fsockopen_support() {
    if(!function_exists('fsockopen') || !function_exists("fopen"))
        return false;

    if(strpos(ini_get('disable_functions'),'fsockopen') || strpos(ini_get('disable_functions'),'file_get_contents') || strpos(ini_get('disable_functions'),'fopen'))
        return false;

    return true;
}

//-> Auslesen der UserID
function userid() {
    global $db;

    if(empty($_SESSION['id']) || empty($_SESSION['pwd'])) return 0;
    $sql = db("SELECT id FROM ".$db['users']." WHERE id = '".$_SESSION['id']."' AND pwd = '".$_SESSION['pwd']."'");
    if(!_rows($sql)) return 0;
    $get = _fetch($sql);
    return $get['id'];
}

//-> Templateswitch
$files = get_files(basePath.'/inc/_templates_/',true);
if(isset($_GET['tmpl_set'])) {
    foreach ($files as $templ) {
        if($templ == $_GET['tmpl_set']) {
            set_cookie($prev.'tmpdir',$templ);
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}

if(isset($_COOKIE[$prev.'tmpdir']) && $_COOKIE[$prev.'tmpdir'] != NULL) {
    if(file_exists(basePath."/inc/_templates_/".$_COOKIE[$prev.'tmpdir']))
        $tmpdir = $_COOKIE[$prev.'tmpdir'];
    else
        $tmpdir = $files[0];
} else {
    if(file_exists(basePath."/inc/_templates_/".$sdir))
        $tmpdir = $sdir;
    else
        $tmpdir = $files[0];
}
unset($files);

$designpath = '../inc/_templates_/'.$tmpdir;

//-> Languagefiles einlesen
function lang($lng,$pfad='') {
    global $charset;
    if(!file_exists(basePath."/inc/lang/languages/".$lng.".php"))
    {
        $files = get_files(basePath.'/inc/lang/languages/',false,true,array('php'));
        $lng = str_replace('.php','',$files[0]);
    }

    include(basePath."/inc/lang/global.php");
    include(basePath."/inc/lang/languages/".$lng.".php");
}

//-> Sprachdateien auflisten
function languages() {
    $lang="";
    $files = get_files('../inc/lang/languages/',false,true,array('php'));
    for($i=0;$i<=count($files)-1;$i++) {
        $file = str_replace('.php','',$files[$i]);
        $upFile = strtoupper(substr($file,0,1)).substr($file,1);
        if(file_exists('../inc/lang/flaggen/'.$file.'.gif'))
            $lang .= '<a href="../user/?action=language&amp;set='.$file.'"><img src="../inc/lang/flaggen/'.$file.'.gif" alt="'.$upFile.'" title="'.$upFile.'" class="icon" /></a> ';
    }

    return $lang;
}

//-> Userspezifiesche Dinge
if($userid >= 1 && $ajaxJob != true)
    db("UPDATE ".$db['userstats']." SET `hits` = hits+1, `lastvisit` = '".((int)$_SESSION['lastvisit'])."' WHERE user = ".$userid);

//-> Settings auslesen
function settings($what,$use_dbc=true) {
    global $db;

    if($use_dbc)
        return dbc_index::getIndexKey('settings', $what);

    $get = db("SELECT `".$what."` FROM ".$db['settings'],false,true);
    return $get[$what];
}

//-> Config auslesen
function config($what,$use_dbc=true) {
    global $db;

    if($use_dbc)
        return dbc_index::getIndexKey('config', $what);

    $get = db("SELECT `".$what."` FROM ".$db['config'],false,true);
    return $get[$what];
}

//-> PHP-Code farbig anzeigen
function highlight_text($txt) {
    while(preg_match("=\[php\](.*)\[/php\]=Uis",$txt)!=FALSE) {
        $res = preg_match("=\[php\](.*)\[/php\]=Uis",$txt,$matches);
        $src = $matches[1];
        $src = str_replace('<?php','',$src);
        $src = str_replace('<?php','',$src);
        $src = str_replace('?>','',$src);
        $src = str_replace("&#39;", "'", $src);
        $src = str_replace("&#34;", "\"", $src);
        $src = str_replace("&amp;","&",$src);
        $src = str_replace("&lt;","<",$src);
        $src = str_replace("&gt;",">",$src);
        $src = str_replace('<?php','&#60;?',$src);
        $src = str_replace('?>','?&#62;',$src);
        $src = str_replace("&quot;","\"",$src);
        $src = str_replace("&nbsp;"," ",$src);
        $src = str_replace("&nbsp;"," ",$src);
        $src = str_replace("<p>","\n",$src);
        $src = str_replace("</p>","",$src);
        $l = explode("<br />", $src);
        $src = preg_replace("#\<br(.*?)\>#is","\n",$src);
        $src = '<?php'.$src.' ?>';
        $colors = array('#111111' => 'string', '#222222' => 'comment', '#333333' => 'keyword', '#444444' => 'bg',     '#555555' => 'default', '#666666' => 'html');

        foreach ($colors as $color => $key)
            ini_set('highlight.'.$key, $color);

        // Farben ersetzen & highlighten
        $src = preg_replace('!style="color: (#\d{6})"!e','"class=\"".$prefix.$colors["\1"]."\""',highlight_string($src, TRUE));

        // PHP-Tags komplett entfernen
        $src = str_replace('&lt;?php','',$src);
        $src = str_replace('?&gt;','',$src);
        $src = str_replace('&amp;</span><span class="comment">#60;?','&lt;?',$src);
        $src = str_replace('?&amp;</span><span class="comment">#62;','?&gt;',$src);
        $src = str_replace('&amp;#60;?','&lt;?',$src);
        $src = str_replace('?&amp;#62;','?&gt;',$src);
        $src = str_replace(":", "&#58;", $src);
        $src = str_replace("(", "&#40;", $src);
        $src = str_replace(")", "&#41;", $src);
        $src = str_replace("^", "&#94;", $src);

        // Zeilen zaehlen
        $lines = "";
        for($i=1;$i<=count($l)+1;$i++)
            $lines .= $i.".<br />";

        // Ausgabe
        $code = '<div class="codeHead">&nbsp;&nbsp;&nbsp;Code:</div><div class="code"><table style="width:100%;padding:0px" cellspacing="0"><tr><td class="codeLines">'.$lines.'</td><td class="codeContent">'.$src.'</td></table></div>';
        $txt = preg_replace("=\[php\](.*)\[/php\]=Uis",$code,$txt,1);
    }

    return $txt;
}

//-> Glossarfunktion
$gl_words = array(); $gl_desc = array();
if(!$ajaxJob) {
    $qryglossar = db("SELECT `word`,`glossar` FROM ".$db['glossar']);
    while($getglossar = _fetch($qryglossar)) {
        $gl_words[] = re($getglossar['word']);
        $gl_desc[]  = $getglossar['glossar'];
    }
    unset($getglossar,$qryglossar);
}

function regexChars($txt) {
    $txt = strip_tags($txt);
    $txt = str_replace('"','&quot;',$txt);
    $txt = str_replace('\\','\\\\',$txt);
    $txt = str_replace('<','\<',$txt);
    $txt = str_replace('>','\>',$txt);
    $txt = str_replace('/','\/',$txt);
    $txt = str_replace('.','\.',$txt);
    $txt = str_replace(':','\:',$txt);
    $txt = str_replace('^','\^',$txt);
    $txt = str_replace('$','\$',$txt);
    $txt = str_replace('|','\|',$txt);
    $txt = str_replace('?','\?',$txt);
    $txt = str_replace('*','\*',$txt);
    $txt = str_replace('+','\+',$txt);
    $txt = str_replace('-','\-',$txt);
    $txt = str_replace('(','\(',$txt);
    $txt = str_replace(')','\)',$txt);
    $txt = str_replace('[','\[',$txt);
    $txt = str_replace(']','\]',$txt);
    $txt = str_replace('}','\}',$txt);
    $txt = str_replace('{','\{',$txt);
    $txt = str_replace("\r",'',$txt);
    return str_replace("\n",'',$txt);
}

$use_glossar = true; //Global
function glossar($txt) {
    global $db,$gl_words,$gl_desc,$use_glossar;

    if(!$use_glossar)
        return $txt;

    $txt = str_replace('&#93;',']',$txt);
    $txt = str_replace('&#91;','[',$txt);

    // mark words
    for($s=0;$s<=count($gl_words)-1;$s++)
    {
        $w = addslashes(regexChars(html_entity_decode($gl_words[$s])));
        $txt = str_ireplace(' '.$w.' ', ' <tmp|'.$w.'|tmp> ', $txt);
        $txt = str_ireplace('>'.$w.'<', '> <tmp|'.$w.'|tmp> <', $txt);
        $txt = str_ireplace('>'.$w.' ', '> <tmp|'.$w.'|tmp> ', $txt);
        $txt = str_ireplace(' '.$w.'<', ' <tmp|'.$w.'|tmp> <', $txt);
    }

    // replace words
    for($g=0;$g<=count($gl_words)-1;$g++)
    {
        $desc = regexChars($gl_desc[$g]);
        $info = 'onmouseover="DZCP.showInfo(\''.jsconvert($desc).'\')" onmouseout="DZCP.hideInfo()"';
        $w = regexChars(html_entity_decode($gl_words[$g]));
        $r = "<a class=\"glossar\" href=\"../glossar/?word=".$gl_words[$g]."\" ".$info.">".$gl_words[$g]."</a>";
        $txt = str_ireplace('<tmp|'.$w.'|tmp>', $r, $txt);
    }

    $txt = str_replace(']','&#93;',$txt);
    return str_replace('[','&#91;',$txt);
}

function bbcodetolow($founds) {
    return "[".strtolower($founds[1])."]".trim($founds[2])."[/".strtolower($founds[3])."]";
}

//-> Replaces
function replace($txt,$type=0,$no_vid_tag=0) {
    $txt = str_replace("&#34;","\"",$txt);

    if($type == 1)
        $txt = preg_replace("#<img src=\"(.*?)\" mce_src=\"(.*?)\"(.*?)\>#i","<img src=\"$2\" alt=\"\">",$txt);

    $txt = preg_replace_callback("/\[(.*?)\](.*?)\[\/(.*?)\]/","bbcodetolow",$txt);
    $var = array("/\[url\](.*?)\[\/url\]/",
                 "/\[img\](.*?)\[\/img\]/",
                 "/\[url\=(http\:\/\/)?(.*?)\](.*?)\[\/url\]/",
                 "/\[b\](.*?)\[\/b\]/",
                 "/\[i\](.*?)\[\/i\]/",
                 "/\[u\](.*?)\[\/u\]/",
                 "/\[color=(.*?)\](.*?)\[\/color\]/");

    $repl = array("<a href=\"$1\" target=\"_blank\">$1</a>",
                  "<img src=\"$1\" class=\"content\" alt=\"\" />",
                  "<a href=\"http://$2\" target=\"_blank\">$3</a>",
                  "<b>$1</b>",
                  "<i>$1</i>",
                  "<u>$1</u>",
                  "<span style=\"color:$1\">$2</span>");

    $txt = preg_replace($var,$repl,$txt);
    $txt = preg_replace_callback("#\<img(.*?)\>#", create_function('$img','if(preg_match("#class#i",$img[1])) return "<img".$img[1].">"; else return "<img class=\"content\"".$img[1].">";'), $txt);

    if($no_vid_tag == 0)
        $txt = preg_replace_callback("#\[youtube\]http\:\/\/www.youtube.com\/watch\?v\=(.*)\[\/youtube\]#Uis", create_function('$yt','$width = 425; $height = 344;return "<object width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"".$width."\" height=\"".$height."\"></embed></object>";'), $txt);

    $txt = str_replace("\"","&#34;",$txt);
    return preg_replace("#(\w){1,1}(&nbsp;)#Uis","$1 ",$txt);
}

//-> Badword Filter
function BadwordFilter($txt) {
    $words = explode(",",trim(settings('badwords')));
    foreach($words as $word)
    { $txt = preg_replace("#".$word."#i", str_repeat("*", strlen($word)), $txt); }
    return $txt;
}

//-> Funktion um Bestimmte Textstellen zu markieren
function hl($text, $word) {
    if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'text') {
        if($_SESSION['search_con'] == 'or') {
            $words = explode(" ",$word);
            for($x=0;$x<count($words);$x++)
                $ret['text'] = preg_replace("#".$words[$x]."#i",'<span class="fontRed" title="'.$words[$x].'">'.$words[$x].'</span>',$text);
        }
        else
            $ret['text'] = preg_replace("#".$word."#i",'<span class="fontRed" title="'.$word.'">'.$word.'</span>',$text);

        if(!preg_match("#<span class=\"fontRed\" title=\"(.*?)\">#", $ret['text']))
            $ret['class'] = 'class="commentsRight"';
        else
            $ret['class'] = 'class="highlightSearchTarget"';
    } else {
        $ret['text'] = $text;
        $ret['class'] = 'class="commentsRight"';
    }

    return $ret;
}

//-> Emailadressen in Unicode umwandeln
function eMailAddr($email) {
    $address = trim($email);
    $output = "";

    for($i=0;$i<strlen($email);$i++)
    { $output.=str_replace(substr($email,$i,1),"&#".ord(substr($email,$i,1)).";",substr($email,$i,1)); }

    return $output;
}

//-> Leerzeichen mit + ersetzen (w3c)
function convSpace($string) {
    return str_replace(" ","+",$string);
}

//-> BBCode
function re_bbcode($txt) {
    $txt = spChars($txt);
    $txt = str_replace("'", "&#39;", $txt);
    $txt = str_replace("[","&#91;",$txt);
    $txt = str_replace("]","&#93;",$txt);
    $txt = str_replace("&lt;","&#60;",$txt);
    $txt = str_replace("&gt;","&#62;",$txt);
    return stripslashes($txt);
}

/* START # from wordpress under GBU GPL license
   URL autolink function */
function _make_url_clickable_cb($matches)
{
    $ret = '';
    $url = $matches[2];

    if ( empty($url) )
        return $matches[0];
    // removed trailing [.,;:] from URL
    if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
        $ret = substr($url, -1);
        $url = substr($url, 0, strlen($url)-1);
    }

    return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
}

function _make_web_ftp_clickable_cb($matches) {
    $ret = '';
    $dest = $matches[2];
    $dest = 'http://' . $dest;

    if ( empty($dest) )
        return $matches[0];

    // removed trailing [,;:] from URL
    if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
        $ret = substr($dest, -1);
        $dest = substr($dest, 0, strlen($dest)-1);
    }

    return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
}

function _make_email_clickable_cb($matches) {
    $email = $matches[2] . '@' . $matches[3];
    return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
}

function make_clickable($ret) {
    $ret = ' ' . $ret;
    // in testing, using arrays here was found to be faster
    $ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
    $ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret);
    $ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret);

    // this one is not in an array because we need it to run last, for cleanup of accidental links within links
    $ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
    return trim($ret);
}

/* END # from wordpress under GBU GPL license */

//Diverse BB-Codefunktionen
function bbcode($txt, $tinymce=0, $no_vid=0, $ts=0, $nolink=0) {
    global $charset;

    $txt = html_entity_decode($txt,ENT_COMPAT,$charset);
    if($no_vid == 0 && settings('urls_linked') && $nolink == 0)
        $txt = make_clickable($txt);

    $txt = str_replace("\\","\\\\",$txt);
    $txt = str_replace("\\n","<br />",$txt);
    $txt = BadwordFilter($txt);
    $txt = replace($txt,$tinymce,$no_vid);
    $txt = highlight_text($txt);
    $txt = re_bbcode($txt);

    if($ts == 0)
        $txt = strip_tags($txt,"<br><object><em><param><embed><strong><iframe><hr><table><tr><td><div><span><a><b><font><i><u><p><ul><ol><li><br /><img>");

    $txt = smileys($txt);

    if($no_vid == 0)
        $txt = glossar($txt);

    $txt = str_replace("&#34;","\"",$txt);
    return str_replace('<p></p>', '<p>&nbsp;</p>', $txt);
}

function bbcode_nletter($txt) {
    $txt = stripslashes($txt);
    $txt = nl2br(trim($txt));
    return '<style type="text/css">p { margin: 0px; padding: 0px; }</style>'.$txt;
}

function bbcode_nletter_plain($txt) {
    $txt = preg_replace("#\<\/p\>#Uis","\r\n",$txt);
    $txt = preg_replace("#\<br(.*?)\>#Uis","\r\n",$txt);
    $txt = str_replace("p { margin: 0px; padding: 0px; }","",$txt);
    $txt = convert_feed($txt);
    $txt = str_replace("&amp;#91;","[",$txt);
    $txt = str_replace("&amp;#93;","]",$txt);
    return strip_tags($txt);
}

function bbcode_html($txt,$tinymce=0) {
    $txt = str_replace("&lt;","<",$txt);
    $txt = str_replace("&gt;",">",$txt);
    $txt = str_replace("&quot;","\"",$txt);
    $txt = BadwordFilter($txt);
    $txt = replace($txt,$tinymce);
    $txt = highlight_text($txt);
    $txt = re_bbcode($txt);
    $txt = smileys($txt);
    $txt = glossar($txt);
    return str_replace("&#34;","\"",$txt);
}

function bbcode_email($txt) {
    $txt = bbcode($txt);
    $txt = str_replace("&#91;","[",$txt);
    return str_replace("&#93;","]",$txt);
}

//-> Textteil in Zitat-Tags setzen
function zitat($nick,$zitat) {
    $zitat = str_replace(chr(145), chr(39), $zitat);
    $zitat = str_replace(chr(146), chr(39), $zitat);
    $zitat = str_replace("'", "&#39;", $zitat);
    $zitat = str_replace(chr(147), chr(34), $zitat);
    $zitat = str_replace(chr(148), chr(34), $zitat);
    $zitat = str_replace(chr(10), " ", $zitat);
    $zitat = str_replace(chr(13), " ", $zitat);
    $zitat = preg_replace("#[\n\r]+#", "<br />", $zitat);
    return '<div class="quote"><b>'.$nick.' '._wrote.':</b><br />'.re_bbcode($zitat).'</div><br /><br /><br />';
}

//-> convert string for output
function re($txt) {
    $txt = stripslashes($txt);
    $txt = str_replace("& ","&amp; ",$txt);
    $txt = str_replace("[","&#91;",$txt);
    $txt = str_replace("]","&#93;",$txt);
    $txt = str_replace("\"","&#34;",$txt);
    $txt = str_replace("<","&#60;",$txt);
    $txt = str_replace(">","&#62;",$txt);
    $txt = str_replace("(", "&#40;", $txt);
    return str_replace(")", "&#41;", $txt);
}

function re_entry($txt) {
    return stripslashes($txt);
}

//-> Smileys ausgeben
function smileys($txt) {
    $files = get_files('../inc/images/smileys',false,true);
    for($i=0; $i<count($files); $i++) {
        $smileys = $files[$i];
        $bbc = preg_replace("=.gif=Uis","",$smileys);

        if(preg_match("=:".$bbc.":=Uis",$txt)!=FALSE)
            $txt = preg_replace("=:".$bbc.":=Uis","<img src=\"../inc/images/smileys/".$bbc.".gif\" alt=\"\" />", $txt);
    }

    $var = array("/\ :D/",
                 "/\ :P/",
                 "/\ ;\)/",
                 "/\ :\)/",
                 "/\ :-\)/",
                 "/\ :\(/",
                 "/\ :-\(/",
                 "/\ ;-\)/");

    $repl = array(" <img src=\"../inc/images/smileys/grin.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/zunge.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/zwinker.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/smile.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/smile.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/traurig.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/traurig.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/zwinker.gif\" alt=\"\" />");

  $txt = preg_replace($var,$repl, $txt);
  return str_replace(" ^^"," <img src=\"../inc/images/smileys/^^.gif\" alt=\"\" />", $txt);
}

//-> Flaggen ausgeben
function flagge($txt) {
    $var = array("/\:de:/",
                 "/\:ch:/",
                 "/\:at:/",
                 "/\:au:/",
                 "/\:be:/",
                 "/\:br:/",
                 "/\:ca:/",
                 "/\:gb:/",
                 "/\:pl:/",
                 "/\:cz:/",
                 "/\:dk:/",
                 "/\:es:/",
                 "/\:en:/",
                 "/\:fi:/",
                 "/\:fr:/",
                 "/\:gr:/",
                 "/\:hr:/",
                 "/\:us:/",
                 "/\:it:/",
                 "/\:se:/",
                 "/\:eu:/",
                 "/\:nl:/",
                 "/\:na:/",
                 "/\:no:/",
                 "/\:ru:/");

    $repl = array("<img src=\"../inc/images/flaggen/de.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/ch.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/at.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/au.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/be.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/br.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/ca.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/uk.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/pl.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/cz.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/dk.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/es.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/fo.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/fi.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/fr.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/gr.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/hr.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/us.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/it.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/se.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/eu.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/nl.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/nocountry.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/no.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/ru.gif\" alt=\"\" />" );

    return preg_replace($var,$repl, $txt);
}

//-> Funktion um Ausgaben zu kuerzen
function cut($str, $length = null, $dots = true) {
    if($length === 0)
        return '';

    $start = 0;
    $dots = ($dots == true && strlen(html_entity_decode($str)) > $length) ? '...' : '';

    if(strpos($str, '&') === false)
        return (($length === null) ? substr($str, $start) : substr($str, $start, $length)).$dots;

    $chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
    $html_length = count($chars);

    if(($html_length === 0) || ($start >= $html_length) || (isset($length) && ($length <= -$html_length)))
        return '';

    if($start >= 0)
        $real_start = $chars[$start][1];
    else {
        $start = max($start,-$html_length);
        $real_start = $chars[$html_length+$start][1];
    }

    if (!isset($length))
        return substr($str, $real_start).$dots;
    else if($length > 0)
        return (($start+$length >= $html_length) ? substr($str, $real_start) : substr($str, $real_start, $chars[max($start,0)+$length][1] - $real_start)).$dots;
    else
        return substr($str, $real_start, $chars[$html_length+$length][1] - $real_start).$dots;
}

function wrap($str, $width = 75, $break = "\n", $cut = true) {
    return strtr(str_replace(htmlentities($break), $break, htmlentities(wordwrap(html_entity_decode($str), $width, $break, $cut), ENT_QUOTES)), array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_COMPAT)));
}

//-> Funktion um Dateien aus einem Verzeichnis auszulesen
function get_files($dir=null,$only_dir=false,$only_files=false,$file_ext=array(),$preg_match=false,$blacklist=array()) {
    $files = array();
    if(!file_exists($dir) && !is_dir($dir)) return $files;
    if($handle = @opendir($dir)) {
        if($only_dir) {
            while(false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..' && !is_file($dir.'/'.$file)) {
                    if(!count($blacklist) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else {
                        if(!in_array($file, $blacklist) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                }
            } //while end
        } else if($only_files) {
            while(false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..' && is_file($dir.'/'.$file)) {
                    if(!in_array($file, $blacklist) && !count($file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else {
                        ## Extension Filter ##
                        $exp_string = array_reverse(explode(".", $file));
                        if(!in_array($file, $blacklist) && in_array(strtolower($exp_string[0]), $file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                }
            } //while end
        } else {
            while(false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..' && is_file($dir.'/'.$file)) {
                    if(!in_array($file, $blacklist) && !count($file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else {
                        ## Extension Filter ##
                        $exp_string = array_reverse(explode(".", $file));
                        if(!in_array($file, $blacklist) && in_array(strtolower($exp_string[0]), $file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                } else {
                    if(!in_array($file, $blacklist) && $file != '.' && $file != '..' && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                }
            } //while end
        }

        if(is_resource($handle))
            closedir($handle);

        if(!count($files))
            return false;

        return $files;
    }
    else
        return false;
}

//-> Gibt einen Teil eines nummerischen Arrays wieder
function limited_array($array=array(),$begin,$max) {
    $array_exp = array();
    $range=range($begin=($begin-1), ($begin+$max-1));
    foreach($array as $key => $wert) {
        if(array_var_exists($key, $range))
            $array_exp[$key] = $wert;
    }

    return $array_exp;
}

function array_var_exists($var,$search)
{ foreach($search as $key => $var_) { if($var_==$var) return true; } return false; }

//-> Funktion um eine Datei im Web auf Existenz zu prfen
function fileExists($url) {
    $url_p = @parse_url($url);
    $host = $url_p['host'];
    $port = isset($url_p['port']) ? $url_p['port'] : 80;

    if(!fsockopen_support()) return false;
    $fp = @fsockopen($url_p['host'], $port, $errno, $errstr, 5);
    if(!$fp) return false;

    @fputs($fp, 'GET '.$url_p['path'].' HTTP/1.1'.chr(10));
    @fputs($fp, 'HOST: '.$url_p['host'].chr(10));
    @fputs($fp, 'Connection: close'.chr(10).chr(10));

    $response = @fgets($fp, 1024);
    $content = @fread($fp,1024);
    $ex = explode("\n",$content);
    $content = $ex[count($ex)-1];
    @fclose ($fp);

    if(preg_match("#404#",$response)) return false;
    else return trim($content);
}

//-> Informationen ueber die mySQL-Datenbank
function dbinfo()
{
    $info = array(); $entrys = 0;
    $qry = db("Show table status");
    while($data = _fetch($qry)) {
        $allRows = $data["Rows"];
        $dataLength  = $data["Data_length"];
        $indexLength = $data["Index_length"];
        $tableSum    = $dataLength + $indexLength;

        $sum += $tableSum;
        $rows += $allRows;
        $entrys ++;
    }

    $info["entrys"] = $entrys;
    $info["rows"] = $rows;
    $info["size"] = @round($sum/1048576,2);
    return $info;
}

//-> Funktion um Sonderzeichen zu konvertieren
function spChars($txt) {
  $txt = str_replace("Ä","&Auml;",$txt);
  $txt = str_replace("ä","&auml;",$txt);
  $txt = str_replace("Ü","&Uuml;",$txt);
  $txt = str_replace("ü","&uuml;",$txt);
  $txt = str_replace("Ö","&Ouml;",$txt);
  $txt = str_replace("ö","&ouml;",$txt);
  $txt = str_replace("ß","&szlig;",$txt);
  return str_replace("€","&euro;",$txt);
}

//-> Funktion um sauber in die DB einzutragen
function up($txt, $bbcode=0, $charset_set='') {
    global $charset;

    if(!empty($charset_set))
        $charset = $charset_set;

    $txt = str_replace("& ","&amp; ",$txt);
    $txt = str_replace("\"","&#34;",$txt);

    if(empty($bbcode)) {
        $txt = htmlentities(html_entity_decode($txt), ENT_QUOTES, $charset);
        $txt = nl2br($txt);
    }

    return trim(spChars($txt));
}

//-> Funktion um diverse Dinge aus Tabellen auszaehlen zu lassen
function cnt($count, $where = "", $what = "id") {
    $cnt_sql = db("SELECT COUNT(".$what.") AS num FROM ".$count." ".$where.";");
    if(_rows($cnt_sql)) {
        $cnt = _fetch($cnt_sql);
        return $cnt['num'];
    }

    return 0;
}

//-> Funktion um diverse Dinge aus Tabellen zusammenzaehlen zu lassen
function sum($db, $where = "", $what) {
    $cnt_sql = db("SELECT SUM(".$what.") AS num FROM ".$db.$where.";");
    if(_rows($cnt_sql)) {
        $cnt = _fetch($cnt_sql);
        return $cnt['num'];
    }

    return 0;
}

function orderby($sort) {
    $split = explode("&",$_SERVER['QUERY_STRING']);
    $url = "?";

    foreach($split as $part) {
        if(strpos($part,"orderby") === false && strpos($part,"order") === false && !empty($part)) {
            $url .= $part;
            $url .= "&";
        }
    }

    if(isset($_GET['orderby']) && $_GET['order']) {
        if($_GET['orderby'] == $sort && $_GET['order'] == "ASC")
            return $url."orderby=".$sort."&order=DESC";
    }

    return $url."orderby=".$sort."&order=ASC";
}

//-> Funktion um einer id einen Nick zuzuweisen
function nick_id($tid) {
    global $db;
    $get = db("SELECT nick FROM ".$db['users']." WHERE id = '".$tid."'",false,true);
    return $get['nick'];
}

//-> Funktion um ein Datenbankinhalt zu highlighten
function highlight($word) {
    if(substr(phpversion(),0,1) == 5)
        return str_ireplace($word,'<span class="fontRed">'.$word.'</span>',$word);
    else
        return str_replace($word,'<span class="fontRed">'.$word.'</span>',$word);
}

//-> Counter updaten
function updateCounter() {
    global $db,$reload,$today,$datum,$userip;
    $ipcheck = db("SELECT id,ip,datum FROM ".$db['c_ips']." WHERE ip = '".$userip."' AND FROM_UNIXTIME(datum,'%d.%m.%Y') = '".date("d.m.Y")."'");
    $get = _fetch($ipcheck);

    db("DELETE FROM ".$db['c_ips']." WHERE datum+".$reload." <= ".time()." OR FROM_UNIXTIME(datum,'%d.%m.%Y') != '".date("d.m.Y")."'");
    $count = db("SELECT id,visitors,today FROM ".$db['counter']." WHERE today = '".$today."'");
    if(_rows($ipcheck)>=1) {
        $sperrzeit = $get['datum']+$reload;
        if($sperrzeit <= time()) {
            db("DELETE FROM ".$db['c_ips']." WHERE ip = '".$userip."'");

            if(_rows($count))
                db("UPDATE ".$db['counter']." SET `visitors` = visitors+1 WHERE today = '".$today."'");
            else
                db("INSERT INTO ".$db['counter']." SET `visitors` = '1', `today` = '".$today."'");

            db("INSERT INTO ".$db['c_ips']." SET `ip` = '".$userip."', `datum` = '".((int)$datum)."'");
        }
    } else {
        if(_rows($count))
            db("UPDATE ".$db['counter']." SET `visitors` = visitors+1 WHERE today = '".$today."'");
       else
            db("INSERT INTO ".$db['counter']." SET `visitors` = '1', `today` = '".$today."'");

        db("INSERT INTO ".$db['c_ips']." SET `ip` = '".$userip."', `datum` = '".((int)$datum)."'");
    }
}

//-> Updatet die Maximalen User die gleichzeitig online sind
function update_maxonline() {
    global $db,$today;

    $get = db("SELECT maxonline FROM ".$db['counter']." WHERE today = '".$today."'",false,true);
    $count = cnt($db['c_who']);

    if($get['maxonline'] <= $count)
        db("UPDATE ".$db['counter']." SET `maxonline` = '".((int)$count)."' WHERE today = '".$today."'");
}

//-> Prueft, wieviele Besucher gerade online sind
function online_guests($where='') {
    global $db,$useronline,$userip,$chkMe,$isSpider;

    if(!$isSpider) {
        $logged = !$chkMe ? 0 : 1;
        db("DELETE FROM ".$db['c_who']." WHERE online < ".time());
        db("REPLACE INTO ".$db['c_who']."
               SET `ip`       = '".$userip."',
                   `online`   = '".((int)(time()+$useronline))."',
                   `whereami` = '".up($where)."',
                   `login`    = '".((int)$logged)."'");

        return cnt($db['c_who']);
    }
}
//-> Prueft, wieviele registrierte User gerade online sind
function online_reg() {
    global $db,$useronline;
    return cnt($db['users'], " WHERE time+'".$useronline."'>'".time()."' AND online = '1'");
}

//-> Prueft, ob der User eingeloggt ist und wenn ja welches Level besitzt er
function checkme($userid_set=0) {
    global $db;

    if(!$userid = ($userid_set != 0 ? intval($userid_set) : userid()))
        return 0;

    $qry = db("SELECT level FROM ".$db['users']." WHERE id = ".$userid." AND pwd = '".$_SESSION['pwd']."' AND ip = '".$_SESSION['ip']."'");
    if(_rows($qry)) {
        $get = _fetch($qry);
        return $get['level'];
    }
    else
        return 0;
}

//-> Prueft, ob der User gesperrt ist und meldet ihn ab
function isBanned($userid_set=0,$logout=true) {
    global $db,$userid,$prev;
    $userid_set = $userid_set ? $userid_set : $userid;
    if(checkme($userid_set) >= 1 || $userid_set) {
        $get = db("SELECT banned FROM ".$db['users']." WHERE `id` = ".intval($userid_set)." LIMIT 1",false,true);
        if($get['banned']) {
            if($logout) {
                $_SESSION['id']        = '';
                $_SESSION['pwd']       = '';
                $_SESSION['ip']        = '';
                $_SESSION['lastvisit'] = '';
                session_unset();
                session_destroy();
                session_regenerate_id();
                set_cookie($prev.'id', '');
                set_cookie($prev.'pkey',"");
                set_cookie(session_name(), '');
                $userid = 0; $chkMe = 0;
            }

            return true;
        }
    }

    return false;
}

//-> Prueft, ob ein User diverse Rechte besitzt
function permission($check,$uid=0) {
    global $db,$userid,$chkMe;
    if(!$uid) $uid = $userid;

    if($chkMe == 4)
        return true;
    else {
        if($userid) {
            // check rank permission
            if(db("SELECT s1.`".$check."` FROM ".$db['permissions']." AS s1
                   LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi`
                   WHERE s2.`user` = '".intval($uid)."' AND s1.`".$check."` = '1' AND s2.`posi` != '0'",true))
                return true;

            // check user permission
            if(!dbc_index::issetIndex('user_permission_'.$uid)) {
                $permissions = db("SELECT * FROM ".$db['permissions']." WHERE user = '".intval($uid)."'",false,true);
                dbc_index::setIndex('user_permission_'.$uid, $permissions);
            }

            return dbc_index::getIndexKey('user_permission_'.$uid, $check) ? true : false;
        }
        else
            return false;
    }
}

//-> Checkt, ob neue Nachrichten vorhanden sind
function check_msg() {
    global $db;
    if(db("SELECT page FROM ".$db['msg']." WHERE an = '".$_SESSION['id']."' AND page = 0",true)) {
        db("UPDATE ".$db['msg']." SET `page` = '1' WHERE an = '".$_SESSION['id']."'");
        return show("user/new_msg", array("new" => _site_msg_new));
    }

    return '';
}

//-> Prueft sicherheitsrelevante Gegebenheiten im Forum
function forumcheck($tid, $what) {
    global $db;
    return db("SELECT ".$what." FROM ".$db['f_threads']." WHERE id = '".intval($tid)."' AND ".$what." = '1'",true) ? true : false;
}

//-> Prueft ob ein User schon in der Buddyliste vorhanden ist
function check_buddy($buddy) {
    global $db,$userid;
    return !db("SELECT buddy FROM ".$db['buddys']." WHERE user = '".intval($userid)."' AND buddy = '".intval($buddy)."'",true) ? true : false;
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten
function cw_result($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/draw.gif" alt="" class="icon" />';
}

function cw_result_pic($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<img src="../inc/images/draw.gif" alt="" class="icon" />';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild
function cw_result_nopic($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span>';
    else if($punkte < $gpunkte)
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span>';
    else
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span>';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild und ohne farbe
function cw_result_nopic_nocolor($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return $punkte.':'.$gpunkte;
    else if($punkte < $gpunkte)
        return $punkte.':'.$gpunkte;
    else
        return $punkte.':'.$gpunkte;
}

//-> Funktion um bei Clanwars Details Endergebnisse auszuwerten ohne bild
function cw_result_details($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwWon">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwLost">'.$gpunkte.'</span></td>';
    else if($punkte < $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwLost">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwWon">'.$gpunkte.'</span></td>';
    else
        return '<td class="contentMainFirst" align="center"><span class="CwDraw">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwDraw">'.$gpunkte.'</span></td>';
}

//-> Flaggen ausgeben
function flag($code) {
    if(!file_exists(basePath."/inc/images/flaggen/".$code.".gif"))
        return '<img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" />';
    else
        return'<img src="../inc/images/flaggen/'.$code.'.gif" alt="" class="icon" />';
}

function rawflag($code) {
    if(!file_exists(basePath."/inc/images/flaggen/".$code.".gif"))
        return '<img src=../inc/images/flaggen/nocountry.gif alt= class=icon />';
    else
        return'<img src=../inc/images/flaggen/'.$code.'.gif alt= class=icon />';
}

//-> Liste der Laender ausgeben
function show_countrys($i="") {
    if($i != "")
        $options = preg_replace('#<option value="'.$i.'">(.*?)</option>#', '<option value="'.$i.'" selected="selected"> \\1</option>', _country_list);
    else
        $options = preg_replace('#<option value="de"> Deutschland</option>#', '<option value="de" selected="selected"> Deutschland</option>', _country_list);

    return '<select id="land" name="land" class="dropdown">'.$options.'</select>';
}

//-> Gameicon ausgeben
function squad($code) {
    if(!isset($code))
        return '<img src="../inc/images/gameicons/nogame.gif" alt="" class="icon" />';
    else
        return '<img  src="../inc/images/gameicons/'.$code.'" alt="" class="icon" />';
}

//-> Funktion um bei DB-Eintraegen URLs einem http:// zuzuweisen
function links($hp) {
    if(!empty($hp))
        return 'http://'.str_replace("http://","",$hp);

    return $hp;
}

//-> Funktion um Passwoerter generieren zu lassen
function mkpwd() {
    $chars = '1234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($chars) - 1; $pw = '';
    for($i = 0; $i < 10; $i++)
    { $pw .= $chars{rand(0, $len)}; }
    return $pw;
}

//-> set cookies
function set_cookie($name, $value = '', $path = '/', $secure = false, $http_only = true) {
    if($value == '')
        $expires = time() - 6000;
    else
        $expires = time() + 3600 * 24 * 360;

    $domain = $_SERVER['HTTP_HOST'];
    $domain = (strtolower(substr($domain, 0, 4)) == 'www.' ? substr($domain, 4) : '.' . $domain);
    $port = strpos($domain, ':');

    if($port !== false)
        $domain = substr($domain, 0, $port);

    header('Set-Cookie: ' . rawurlencode($name) . '=' . rawurlencode($value)
                          . (empty($expires) ? '' : '; expires=' . gmdate('D, d-M-Y H:i:s \\G\\M\\T', $expires))
                          . (empty($path)    ? '' : '; path=' . $path)
                          . '; domain=' . $domain
                          . (!$secure        ? '' : '; secure')
                          . (!$http_only    ? '' : '; HttpOnly'), false);
}

//-> Passwortabfrage
function checkpwd($user, $pwd) {
    global $db;
    return db("SELECT id,user,nick,pwd
               FROM ".$db['users']."
               WHERE user = '".up($user)."'
               AND pwd = '".up($pwd)."'
               AND level != '0'",true) ? true : false;
}

//-> Infomeldung ausgeben
function info($msg, $url, $timeout = 5) {
    if(config('direct_refresh'))
        return header('Location: '.str_replace('&amp;', '&', $url));

    $u = parse_url($url); $parts = '';
    $u['query'] = str_replace('&amp;', '&', $u['query']);
    foreach(explode('&', $u['query']) as $p) {
        $p = explode('=', $p);
        if(count($p) == 2)
            $parts .= '<input type="hidden" name="'.$p[0].'" value="'.$p[1].'" />'."\r\n";
    }

    if(!array_key_exists('path',$u)) $u['path'] = '';
    return show("errors/info", array("msg" => $msg,
                                     "url" => $u['path'],
                                     "rawurl" => html_entity_decode($url),
                                     "parts" => $parts,
                                     "timeout" => $timeout,
                                     "info" => _info,
                                     "weiter" => _weiter,
                                     "backtopage" => _error_fwd));
}

//-> Errormmeldung ausgeben
function error($error, $back=1) {
    return show("errors/error", array("error" => $error, "back" => $back, "fehler" => _error, "backtopage" => _error_back));
}

//-> Errormmeldung ohne "zurueck" ausgeben
function error2($error) {
    return show("errors/error2", array("error" => $error, "fehler" => _error));
}

//-> Email wird auf korrekten Syntax & Erreichbarkeit ueberprueft
function check_email($email) {
    return (!preg_match("#^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$#", $email) ? false : true);
}

//-> Bilder verkleinern
function img_size($img) {
    $s = getimagesize("../".$img);
    return "<a href=\"../".$img."\" rel=\"lightbox[l_".intval($img)."]\"><img src=\"../thumbgen.php?img=".$img."\" alt=\"\" /></a>";
}

function img_cw($folder="", $img="") {
    $s = getimagesize("../".$folder."_".$img);
    return "<a href=\"../".$folder."_".$img."\" rel=\"lightbox[cw_".intval($folder)."]\"><img src=\"../thumbgen.php?img=".$folder."_".$img."\" alt=\"\" /></a>";
}

function gallery_size($img="") {
    $s = getimagesize("../gallery/images/".$img);
    return "<a href=\"../gallery/images/".$img."\" rel=\"lightbox[gallery_".intval($img)."]\"><img src=\"../thumbgen.php?img=gallery/images/".$img."\" alt=\"\" /></a>";
}

//-> URL wird auf Richtigkeit ueberprueft
function check_url($url) {
    if($url && $fp = @fopen($url, "r"))
    {
        return true;
        @fclose($fp);
    }

    return false;
}

//-> Blaetterfunktion
function nav($entrys, $perpage, $urlpart, $icon=true) {
    global $page;
    if($perpage == 0)
        return "&#xAB; <span class=\"fontSites\">0</span> &#xBB;";

    if($icon == true)
        $icon = '<img src="../inc/images/multipage.gif" alt="" class="icon" /> '._seiten;

    if($entrys <= $perpage)
        return $icon.' &#xAB; <span class="fontSites">1</span> &#xBB;';

    if(!$page || $page < 1)
        $page = 2;

    $pages = ceil($entrys/$perpage);

    if(($page-5) <= 2 && $page != 1)
        $first = '<a class="sites" href="'.$urlpart.'&amp;page='.($page-1).'">&#xAB;</a><span class="fontSitesMisc">&#xA0;</span> <a  class="sites" href="'.$urlpart.'&amp;page=1">1</a> ';
    else if($page > 1)
        $first = '<a class="sites" href="'.$urlpart.'&amp;page='.($page-1).'">&#xAB;</a><span class="fontSitesMisc">&#xA0;</span> <a class="sites" href="'.$urlpart.'&amp;page=1">1</a>...';
    else
        $first = '<span class="fontSitesMisc">&#xAB;&#xA0;</span>';

    if($page == $pages)
        $last = '<span class="fontSites">'.$pages.'</span><span class="fontSitesMisc">&#xA0;&#xBB;<span>';
    else if(($page+5) >= $pages)
        $last = '<a class="sites" href="'.$urlpart.'&amp;page='.($pages).'">'.$pages.'</a>&#xA0;<a class="sites" href="'.$urlpart.'&amp;page='.($page+1).'">&#xBB;</a>';
    else
        $last = '...<a class="sites" href="'.$urlpart.'&amp;page='.($pages).'">'.$pages.'</a>&#xA0;<a class="sites" href="'.$urlpart.'&amp;page='.($page+1).'">&#xBB;</a>';

    $result = ''; $resultm = '';
    for($i = $page;$i<=($page+5) && $i<=($pages-1);$i++) {
        if($i == $page)
            $result .= '<span class="fontSites">'.$i.'</span><span class="fontSitesMisc">&#xA0;</span>';
        else
            $result .= '<a class="sites" href="'.$urlpart.'&amp;page='.$i.'">'.$i.'</a><span class="fontSitesMisc">&#xA0;</span>';
    }

    for($i=($page-5);$i<=($page-1);$i++) {
        if($i >= 2)
            $resultm .= '<a class="sites" href="'.$urlpart.'&amp;page='.$i.'">'.$i.'</a> ';
    }

    return $icon.' '.$first.$resultm.$result.$last;
}

//-> Funktion um Seiten-Anzahl der Artikel zu erhalten
function artikelSites($sites, $id) {
    global $part;
    $i = 0; $seiten = '';
    for($i=0;$i<$sites;$i++) {
        if ($i == $part)
            $seiten .= show(_page, array("num" => ($i+1)));
        else
            $seiten .= show(_artike_sites, array("part" => $i,"id" => $id,"num" => ($i+1)));
    }

    return $seiten;
}

//-> Nickausgabe mit Profillink oder Emaillink (reg/nicht reg)
function autor($uid, $class="", $nick="", $email="", $cut="",$add="") {
    global $db;
    $qry = db("SELECT nick,country FROM ".$db['users']." WHERE id = '".intval($uid)."'");
    if(_rows($qry)) {
        $get = _fetch($qry);
        $nickname = (!empty($cut)) ? cut(re($get['nick']), $cut) : re($get['nick']);
        return show(_user_link, array("id" => $uid,
                                         "country" => flag($get['country']),
                                         "class" => $class,
                                         "get" => $add,
                                         "nick" => $nickname));
    } else {
        $nickname = (!empty($cut)) ? cut(re($nick), $cut) : re($nick);
        return show(_user_link_noreg, array("nick" => $nickname, "class" => $class, "email" => eMailAddr($email)));
    }
}

function cleanautor($uid, $class="", $nick="", $email="", $cut="") {
    global $db;
    $qry = db("SELECT nick,country FROM ".$db['users']." WHERE id = '".intval($uid)."'");
    if(_rows($qry)) {
        $get = _fetch($qry);
        return show(_user_link_preview, array("id" => $uid, "country" => flag($get['country']), "class" => $class, "nick" => re($get['nick'])));
    }
    else
        return show(_user_link_noreg, array("nick" => re($nick), "class" => $class, "email" => eMailAddr($email)));
}

function rawautor($uid) {
    global $db;
    $qry = db("SELECT nick,country FROM ".$db['users']." WHERE id = '".intval($uid)."'");
    if(_rows($qry)) {
        $get = _fetch($qry);
        return rawflag($get['country'])." ".jsconvert(re($get['nick']));
    }
    else
        return rawflag('')." ".jsconvert(re($uid));
}

//-> Nickausgabe ohne Profillink oder Emaillink fr das ForenAbo
function fabo_autor($uid) {
    global $db;
    $qry = db("SELECT nick FROM ".$db['users']." WHERE id = '".$uid."'");
    if(_rows($qry)) {
        $get = _fetch($qry);
        return show(_user_link_fabo, array("id" => $uid, "nick" => re($get['nick'])));
    }

    return '';
}

function blank_autor($uid) {
    global $db;
    $qry = db("SELECT nick FROM ".$db['users']." WHERE id = '".$uid."'");
    if(_rows($qry)) {
        $get = _fetch($qry);
        return show(_user_link_blank, array("id" => $uid, "nick" => re($get['nick'])));
    }

    return '';
}

//-> Rechte abfragen
function jsconvert($txt)
{ return str_replace(array("'","&#039;","\"","\r","\n"),array("\'","\'","&quot;","",""),$txt); }

//-> interner Forencheck
function fintern($id) {
    global $db,$userid,$chkMe;
    $fget = _fetch(db("SELECT s1.intern,s2.id FROM ".$db['f_kats']." AS s1 LEFT JOIN ".$db['f_skats']." AS s2 ON s2.`sid` = s1.id WHERE s2.`id` = '".intval($id)."'"));

    if(!$chkMe)
        return empty($fget['intern']) ? true : false;
    else
    {
      $team = db("SELECT * FROM ".$db['f_access']." AS s1 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi` WHERE s2.`user` = '".intval($userid)."' AND s2.`posi` != '0' AND s1.`forum` = '".intval($id)."'");
      $user = db("SELECT * FROM ".$db['f_access']." WHERE `user` = '".intval($userid)."' AND `forum` = '".intval($id)."'");

      if(_rows($user) || _rows($team) || $chkMe == 4 || !$fget['intern'])
          return true;
      else if(!$chkMe)
          return false;
      else
          return false;
    }
}

//-> Einzelne Userdaten ermitteln
function data($what,$tid=0) {
    global $db,$userid;
    if(!$tid) $tid = $userid;
    if(!dbc_index::issetIndex('user_'.$tid)) {
        $get = db("SELECT * FROM ".$db['users']." WHERE id = '".intval($tid)."'",false,true);
        dbc_index::setIndex('user_'.$tid, $get);
    }

    return re_entry(dbc_index::getIndexKey('user_'.$tid, $what));
}

//-> Einzelne Userstatistiken ermitteln
function userstats($what,$tid=0) {
    global $db,$userid;
    if(!$tid) $tid = $userid;
    if(!dbc_index::issetIndex('userstats_'.$tid)) {
        $get = db("SELECT * FROM ".$db['userstats']." WHERE user = '".intval($tid)."'",false,true);
        dbc_index::setIndex('userstats_'.$tid, $get);
    }

    return re_entry(dbc_index::getIndexKey('userstats_'.$tid, $what));
}

//- Funktion zum versenden von Emails
function sendMail($mailto,$subject,$content) {
    global $language;
    $mail = new PHPMailer;
    $mail->isHTML(true);
    $mail->From = ($mailfrom =settings('mailfrom'));
    $mail->FromName = $mailfrom;
    $mail->AddAddress(preg_replace('/(\\n+|\\r+|%0A|%0D)/i', '',$mailto));
    $mail->Subject = $subject;
    $mail->WordWrap = 50;
    $mail->Body = $content;
    $mail->AltBody = bbcode_nletter_plain($content);
    $mail->setLanguage(($language=='deutsch')?'de':'en', basePath.'/inc/lang/sendmail/');
    return $mail->Send();
}

function check_msg_emal() {
    global $db,$httphost;
    $qry = db("SELECT s1.an,s1.page,s1.titel,s1.sendmail,s1.id AS mid,s2.id,s2.nick,s2.email,s2.pnmail FROM ".$db['msg']." AS s1 LEFT JOIN ".$db['users']." AS s2 ON s2.id = s1.an WHERE page = 0 AND sendmail = 0");
    while($get = _fetch($qry)) {
        if($get['pnmail']) {
            db("UPDATE ".$db['msg']." SET `sendmail` = '1' WHERE id = '".$get['mid']."'");
            $subj = show(settings('eml_pn_subj'), array("domain" => $httphost));
            $message = show(bbcode_email(settings('eml_pn')), array("nick" => re($get['nick']), "domain" => $httphost, "titel" => $get['titel'], "clan" => settings('clanname')));
            sendMail(re($get['email']), $subj, $message);
        }
    }
}

if(!$ajaxJob)
    check_msg_emal();

//-> Checkt ob ein Ereignis neu ist
function check_new($datum,$new = "",$datum2 = "") {
    global $db,$userid;
    if($userid) {
        if($datum >= userstats('lastvisit') || $datum2 >= userstats('lastvisit'))
            return (empty($new) ? _newicon : $new);
    }

    return empty($new) ? false : '';
}

//-> DropDown Mens Date/Time
function dropdown($what, $wert, $age = 0) {
    if($what == "day") {
        if($age == 1)
            $return ='<option value="" class="dropdownKat">'._day.'</option>'."\n";

        for($i=1; $i<32; $i++) {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } else if($what == "month") {
        if($age == 1)
            $return .='<option value="" class="dropdownKat">'._month.'</option>'."\n";

        for($i=1; $i<13; $i++) {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } else if($what == "year") {
        if($age == 1) {
            $return .='<option value="" class="dropdownKat">'._year.'</option>'."\n";
            for($i=date("Y",time())-80; $i<date("Y",time())-10; $i++)
            {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        } else {
            for($i=date("Y",time())-3; $i<date("Y",time())+3; $i++) {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
    } else if($what == "hour") {
        for($i=0; $i<24; $i++) {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } else if($what == "minute") {
        for($i="00"; $i<60; $i++) {
            if($i == 0 || $i == 15 || $i == 30 || $i == 45) {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
    }

    return $return;
}

//Games fuer den Livestatus
function sgames($game = '') {
    $protocols = get_files(basePath.'/inc/server_query/',false,true,array('php')); $games = '';
    foreach($protocols AS $protocol) {
        unset($gamemods, $server_name_config);
        $protocol = str_replace('.php', '', $protocol);
        if(substr($protocol, 0, 1) != '_') {
            $explode = '##############################################################################################################################';
            $protocol_config = explode($explode, file_get_contents(basePath.'/inc/server_query/'.$protocol.'.php'));
            eval(str_replace('<?php', '', $protocol_config[0]));

            if(!empty($server_name_config) && count($server_name_config) > 2) {
                $gamemods = '';
                foreach($server_name_config AS $slabel => $sconfig)
                    $gamemods .= $sconfig[1].', ';
            }

            $gamemods = empty($gamemods) ? '' : ' ('.substr($gamemods, 0, strlen($gamemods) - 2).')';
            $games .= '<option value="'.$protocol.'">';

            switch($protocol):
                case 'bf1942'; case 'bf2142'; case 'bf2'; case 'bfvietnam'; case 'bfbc2';
                    $protocol = strtr($protocol, array('bfbc2' => 'Battlefield Bad Company 2', 'bfv' => 'Battlefield V', 'bf' => 'Battlefield '));
                break;
                case 'bf3'; $protocol = 'Battlefield 3'; break;
                case 'swat4'; $protocol = strtoupper($protocol); break;
                case 'aarmy'; $protocol = 'Americas Army'; break;
                case 'arma'; $protocol = 'Armed Assault'; break;
                case 'wet'; $protocol = 'Wolfenstein: Enemy Territory'; break;
                case 'mta'; $protocol = 'Multi-Theft-Auto'; break;
                case 'cnc'; $protocol = 'Command &amp; Conquer'; break;
                case 'sof2'; $protocol = 'Soldiers of Fortune 2'; break;
                case 'ut'; $protocol = 'Unreal Tournament'; break;
                default;
                    $protocol = ucfirst(str_replace('_', ' ', $protocol));
                    $protocol = (strlen($protocol) < 4) ? strtoupper($protocol) : $protocol;
                break;
            endswitch;

            $games .= $protocol.$gamemods;
            $games .= '</option>';
        }
    }

    return str_replace("value=\"".$game."\"","value=\"".$game."\" selected=\"selected\"",$games);
}

//Umfrageantworten selektieren
function voteanswer($what, $vid) {
    global $db;
    $get = db("SELECT sel FROM ".$db['vote_results']." WHERE what = '".up($what)."' AND vid = '".$vid."'",false,true);
    return $get['sel'];
}

//Profilfelder konvertieren
function conv($txt) {
    return str_replace(array("ä","ü","ö","Ä","Ü","Ö","ß"), array("ae","ue","oe","Ae","Ue","Oe","ss"), $txt);
}

//PHPInfo in array lesen
function parsePHPInfo() {
    ob_start();
        phpinfo();
        $s = ob_get_contents();
    ob_end_clean();

    $s = strip_tags($s,'<h2><th><td>');
    $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
    $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
    $vTmp = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
    $vModules = array();
    for ($i=1;$i<count($vTmp);$i++) {
        if(preg_match('/<h2[^>]*>([^<]+)<\/h2>/',$vTmp[$i],$vMat)) {
            $vName = trim($vMat[1]);
            $vTmp2 = explode("\n",$vTmp[$i+1]);
            foreach ($vTmp2 AS $vOne) {
                $vPat = '<info>([^<]+)<\/info>';
                $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                $vPat2 = "/$vPat\s*$vPat/";

                if(preg_match($vPat3,$vOne,$vMat))
                    $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
                else if(preg_match($vPat2,$vOne,$vMat))
                    $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
            }
        }
    }

    return $vModules;
}

//-> Prueft, ob eine Userid existiert
function exist($tid) {
    global $db;
    return db("SELECT id FROM ".$db['users']." WHERE id = '".intval($tid)."'",true) ? true : false;
}

//-> Geburtstag errechnen
function getAge($bday) {
    if(!empty($bday) || $bday == '..') {
        list($tiday,$iMonth,$iYear) = explode(".",$bday);
        $iCurrentDay = date('j');
        $iCurrentMonth = date('n');
        $iCurrentYear = date('Y');

        if(($iCurrentMonth>$iMonth) || (($iCurrentMonth==$iMonth) && ($iCurrentDay>=$tiday)))
            return $iCurrentYear - $iYear;
        else
            return $iCurrentYear - ($iYear + 1);
    }
    else
        return '-';
}

//-> Ausgabe der Position des einzelnen Members
function getrank($tid, $squad="", $profil=0) {
    global $db;
    if($squad) {
        if($profil == 1)
            $qry = db("SELECT * FROM ".$db['userpos']." AS s1 LEFT JOIN ".$db['squads']." AS s2 ON s1.squad = s2.id WHERE s1.user = '".intval($tid)."' AND s1.squad = '".intval($squad)."' AND s1.posi != '0'");
        else
            $qry = db("SELECT * FROM ".$db['userpos']." WHERE user = '".intval($tid)."' AND squad = '".intval($squad)."' AND posi != '0'");

        if(_rows($qry)) {
            while($get = _fetch($qry)) {
                $getp = db("SELECT * FROM ".$db['pos']." WHERE id = '".intval($get['posi'])."'",false,true);
                if(!empty($get['name'])) $squadname = '<b>'.$get['name'].':</b> ';
                else $squadname = '';
                return $squadname.$getp['position'];
            }
        } else {
            $get = _fetch(db("SELECT level,banned FROM ".$db['users']." WHERE id = '".intval($tid)."'"));
            if(!$get['level'] && !$get['banned'])     return _status_unregged;
            else if($get['level'] == 1)               return _status_user;
            else if($get['level'] == 2)               return _status_trial;
            else if($get['level'] == 3)               return _status_member;
            else if($get['level'] == 4)               return _status_admin;
            else if(!$get['level'] && $get['banned']) return _status_banned;
            else return _gast;
        }
    } else {
        $qry = db("SELECT s1.*,s2.position FROM ".$db['userpos']." AS s1 LEFT JOIN ".$db['pos']." AS s2 ON s1.posi = s2.id WHERE s1.user = '".intval($tid)."' AND s1.posi != '0' ORDER BY s2.pid ASC");
        if(_rows($qry)) {
            $get = _fetch($qry);
            return $get['position'];
        } else {
            $get = _fetch(db("SELECT level,banned FROM ".$db['users']." WHERE id = '".intval($tid)."'"));
            if(!$get['level'] && !$get['banned'])    return _status_unregged;
            elseif($get['level'] == 1)               return _status_user;
            elseif($get['level'] == 2)               return _status_trial;
            elseif($get['level'] == 3)               return _status_member;
            elseif($get['level'] == 4)               return _status_admin;
            elseif(!$get['level'] && $get['banned']) return _status_banned;
            else return _gast;
        }
    }
}

//-> Session fuer den letzten Besuch setzen
function set_lastvisit() {
    global $db,$useronline,$userid;
    if($userid) {
        if(!db("SELECT id FROM ".$db['users']." WHERE id = ".intval($userid)." AND time+'".$useronline."'>'".time()."'",true)) {
            $_SESSION['lastvisit'] = data("time");
        }
    }
}

//-> Checkt welcher User gerade noch online ist
function onlinecheck($tid) {
    global $db,$useronline;
    $row = db("SELECT id FROM ".$db['users']." WHERE id = '".intval($tid)."' AND time+'".$useronline."'>'".time()."' AND online = 1",true);
    return $row ? "<img src=\"../inc/images/online.gif\" alt=\"\" class=\"icon\" />" : "<img src=\"../inc/images/offline.gif\" alt=\"\" class=\"icon\" />";
}

//Funktion fuer die Sprachdefinierung der Profilfelder
function pfields_name($name) {
    $pattern = array("=_city_=Uis","=_hobbys_=Uis","=_motto_=Uis","=_job_=Uis","=_exclans_=Uis","=_email2_=Uis","=_email3_=Uis","=_autor_=Uis","=_auto_=Uis","=_buch_=Uis",
    "=_drink_=Uis","=_essen_=Uis","=_favoclan_=Uis","=_film_=Uis","=_game_=Uis","=_map_=Uis","=_musik_=Uis","=_person_=Uis","=_song_=Uis","=_spieler_=Uis","=_sportler_=Uis",
    "=_sport_=Uis","=_waffe_=Uis","=_board_=Uis","=_cpu_=Uis","=_graka_=Uis","=_hdd_=Uis","=_headset_=Uis","=_inet_=Uis","=_maus_=Uis","=_mauspad_=Uis","=_monitor_=Uis",
    "=_ram_=Uis","=_system_=Uis");

    $replacement = array(_profil_city,_profil_hobbys,_profil_motto,_profil_job,_profil_exclans,_profil_email2,_profil_email3,_profil_autor,_profil_auto,
    _profil_buch,_profil_drink,_profil_essen,_profil_favoclan,_profil_film,_profil_game,_profil_map,_profil_musik,_profil_person,_profil_song,_profil_spieler,
    _profil_sportler,_profil_sport,_profil_waffe,_profil_board,_profil_cpu,_profil_graka,_profil_hdd,_profil_headset,_profil_inet,_profil_maus,_profil_mauspad,
    _profil_monitor,_profil_ram,_profil_os);

    return preg_replace($pattern, $replacement, $name);
}

//-> Checkt versch. Dinge anhand der Hostmaske eines Users
function ipcheck($what,$time = "") {
    global $db,$userip;
    $get = _fetch(db("SELECT time,what FROM ".$db['ipcheck']." WHERE what = '".$what."' AND ip = '".$userip."' ORDER BY time DESC"));
    if(preg_match("#vid#", $get['what']))
        return true;
    else {
        if($get['time']+$time<time())
            db("DELETE FROM ".$db['ipcheck']." WHERE what = '".$what."' AND ip = '".$userip."' AND time+'".$time."'<'".time()."'");

        if($get['time']+$time>time())
            return true;
        else
            return false;
    }
}

//-> Gibt die Tageszahl eines Monats aus
function days_in_month($month, $year)
{ return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); }

//-> Setzt bei einem Tag >10 eine 0 vorran (Kalender)
function cal($i) {
    if(preg_match("=10|20|30=Uis",$i) == FALSE) $i = preg_replace("=0=", "", $i);
    if($i < 10) $tag_nr = "0".$i;
    else $tag_nr = $i;
    return $tag_nr;
}

//-> Entfernt fuehrende Nullen bei Monatsangaben
function nonum($i) {
    if(preg_match("=10=Uis",$i) == false)
        return preg_replace("=0=", "", $i);

    return $i;
}

//-> maskiert Zeilenumbrueche fuer <textarea>
function txtArea($txt)
{ return $txt; }

//-> Konvertiert Platzhalter in die jeweiligen bersetzungen
function navi_name($name) {
    $name = trim($name);
    if(preg_match("#^_(.*?)_$#Uis",$name))
        @eval("\$name = _".preg_replace("#_(.*?)_#Uis", "$1", $name).";");

    return $name;
}

//RSS News Feed erzeugen
function convert_feed($txt) {
    global $charset;
    $txt = stripslashes($txt);
    $txt = str_replace("&Auml;","Ae",$txt);
    $txt = str_replace("&auml;","ae",$txt);
    $txt = str_replace("&Uuml;","Ue",$txt);
    $txt = str_replace("&uuml;","ue",$txt);
    $txt = str_replace("&Ouml;","Oe",$txt);
    $txt = str_replace("&ouml;","oe",$txt);
    $txt = htmlentities($txt, ENT_QUOTES, $charset);
    $txt = str_replace("&amp;","&",$txt);
    $txt = str_replace("&lt;","<",$txt);
    $txt = str_replace("&gt;",">",$txt);
    $txt = str_replace("&#60;","<",$txt);
    $txt = str_replace("&#62;",">",$txt);
    $txt = str_replace("&#34;","\"",$txt);
    $txt = str_replace("&nbsp;"," ",$txt);
    $txt = str_replace("&szlig;","ss",$txt);
    $txt = preg_replace("#&(.*?);#is","",$txt);
    $txt = str_replace("&","&amp;",$txt);
    $txt = str_replace("", "\"",$txt);
    $txt = str_replace("", "\"",$txt);
    return strip_tags($txt);
}

function feed() {
    global $db,$pagetitle,$charset;

    $host = $_SERVER['HTTP_HOST'];
    $pfad = preg_replace("#^(.*?)\/(.*?)#Uis","$1",dirname($_SERVER['PHP_SELF']));
    $feed = '<?xml version="1.0" encoding="'.$charset.'" ?>';
    $feed .= "\r\n";
    $feed .= '<rss version="0.91">';
    $feed .= "\r\n";
    $feed .= '<channel>';
    $feed .= "\r\n";
    $feed .= '  <title>'.convert_feed($pagetitle).'</title>';
    $feed .= "\r\n";
    $feed .= '  <link>http://'.$host.'</link>';
    $feed .= "\r\n";
    $feed .= '  <description>Clannews von '.convert_feed(settings('clanname')).'</description>';
    $feed .= "\r\n";
    $feed .= '  <language>de-de</language>';
    $feed .= "\r\n";
    $feed .= '  <copyright>'.date("Y", time()).' '.convert_feed(settings('clanname')).'</copyright>';
    $feed .= "\r\n";

    $data = @fopen("../rss.xml","w+");
    @fwrite($data, $feed);
    $qry = db("SELECT * FROM ".$db['news']." WHERE intern = 0 AND public = 1 ORDER BY datum DESC LIMIT 15");
    while($get = _fetch($qry)) {
        $get1 = _fetch(db("SELECT nick FROM ".$db['users']." WHERE id = '".$get['autor']."'"));
        $feed .= '  <item>';
        $feed .= "\r\n";
        $feed .= '    <pubDate>'.date("r", $get['datum']).'</pubDate>';
        $feed .= "\r\n";
        $feed .= '    <author>'.convert_feed($get1['nick']).'</author>';
        $feed .= "\r\n";
        $feed .= '    <title>'.convert_feed($get['titel']).'</title>';
        $feed .= "\r\n";
        $feed .= '    <description>';
        $feed .= convert_feed($get['text']);
        $feed .= '    </description>';
        $feed .= "\r\n";
        $feed .= '    <link>http://'.$host.$pfad.'/news/?action=show&amp;id='.$get['id'].'</link>';
        $feed .= "\r\n";
        $feed .= '  </item>';
        $feed .= "\r\n";
        $data = @fopen("../rss.xml","w+");
        @fwrite($data, $feed);
    }

    $feed .= '</channel>';
    $feed .= "\r\n";
    $feed .= '</rss>';
    $data = @fopen("../rss.xml","w+");
    @fwrite($data, $feed);
}

// Userpic ausgeben
function userpic($userid, $width=170,$height=210) {
    global $picformat;
    foreach($picformat as $endung) {
        if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$endung)) {
            $pic = show(_userpic_link, array("id" => $userid, "endung" => $endung, "width" => $width, "height" => $height));
            break;
        }
        else
            $pic = show(_no_userpic, array("width" => $width, "height" => $height));
    }

    return $pic;
}

// Useravatar ausgeben
function useravatar($uid=0, $width=100,$height=100) {
    global $picformat,$userid;
    $uid = $uid == 0 ? $userid : $uid;
    foreach($picformat as $endung) {
        if(file_exists(basePath."/inc/images/uploads/useravatare/".$uid.".".$endung))
        {
            $pic = show(_userava_link, array("id" => $uid, "endung" => $endung, "width" => $width, "height" => $height));
            break;
        }
        else
            $pic = show(_no_userava, array("width" => $width, "height" => $height));
    }

    return $pic;
}

// Userpic fuer Hoverinformationen ausgeben
function hoveruserpic($userid, $width=170,$height=210) {
    global $picformat;
    foreach($picformat as $endung) {
        if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$endung)) {
            $pic = "../inc/images/uploads/userpics/".$userid.".".$endung."', '".$width."', '".$height."";
            break;
        }
        else
            $pic = "../inc/images/nopic.gif".$userid.".".$endung."', '".$width."', '".$height."";
    }

    return $pic;
}

// Adminberechtigungen ueberpruefen
function admin_perms($userid) {
    global $db,$chkMe;

    if(empty($userid))
        return false;

   // no need for these admin areas
    $e = array('gb', 'shoutbox', 'editusers', 'votes', 'contact', 'joinus', 'intnews', 'forum', 'gs_showpw');

   // check user permission
    $c = db("SELECT * FROM ".$db['permissions']." WHERE user = '".intval($userid)."'",false,true);
    if(!empty($c)) {
        foreach($c AS $v => $k) {
            if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e)) {
                if($k == 1) {
                    return true;
                    break;
                }
            }
        }
    }

   // check rank permission
    $qry = db("SELECT s1.* FROM ".$db['permissions']." AS s1 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi` WHERE s2.`user` = '".intval($userid)."' AND s2.`posi` != '0'");
    while($r = _fetch($qry)) {
        foreach($r AS $v => $k) {
            if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e)) {
                if($k == 1) {
                    return true;
                    break;
                }
            }
        }
    }

    return ($chkMe == 4) ? true : false;
}

//-> blacklist um spider/crawler von der Besucherstatistik auszuschliessen
function isSpider() {
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    $ex = explode("\n", file_get_contents(basePath.'/inc/_spiders.txt'));
    for($i=0;$i<=count($ex)-1;$i++) {
        if(stristr($uagent, trim($ex[$i])))
            return true;
    }

    return false;
}

//-> filter placeholders
function pholderreplace($pholder) {
    $search = array('@<script[^>]*?>.*?</script>@si',
                    '@<style[^>]*?>.*?</style>@siU',
                    '@<[\/\!]*?[^<>]*?>@si',
                    '@<![\s\S]*?--[ \t\n\r]*>@');
    //Replace
    $pholder = preg_replace("#<script(.*?)</script>#is","",$pholder);
    $pholder = preg_replace("#<style(.*?)</style>#is","",$pholder);
    $pholder = preg_replace($search, '', $pholder);
    $pholder = str_replace(" ","",$pholder);
    $pholder = preg_replace("#[0-9]#is","",$pholder);
    $pholder = preg_replace("#&(.*?);#s","",$pholder);
    $pholder = str_replace("\r","",$pholder);
    $pholder = str_replace("\n","",$pholder);
    $pholder = preg_replace("#\](.*?)\[#is","][",$pholder);
    $pholder = str_replace("][","^",$pholder);
    $pholder = preg_replace("#^(.*?)\[#s","",$pholder);
    $pholder = preg_replace("#\](.*?)$#s","",$pholder);
    $pholder = str_replace("[","",$pholder);
    return str_replace("]","",$pholder);
}

//-> Zugriffsberechtigung auf die Seite
function check_internal_url() {
    global $db,$chkMe;
    if($chkMe >= 1) return false;
    $install_pfad = explode("/",dirname(dirname($_SERVER['SCRIPT_NAME'])."../"));
    $now_pfad = explode("/",$_SERVER['REQUEST_URI']); $pfad = '';
    foreach($now_pfad as $key => $value) {
        if(!empty($value)) {
            if(!isset($install_pfad[$key]) || $value != $install_pfad[$key]) {
                $pfad .= "/".$value;
            }
        }
    }

    list($pfad) = explode('&',$pfad);
    $pfad = "..".$pfad;

    if(strpos($pfad, "?") === false && strpos($pfad, ".php") === false)
        $pfad .= "/";

    if(strpos($pfad, "index.php") !== false)
        $pfad = str_replace('index.php','',$pfad);

    $qry_navi = db("SELECT `internal` FROM ".$db['navi']." WHERE `url` = '".$pfad."' OR `url` = '".$pfad.'index.php'."'");
    if(_rows($qry_navi)) {
        $get_navi = _fetch($qry_navi);
        if($get_navi['internal'])
            return true;
    }

    return false;
}

//-> Ladezeit
function generatetime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

//-> Rechte abfragen
function getPermissions($checkID = 0, $pos = 0) {
    global $db;

    if(!empty($checkID)) {
        $check = empty($pos) ? 'user' : 'pos'; $checked = array();
        $qry = db("SELECT * FROM ".$db['permissions']." WHERE `".$check."` = '".intval($checkID)."'");
        if(_rows($qry)) foreach(_fetch($qry) AS $k => $v) $checked[$k] = $v;
    }

    $permission = array();
    $qry = db("SHOW COLUMNS FROM ".$db['permissions']."");
    while($get = _fetch($qry)) {
        if($get['Field'] != 'id' && $get['Field'] != 'user' && $get['Field'] != 'pos' && $get['Field'] != 'intforum') {
            @eval("\$lang = _perm_".$get['Field'].";");
            $chk = empty($checked[$get['Field']]) ? '' : ' checked="checked"';
            $permission[$lang] = '<input type="checkbox" class="checkbox" id="'.$get['Field'].'" name="perm[p_'.$get['Field'].']" value="1"'.$chk.' /><label for="'.$get['Field'].'"> '.$lang.'</label> ';
        }
    }

    natcasesort($permission); $break = 1; $p = '';
    foreach($permission AS $perm) {
        $br = ($break % 2) ? '<br />' : ''; $break++;
        $p .= $perm.$br;
    }

    return $p;
}

//-> interne Foren-Rechte abfragen
function getBoardPermissions($checkID = 0, $pos = 0) {
    global $db, $dir;

    $break = 0; $i_forum = ''; $fkats = '';
    $qry = db("SELECT id,name FROM ".$db['f_kats']." WHERE intern = '1' ORDER BY `kid` ASC");
    while($get = _fetch($qry)) {
        unset($kats, $fkats, $break);
        $kats = (empty($katbreak) ? '' : '<div style="clear:both">&nbsp;</div>').'<table class="hperc" cellspacing="1"><tr><td class="contentMainTop"><b>'.re($get["name"]).'</b></td></tr></table>';
        $katbreak = 1;

        $qry2 = db("SELECT kattopic,id FROM ".$db['f_skats']." WHERE `sid` = '".$get['id']."' ORDER BY `kattopic` ASC");
        while($get2 = _fetch($qry2)) {
            $br = ($break % 2) ? '<br />' : ''; $break++;
            $check =  db("SELECT * FROM ".$db['f_access']." WHERE `".(empty($pos) ? 'user' : 'pos')."` = '".intval($checkID)."' AND ".(empty($pos) ? 'user' : 'pos')." != '0' AND `forum` = '".$get2['id']."'");
            $chk = _rows($check) ? ' checked="checked"' : '';
            $fkats .= '<input type="checkbox" class="checkbox" id="board_'.$get2['id'].'" name="board['.$get2['get2'].']" value="'.$get2['id'].'"'.$chk.' /><label for="board_'.$get2['id'].'"> '.re($get2['kattopic']).'</label> '.$br;
        }

        $i_forum .= $kats.$fkats;
    }

    return $i_forum;
}

//-> schreibe in dei IPCheck Tabelle
function setIpcheck($what = '') {
    global $db, $userip;
    db("INSERT INTO ".$db['ipcheck']." SET `ip` = '".$userip."', `what` = '".$what."', `time` = '".time()."'");
}

function hextobin($hexstr) {
    if(is_php('5.4.0'))
        return hex2bin($hexstr);
    // < PHP 5.4
    $n = strlen($hexstr);
    $sbin="";
    $i=0;
    while($i<$n) {
        $a =substr($hexstr,$i,2);
        $c = pack("H*",$a);
        if ($i==0){$sbin=$c;}
        else {$sbin.=$c;}
        $i+=2;
    }

    return $sbin;
}

//-> Speichert Rückgaben der MySQL Datenbank zwischen um SQL-Queries einzusparen
final class dbc_index
{
    private static $index = array();

    public static final function setIndex($index_key,$data) {
        global $cache;

        if(self::MemSetIndex()) {
            $cache->set('dbc_'.$index_key, serialize($data), 2);
        }

        self::$index[$index_key] = $data;
    }

    public static final function getIndex($index_key) {
        if(!array_key_exists($index_key,self::$index))
            return false;

        return self::$index[$index_key];
    }

    public static final function getIndexKey($index_key,$key) {
        if(!array_key_exists($index_key,self::$index))
            return false;

        $data = self::$index[$index_key];
        if(empty($data) || !array_key_exists($key,$data))
            return false;

        return $data[$key];
    }

    public static final function issetIndex($index_key) {
        global $cache;

        if(self::MemSetIndex() && $cache->isExisting('dbc_'.$index_key)) {
            self::$index[$index_key] = unserialize($cache->get('dbc_'.$index_key));
            return true;
        }

        return array_key_exists($index_key,self::$index);
    }

    private static final function MemSetIndex() {
        global $config_cache;
        if(!$config_cache['dbc']) return false;
        switch ($config_cache['storage']) {
            case 'apc': return (extension_loaded('apc') && ini_get('apc.enabled') && strpos(PHP_SAPI,"CGI") === false); break;
            case 'memcached': return (ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && class_exists("memcached")); break;
            case 'memcache': return (ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && function_exists("memcache_connect")); break;
            case 'xcache': return (extension_loaded('xcache') && function_exists("xcache_get")); break;
            case 'wincache': return (extension_loaded('wincache') && function_exists("wincache_ucache_set")); break;
            case 'auto':
                return ((extension_loaded('apc') && ini_get('apc.enabled') && strpos(PHP_SAPI,"CGI") === false) ||
                       ($config_cache['dbc_auto_memcache'] && ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && class_exists("memcached")) ||
                       ($config_cache['dbc_auto_memcache'] && ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && function_exists("memcache_connect")) ||
                       (extension_loaded('xcache') && function_exists("xcache_get")) ||
                       (extension_loaded('wincache') && function_exists("wincache_ucache_set")));
            break;
            default: return false; break;
        }

        return false;
    }
}

//-> Neue Languages einbinden, sofern vorhanden
if($language_files = get_files(basePath.'/inc/additional-languages/'.$language.'/',false,true,array('php'))) {
    foreach($language_files AS $languages)
    { include(basePath.'/inc/additional-languages/'.$language.'/'.$languages); }
    unset($language_files,$languages);
}

//-> Neue Funktionen einbinden, sofern vorhanden
if($functions_files = get_files(basePath.'/inc/additional-functions/',false,true,array('php'))) {
    foreach($functions_files AS $func)
    { include(basePath.'/inc/additional-functions/'.$func); }
    unset($functions_files,$func);
}

//-> Navigation einbinden
include_once(basePath.'/inc/menu-functions/navi.php');

//-> Timer Start
$time_start = generatetime();

//-> Ausgabe des Indextemplates
function page($index='',$title='',$where='',$wysiwyg='',$index_templ='index')
{
    global $db,$userid,$userip,$tmpdir,$chkMe,$charset,$mysql;
    global $designpath,$language,$cp_color,$copyright,$time_start;

    // user gebannt? Logge aus!
    if(isBanned()) header("Location: ../news/");

    // Timer Stop
    $time = round(generatetime() - $time_start,4);

    // JS-Dateine einbinden
    $lng = ($language=='deutsch')?'de':'en';
    $edr = ($wysiwyg=='_word')?'advanced':'normal';
    $lcolor = ($cp_color==1)?'lcolor=true;':'';
    $java_vars = '<script language="javascript" type="text/javascript">var maxW = '.config('maxwidth').',lng = \''.$lng.'\',dzcp_editor = \''.$edr.'\';'.$lcolor.'</script>'."\n";

    if(!strstr($_SERVER['HTTP_USER_AGENT'],'Android') && !strstr($_SERVER['HTTP_USER_AGENT'],'webOS'))
        $java_vars .= '<script language="javascript" type="text/javascript" src="'.$designpath.'/_js/wysiwyg.js"></script>'."\n";;

    if(settings("wmodus") && $chkMe != 4) {
        if(config('securelogin'))
            $secure = show("menu/secure", array("help" => _login_secure_help, "security" => _register_confirm));

        $login = show("errors/wmodus_login", array("what" => _login_login, "secure" => $secure, "signup" => _login_signup, "permanent" => _login_permanent, "lostpwd" => _login_lostpwd));
        echo show("errors/wmodus", array("wmodus" => _wartungsmodus,
                                         "head" => _wartungsmodus_head,
                                         "tmpdir" => $tmpdir,
                                         "java_vars" => $java_vars,
                                         "dir" => $designpath,
                                         "title" => re(strip_tags($title)),
                                         "login" => $login));
    } else {
        updateCounter();
        update_maxonline();

        //check permissions
        if(!$chkMe)
            include_once(basePath.'/inc/menu-functions/login.php');
        else {
            $check_msg = check_msg(); set_lastvisit(); $login = "";
            db("UPDATE ".$db['users']." SET `time` = '".((int)time())."', `whereami` = '".up($where)."' WHERE id = '".intval($userid)."'");
        }

        //init templateswitch
        $tmpldir=""; $tmps = get_files('../inc/_templates_/',true);
        foreach ($tmps as $tmp) {
            $selt = ($tmpdir == $tmp ? 'selected="selected"' : '');
            $tmpldir .= show(_select_field, array("value" => "?tmpl_set=".$tmp,  "what" => $tmp,  "sel" => $selt));
        }

        //misc vars
        $template_switch = show("menu/tmp_switch", array("templates" => $tmpldir));
        $clanname = re(settings("clanname"));
        $time = show(_generated_time, array("time" => $time));
        $headtitle = show(_index_headtitle, array("clanname" => $clanname));
        $rss = $clanname;
        $dir = $designpath;
        $title = re(strip_tags($title));

        if(check_internal_url())
            $index = error(_error_have_to_be_logged, 1);

        $where = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return data("nick","$id[1]");'),$where);
        $index = empty($index) ? '' : (empty($check_msg) ? '' : $check_msg).'<table class="mainContent" cellspacing="1" style="margin-top:0">'.$index.'</table>';

        //-> Sort & filter placeholders
        //default placeholders
        $arr = array("idir" => '../inc/images/admin', "dir" => $designpath);

        //check if placeholders are given
        $pholder = file_get_contents($designpath."/index.html");

        //filter placeholders
        $blArr = array("[clanname]","[title]","[copyright]","[java_vars]","[login]", "[template_switch]","[headtitle]","[index]", "[time]","[rss]","[dir]","[charset]","[where]");
        $pholdervars = '';
        for($i=0;$i<=count($blArr)-1;$i++) {
            if(preg_match("#".$blArr[$i]."#",$pholder))
                $pholdervars .= $blArr[$i];
        }

        for($i=0;$i<=count($blArr)-1;$i++)
            $pholder = str_replace($blArr[$i],"",$pholder);

        $pholder = pholderreplace($pholder);
        $pholdervars = pholderreplace($pholdervars);

        //put placeholders in array
        $pholder = explode("^",$pholder);
        for($i=0;$i<=count($pholder)-1;$i++) {
            if(strstr($pholder[$i], 'nav_'))
                $arr[$pholder[$i]] = navi($pholder[$i]);
            else {
                if(@file_exists(basePath.'/inc/menu-functions/'.$pholder[$i].'.php'))
                    include_once(basePath.'/inc/menu-functions/'.$pholder[$i].'.php');

                if(function_exists($pholder[$i]))
                    $arr[$pholder[$i]] = $pholder[$i]();
            }
        }

        $pholdervars = explode("^",$pholdervars);
        for($i=0;$i<=count($pholdervars)-1;$i++)
        { $arr[$pholdervars[$i]] = $$pholdervars[$i]; }

        //index output
        $index = (file_exists("../inc/_templates_/".$tmpdir."/".$index_templ.".html") ? show($index_templ, $arr) : show("index", $arr));
        $mysql->close(); //MySQL
        echo view_error_reporting ? DebugConsole::show_logs().$index : $index; //Debug Console + Index Out
    }
}