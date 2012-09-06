<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include("../inc/config.php");
include("../inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "search";
$where = _search_head;
$title = $pagetitle." - ".$where."";

//#################################### CHANGELOG ####################################
// Version # Autor    # Details                                                     #
//###################################################################################
// 1.6     # BlueTeck #  -GUI überarbeitet                                          #
//         #          #  -Forum suche und normale Suche zusammengefasst             #
//         #          #  -neue Suchmethode hinzugefügt (AND B)                      #
//         #          #  -Events + Clanwars hinzugefügt                             #
//###################################################################################

//check $_GET var
if($_GET['area'] == 'topic') $acheck2 = "checked=\"checked\"";
else                         $acheck1 = "checked=\"checked\"";
if($_GET['type'] == 'autor') $tcheck2 = "checked=\"checked\"";
else                         $tcheck1 = "checked=\"checked\"";

//gewählte Kategorien auslesen
$i=0;
for(reset($_GET);list($key,$value)=each($_GET);$i++)
{
	$key = trim($key);
    if($i == 0) $sep = '?';
	else        $sep = '&';
	$getstr .= $sep.$key.'='.$value;
    
    if(preg_match("#k_#",$key))
		$strkat .= $key.'|';
}      

//Query für alle Kategorien (intern oder nur extern)
if(permission("intforum"))
{
    $qry = db("SELECT * FROM ".$db['f_kats']."
               ORDER BY kid");
} else {
	$qry = db("SELECT * FROM ".$db['f_kats']."
               WHERE intern = 0
               ORDER BY kid");
}
//Kats anzeigen, je nach Berechtigung
while($get = _fetch($qry))
{
    $fkats .= '<li><label class="searchKat" style="text-align:center">'.re($get['name']).'</label></li>';

    $showt = "";
    $qrys = db("SELECT * FROM ".$db['f_skats']."
                WHERE sid = '".$get['id']."'
                ORDER BY kattopic");
    while($gets = _fetch($qrys))
    {
		$intF = db("SELECT * FROM ".$db['f_access']."
                  WHERE user = '".$_SESSION['id']."'
                  AND forum = '".$gets['id']."'");
		if($get['intern'] == 0 || (($get['intern'] == 1 && _rows($intF)) || $chkMe == 4))
		{
			if(preg_match("#k_".$gets['id']."\|#",$strkat)) $kcheck = "checked=\"checked\"";
			else  $kcheck = '';
        
			$fkats .= '<li><label class="search" for="k_'.$gets['id'].'"><input type="checkbox" class="chksearch" name="k_'.$gets['id'].'" id="k_'.$gets['id'].'" '.$kcheck.' onclick="DZCP.hideForumFirst()" value="true" />&nbsp;&nbsp;'.re($gets['kattopic']).'</label></li>';
		}
    }
}
//###############################################################################################################  
//Auswertung für Forensuche
if($_GET['where'] == 'forum') { 
 
    if(isset($_GET['page'])) $page = $_GET['page'];
    else $page = 1;
    $maxfsearch = 20;
   
	$_SESSION['search_con'] = $_GET['con'];
//Suche nach Autor      
	if($_GET['type'] == 'autor') 
	{
		$_SESSION['search_type'] = 'autor';
		if($_GET['con'] == 'or' OR $_GET['con'] == 'andb')
		{
			$suche = explode(" ",$_GET['search']);
			for($x=0;$x<count($suche);$x++)
			{     
				$z=0;   
				$qryu = db("SELECT id,nick FROM ".$db['users']."
                            WHERE lower(nick) LIKE lower('%".trim($suche[$x])."%')");
				if(_rows($qryu))
				{
					while($getu = _fetch($qryu))
					{
					if($z == 0) $c = 'WHERE (';
					else        $c = 'OR ';
               
					$dosearch .= $c."s1.t_reg = '".$getu['id']."' OR s2.reg = '".$getu['id']."' ";
					}
					$z++;
				}
			}
          
			$suche = explode(" ",$_GET['search']);
			for($x=0;$x<count($suche);$x++)
			{  
				if($z == 0) $b = 'WHERE (';
				else        $b = 'OR ';
				$dosearch .= $b."lower(s1.t_nick) LIKE lower('%".trim($suche[$x])."%') OR lower(s2.nick) LIKE lower('%".trim($suche[$x])."%') ";
				$z++;
			}
		} else {
			$qryu = db("SELECT id,nick FROM ".$db['users']."
                     WHERE lower(nick) LIKE lower('%".trim($_GET['search'])."%')");
			if(_rows($qryu))
			{
				$x=0;
				while($getu = _fetch($qryu))
				{
					if($x == 0) $c = 'WHERE (';
					else        $c = 'OR ';
             
					$dosearch .= $c."s1.t_reg = '".$getu['id']."' OR s2.reg = '".$getu['id']."' ";
					$x++;
				}
			}
			if($x == 0) { $c = 'WHERE (';
			} elseif($_GET['con'] == 'andb') { $c = 'AND ';}
			 else {       $c = 'OR ';}
			$dosearch .= $c."lower(s1.t_nick) LIKE lower('%".trim($_GET['search'])."%') OR lower(s2.nick) LIKE lower('%".trim($_GET['search'])."%')";
		}
		$dosearch .= ')';
//Suche nach Inhalt		
	} else {
		$_SESSION['search_type'] = 'text';
		if($_GET['con'] == 'or' OR $_GET['con'] == 'andb')
		{
			$suche = explode(" ",$_GET['search']);
			for($x=0;$x<count($suche);$x++)
			{        
				if($x == 0) $c = 'WHERE (';
				else        $c = 'OR ';
				if($_GET['area'] != 'topic')
				$dosearch .= $c." lower(s1.t_text) LIKE lower('%".trim($suche[$x])."%') OR lower(s2.text) LIKE lower('%".trim($suche[$x])."%') ";
				else $dosearch .= $c." s1.topic LIKE '%".trim($suche[$x])."%' ";
			}
		} else {
			if($_GET['area'] != 'topic')
				$dosearch .= "WHERE (lower(s1.t_text) LIKE lower('%".trim($_GET['search'])."%') OR lower(s2.text) LIKE lower('%".trim($_GET['search'])."%')";
			else $dosearch .= "WHERE (lower(s1.topic) LIKE lower('%".trim($_GET['search'])."%')";
		}
		$dosearch .= ')';
	}
     
	if(!empty($strkat)) 
	{
		$dosearch .= ' AND (';
		$kat = explode("|",$strkat);
		for($y=0;$y<count($kat)-1;$y++)
		{     
			if($y == 0) $d = '';
			else        $d = 'OR ';
			$k = $kat[$y];
			$k = str_replace("k_","",$k);
			$dosearch .= $d."s3.id = '".intval($k)."' ";
		}
		$dosearch .= ')';
	}
     
	if(!permission("intforum")) $dosearch .= ' AND s4.intern = 0';
		$qry = db("SELECT s1.id,s1.topic,s1.kid,s1.t_reg,s1.t_email,s1.t_nick,s1.hits,s4.intern,s3.id AS subid
                FROM ".$db['f_threads']." AS s1
                LEFT JOIN ".$db['f_posts']." AS s2
                ON s1.id = s2.sid
                LEFT JOIN ".$db['f_skats']." AS s3
                ON s1.kid = s3.id
                LEFT JOIN ".$db['f_kats']." AS s4
                ON s3.sid = s4.id
                ".$dosearch."
                GROUP by s1.id
                ORDER BY s1.lp DESC
                LIMIT ".($page - 1)*$maxfsearch.",".$maxfsearch."");
                  
	$qrye = db("SELECT s1.id
                FROM ".$db['f_threads']." AS s1
                LEFT JOIN ".$db['f_posts']." AS s2
                ON s1.id = s2.sid
                LEFT JOIN ".$db['f_skats']." AS s3
                ON s2.kid = s3.id
                AND s1.kid = s3.id
                LEFT JOIN ".$db['f_kats']." AS s4
                ON s3.sid = s4.id
                ".$dosearch."
                GROUP by s1.id");
	$entrys = _rows($qrye);
 
    while($get = _fetch($qry))
    {
		$intF = db("SELECT * FROM ".$db['f_access']."
                     WHERE user = '".$_SESSION['id']."'
                     AND forum = '".$get['subid']."'");
		if(($get['intern'] == 1 && !_rows($intF) && $chkMe != 4)) $entrys--;
		if($get['intern'] == 0 || (($get['intern'] == 1 && _rows($intF)) || $chkMe == 4))
		{
			if($get['sticky'] == 1) $sticky = _forum_sticky;
			else $sticky = "";
    		if($get['closed'] == 1) $closed = _closedicon;
			else $closed = "";
    			$cntpage = cnt($db['f_posts'], " WHERE sid = ".$get['id']);
			if($cntpage == 0) $pagenr = 1;
			else $pagenr = ceil($cntpage/$maxfposts);
      
			$qrylp = db("SELECT date,nick,reg,email FROM ".$db['f_posts']."
                        WHERE sid = '".$get['id']."'
                        ORDER BY date DESC");   
			if(_rows($qrylp))
			{
				$getlp = _fetch($qrylp);
				$lpost = show(_forum_thread_lpost, array("nick" => autor($getlp['reg'], '', $getlp['nick'], $getlp['email']),
                                                      "date" => date("d.m.y H:i", $getlp['date'])._uhr));
				$lpdate = $getlp['date'];
			} else {
				$lpost = "-";
				$lpdate = "";
			}
      
			$threadlink = show(_forum_thread_search_link, array("topic" => cut(re($get['topic']),$lforumtopic),
                                                                "id" => $get['id'],
                                                                "sticky" => $sticky,
                                                                "hl" => $_GET['search'],
                                                                "closed" => $closed,
                                                                "lpid" => $cntpage+1,
                                                                "page" => $pagenr));
                                                                
			$class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;	
    
			$results .= show($dir."/forum_search_results", array("new" => check_new($get['lp']),
                                                                 "topic" => $threadlink,
                                                                 "subtopic" => cut(re($get['subtopic']),$lforumsubtopic),
                                                                 "hits" => $get['hits'],
                                                                 "replys" => cnt($db['f_posts'], " WHERE sid = '".$get['id']."'"),
                                                                 "class" => $class,
                                                                 "lpost" => $lpost,
                                                                 "autor" => autor($get['t_reg'], '', $get['t_nick'], $get['t_email'])));
		}
    }
        
    $nav = nav($entrys,$maxfsearch,$getstr);
    $show = show($dir."/forum_search_show", array("head" => _forum_search_results,
                                                    "autor" => _autor,
                                                    "thread" => _forum_thread,
                                                    "lpost" => _forum_lpost,
                                                    "nav" => $nav,
                                                    "results" => $results,
                                                    "replys" => _forum_replys,
                                                    "hits" => _hits));
       
} elseif($_GET['where'] = 'site'){
//###############################################################################################################
//Auswertung für Websitesuche
//DOSEARCH FÜR NEWS,ARTIKEL,SITES
	if($_GET['con'] == 'or' OR $_GET['con'] == 'andb')
	{
		$suche = explode(" ",$_GET['search']);
		for($x=0;$x<count($suche);$x++)
		{        
			if($x == 0) { $c = 'WHERE (';
			} elseif($_GET['con'] == 'andb') { $c = 'AND ';}
			 else {       $c = 'OR ';}
			$dosearch .= $c." (lower(titel) LIKE lower('%".trim($suche[$x])."%') AND titel != '') 
							OR (lower(text) LIKE lower('%".trim($suche[$x])."%') AND `text` != '')";
		}
	} else { 
		$dosearch .= "WHERE ((lower(titel) LIKE lower('%".up($_GET['search'])."%') AND titel != '') OR (lower(text) LIKE lower('%".up($_GET['search'])."%') AND `text` != '')";
	}
	$dosearch .= ')';
//NEWS

//intern:
	if(permission("intnews")) {
   	 $qry = db("SELECT * FROM ".$db['news']." 
     	        ".$dosearch." AND public != 0 AND intern != 0
				 ORDER BY titel ASC");
//nicht intern	
	} else {
		$qry = db("SELECT * FROM ".$db['news']." 
     	        ".$dosearch." AND public != 0 AND intern != 1
				 ORDER BY titel ASC");
	}
		 
			 
	while($get = _fetch($qry))
	{
		$class = ($color % 2) ? "contentMainFirst" : "contentMainSecond"; $color++;
		$shownews .= show($dir."/search_show", array("class" => $class,
                                               "type" => 'news',
                                               "href" => '../news/index.php?action=show&amp;id='.$get['id'],
                                               "titel" => re($get['titel'])
                                              ));
	}
  
	unset($class);
//ARTIKEL
	$qry = db("SELECT * FROM ".$db['artikel']." 
             ".$dosearch." 
             ORDER BY titel ASC");
	while($get = _fetch($qry))
	{
		$class = ($color % 2) ? "contentMainFirst" : "contentMainSecond"; $color++;
		$showartikel .= show($dir."/search_show", array(
                                               "href" => '../artikel/index.php?action=show&amp;id='.$get['id'],
                                               "class" => $class,
                                               "type" => 'artikel',
                                               "titel" => re($get['titel'])
                                              ));
	}

	unset($class);
//SITES
	$qry = db("SELECT * FROM ".$db['sites']." 
             ".$dosearch." 
			 ORDER BY titel ASC");
	while($get = _fetch($qry))
	{
		$class = ($color % 2) ? "contentMainFirst" : "contentMainSecond"; $color++;
		$showsites .= show($dir."/search_show", array(
                                               "href" => '../sites/?show='.$get['id'],
                                               "class" => $class,
                                               "type" => 'site',
                                               "titel" => re($get['titel'])
                                              ));
	}

	unset($class,$dosearch);
//CLANWARS  
	if($_GET['con'] == 'or' OR $_GET['con'] == 'andb')
	{
		$suche = explode(" ",$_GET['search']);
		for($x=0;$x<count($suche);$x++)
		{        
			if($x == 0) { $c = 'WHERE (';
			} elseif($_GET['con'] == 'andb') { $c = ' AND ';}
			 else {       $c = 'OR ';}
			$dosearch .= $c." ((lower(s1.bericht) LIKE lower('%".trim($suche[$x])."%')) 
								OR (lower(s1.clantag) LIKE lower('%".trim($suche[$x])."%')) 
								OR (lower(s1.gegner) LIKE lower('%".trim($suche[$x])."%')))";
		}
	} else { 
		$dosearch .= "WHERE (lower(s1.bericht) LIKE lower('%".up($_GET['search'])."%')) 
						OR (lower(s1.clantag) LIKE lower('%".up($_GET['search'])."%')) 
						OR (lower(s1.gegner) LIKE lower('%".up($_GET['search'])."%')";
	}
	$dosearch .= ')';
	$qry = db("SELECT s1.id, s1.gegner, s1.clantag, s2.name FROM ".$db['cw']." AS s1 JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id 
              ".$dosearch." 
			  ORDER BY s1.clantag ASC");
	while($get = _fetch($qry))
	{
		$class = ($color % 2) ? "contentMainFirst" : "contentMainSecond"; $color++;
		$showcw .= show($dir."/search_show", array(
                                               "href" => '../clanwars/?action=details&id='.$get['id'],
                                               "class" => $class,
                                               "type" => 'clanwar',
                                               "titel" => re($get['gegner'])." (".re($get['clantag']).") vs. ".re($get['name'])
                                              ));
	}
	unset($class,$dosearch);
//EVENTS  
	if($_GET['con'] == 'or' OR $_GET['con'] == 'andb')
	{
		$suche = explode(" ",$_GET['search']);
		for($x=0;$x<count($suche);$x++)
		{        
			if($x == 0) { $c = 'WHERE (';
			} elseif($_GET['con'] == 'andb') { $c = 'AND ';}
			 else {       $c = 'OR ';}
			$dosearch .= $c." (lower (title) LIKE lower('%".trim($suche[$x])."%')) 
								OR (lower(event) LIKE lower('%".trim($suche[$x])."%'))";
		}
	} else { 
		$dosearch .= "WHERE (lower (title) LIKE lower('%".up($_GET['search'])."%')) 
				OR (lower(event) LIKE lower('%".up($_GET['search'])."%')";
	}
	$dosearch .= ')';
    $qry = db("SELECT datum, title FROM ".$db['events']." 
			".$dosearch." 
            ORDER BY title ASC");
	while($get = _fetch($qry))
	{
		$class = ($color % 2) ? "contentMainFirst" : "contentMainSecond"; $color++;
		$showev .= show($dir."/search_show", array(
                                               "href" => '../kalender/?action=show&time='.$get['datum'],
                                               "class" => $class,
                                               "type" => 'kalender',
                                               "titel" => re($get['title'])." (".date('d.m.Y', $get['datum']).")"
                                              ));
	}
  
	if(!empty($showcw)) $showcw = '<tr><td class="contentMainTop"><b>'._cw.'</b></td></tr>'.$showcw;
	if(!empty($showev)) $showev = '<tr><td class="contentMainTop"><b>'._kalender.'</b></td></tr>'.$showev;
	if(!empty($shownews)) $shownews = '<tr><td class="contentMainTop"><b>'._news.'</b></td></tr>'.$shownews;
	if(!empty($showartikel)) $showartikel = '<tr><td class="contentMainTop"><b>'._artikel.'</b></td></tr>'.$showartikel;
	if(!empty($showsites)) $showsites = '<tr><td class="contentMainTop"><b>'._search_sites.'</b></td></tr>'.$showsites;
  
	if(empty($showcw) AND empty($showev) AND empty($shownews) AND empty($showartikel) AND empty($showsites)) {
		$status = _gt_not_found;
	}
  
	$show = show($dir."/search_global", array("shownews" => $shownews,
                                             "showartikel" => $showartikel,
                                             "showsites" => $showsites,
											 "showcw" => $showcw, 
											 "showev" => $showev,
											 "status" => $status,
											 "forum" => _forum_search_head,
                                             "results" => _search_results));
  
}
//###############################################################################################################  
//Diverse Abfragen
if($_GET['searchplugin'] == true)
{
	$onclick = 'onclick="more(1)" style="cursor:pointer"';
    $img = '<img id="img1" src="../inc/images/expand.gif" alt="" />';
    $style = 'style="display:none"';
    
    if($_GET['si_board'] == true) $si_board = "checked=\"checked\"";
	//if(empty($strkat)) $all_board = "checked=\"checked\"";
} else {
    $si_board = "checked=\"checked\"";
    
}
if($_GET['con'] == 'or') $chk_con = "selected=\"selected\"";
if($_GET['con'] == 'andb') $chk_con2 = "selected=\"selected\"";

if($_GET['allkat'] == true) { $all_board = "checked=\"checked\"";
} else { $all_board = ""; }
  
if($_GET['where'] == 'site') { 
$where1 = "checked=\"checked\"";
} else { $where2 = "checked=\"checked\""; }
  
$index = show($dir."/search", array("head" => _search_head,
                                    "searchwords" => _search_word,
                                    "board" => _forum,
                                    "fkats" => $fkats,
                                    "show" => $show,
                                    "search" => $_GET['search'],
                                    "searchin" => _search_in,
                                    "onclick" => $onclick,
                                    "img" => $img,
                                    "con_and" => _search_con_and,
                                    "con_or" => _search_con_or,
									"con_andb" => _search_con_andb,
                                    "chkcon" => $chk_con,
									"chkcon2" => $chk_con2,
                                    "style" => $style,
                                    "si_board" => $si_board,
                                    "all_board" => $all_board,
                                    "acheck1" => $acheck1,
                                    "acheck2" => $acheck2,
                                    "tcheck1" => $tcheck1,
                                    "tcheck2" => $tcheck2,
									"where1" => $where1,
									"where2" => $where2,
                                    "value" => _button_value_search1,
                                    "autor" => _search_type_autor,
                                    "searcharea" => _search_for_area,
									"searchareaforum" => _search_for_area_forum,
									"forum" => _forum,
									"site" => _search_website,
                                    "text" => _search_type_text,
                                    "type" => _search_type,
                                    "hint" => _search_forum_hint,
                                    "all" => _search_forum_all,
                                    "full" => _search_type_full,
                                    "intitle" => _search_type_title));

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>