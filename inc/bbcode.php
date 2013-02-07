<?php
## Error Reporting ##
if(is_debug)
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
else
    error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

## INCLUDES/REQUIRES ##
require_once(basePath.'/inc/secure.php');
require_once(basePath.'/inc/_version.php');
require_once(basePath.'/inc/sendmail.php');
require_once(basePath.'/inc/kernel.php');
require_once(basePath.'/inc/server_query/_functions.php');
require_once(basePath."/inc/teamspeak_query.php");

## Is AjaxJob ##
$ajaxJob = (!isset($ajaxJob) ? false : $ajaxJob);

## FUNCTIONS ##
//-> Legt die UserID desRootadmins fest
//-> (dieser darf bestimmte Dinge, den normale Admins nicht duerfen, z.B. andere Admins editieren)
$rootAdmin = 1;

//-> Settingstabelle auslesen
$settings = db("SELECT * FROM ".$db['settings'],false,true);
$prev = $settings['prev'].'_';

//-> Language auslesen
$language = (isset($_COOKIE[$prev.'language']) ? (file_exists(basePath.'/inc/lang/languages/'.$_COOKIE[$prev.'language'].'.php') ? $_COOKIE[$prev.'language'] : $settings["language"]) : $settings["language"]);

//einzelne Definitionen
$isSpider = isSpider();
$subfolder = basename(dirname(dirname($_SERVER['PHP_SELF']).'../'));
$httphost = $_SERVER['HTTP_HOST'].(empty($subfolder) ? '' : '/'.$subfolder);
$domain = str_replace('www.','',$httphost);
$pagetitle = $settings["pagetitel"];
$clanname = $settings["clanname"];
$mailfrom = $settings["mailfrom"];
$double_post = $settings["double_post"];
$forum_vote = $settings["forum_vote"];
$gb_activ = $settings["gb_activ"];
$gametiger_game = $settings["gametiger"];
$ts_ip = $settings["ts_ip"];
$ts_port = $settings["ts_port"];
$balken_cw = $settings["balken_cw"];
$balken_vote = $settings["balken_vote"];
$balken_vote_menu = $settings["balken_vote_menu"];
$i_domain = $settings["i_domain"];
$i_autor = $settings["i_autor"];
$counter_start = $settings["counter_start"];
$sdir = $settings['tmpdir'];
$useronline = 1800;
$reload = 3600 * 24;
$datum = time();
$today = date("j.n.Y");
$picformat = array("jpg", "gif", "png");

//-> Configtabelle auslesen
$config = db("SELECT * FROM ".$db['config'],false,true);

//-> Config
$maxadmincw = 10;
$maxfilesize = @ini_get('upload_max_filesize');
$teamRow = $config['teamrow'];
$allowHover = $config['allowhover'];
$gallery = $config['gallery'];
$upicsize = $config['upicsize'];
$maxgallerypics = $config['m_gallerypics'];
$maxusergb = $config['m_usergb'];
$maxclankasse = $config['m_clankasse'];
$maxbanned = $config['m_banned'];
$maxadminnews = $config['m_adminnews'];
$maxadminartikel = $config["m_adminartikel"];
$martikel = $config["m_artikel"];
$maxshout = $config['m_shout'];
$maxcomments = $config['m_comments'];
$maxcwcomments = $config['m_cwcomments'];
$maxarchivnews = $config['m_archivnews'];
$maxgb = $config['m_gb'];
$maxfthreads = $config['m_fthreads'];
$maxcw = $config['m_clanwars'];
$maxfposts = $config['m_fposts'];
$maxnews = $config['m_news'];
$maxftopics = $config['m_ftopics'];
$maxevent = $config['m_events'];
$maxlnews = $config['m_lnews'];
$maxlartikel = $config['m_lartikel'];
$maxtopdl = $config['m_topdl'];
$maxlwars = $config['m_lwars'];
$maxnwars = $config['m_nwars'];
$maxlreg = $config['m_lreg'];
$maxaway = $config['m_away'];
$maxshoutarchiv = $config['maxshoutarchiv'];
$shout_max_zeichen = $config['shout_max_zeichen'];
$maxpicwidth = 90;
$flood_forum = $config['f_forum'];
$flood_gb = $config['f_gb'];
$flood_membergb = $config['f_membergb'];
$flood_shout = $config['f_shout'];
$flood_newscom = $config['f_newscom'];
$flood_artikelcom = $config['f_artikelcom'];
$flood_cwcom = $config['f_cwcom'];
$lnewsadmin = $config['l_newsadmin'];
$lshouttext = $config['l_shouttext'];
$lshoutnick = $config['l_shoutnick'];
$lnews = $config['l_lnews'];
$lartikel = $config['l_lartikel'];
$ltopdl = $config['l_topdl'];
$lftopics = $config['l_ftopics'];
$llwars = $config['l_lwars'];
$llreg = $config['l_lreg'];
$servermenu = $config['l_servernavi'];
$lnwars = $config['l_nwars'];
$lnewsarchiv = $config['l_newsarchiv'];
$lcwgegner = $config['l_clanwars'];
$l_team = $config['l_team'];
$lforumtopic = $config['l_forumtopic'];
$lforumsubtopic = $config['l_forumsubtopic'];
$maxawards = $config['m_awards'];
unset($config);

if(isset($_COOKIE[$prev.'id']) && isset($_COOKIE[$prev.'pwd']) && empty($_SESSION['id']))
{
    $_SESSION['id']  = intval($_COOKIE[$prev.'id']);
    $_SESSION['pwd'] = $_COOKIE[$prev.'pwd'];
    $_SESSION['ip']  = VisitorIP();
    
    if(data(intval($_COOKIE[$prev.'id']), "ip") != $_SESSION['ip'])
    {
        db("UPDATE ".$db['userstats']." SET `logins` = logins+1 WHERE user = '".intval($_COOKIE[$prev.'id'])."'");
        db("UPDATE ".$db['users']." SET `online` = 1, `sessid` = '".session_id()."', `ip` = '".VisitorIP()."' WHERE id = ".intval($_COOKIE[$prev.'id']));
        $_SESSION['lastvisit'] = data(intval($_COOKIE[$prev.'id']), "time");
    }
    
    if(empty($_SESSION['lastvisit']))
        $_SESSION['lastvisit'] = data(intval($_COOKIE[$prev.'id']), "time");
}

$userid = userid();
$chkMe = checkme();

if($chkMe == "unlogged")
{
    $_SESSION['id']        = '';
    $_SESSION['pwd']       = '';
    $_SESSION['ip']        = '';
    $_SESSION['lastvisit'] = '';
}

//-> Auslesen der UserID
function userid()
{
    global $db;
  
    if(empty($_SESSION['id']) || empty($_SESSION['pwd']))
        return false;
      
    $get = db("SELECT id FROM ".$db['users']." WHERE id = '".$_SESSION['id']."' AND pwd = '".$_SESSION['pwd']."'",false,true);
    return $get['id'];
}

//-> Templateswitch
$files = get_files('../inc/_templates_/',true,false);
if(isset($_COOKIE[$prev.'tmpdir']) && $_COOKIE[$prev.'tmpdir'] != NULL)
    $tmpdir = (file_exists(basePath."/inc/_templates_/".$_COOKIE[$prev.'tmpdir']."/index.html") ? $_COOKIE[$prev.'tmpdir'] : $files[0]);
else 
    $tmpdir = (file_exists(basePath."/inc/_templates_/".$sdir."/index.html") ? $sdir : $files[0]);

$designpath = '../inc/_templates_/'.$tmpdir;

//-> Languagefiles einlesen
function lang($lng,$pfad='')
{
	if(!file_exists(basePath."/inc/lang/languages/".$lng.".php"))
	{
		$files = get_files(basePath.'/inc/lang/languages/',false,true,array('php'));
		
		if(!count($files))
		   die('No language files found in "inc/lang/languages/*"!'); 
		   
		$lng = str_replace('.php','',$files[0]);
	}

	require_once(basePath."/inc/lang/global.php");
	require_once(basePath."/inc/lang/languages/".$lng.".php");
	
	header("Content-type: text/html; charset="._charset);
}

//-> Sprachdateien auflisten
function languages()
{
	$lang="";
	$files = get_files('../inc/lang/languages/',false,true,array('php'));
	for($i=0;$i<=count($files)-1;$i++)
	{
		$file = str_replace('.php','',$files[$i]);
		$upFile = strtoupper(substr($file,0,1)).substr($file,1);

		if(file_exists('../inc/lang/flaggen/'.$file.'.gif'))
			$lang .= '<a href="../user/?action=language&amp;set='.$file.'"><img src="../inc/lang/flaggen/'.$file.'.gif" alt="'.$upFile.'" title="'.$upFile.'" class="icon" /></a> ';
	}

	return $lang;
}

//-> Userspezifiesche Dinge
$u_b1 = ""; $u_b2 = "";
if(isset($userid) && $ajaxJob != true && $userid != false)
{
	db("UPDATE ".$db['userstats']." SET `hits` = hits+1, `lastvisit` = '".((int)$_SESSION['lastvisit'])."'  WHERE user = ".$userid);
	$u_b1 = "<!--";
	$u_b2 = "-->";
}

//-> PHP-Code farbig anzeigen
function highlight_text($txt)
{
    while(preg_match("=\[php\](.*)\[/php\]=Uis",$txt)!=FALSE)
    {
        $res = preg_match("=\[php\](.*)\[/php\]=Uis", $txt, $matches);
        $src = str_replace('<?php','',$matches[1]);
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

        $colors = array(
        '#111111' => 'string', '#222222' => 'comment', '#333333' => 'keyword',
        '#444444' => 'bg',     '#555555' => 'default', '#666666' => 'html');

        foreach ($colors as $color => $key)
            ini_set('highlight.'.$key, $color);
        
        // Farben ersetzen & highlighten
        $src = preg_replace('!style="color: (#\d{6})"!e','"class=\"".$prefix.$colors["\1"]."\""', highlight_string($src, TRUE));

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
        $lines = '';
        for($i=1;$i<=count($l)+1;$i++)
            $lines .= $i.".<br />";
        // Ausgabe
        $code = '<div class="codeHead">&nbsp;&nbsp;&nbsp;Code:</div><div class="code"><table style="width:100%;padding:0px" cellspacing="0"><tr><td class="codeLines">'.$lines.'</td><td class="codeContent">'.$src.'</td></table></div>';
        $txt = preg_replace("=\[php\](.*)\[/php\]=Uis",$code,$txt,1);
    }
    
    return $txt;
}

function regexChars($txt)
{
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

//-> Glossarfunktion *Buggy
if(glossar_enabled)
{
    $gl_words = array();
    $gl_desc = array();
    $qryglossar = db("SELECT * FROM ".$db['glossar']);
    while($getglossar = _fetch($qryglossar))
    {
      $gl_words[] = re($getglossar['word']);
      $gl_desc[]  = $getglossar['glossar'];
    }
}

function glossar($txt)
{
  global $db,$gl_words,$gl_desc;

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
    $desc = regexChars(html_entity_decode($gl_desc[$g]));
    $info = 'onmouseover="DZCP.showInfo(\''.jsconvert($desc).'\')" onmouseout="DZCP.hideInfo()"';

    $w = regexChars(html_entity_decode($gl_words[$g]));
    $r = "<a class=\"glossar\" href=\"../glossar/?word=".$gl_words[$g]."\" ".$info.">".$gl_words[$g]."</a>";

    $txt = str_ireplace('<tmp|'.$w.'|tmp>', $r, $txt);
  }

  $txt = str_replace(']','&#93;',$txt);
  $txt = str_replace('[','&#91;',$txt);

  return $txt;
}

function bbcodetolow($founds) 
{
	return "[".strtolower($founds[1])."]".trim($founds[2])."[/".strtolower($founds[3])."]";
}

//-> Replaces
function replace($txt, $tinymce=true, $no_vid_tag=false)
{
    $txt = str_replace("&#34;","\"",$txt);

    if($tinymce)
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

    if(!$no_vid_tag)
        $txt = preg_replace_callback("#\[youtube\]http\:\/\/www.youtube.com\/watch\?v\=(.*)\[\/youtube\]#Uis", create_function('$yt','$width = 425; $height = 344; return "<object width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"".$width."\" height=\"".$height."\"></embed></object>";'), $txt);

    $txt = str_replace("\"","&#34;",$txt);
    return preg_replace("#(\w){1,1}(&nbsp;)#Uis","$1 ",$txt);
}

//-> Badword Filter
function BadwordFilter($txt)
{
    $words = explode(",",trim(settings('badwords')));
    if(count($words) >= 1)
    {
        foreach($words as $word)
        {
    		$txt = preg_replace("#".$word."#i", str_repeat("*", strlen($word)), $txt);
        }
    }

	return $txt;
}

//-> Funktion um Bestimmte Textstellen zu markieren
function hl($text, $word)
{
    $ret = array('text' => $text, 'class' => 'class="commentsRight"');
    if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'text')
    {
        if($_SESSION['search_con'] == 'or')
        {
            $words = explode(" ",$word);
            for($x=0;$x<count($words);$x++)
                $ret['text'] = preg_replace("#".$words[$x]."#i",'<span class="fontRed" title="'.$words[$x].'">'.$words[$x].'</span>',$text);
        } 
        else 
            $ret['text'] = preg_replace("#".$word."#i",'<span class="fontRed" title="'.$word.'">'.$word.'</span>',$text);

        $ret['class'] = (!preg_match("#<span class=\"fontRed\" title=\"(.*?)\">#", $ret['text']) ? 'class="commentsRight"' : 'class="highlightSearchTarget"');
    } 

    return $ret;
}

//-> Emailadressen in Unicode umwandeln
function eMailAddr($email)
{
    $output = '';
    for($i=0;$i<strlen($email);$i++)
    { 
        $output.=str_replace(substr($email,$i,1),"&#".ord(substr($email,$i,1)).";",substr($email,$i,1)); 
    }

    return $output;
}

//-> Leerzeichen mit + ersetzen (w3c)
function convSpace($string)
{
	return str_replace(" ","+",$string);
}

//-> BBCode
function re_bbcode($txt)
{
    $search  = array("'","[","]","&lt;","&gt;");
    $replace = array("&#39;","&#91;","&#93;","&#60;","&#62;");
    return stripslashes(str_replace($search, $replace, $txt));
}

/* START # from wordpress under GBU GPL license
   URL autolink function */
function _make_url_clickable_cb($matches) {
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
	$ret = trim($ret);
	return $ret;
}

/* END # from wordpress under GBU GPL license */

//Diverse BB-Codefunktionen
function bbcode($txt, $tinymce=0, $no_vid=false, $ts=false, $nolink=false)
{
    if(!$no_vid && settings('urls_linked') && !$nolink)
        $txt = make_clickable($txt);
    
    $txt = str_replace("\\","\\\\",$txt);
    $txt = str_replace("\\n","<br />",$txt);
    $txt = BadwordFilter($txt);
    $txt = replace($txt,$tinymce,$no_vid);
    $txt = highlight_text($txt);
    $txt = re_bbcode($txt);
    
    if(!$ts)
        $txt = strip_tags($txt,"<br><object><em><param><embed><strong><iframe><hr><table><tr><td><div><span><a><b><font><i><u><p><ul><ol><li><br /><img>");

    $txt = smileys($txt);

    if(!$no_vid && glossar_enabled)
        $txt = glossar($txt);

    $txt = str_replace("&#34;","\"",$txt);
    return str_replace('<p></p>', '<p>&nbsp;</p>', $txt);
}

function bbcode_nletter($txt)
{
    $txt = stripslashes($txt);
    $txt = nl2br(trim($txt));
    return '<style type="text/css">p { margin: 0px; padding: 0px; }</style>'.$txt;
}

function bbcode_nletter_plain($txt)
{
    $txt = preg_replace("#\<\/p\>#Uis","\r\n",$txt);
    $txt = preg_replace("#\<br(.*?)\>#Uis","\r\n",$txt);
    $txt = str_replace("p { margin: 0px; padding: 0px; }","",$txt);
    $txt = convert_feed($txt);
    $txt = str_replace("&amp;#91;","[",$txt);
    $txt = str_replace("&amp;#93;","]",$txt);
    return strip_tags($txt);
}

function bbcode_html($txt,$tinymce=0)
{
    $txt = str_replace("&lt;","<",$txt);
    $txt = str_replace("&gt;",">",$txt);
    $txt = str_replace("&quot;","\"",$txt);
    $txt = BadwordFilter($txt);
    $txt = replace($txt,$tinymce);
    $txt = highlight_text($txt);
    $txt = re_bbcode($txt);
    $txt = smileys($txt);
  
    if(glossar_enabled)
        $txt = glossar($txt);
  
    return str_replace("&#34;","\"",$txt);
}

//-> Textteil in Zitat-Tags setzen
function zitat($nick,$zitat)
{
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
function re($txt)
{
    $txt = stripslashes($txt);
    $txt = str_replace("& ","&amp; ",$txt);
    $txt = str_replace("[","&#91;",$txt);
    $txt = str_replace("]","&#93;",$txt);
    $txt = str_replace("\"","&#34;",$txt);
    $txt = str_replace("<","&#60;",$txt);
    $txt = str_replace(">","&#62;",$txt);
    $txt = str_replace("(", "&#40;", $txt);
    $txt = str_replace(")", "&#41;", $txt);
    return htmlspecialchars_decode($txt);
}

function re_entry($txt)
{
    return stripslashes($txt);
}

//-> Smileys ausgeben
function smileys($txt)
{
    $files = get_files('../inc/images/smileys',false,true,array('gif'));
    for($i=0; $i<count($files); $i++)
    {
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

//-> Funktion um Ausgaben zu kuerzen
function cut($str, $length = 0, $dots = true)
{
    if($length == 0) 
        return ''; 
  
    $start = 0;
    $dots = ($dots == true && strlen(html_entity_decode($str)) > $length) ? '...' : '';

    if(strpos($str, '&') === false)
        return (($length === null) ? substr($str, $start) : substr($str, $start, $length)).$dots;

    $chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
    $html_length = count($chars);

    if(($start >= $html_length) || (isset($length) && ($length <= -$html_length)))
        return '';

    if($start >= 0) 
        $real_start = $chars[$start][1];
    else 
    {
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

function wrap($str, $width = 75, $break = "\n", $cut = true)
{
    return strtr(str_replace(htmlentities($break), $break, htmlentities(wordwrap(html_entity_decode($str), $width, $break, $cut), ENT_QUOTES)), array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_COMPAT)));
}

//-> Funktion um Sonderzeichen zu konvertieren
function spChars($txt)
{
    $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "€");
    $replace = array("&Auml;", "&Ouml;", "&Uuml;", "&auml;", "&ouml;", "&uuml;", "&szlig;", "&euro;");
    return str_replace($search, $replace, $txt);
}

//-> Funktion um sauber in die DB einzutragen
function up($txt, $bbcode=0, $charset=_charset)
{
    $txt = str_replace("& ","&amp; ",$txt);
    $txt = str_replace("\"","&#34;",$txt);

    if(empty($bbcode))
        $txt = nl2br(htmlentities(html_entity_decode($txt), ENT_QUOTES, $charset));

    return trim(spChars($txt));
}

//-> Funktion um einer id einen Nick zuzuweisen
function nick_id($tid)
{
    global $db;
    $get = db("SELECT nick FROM ".$db['users']." WHERE id = '".$tid."'",false,true);
    return $get['nick'];
}

//-> Funktion um ein Datenbankinhalt zu highlighten
function highlight($word)
{
    return (is_php('5.0.0') ? str_ireplace($word,'<span class="fontRed">'.$word.'</span>',$word) : str_replace($word,'<span class="fontRed">'.$word.'</span>',$word));
}

//-> Counter updaten
function updateCounter()
{
    global $db,$reload,$today,$datum;
    
    if(db("SELECT id FROM ".$db['c_ips']." WHERE datum+".$reload." <= ".time()." OR FROM_UNIXTIME(datum,'%d.%m.%Y') != '".date("d.m.Y")."'",true) >= 1)
        db("DELETE FROM ".$db['c_ips']." WHERE datum+".$reload." <= ".time()." OR FROM_UNIXTIME(datum,'%d.%m.%Y') != '".date("d.m.Y")."'");

    if(($count=db("SELECT id,visitors,today FROM ".$db['counter']." WHERE today = '".$today."'",true)) >= 1)
    {
        $get = db("SELECT id,ip,datum FROM ".$db['c_ips']." WHERE ip = '".VisitorIP()."' AND FROM_UNIXTIME(datum,'%d.%m.%Y') = '".date("d.m.Y")."'",false,true);
        $sperrzeit = $get['datum']+$reload;
        if($sperrzeit <= time())
        {
            db("DELETE FROM ".$db['c_ips']." WHERE ip = '".VisitorIP()."'");
            db(($count ? "UPDATE ".$db['counter']." SET `visitors` = visitors+1 WHERE today = '".$today."'" : "INSERT INTO ".$db['counter']." SET `visitors` = '1', `today` = '".$today."'"));
            db("INSERT INTO ".$db['c_ips']." SET `ip` = '".VisitorIP()."', `datum`  = '".((int)$datum)."'");
        }
    } 
    else 
    {
        db(($count ? "UPDATE ".$db['counter']." SET `visitors` = visitors+1 WHERE today = '".$today."'" : "INSERT INTO ".$db['counter']." SET `visitors` = '1', `today` = '".$today."'"));
        db("INSERT INTO ".$db['c_ips']." SET `ip` = '".VisitorIP()."', `datum`  = '".((int)$datum)."'");
    }
}

//-> Updatet die Maximalen User die gleichzeitig online sind
function update_maxonline()
{
    global $db,$today;
    
    $get = db("SELECT maxonline FROM ".$db['counter']." WHERE today = '".$today."'",false,true);
    $count = cnt($db['c_who']);
    
    if($get['maxonline'] <= $count)
        db("UPDATE ".$db['counter']." SET `maxonline` = '".((int)$count)."' WHERE today = '".$today."'");
}

//-> Prueft, wieviele Besucher gerade online sind
function online_guests($where='')
{
    global $db,$useronline,$chkMe,$isSpider;
  
    if(!isSpider())
    {
        db("DELETE FROM ".$db['c_who']." WHERE online < ".time());
        db("REPLACE INTO ".$db['c_who']." SET `ip` = '".VisitorIP()."', `online` = '".((int)(time()+$useronline))."', `whereami` = '".up($where)."', `login` = '".($chkMe == 'unlogged' ? '0' : '1')."'");
        return cnt($db['c_who']);
    }
}

//-> Prueft, wieviele registrierte User gerade online sind
function online_reg()
{
    global $db,$useronline;
    return cnt($db['users'], " WHERE time+'".$useronline."'>'".time()."' AND online = '1'");
}

//-> Prueft den Zahlstatus eines Users (Clankasse)
function paycheck($tocheck)
{
    return ($tocheck >= time() ? true : false);
}

//-> Prueft, ob User eingeloggt ist und wenn ja welches Level er besitzt
function checkme()
{
    global $db,$userid;
    
    if(!$userid)
        return "unlogged";
    
    $qry = db("SELECT level FROM ".$db['users']."
               WHERE id = '".intval($userid)."'
               AND pwd = '".$_SESSION['pwd']."'
               AND ip = '".mysql_real_escape_string($_SESSION['ip'])."'");
    
    if(_rows($qry))
    {
        $get = _fetch($qry);
        return $get['level'];
    } 
    else 
        return "unlogged";
}

//-> Prueft, ob ein User diverse Rechte besitzt
function permission($check)
{
    global $db,$userid;
    
    if(checkme() == 4) 
        return true;

    if($userid)
    {
        // check rank permission
        $team = db("SELECT s1.`".$check."` FROM ".$db['permissions']." AS s1
                    LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi`
                    WHERE s2.`user` = '".intval($userid)."' AND s1.`".$check."` = '1' AND s2.`posi` != '0'",true);

        // check user permission
        $user = db("SELECT id FROM ".$db['permissions']." WHERE user = '".intval($userid)."' AND `".$check."` = '1'",true);

        if($user || $team) 
            return true;
    }
    
    return false;
}

//-> Checkt, ob neue Nachrichten vorhanden sind
function check_msg()
{
    global $db;
    
    if(db("SELECT page FROM ".$db['msg']." WHERE an = '".$_SESSION['id']."' AND page = '0'",true))
    {
        db("UPDATE ".$db['msg']." SET `page` = '1' WHERE an = '".$_SESSION['id']."'");
        return show("user/new_msg", array("new" => _site_msg_new));
    }
    
    return '';
}

//-> Prueft sicherheitsrelevante Gegebenheiten im Forum
function forumcheck($tid, $what)
{
    global $db;
    return (db("SELECT ".$what." FROM ".$db['f_threads']." WHERE id = '".intval($tid)."' AND ".$what." = '1'",true) >= 1);
}

//-> Prüft, ob User ein Member des Squads ist
function squadmember($squad_id)
{
    global $db;
    return (db("SELECT id FROM ".$db['squaduser']." WHERE squad = '".intval($squad_id)."' AND user = '".$_SESSION['id']."'",true) ? true : false);
}

//-> Gibt ein selectfield mit Ja und Nein aus
function select_field($what,$where,$tid)
{
    global $db;
    $rows = db("SELECT ".$what." FROM ".$db[$where]." WHERE user = '".intval($tid)."' AND ".$what." = '1'",true);
    return '<option value="0" '.(!$rows ? 'selected="selected"' : '').'>'._no.'</option><option value="1" '.($rows ? 'selected="selected"' : '').'>'._yes.'</option>';
}

//-> Prueft ob ein User schon in der Buddyliste vorhanden ist
function check_buddy($buddy)
{
    global $db,$userid;
    return (db("SELECT buddy FROM ".$db['buddys']." WHERE user = '".intval($userid)."' AND buddy = '".intval($buddy)."'",true) ? false : true);
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten
function cw_result($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/draw.gif" alt="" class="icon" />';
}

function cw_result_pic($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return '<img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<img src="../inc/images/draw.gif" alt="" class="icon" />';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild
function cw_result_nopic($punkte, $gpunkte)
{
    if($punkte > $gpunkte) 
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span>';
    else if($punkte < $gpunkte) 
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span>';
    else 
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span>';
}

function cw_result_nopic_raw($punkte, $gpunkte)
{
    if($punkte > $gpunkte) 
        return '<span class=CwWon>'.$punkte.':'.$gpunkte.'</span>';
    else if($punkte < $gpunkte) 
        return '<span class=CwLost>'.$punkte.':'.$gpunkte.'</span>';
    else 
        return '<span class=CwDraw>'.$punkte.':'.$gpunkte.'</span>';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild und ohne farbe
function cw_result_nopic_nocolor($punkte, $gpunkte)
{
    if($punkte > $gpunkte) 
        return $punkte.':'.$gpunkte;
    else if($punkte < $gpunkte) 
        return $punkte.':'.$gpunkte;
    else 
        return $punkte.':'.$gpunkte;
}

//-> Funktion um bei Clanwars Details Endergebnisse auszuwerten ohne bild
function cw_result_details($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwWon">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwLost">'.$gpunkte.'</span></td>';
    else if($punkte < $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwLost">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwWon">'.$gpunkte.'</span></td>';
    else 
        return '<td class="contentMainFirst" align="center"><span class="CwDraw">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwDraw">'.$gpunkte.'</span></td>';
}

//-> Flaggen ausgeben
function flag($code)
{
    global $picformat;
    
    if(empty($code))
        return '<img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" />';
    
    foreach($picformat AS $end)
    {
        if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
            return '<img src="../inc/images/flaggen/'.$code.'.'.$end.'" alt="" class="icon" />';
    }

    return '<img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" />';
}

function rawflag($code)
{
    global $picformat;
    
    if(empty($code))
        return '<img src=../inc/images/flaggen/nocountry.gif alt= class=icon />';
    
    foreach($picformat AS $end)
    {
        if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
            return '<img src=../inc/images/flaggen/'.$code.'.'.$end.' alt= class=icon />';
    }

    return '<img src=../inc/images/flaggen/nocountry.gif alt= class=icon />';
}

//-> Liste der Laender ausgeben
function show_countrys($i="")
{
    if(!empty($i))
        $options = preg_replace('#<option value="'.$i.'">(.*?)</option>#', '<option value="'.$i.'" selected="selected"> \\1</option>', _country_list);
    else
        $options = preg_replace('#<option value="de"> Deutschland</option>#', '<option value="de" selected="selected"> Deutschland</option>', _country_list);

    return '<select id="land" name="land" class="dropdown">'.$options.'</select>';
}

//-> Gameicon ausgeben
function squad($code)
{
    global $picformat;

    if(file_exists(basePath.'/inc/images/gameicons/'.$code))
        return '<img src="../inc/images/gameicons/'.$code.'" alt="" class="icon" />';
    
    return '<img src="../inc/images/gameicons/nogame.gif" alt="" class="icon" />';
}

//-> Funktion um bei DB-Eintraegen URLs einem http:// zuzuweisen
function links($hp)
{
    if(!empty($hp))
    {
        $link = str_replace("http://","",$hp);
        return 'http://'.$link;
    }
}

//-> set cookies
function set_cookie($name, $value = '', $path = '/', $secure = false, $http_only = true)
{
    $expires = (empty($value) ? time() - 6000 : time() + 3600 * 24 * 360);
    $domain = $_SERVER['HTTP_HOST'];

    if(strtolower(substr($domain, 0, 4)) == 'www.') 
        $domain = substr($domain, 4);

    $domain = '.' . $domain;
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
function checkpwd($user, $pwd)
{
    global $db;
    return db("SELECT id FROM ".$db['users']." WHERE user = '".up($user)."' AND pwd = '".up($pwd)."' AND level != '0'",true) ? true : false;
}

//-> Infomeldung ausgeben
function info($msg, $url, $timeout = 5)
{
    if(config('direct_refresh')) 
        return header('Location: '.str_replace('&amp;', '&', $url));

    $u = parse_url($url); $parts = '';
    if(array_key_exists('query',$u) && !empty($u['query']))
    {
        $u['query'] = str_replace('&amp;', '&', $u['query']);
        foreach(explode('&', $u['query']) as $p)
        {
            $p = explode('=', $p);
            if(count($p) == 2) 
            $parts .= '<input type="hidden" name="'.$p[0].'" value="'.$p[1].'" />'."\r\n";
        }
    }

    return show("errors/info", array("msg" => $msg,
                                     "url" => (array_key_exists('path',$u) && !empty($u['path']) ? $u['path'] : ''),
                                     "rawurl" => html_entity_decode($url),
                                     "parts" => $parts,
                                     "timeout" => $timeout,
                                     "info" => _info,
                                     "weiter" => _weiter,
                                     "backtopage" => _error_fwd));
}

//-> Errormmeldung ausgeben
function error($error, $back=1)
{
    return show("errors/error", array("error" => $error, "back" => $back, "fehler" => _error, "backtopage" => _error_back));
}

//-> Errormmeldung ohne "zurueck" ausgeben
function error2($error)
{
    return show("errors/error2", array("error" => $error, "fehler" => _error));
}

//-> Email wird auf korrekten Syntax & Erreichbarkeit ueberprueft
function check_email($email)
{
    return (!preg_match("#^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$#", $email) ? false : true);
}

//-> Bilder verkleinern
function img_size($img)
{
    return "<a href=\"../".$img."\" rel=\"lightbox[l_".intval($img)."]\"><img src=\"../thumbgen.php?img=".$img."\" alt=\"\" /></a>";
}

function img_cw($folder="", $img="")
{
    return "<a href=\"../".$folder."_".$img."\" rel=\"lightbox[cw_".intval($folder)."]\"><img src=\"../thumbgen.php?img=".$folder."_".$img."\" alt=\"\" /></a>";
}

function gallery_size($img="")
{
    return "<a href=\"../gallery/images/".$img."\" rel=\"lightbox[gallery_".intval($img)."]\"><img src=\"../thumbgen.php?img=gallery/images/".$img."\" alt=\"\" /></a>";
}

//-> Blaetterfunktion
function nav($entrys, $perpage, $urlpart, $icon=true)
{
    global $page;

    if($icon) 
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

    $result = '';
    for($i = $page;$i<=($page+5) && $i<=($pages-1);$i++)
    {
        if($i == $page) 
            $result .= '<span class="fontSites">'.$i.'</span><span class="fontSitesMisc">&#xA0;</span>';
        else 
            $result .= '<a class="sites" href="'.$urlpart.'&amp;page='.$i.'">'.$i.'</a><span class="fontSitesMisc">&#xA0;</span>';
    }
    
    $resultm = '';
    for($i=($page-5);$i<=($page-1);$i++)
    {
        if($i >= 2) 
            $resultm .= '<a class="sites" href="'.$urlpart.'&amp;page='.$i.'">'.$i.'</a> ';
    }

    return $icon.' '.$first.$resultm.$result.$last;
}

//-> Nickausgabe mit Profillink oder Emaillink (reg/nicht reg)
function autor($uid, $class="", $nick="", $email="", $cut="",$add="")
{
	global $db;
	
	$qry = db("SELECT nick,country FROM ".$db['users']." WHERE id = '".intval($uid)."'");
	if(_rows($qry))
	{
	    $get = _fetch($qry);
		$nickname = (!empty($cut)) ? cut(re($get['nick']), $cut) : re($get['nick']);
		return show(_user_link, array("id" => $uid, "country" => flag($get['country']), "class" => $class, "get" => $add, "nick" => $nickname));
	} 
    else 
    {
        $nickname = (!empty($cut)) ? cut(re($nick), $cut) : re($nick);
        return show(_user_link_noreg, array("nick" => $nickname, "class" => $class, "email" => eMailAddr($email)));
    }
}

function cleanautor($uid, $class="", $nick="", $email="", $cut="")
{
    global $db;
    
    $qry = db("SELECT nick,country FROM ".$db['users']." WHERE id = '".intval($uid)."'");
    if(_rows($qry))
    {
        $get = _fetch($qry);
        return show(_user_link_preview, array("id" => $uid, "country" => flag($get['country']), "class" => $class, "nick" => re($get['nick'])));
    }
    else
        return show(_user_link_noreg, array("nick" => re($nick), "class" => $class, "email" => eMailAddr($email)));
}

function rawautor($uid)
{
    global $db;

    $qry = db("SELECT nick,country FROM ".$db['users']." WHERE id = '".intval($uid)."'");
    if(_rows($qry))
    {
        $get = _fetch($qry);
        return rawflag($get['country'])." ".jsconvert(re($get['nick']));
    }
    else
        return rawflag('')." ".jsconvert(re($uid));
}

//-> Nickausgabe ohne Profillink oder Emaillink fr das ForenAbo
function fabo_autor($uid)
{
    global $db;
    
    $return = '';
    $qry = db("SELECT nick FROM ".$db['users']." WHERE id = '".$uid."'");
    if(_rows($qry))
    {
        $get = _fetch($qry);
        $return = show(_user_link_fabo, array("id" => $uid, "nick" => re($get['nick'])));
    }
    
    return $return;
}

function blank_autor($uid)
{
    global $db;
    
    $return = '';
    $qry = db("SELECT nick FROM ".$db['users']." WHERE id = '".$uid."'");
    if(_rows($qry))
    {
        $get = _fetch($qry);
        $result = show(_user_link_blank, array("id" => $uid, "nick" => re($get['nick'])));
    }
    
    return $return;
}

//-> Rechte abfragen
function jsconvert($txt)
{
    $search  = array("'", "&#039;", "\"", "\r", "\n");
    $replace = array("\'", "\'", "&quot;", '', '');
    return str_replace($search, $replace, $txt);
}

//-> interner Forencheck
function fintern($id)
{
    global $db,$userid,$chkMe;
    $fget = db("SELECT s1.intern,s2.id FROM ".$db['f_kats']." AS s1 LEFT JOIN ".$db['f_skats']." AS s2 ON s2.`sid` = s1.id WHERE s2.`id` = '".intval($id)."'",false,true);

    if($chkMe == "unlogged") 
        return empty($fget['intern']) ? true : false;
    else 
    {
        $team = db("SELECT * FROM ".$db['f_access']." AS s1 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi` WHERE s2.`user` = '".intval($userid)."' AND s2.`posi` != '0' AND s1.`forum` = '".intval($id)."'",true);
        $user = db("SELECT * FROM ".$db['f_access']." WHERE `user` = '".intval($userid)."' AND `forum` = '".intval($id)."'",true);

        if($user || $team || $chkMe == 4 || !$fget['intern']) 
            return true;
    }
    
    return false;
}

//-> einzelne Userdaten ermitteln
function data($tid, $what)
{
    global $db;
    $get = db("SELECT ".$what." FROM ".$db['users']." WHERE id = '".intval($tid)."'",false,true);
    return re_entry($get[$what]);
}

//-> einzelne Userstatistiken ermitteln
function userstats($tid, $what)
{
    global $db;
    $get = db("SELECT ".$what." FROM ".$db['userstats']." WHERE user = '".intval($tid)."'",false,true);
    return $get[$what];
}

//- Funktion zum versenden von Emails
function sendMail($mailto,$subject,$content)
{
    global $mailfrom;
    $mail = new Mailer();
    $mail->IsHTML(true);
    $mail->From = $mailfrom;
    $mail->FromName = $mailfrom;
    $mail->AddAddress(preg_replace('/(\\n+|\\r+|%0A|%0D)/i', '',$mailto));
    $mail->Subject = $subject;
    $mail->Body = bbcode_nletter($content);
    $mail->AltBody = bbcode_nletter_plain($content);
    return $mail->Send();
}

check_msg_email();
function check_msg_email()
{
	global $db,$clanname,$httphost;
    $qry = db("SELECT s1.an,s1.page,s1.titel,s1.sendmail,s1.id AS mid,s2.id,s2.nick,s2.email,s2.pnmail FROM ".$db['msg']." AS s1 LEFT JOIN ".$db['users']." AS s2 ON s2.id = s1.an WHERE page = 0 AND sendmail = 0");
	while($get = _fetch($qry))
	{
		if($get['pnmail'])
		{
			db("UPDATE ".$db['msg']." SET `sendmail` = '1' WHERE id = '".$get['mid']."'");
			$subj = show(settings('eml_pn_subj'), array("domain" => $httphost));
			$message = show(settings('eml_pn'), array("nick" => re($get['nick']), "domain" => $httphost, "titel" => $get['titel'], "clan" => $clanname));
			sendMail(re($get['email']), $subj, $message);
		}
	}
}

function perm_sendnews($uID)
{
    global $db;
    $team = db("SELECT s1.`news` FROM ".$db['permissions']." AS s1 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi` WHERE s2.`user` = '".intval($uID)."' AND s1.`news` = '1' AND s2.`posi` != '0'",true);
    $user = db("SELECT id FROM ".$db['permissions']." WHERE user = '".intval($uID)."' AND `news` = '1'",true);
    return ($user || $team ? true : false);
}

//-> Checkt ob ein Ereignis neu ist
function check_new($datum, $new = "", $datum2 = "")
{
    global $db,$userid;
    if($userid)
    {
        $get = db("SELECT lastvisit FROM ".$db['userstats']." WHERE user = '".intval($userid)."'",false,true);
        if($datum >= $get['lastvisit'] || $datum2 >= $get['lastvisit'])
        {
            if(empty($new)) 
                return _newicon;
        }
    }
    
    return '';
}

//-> DropDown Mens Date/Time
function dropdown($what, $wert, $age = 0)
{
    $return='';
    if($what == "day")
    {
        if($age == 1)
            $return .='<option value="" class="dropdownKat">'._day.'</option>'."\n";
    
        for($i=1; $i<32; $i++)
        {
            if($i==$wert) 
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else 
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } 
    else if($what == "month") 
    {
        if($age == 1)
            $return .='<option value="" class="dropdownKat">'._month.'</option>'."\n";
        
        for($i=1; $i<13; $i++)
        {
            if($i==$wert) 
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else 
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } 
    else if($what == "year") 
    {
        if($age == 1)
        {
            $return .='<option value="" class="dropdownKat">'._year.'</option>'."\n";
            for($i=date("Y",time())-80; $i<date("Y",time())-10; $i++)
            {
                if($i==$wert) 
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else 
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        } 
        else 
        {
            for($i=date("Y",time())-3; $i<date("Y",time())+3; $i++)
            {
                if($i==$wert) 
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else 
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
    } 
    else if($what == "hour") 
    {
        for($i=0; $i<24; $i++)
        {
            if($i==$wert) 
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else 
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } 
    else if($what == "minute") 
    {
        for($i="00"; $i<60; $i++)
        {
            if($i == 0 || $i == 15 || $i == 30 || $i == 45)
            {
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
function sgames($game = '')
{
  $protocols = get_files(basePath.'/inc/server_query/',false,true,array('php'));
  foreach($protocols AS $protocol)
  {
    unset($gamemods, $server_name_config);
    $protocol = str_replace('.php', '', $protocol);
    if(substr($protocol, 0, 1) != '_')
    {
      $explode = '##############################################################################################################################';
      $protocol_config = explode($explode, file_get_contents(basePath.'/inc/server_query/'.$protocol.'.php'));
      eval(str_replace('<?php', '', $protocol_config[0]));

      if(!empty($server_name_config) && count($server_name_config) > 2) {
        foreach($server_name_config AS $slabel => $sconfig) {
          $gamemods .= $sconfig[1].', ';
        }
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
function voteanswer($what, $vid)
{
    global $db;
    $get = db("SELECT * FROM ".$db['vote_results']." WHERE what = '".up($what)."' AND vid = '".$vid."'",false,true);
    return $get['sel'];
}

//-> Prueft, ob eine Userid existiert
function exist($tid)
{
    global $db;
    return (db("SELECT id FROM ".$db['users']." WHERE id = '".intval($tid)."'",true) ? true : false);
}

//-> Geburtstag errechnen
function getAge($bday)
{
    if(!empty($bday) || $bday == '..')
    {
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
function getrank($tid, $squad="", $profil=0)
{
    global $db;
    if($squad)
    {
        if($profil)
            $qry = db("SELECT * FROM ".$db['userpos']." AS s1 LEFT JOIN ".$db['squads']." AS s2 ON s1.squad = s2.id WHERE s1.user = '".intval($tid)."' AND s1.squad = '".intval($squad)."' AND s1.posi != '0'");
        else
            $qry = db("SELECT * FROM ".$db['userpos']." WHERE user = '".intval($tid)."' AND squad = '".intval($squad)."' AND posi != '0'");
    	  
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $getp = db("SELECT * FROM ".$db['pos']." WHERE id = '".intval($get['posi'])."'",false,true);
                $squadname = (!empty($get['name']) ? '<b>'.$get['name'].':</b> ' : '');
                return $squadname.$getp['position'];
            }
        } 
        else 
        {
            $get = db("SELECT level FROM ".$db['users']." WHERE id = '".intval($tid)."'",false,true);
			switch ($get['level']) 
			{
			    case 0: return _status_unregged; break;
			    case 1: return _status_user; break;
			    case 2: return _status_trial; break;
			    case 3: return _status_member; break;
			    case 4: return _status_admin; break;
			    case 'banned': return _status_banned; break;
			    default: return _gast; break;
			}
        }
    } 
    else 
    {
        $qry = db("SELECT s1.*,s2.position FROM ".$db['userpos']." AS s1 LEFT JOIN ".$db['pos']." AS s2 ON s1.posi = s2.id WHERE s1.user = '".intval($tid)."' AND s1.posi != '0' ORDER BY s2.pid ASC");
        if(_rows($qry))
        {
            $get = _fetch($qry);
            return $get['position'];
        } 
        else 
        {
            $get = db("SELECT level FROM ".$db['users']." WHERE id = '".intval($tid)."'",false,true);
			switch ($get['level']) 
			{
			    case 0: return _status_unregged; break;
			    case 1: return _status_user; break;
			    case 2: return _status_trial; break;
			    case 3: return _status_member; break;
			    case 4: return _status_admin; break;
			    case 'banned': return _status_banned; break;
			    default: return _gast; break;
			}
        }
    }
}

//-> maskiert Zeilenumbrueche fuer <textarea>
function txtArea($txt)
{
    return $txt;
}

//-> Session fuer den letzten Besuch setzen
function set_lastvisit()
{
    global $db,$useronline,$userid;
    if($userid)
    {
        if(!db("SELECT id FROM ".$db['users']." WHERE id = ".intval($userid)." AND time+'".$useronline."'>'".time()."'",true))
        {
            $time = data($userid, "time");
            $_SESSION['lastvisit'] = $time;
        }
    }
}

//-> Checkt welcher User gerade noch online ist
function onlinecheck($tid)
{
    global $db,$useronline;
    if(db("SELECT id FROM ".$db['users']." WHERE id = '".intval($tid)."' AND time+'".$useronline."'>'".time()."' AND online = 1",true)) 
        return '<img src="../inc/images/online.gif" alt="" class="icon" />';
    else 
        return '<img src="../inc/images/offline.gif" alt="" class="icon" />';
}

//Funktion fuer die Sprachdefinierung der Profilfelder
function pfields_name($name)
{
    $searchpattern = array("=_city_=Uis","=_hobbys_=Uis","=_job_=Uis","=_motto_=Uis","=_exclans_=Uis","=_email2_=Uis","=_email3_=Uis","=_autor_=Uis","=_auto_=Uis","=_buch_=Uis","=_drink_=Uis","=_essen_=Uis",
    "=_favoclan_=Uis","=_film_=Uis","=_game_=Uis","=_map_=Uis","=_musik_=Uis","=_person_=Uis","=_song_=Uis","=_spieler_=Uis","=_sportler_=Uis","=_sport_=Uis","=_waffe_=Uis","=_board_=Uis","=_cpu_=Uis",
    "=_graka_=Uis","=_hdd_=Uis","=_headset_=Uis","=_inet_=Uis","=_maus_=Uis","=_mauspad_=Uis","=_monitor_=Uis","=_ram_=Uis","=_system_=Uis");
    $replacement  = array(_profil_city,_profil_hobbys,_profil_job,_profil_motto,_profil_exclans,_profil_email2,_profil_email3,_profil_autor,_profil_auto,_profil_buch,_profil_drink,_profil_essen,
    _profil_favoclan,_profil_film,_profil_game,_profil_map,_profil_musik,_profil_person,_profil_song,_profil_spieler,_profil_sportler,_profil_sport,_profil_waffe,_profil_board,_profil_cpu,_profil_graka,
    _profil_hdd,_profil_headset,_profil_inet,_profil_maus,_profil_mauspad,_profil_monitor,_profil_monitor,_profil_ram,_profil_os);
    return preg_replace($searchpattern, $replacement , $name);
}

//-> Gibt die Tageszahl eines Monats aus
function days_in_month($month, $year)
{
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

//-> Setzt bei einem Tag >10 eine 0 vorran (Kalender)
function cal($i)
{
    if(!preg_match("=10|20|30=Uis",$i)) 
        $i = preg_replace("=0=", "", $i);
    
    if($i < 10) 
        return "0".$i;
    else 
        return $i;
}

//-> Entfernt fuehrende Nullen bei Monatsangaben
function nonum($i)
{
    if(!preg_match("=10=Uis",$i)) 
        $i = preg_replace("=0=", "", $i);
  
    return $i;
}

//-> Konvertiert Platzhalter in die jeweiligen bersetzungen
function navi_name($name)
{
    $name = trim($name);
    if(preg_match("#^_(.*?)_$#Uis",$name))
    {
        $name = preg_replace("#_(.*?)_#Uis", "$1", $name);
        @eval("\$name = _".$name.";");
    }

    return $name;
}

//RSS News Feed erzeugen
function convert_feed($txt)
{
  $txt = stripslashes($txt);
  $txt = str_replace("","Ae",$txt);
  $txt = str_replace("","ae",$txt);
  $txt = str_replace("","Ue",$txt);
  $txt = str_replace("","ue",$txt);
  $txt = str_replace("","Oe",$txt);
  $txt = str_replace("","oe",$txt);
  $txt = str_replace("&Auml;","Ae",$txt);
  $txt = str_replace("&auml;","ae",$txt);
  $txt = str_replace("&Uuml;","Ue",$txt);
  $txt = str_replace("&uuml;","ue",$txt);
  $txt = str_replace("&Ouml;","Oe",$txt);
  $txt = str_replace("&ouml;","oe",$txt);
  $txt = htmlentities($txt, ENT_QUOTES, _charset);
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
  $txt = strip_tags($txt);
  return $txt;
}

function feed()
{
  global $db,$pagetitle,$clanname;
    $host = $_SERVER['HTTP_HOST'];
    $pfad = preg_replace("#^(.*?)\/(.*?)#Uis","$1",dirname($_SERVER['PHP_SELF']));

    $feed = '<?xml version="1.0" encoding="'._charset.'" ?>';
    $feed .= "\r\n";
    $feed .= '<rss version="0.91">';
    $feed .= "\r\n";
    $feed .= '<channel>';
    $feed .= "\r\n";
    $feed .= '  <title>'.convert_feed($pagetitle).'</title>';
    $feed .= "\r\n";
    $feed .= '  <link>http://'.$host.'</link>';
    $feed .= "\r\n";
    $feed .= '  <description>Clannews von '.convert_feed($clanname).'</description>';
    $feed .= "\r\n";
    $feed .= '  <language>de-de</language>';
    $feed .= "\r\n";
    $feed .= '  <copyright>'.date("Y", time()).' '.convert_feed($clanname).'</copyright>';
    $feed .= "\r\n";

    $data = @fopen("../rss.xml","w+");
    @fwrite($data, $feed);

    $qry = db("SELECT * FROM ".$db['news']." WHERE intern = 0 AND public = 1 ORDER BY datum DESC LIMIT 15");
    while($get = _fetch($qry))
    {
      $get1 = db("SELECT nick FROM ".$db['users']." WHERE id = '".$get['autor']."'",false,true);

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
function userpic($userid, $width=170,$height=210)
{
    global $picformat;
    foreach($picformat as $endung)
    {
        if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$endung))
        {
            $pic = show(_userpic_link, array("id" => $userid, "endung" => $endung, "width" => $width, "height" => $height));
            break;
        }	
        else 
            $pic = show(_no_userpic, array("width" => $width, "height" => $height));
    }

    return $pic;
}

// Useravatar ausgeben
function useravatar($userid, $width=100,$height=100)
{
    global $picformat;
    foreach($picformat as $endung)
    {
        if(file_exists(basePath."/inc/images/uploads/useravatare/".$userid.".".$endung))
        {
            $pic = show(_userava_link, array("id" => $userid, "endung" => $endung, "width" => $width, "height" => $height));
            break;
        }
        else 
            $pic = show(_no_userava, array("width" => $width, "height" => $height));
    }

    return $pic;
}

// Userpic für Hoverinformationen ausgeben
function hoveruserpic($userid, $width=170,$height=210)
{
    global $picformat;
    foreach($picformat as $endung)
    {
        if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$endung))
		{
    		$pic = "../inc/images/uploads/userpics/".$userid.".".$endung."', '".$width."', '".$height."";
    		break;
		}
		else
            $pic = "../inc/images/nopic.gif".$userid.".".$endung."', '".$width."', '".$height."";
    }

    return $pic;
}

// Adminberechtigungen ueberpruefen
function admin_perms($userid)
{
    global $db,$chkMe;

    if(empty($userid)) 
        return false;

    // no need for these admin areas
    $e = array('gb', 'shoutbox', 'editusers', 'votes', 'contact', 'joinus', 'intnews', 'forum', 'gs_showpw');

    // check user permission
    $c = db("SELECT * FROM ".$db['permissions']." WHERE user = '".intval($userid)."'",false,true);
    if(!empty($c))
    {
        foreach($c AS $v => $k)
        {
            if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e))
            {
                if($k == 1)
                    return true;
            }
        }
    }

    // check rank permission
    $qry = db("SELECT s1.* FROM ".$db['permissions']." AS s1 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi` WHERE s2.`user` = '".intval($userid)."' AND s2.`posi` != '0'");
    while($r = _fetch($qry))
    {
        foreach($r AS $v => $k)
        {
            if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e))
            {
                if($k == 1)
                    return true;
            }
        }
    }

    return ($chkMe == 4) ? true : false;
}

//-> filter placeholders
function pholderreplace($pholder)
{
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
  $pholder = str_replace("]","",$pholder);

  return $pholder;
}

//-> Rechte abfragen
function getPermissions($checkID = 0, $pos = 0)
{
    global $db;

    if(!empty($checkID))
    {
        $check = empty($pos) ? 'user' : 'pos';
        $checked = array();
        $qry = db("SELECT * FROM ".$db['permissions']." WHERE `".$check."` = '".intval($checkID)."'");
        
        if(_rows($qry))
        {
            foreach(_fetch($qry) AS $k => $v) 
                $checked[$k] = $v;
        }
    }

    $permission = array();
    $qry = db("SHOW COLUMNS FROM ".$db['permissions']."");
    while($get = _fetch($qry))
    {
        if($get['Field'] != 'id' && $get['Field'] != 'user' && $get['Field'] != 'pos' && $get['Field'] != 'intforum')
        {
            @eval("\$lang = _perm_".$get['Field'].";");
            $chk = empty($checked[$get['Field']]) ? '' : ' checked="checked"';
            $permission[$lang] = '<input type="checkbox" class="checkbox" id="'.$get['Field'].'" name="perm[p_'.$get['Field'].']" value="1"'.$chk.' /><label for="'.$get['Field'].'"> '.$lang.'</label> ';
        }
    }
    
    natcasesort($permission); $p = '';
    foreach($permission AS $perm) 
    {
        $br = ($break % 2) ? '<br />' : ''; $break++;
        $p .= $perm.$br;
    }

    return $p;
}

//-> interne Foren-Rechte abfragen
function getBoardPermissions($checkID = 0, $pos = 0)
{
    global $db, $dir;
    $qry = db("SELECT id,name FROM ".$db['f_kats']." WHERE intern = '1' ORDER BY `kid` ASC"); $i_forum = '';
    while($get = _fetch($qry))
    {
        unset($kats, $fkats, $break);
        $kats = (empty($katbreak) ? '' : '<div style="clear:both">&nbsp;</div>').'<table class="hperc" cellspacing="1"><tr><td class="contentMainTop"><b>'.re($get["name"]).'</b></td></tr></table>';
        $katbreak = 1;

        $qry2 = db("SELECT kattopic,id FROM ".$db['f_skats']." WHERE `sid` = '".$get['id']."' ORDER BY `kattopic` ASC"); $fkats = '';
        while($get2 = _fetch($qry2))
        {
            $br = ($break % 2) ? '<br />' : ''; $break++;
            $chk =  db("SELECT * FROM ".$db['f_access']." WHERE `".(empty($pos) ? 'user' : 'pos')."` = '".intval($checkID)."' AND ".(empty($pos) ? 'user' : 'pos')." != '0' AND `forum` = '".$get2['id']."'",true) ? ' checked="checked"' : '';
            $fkats .= '<input type="checkbox" class="checkbox" id="board_'.$get2['id'].'" name="board['.$get2['get2'].']" value="'.$get2['id'].'"'.$chk.' /><label for="board_'.$get2['id'].'"> '.re($get2['kattopic']).'</label> '.$br;
        }
    
        $i_forum .= $kats.$fkats;
    }

    return $i_forum;
}

//-> Show Xfire Status
function xfire($username='')
{
    if(empty($username))
        return '-';
    
    switch(xfire_skin)
    {
        case 'shadow': $skin = 'sh'; break;
        case 'kampf': $skin = 'co'; break;
        case 'scifi': $skin = 'sf'; break;
        case 'fantasy': $skin = 'os'; break;
        case 'wow': $skin = 'wow'; break;
        default: $skin = 'bg'; break;
    }
    
    if(xfire_preloader)
    {
        if(cache('xfire_'.$username, xfire_refresh, 'c'))
        {
            if(!$img_stream = fileExists('http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'))
                return show(_xfireicon,array('username' => $username, 'img' => 'http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'));
            
            cache('xfire_'.$username, $img_stream, 'w');
            return show(_xfireicon,array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode($img_stream)));
        }
        else
            return show(_xfireicon,array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode(cache('xfire_'.$username, null, 'r'))));
    }
       
    return show(_xfireicon,array('username' => $username, 'img' => 'http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'));
}

function logout()
{
    global $userid;
    db("UPDATE ".$db['users']." SET online = '0', sessid = '' WHERE id = '".$userid."'");
    set_cookie($prev.'id', '');
    set_cookie($prev.'pwd', '');
    set_cookie(session_name(), '');
    session_unset();
    session_destroy();
    session_regenerate_id();
}

//Prüft ob alle clicks nur einmal gezählt werden *gast/user
function count_clicks($side_tag='',$clickedID=0)
{
    global $db,$userid;
    
    if(checkme() != 'unlogged')
    {
        if(db("SELECT id FROM ".$db['clicks_ips']." WHERE `uid` = '".$userid."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'",true))
            return false;

        if(db("SELECT id FROM ".$db['clicks_ips']." WHERE `ip` = '".visitorIp()."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'",true))
        {
            db("UPDATE `".$db['clicks_ips']."` SET `uid` = '".$userid."' WHERE `ip` = '".visitorIp()."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'");
            return false;
        }
        else
        {
            db("INSERT INTO ".$db['clicks_ips']." (`id` ,`ip` ,`uid` ,`ids`, `side`) VALUES (NULL , '".visitorIp()."', '".$userid."', '".$clickedID."', '".$side_tag."')");
            return true;
        }
    }
    else
    {
        if(!db("SELECT id FROM ".$db['clicks_ips']." WHERE `ip` = '".visitorIp()."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'",true))
        {
            db("INSERT INTO ".$db['clicks_ips']." (`id` ,`ip` ,`uid` ,`ids`, `side`) VALUES (NULL , '".visitorIp()."', '0', '".$clickedID."', '".$side_tag."')");
            return true;
        }
    }
    
    return false;
}

//Mods und Api Laden
include_once(basePath.'/inc/mod_api.php');

//-> Navigation einbinden
include_once(basePath.'/inc/menu-functions/navi.php');

//-> Ausgabe des Indextemplates
function page($index,$title,$where,$time,$wysiwyg='',$index_templ=false)
{
    global $db,$userid,$userip,$tmpdir,$chkMe;
    global $u_b1,$u_b2,$designpath,$language,$cp_color;
	  
    // installer vorhanden?
	if(file_exists(basePath."/_installer") && $chkMe == 4)
        $index = _installdir;
	  
	// user gebannt? Logge aus!
    if($chkMe == 'banned')
    {
        logout();
        header("Location: ../user/?action=login");
    }
    
	//  JS-Dateine einbinden
    $lng = ($language=='deutsch') ? 'de':'en';
    $edr = ($wysiwyg=='_word') ? 'advanced':'normal';
    $lcolor = ($cp_color) ? 'lcolor=true;':'';
    $java_vars = '<script language="javascript" type="text/javascript">var maxW = '.config('maxwidth').',lng = \''.$lng.'\',dzcp_editor = \''.$edr.'\';'.$lcolor.'</script>';
    $login = ''; $check_msg = '';
	
	if(!strstr($_SERVER['HTTP_USER_AGENT'],'Android') AND !strstr($_SERVER['HTTP_USER_AGENT'],'webOS')) 
        $java_vars .= '<script language="javascript" type="text/javascript" src="'.$designpath.'/_js/wysiwyg'.$wysiwyg.'.js"></script>';
	
    if(settings("wmodus") && $chkMe != 4)
    {
        $secure = (config('securelogin') ? show("menu/secure", array("help" => _login_secure_help, "security" => _register_confirm)) : '');
        $login = show("errors/wmodus_login", array("what" => _login_login, "secure" => $secure, "signup" => _login_signup, "permanent" => _login_permanent, "lostpwd" => _login_lostpwd));
        echo show("errors/wmodus", array("wmodus" => _wartungsmodus,
                                         "head" => _wartungsmodus_head,
                                         "tmpdir" => $tmpdir,
                                         "java_vars" => $java_vars,
                                         "dir" => $designpath,
                                         "title" => re(strip_tags($title)),
                                         "login" => $login));
    }
    else 
    {
        updateCounter();
        update_maxonline();
	
        //check permissions
	    if($chkMe == "unlogged") 
            include_once(basePath.'/inc/menu-functions/login.php');
	    else 
		{
			$check_msg = check_msg();
			set_lastvisit();
			db("UPDATE ".$db['users']." SET `time` = '".((int)time())."', `whereami` = '".up($where)."' WHERE id = '".intval($userid)."'");
	    }
	
        //init templateswitch
		$tmpldir=''; $tmps = get_files('../inc/_templates_/',true,false);
	    for($i=0; $i<count($tmps); $i++)
	    {
			$selt = ($tmpdir == $tmps[$i] ? 'selected="selected"' : '');
			$tmpldir .= show(_select_field, array("value" => "../user/?action=switch&amp;set=".$tmps[$i],  "what" => $tmps[$i],  "sel" => $selt));
	    }
		
	    //misc vars
	    $template_switch = show("menu/tmp_switch", array("templates" => $tmpldir));
	    $clanname = re(settings("clanname"));
	    $time = show(_generated_time, array("time" => $time));
	    $headtitle = show(_index_headtitle, array("clanname" => $clanname));
	    $rss = $clanname;
	    $dir = $designpath;
	    $title = re(strip_tags($title));
		$charset = _charset;
	    $index = empty($index) ? '' : (empty($check_msg) ? '' : $check_msg).'<table class="mainContent" cellspacing="1" style="margin-top:0">'.$index.'</table>';
	
	    //-> Sort & filter placeholders
	    //default placeholders
	    $arr = array("idir" => '../inc/images/admin', "dir" => $designpath);
	    
	    //check if placeholders are given
	    $pholder = file_get_contents($designpath."/index.html");
	    
	    //filter placeholders
		$pholdervars = '';
	    $blArr = array("[title]","[copyright]","[java_vars]","[login]", "[template_switch]","[headtitle]","[index]", "[time]","[rss]","[dir]","[charset]");
	    for($i=0;$i<=count($blArr)-1;$i++)
	    {
            if(preg_match("#".$blArr[$i]."#",$pholder))
                $pholdervars .= $blArr[$i];
	    }
	    
	    for($i=0;$i<=count($blArr)-1;$i++)
            $pholder = str_replace($blArr[$i],"",$pholder);
	
	    $pholder = pholderreplace($pholder);
	    $pholdervars = pholderreplace($pholdervars);
	    
	    //put placeholders in array
	    $pholder = explode("^",$pholder);
	    for($i=0;$i<=count($pholder)-1;$i++) 
		{
			if(strstr($pholder[$i], 'nav_')) 
				$arr[$pholder[$i]] = navi($pholder[$i]);
			else 
			{
				if(@file_exists(basePath.'/inc/menu-functions/'.$pholder[$i].'.php')) 
					include_once(basePath.'/inc/menu-functions/'.$pholder[$i].'.php');
				
				if(function_exists($pholder[$i]))
					$arr[$pholder[$i]] = call_user_func($pholder[$i]);
			}
		}
		
	    $pholdervars = explode("^",$pholdervars);
	    for($i=0;$i<=count($pholdervars)-1;$i++) 
		{ @eval("if(isset(\$".$pholdervars[$i].")) \$arr[".$pholdervars[$i]."] = \$".$pholdervars[$i].";"); }
	
		//index output
	    echo show((($index_templ != false ? file_exists(basePath."/inc/_templates_/".$tmpdir."/".$index_templ.".html") : false) ? $index_templ : 'index') , $arr);
    }
}
?>