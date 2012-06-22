<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_forum;
$title = $pagetitle." - ".$where."";
$dir = "forum";
## SECTIONS
if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];

switch ($action):
default:
  $qry = db("SELECT * FROM ".$db['f_kats']." ORDER BY kid");
  while($get = _fetch($qry))
  {
    $showt = "";
    $qrys = db("SELECT * FROM ".$db['f_skats']."
                WHERE sid = '".$get['id']."'
                ORDER BY pos");
    while($gets = _fetch($qrys))
    {
      if($get['intern'] == 0 || ($get['intern'] == 1 && fintern($gets['id'])))
      {
        unset($lpost);
        $qrylt = db("SELECT t_date,t_nick,t_email,t_reg,lp,first,topic
                     FROM ".$db['f_threads']."
                     WHERE kid = '".$gets['id']."'
                     ORDER BY lp DESC");
        $getlt = _fetch($qrylt);
  
        $qrylp = db("SELECT s1.date,s1.nick,s1.reg,s1.email,s2.t_date,s2.lp,s2.first
                     FROM ".$db['f_posts']." AS s1
                     LEFT JOIN ".$db['f_threads']." AS s2
                     ON s2.lp = s1.date
                     WHERE s2.kid = '".$gets['id']."'
                     ORDER BY s1.date DESC");
        $getlp = _fetch($qrylp);
  
        if(cnt($db['f_threads'], " WHERE kid = '".$gets['id']."'") == "0")
        {
          $lpost = "-";
          $lpdate = "";
        } elseif($getlt['first'] == "1") {
          $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlt['t_reg'], '', $getlt['t_nick'], $getlt['t_email']),
                                                    "date" => date("d.m.y H:i", $getlt['t_date'])._uhr));
  
          $lpdate = $getlt['t_date'];
        } elseif($getlt['first'] == "0") {
          $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlp['reg'], '', $getlp['nick'], $getlp['email']),
                                                    "date" => date("d.m.y H:i", $getlp['date'])._uhr));
          $lpdate = $getlp['date'];
        }
  
        $threads = cnt($db['f_threads'], " WHERE kid = '".$gets['id']."'");
        $posts = cnt($db['f_posts'], " WHERE kid = '".$gets['id']."'");
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
  
        $showt .= show($dir."/kats_show", array("topic" => re($gets['kattopic']),
                                                "subtopic" => re($gets['subtopic']),
                                                "lpost" => $lpost,
                                                "new" => check_new($lpdate),
                                                "threads" => $threads,
                                                "posts" => $posts+$threads,
                                                "class" => $class,
                                                "kid" => $gets['sid'],
                                                "id" => $gets['id']));
      }
    }

    if($get['intern'] == 1) $katname =  show(_forum_katname_intern, array("katname" => re($get['name'])));
    else $katname = re($get['name']);

    if(!empty($showt))
    {
      $show .= show($dir."/kats", array("katname" => $katname,
                                        "topic" => _forum_topic,
                                        "lpost" => _forum_lpost,
                                        "threads" => _forum_threads,
                                        "posts" => _forum_posts,
                                        "showt" => $showt));
    }
  }
  $threads = show(_forum_cnt_threads, array("threads" => cnt($db['f_threads'])));
  $posts = show(_forum_cnt_posts, array("posts" => cnt($db['f_posts'])+cnt($db['f_threads'])));

  $qrytp = db("SELECT id,user,forumposts FROM ".$db['userstats']."
               ORDER BY forumposts DESC, id
               LIMIT 5");
			   
  while($gettp = _fetch($qrytp))
  {
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show_top .= show($dir."/top_posts_show", array("nick" => autor($gettp['user']),
                                                    "posts" => $gettp['forumposts'],
                                                    "class" => $class));
  }


  $top_posts = show($dir."/top_posts", array("head" => _forum_top_posts,
                                             "show" => $show_top,
                                             "nick" => _nick,
                                             "posts" => _forum_posts));

  $qryo = db("SELECT id FROM ".$db['users']."
              WHERE whereami = 'Forum'
              AND time+'".$useronline."'>'".time()."'
              AND id != '".$userid."'");
  if(_rows($qryo))
  {
    $i=0;
    $check = 1;
    $cnto = cnt($db['users'], " WHERE time+'".$useronline."'>'".time()."' AND whereami = 'Forum' AND id != '".$userid."'");
    while($geto = _fetch($qryo))
    {
      if($i == 5)
      {
        $end = "<br />";
        $i=0;
      } else {
        if($cnto == $check) $end = "";
        else $end = ", ";
      }
      $nick .= autor($geto['id']).$end;

      $i++;
      $check++;
    }
  } else {
    if($chkMe == "unlogged") $nick = "<center>"._forum_nobody_is_online."</center>";
    else                        $nick = "<center>"._forum_nobody_is_online2."</center>";
  }

  $online = show($dir."/online", array("nick" => $nick,
                                       "head" => _forum_online_head));

  $index = show($dir."/forum", array("head" => _forum_head,
                                     "threads" => $threads,
                                     "search" => _forum_searchlink,
                                     "posts" => $posts,
                                     "show" => $show,
                                     "online" => $online,
                                     "top_posts" => $top_posts));
break;
case 'show';
  $check = db("SELECT s2.id,s1.intern FROM ".$db['f_kats']." AS s1
               LEFT JOIN ".$db['f_skats']." AS s2
               ON s2.sid = s1.id
               WHERE s2.id = '".intval($_GET['id'])."'");
  $checks = _fetch($check);

  if($checks['intern'] == 1 && (!permission("intforum") && !fintern($checks['id'])))
  {
    $index = error(_error_no_access, 1);
  } else {
    if(isset($_GET['page']))  $page = $_GET['page'];
    else $page = 1;

    if(empty($_POST['suche']))
    {
      $qry = db("SELECT * FROM ".$db['f_threads']."
                 WHERE kid ='".intval($_GET['id'])."'
                 OR global = 1
                 ORDER BY global DESC, sticky DESC, lp DESC, t_date DESC
                 LIMIT ".($page - 1)*$maxfthreads.",".$maxfthreads."");
    } else {
      $qry = db("SELECT s1.global,s1.topic,s1.subtopic,s1.t_text,s1.t_email,s1.hits,s1.t_reg,s1.t_date,s1.closed,s1.sticky,s1.id
                 FROM ".$db['f_threads']." AS s1
                 WHERE s1.topic LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".intval($_GET['id'])."'
                 OR s1.subtopic LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".intval($_GET['id'])."'
                 OR s1.t_text LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".intval($_GET['id'])."'
                 ORDER BY s1.global DESC, s1.sticky DESC, s1.lp DESC, s1.t_date DESC
                 LIMIT ".($page - 1)*$maxfthreads.",".$maxfthreads."");
    }

    $entrys = cnt($db['f_threads'], " WHERE kid = ".intval($_GET['id']));
    $i = 2;

    while($get = _fetch($qry))
    {
      if($get['sticky'] == "1") $sticky = _forum_sticky;
      else $sticky = "";

      if($get['global'] == "1") $global = _forum_global;
      else $global = "";

      if($get['closed'] == "1") $closed = show("page/button_closed", array());
      else $closed = "";

      $cntpage = cnt($db['f_posts'], " WHERE sid = ".$get['id']);

      if($cntpage == "0") $pagenr = "1";
      else $pagenr = ceil($cntpage/$maxfposts);

      if(empty($_POST['suche']))
      {
        $qrys = db("SELECT id FROM ".$db['f_skats']."
                    WHERE id = '".intval($_GET['id'])."'");
        $gets = _fetch($qrys);

        $threadlink = show(_forum_thread_link, array("topic" => re(cut($get['topic'],$lforumtopic)),
                                                     "id" => $get['id'],
                                                     "kid" => $gets['id'],
                                                     "sticky" => $sticky,
                                                     "global" => $global,
                                                     "closed" => $closed,
                                                     "lpid" => $cntpage+1,
                                                     "page" => $pagenr));
      } else {
        $threadlink = show(_forum_thread_search_link, array("topic" => re(cut($get['topic'],$lforumtopic)),
                                                            "id" => $get['id'],
                                                            "sticky" => $sticky,
                                                            "hl" => $_POST['suche'],
                                                            "closed" => $closed,
                                                            "lpid" => $cntpage+1,
                                                            "page" => $pagenr));
      }

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

      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $threads .= show($dir."/forum_show_threads", array("new" => check_new($get['lp']),
                                                         "topic" => $threadlink,
                                                         "subtopic" => re(cut($get['subtopic'],$lforumsubtopic)),
                                                         "hits" => $get['hits'],
                                                         "replys" => cnt($db['f_posts'], " WHERE sid = '".$get['id']."'"),
                                                         "class" => $class,
                                                         "lpost" => $lpost,
                                                         "autor" => autor($get['t_reg'], '', $get['t_nick'], $get['t_email'])));
      $i--;
    }

    $qrys = db("SELECT id,kattopic FROM ".$db['f_skats']."
                WHERE id = '".intval($_GET['id'])."'");
    $gets = _fetch($qrys);

    $search = show($dir."/forum_skat_search", array("head_search" => _forum_head_skat_search,
                                                    "id" => $_GET['id'],
                                                    "suchwort" => re($_POST['suche'])));
    $nav = nav($entrys,$maxfthreads,"?action=show&amp;id=".$_GET['id']."");

    if(!empty($_POST['suche']))
    {
      $what = show($dir."/search", array("head" => _forum_search_head,
                                         "thread" => _forum_thread,
                                         "autor" => _autor,
                                         "lpost" => _forum_lpost,
                                         "hits" => _hits,
                                         "replys" => _forum_replys,
                                         "threads" => $threads,
                                         "nav" => $nav));
    } else {
      $new = show(_forum_new_thread, array("id" => $_GET['id']));
      $what = show($dir."/forum_show_thread", array("head_threads" => _forum_head_threads,
                                                    "thread" => _forum_thread,
                                                    "autor" => _autor,
                                                    "lpost" => _forum_lpost,
                                                    "hits" => _hits,
                                                    "replys" => _forum_replys,
                                                    "nav" => $nav,
                                                    "threads" => $threads,
                                                    "new" => $new,));
    }

    $qrysub = db("SELECT sid FROM ".$db['f_skats']."
                  WHERE id = '".intval($_GET['id'])."'");
    $subkat = _fetch($qrysub);

    $qryk = db("SELECT name FROM ".$db['f_kats']."
                WHERE id = '".$subkat['sid']."'");
    $kat = _fetch($qryk);

    $wheres = show(_forum_subkat_where, array("where" => re($gets['kattopic']),
                                              "id" => $gets['id']));

    $index = show($dir."/forum_show", array("head" => _forum_head,
                                            "where" => $wheres,
                                            "mainkat" => re($kat['name']),
                                            "what" => $what,
                                            "search" => $search));
  }
break;
case 'showthread';
  $check = db("SELECT s3.name,s3.intern,s2.sid,s1.kid,s2.id
               FROM ".$db['f_kats']." s3, ".$db['f_skats']." s2, ".$db['f_threads']." s1
               WHERE s1.kid = s2.id
               AND s2.sid = s3.id
               AND s1.id = '".intval($_GET['id'])."'");
  $checks = _fetch($check);

  $f_check = db("SELECT * FROM ".$db['f_threads']."
                 WHERE id = '".intval($_GET['id'])."'
                 AND kid = '".$checks['kid']."'");
  if(_rows($f_check))
  {
    if($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id']))
    {
      $index = error(_error_wrong_permissions, 1);
    } else {
      $update = db("UPDATE ".$db['f_threads']."
                    SET `hits` = hits+1
                    WHERE id = '".intval($_GET['id'])."'");

      if(isset($_GET['page'])) $page = $_GET['page'];
      else $page = 1;

      $qryp = db("SELECT * FROM ".$db['f_posts']."
                  WHERE sid = '".intval($_GET['id'])."'
                  ORDER BY id
                  LIMIT ".($page - 1)*$maxfposts.",".$maxfposts."");

      $entrys = cnt($db['f_posts'], " WHERE sid = ".intval($_GET['id']));
      $i = 2;

      if($entrys == 0) $pagenr = "1";
      else $pagenr = ceil($entrys/$maxfposts);
      
      if(!empty($_GET['hl'])) $hL = '&amp;hl='.$_GET['hl'];
      else                    $hL = '';
      
      $lpost = show(_forum_lastpost, array("id" => $entrys+1,
                                           "tid" => $_GET['id'],
                                           "page" => $pagenr.$hL));

      while($getp = _fetch($qryp))
      {
        if(data($getp['reg'], "signatur")) $sig = _sig.bbcode(data($getp['reg'], "signatur"));
        else                               $sig = "";

        if($getp['reg'] != 0) $userposts = show(_forum_user_posts, array("posts" => userstats($getp['reg'], "forumposts")));
        else                  $userposts = "";

        if($getp['reg'] == 0) $onoff = "";
        else                  $onoff = onlinecheck($getp['reg']);

        $zitat = show("page/button_zitat", array("id" => $_GET['id'],
                                                 "action" => "action=post&amp;do=add&amp;kid=".$getp['kid']."&amp;zitat=".$getp['id'],
                                                 "title" => _button_title_zitat));

        if($getp['reg'] == $userid || permission("forum"))
        {
          $edit = show("page/button_edit_single", array("id" => $getp['id'],
                                                       "action" => "action=post&amp;do=edit",
                                                       "title" => _button_title_edit));

          $delete = show("page/button_delete_single", array("id" => $getp['id'],
                                                           "action" => "action=post&amp;do=delete",
                                                           "title" => _button_title_del,
                                                           "del" => convSpace(_confirm_del_entry)));
        } else {
          $delete = "";
          $edit = "";
        }
        
        $ftxt = hl($getp['text'], $_GET['hl']);
        if($_GET['hl']) $text = bbcode($ftxt['text']);
        else $text = bbcode($getp['text']);
        
        if($chkMe == 4) $posted_ip = $getp['ip'];
        else $posted_ip = _logged;

        $titel = show(_eintrag_titel_forum, array("postid" => $i+($page-1)*$maxfposts,
				  								 				     			"datum" => date("d.m.Y", $getp['date']),
					  								 		 			    	"zeit" => date("H:i", $getp['date'])._uhr,
                                            "url" => '?action=showthread&amp;id='.intval($_GET['id']).'&amp;page='.intval(empty($_GET['page']) ? 1 : $_GET['page']).'#p'.($i+($page-1)*$maxfposts),
                                            "edit" => $edit,
                                            "delete" => $delete));

        if($getp['reg'] != 0)
        {
          $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                      WHERE id = '".$getp['reg']."'");
          $getu = _fetch($qryu);

          $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
          $pn = show(_pn_write_forum, array("id" => $getp['reg'],
  		  	  												        "nick" => $getu['nick']));

          if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
      		else {
            $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
            $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
      		}

          if(empty($getu['hp'])) $hp = "";
          else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
        } else {
          $icq = "";
          $pn = "";
          $email = show(_emailicon_forum, array("email" => eMailAddr($getp['email'])));
          if(empty($getp['hp'])) $hp = "";
          else $hp = show(_hpicon_forum, array("hp" => $getp['hp']));
        }
      
        $nick = autor($getp['reg'], '', $getp['nick'], $getp['email']);
        if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
        {
          if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
        }
      
        $show .= show($dir."/forum_posts_show", array("nick" => $nick,
                                                      "postnr" => "#".($i+($page-1)*$maxfposts),
                                                      "p" => ($i+($page-1)*$maxfposts),
                                                      "text" => $text,
                                                      "pn" => $pn,
                                                      "class" => $ftxt['class'],
                                                      "icq" => $icq,
                                                      "hp" => $hp,
                                                      "email" => $email,
                                                      "status" => getrank($getp['reg']),
                                                      "avatar" => useravatar($getp['reg']),
                                                      "ip" => $posted_ip,
                                                      "edited" => $getp['edited'],
                                                      "posts" => $userposts,
                                                      "titel" => $titel,
                                                      "signatur" => $sig,
                                                      "zitat" => $zitat,
                                                      "onoff" => $onoff,
                                                      "top" => _topicon,
                                                      "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
        $i++;
      }

      $qry = db("SELECT * FROM ".$db['f_threads']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      $qryw = db("SELECT s1.kid,s1.topic,s2.kattopic,s2.sid
                  FROM ".$db['f_threads']." AS s1
                  LEFT JOIN ".$db['f_skats']." AS s2
                  ON s1.kid = s2.id
                  WHERE s1.id = '".intval($_GET['id'])."'");
      $getw = _fetch($qryw);

      $qrykat = db("SELECT name FROM ".$db['f_kats']."
                    WHERE id = '".$getw['sid']."'");
      $kat = _fetch($qrykat);

      $wheres = show(_forum_post_where, array("wherepost" => re($getw['topic']),
                                              "wherekat" => re($getw['kattopic']),
                                              "mainkat" => re($kat['name']),
                                              "tid" => $_GET['id'],
                                              "kid" => $getw['kid']));
      if($get['t_reg'] == "0")
      {
        $userposts = "";
        $onoff = "";
      } else {
        $onoff = onlinecheck($get['t_reg']);
        $userposts = show(_forum_user_posts, array("posts" => userstats($get['t_reg'], "forumposts")));
      }

      $zitat = show("page/button_zitat", array("id" => $_GET['id'],
                                               "action" => "action=post&amp;do=add&amp;kid=".$getw['kid']."&amp;zitatt=".$get['id'],
                                               "title" => _button_title_zitat));
      if($get['closed'] == "1")
      {
        $add = show("page/button_closed", array());
      } else {
        $add = show(_forum_addpost, array("id" => $_GET['id'],
                                          "kid" => $getw['kid']));
      }

      $nav = nav($entrys,$maxfposts,"?action=showthread&amp;id=".$_GET['id'].$hL);

      if(data($get['t_reg'], "signatur")) $sig = _sig.bbcode(data($get['t_reg'], "signatur"));
      else $sig = "";

      if($get['t_reg'] == $userid || permission("forum"))
        $editt = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "action=thread&amp;do=edit",
                                                      "title" => _button_title_edit));

      if(permission("forum"))
      {
        if($get['sticky'] == "1") $sticky = "checked=\"checked\"";
        if($get['global'] == "1") $global = "checked=\"checked\"";

        if($get['closed'] == "1")
        {
          $closed = "checked=\"checked\"";
          $opened = "";
        } else {
          $opened = "checked=\"checked\"";
          $closed = "";
        }

        $qryok = db("SELECT * FROM ".$db['f_kats']."
                     ORDER BY kid");
        while($getok = _fetch($qryok))
        {
          $skat = "";
          $qryo = db("SELECT * FROM ".$db['f_skats']."
                      WHERE sid = '".$getok['id']."'
                      ORDER BY kattopic");
          while($geto = _fetch($qryo))
          {
            $skat .= show(_forum_select_field_skat, array("value" => $geto['id'],
                                                          "what" => re($geto['kattopic'])));
          }

          $move .= show(_forum_select_field_kat, array("value" => "lazy",
                                                       "what" => re($getok['name']),
                                                       "skat" => $skat));
        }

        $admin = show($dir."/admin", array("admin" => _admin,
                                           "id" => $get['id'],
                                           "open" => _forum_admin_open,
                                           "close" => _forum_admin_close,
                                           "asticky" => _forum_admin_addsticky,
                                           "delete" => _forum_admin_delete,
                                           "moveto" => _forum_admin_moveto,
                                           "aglobal" => _forum_admin_global,
                                           "move" => $move,
                                           "closed" => $closed,
                                           "opened" => $opened,
                                           "global" => $global,
                                           "sticky" => $sticky));
      }

      $ftxt = hl($get['t_text'], $_GET['hl']);
      if($_GET['hl']) $text = bbcode($ftxt['text']);
      else $text = bbcode($get['t_text']);

      if($chkMe == "4") $posted_ip = $get['ip'];
      else $posted_ip = _logged;

      $titel = show(_eintrag_titel_forum, array("postid" => "1",
												 				     			"datum" => date("d.m.Y", $get['t_date']),
													 		 			    	"zeit" => date("H:i", $get['t_date'])._uhr,
                                          "url" => '?action=showthread&amp;id='.intval($_GET['id']).'&amp;page=1#p1',
                                          "edit" => $editt,
                                          "delete" => ""));


      if($get['t_reg'] != 0)
      {
        $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                    WHERE id = '".$get['t_reg']."'");
        $getu = _fetch($qryu);

        $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
        $pn = show(_pn_write_forum, array("id" => $get['t_reg'],
		  	  												        "nick" => $getu['nick']));
        if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
    		else {
          $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
          $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
    		}

        if(empty($getu['hp'])) $hp = "";
        else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
      } else {
        $pn = "";
        $icq = "";
        $email = show(_emailicon_forum, array("email" => eMailAddr($get['t_email'])));
        if(empty($get['t_hp'])) $hp = "";
        else $hp = show(_hpicon_forum, array("hp" => $get['t_hp']));
      }
      
      $nick = autor($get['t_reg'], '', $get['t_nick'], $get['t_email']);
      if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
      {
        if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
      }

	  $qryabo = db("SELECT user,fid FROM ".$db['f_abo']."
                    WHERE user = '".$userid."'
					AND fid = '".intval($_GET['id'])."'");
	  $getabo = _fetch($qryabo);
	  if(_rows($qryabo)) $abo = 'checked="checked"';

	  if($chkMe == "unlogged")
	  {
		  $f_abo = '';
	  } else { 
	  	$f_abo = show($dir."/forum_abo", array("id" => intval($_GET['id']),
                                             "abo" => $abo,
                                             "abo_info" => _foum_fabo_checkbox,
                                             "abo_title" => _forum_abo_title,
                                             "submit" => _button_value_save
                                            )); 
		} 
 
  	  if(empty($get['vote'])) $vote = "";
  	  else {
        include_once(basePath.'/inc/menu-functions/fvote.php');
        $vote = '<tr><td>'.fvote($get['vote']).'</td></tr>';
	  	}
      
      $title = re($getw['topic']).' - '.$title;
      $index = show($dir."/forum_posts", array("head" => _forum_head,
                                               "where" => $wheres,
                                               "admin" => $admin,
                                               "nick" => $nick,
                                               "threadhead" => re($getw['topic']),
                                               "titel" => $titel,
                                               "postnr" => "1",
                                               "class" => $ftxt['class'],
                                               "pn" => $pn,
                                               "icq" => $icq,
                                               "hp" => $hp,
                                               "email" => $email,
                                               "posts" => $userposts,
                                               "text" => $text,
                                               "status" => getrank($get['t_reg']),
                                               "avatar" => useravatar($get['t_reg']),
                                               "edited" => $get['edited'],
                                               "signatur" => $sig,
                                               "date" => _posted_by.date("d.m.y H:i", $get['t_date'])._uhr,
                                               "zitat" => $zitat,
                                               "onoff" => $onoff,
                                               "ip" => $posted_ip,
                                               "top" => _topicon,
                                               "lpost" => $lpost,
                                               "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1,
                                               "add" => $add,
                                               "nav" => $nav,
                      											   "vote" => $vote,
                      											   "f_abo" => $f_abo, 
                                               "show" => $show));
    }
  } else {
    $index = error(_error_wrong_permissions, 1);
  }
break;
case 'thread';
  if($_GET['do'] == "edit")
  {
    $qry = db("SELECT * FROM ".$db['f_threads']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);
    if($get['t_reg'] == $userid || permission("forum"))
    {
      if(permission("forum"))
      {
        if($get['sticky'] == 1) $sticky = "checked=\"checked\"";
        if($get['global'] == 1) $global = "checked=\"checked\"";
  
        $admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
                                                "addsticky" => _forum_admin_addsticky,
                                                "sticky" => $sticky,
                                                "addglobal" => _forum_admin_addglobal,
                                                "global" => $global));
      }
  
      if($get['t_reg'] != 0)
      {
        $form = show("page/editor_regged", array("nick" => autor($get['t_reg']),
                                                 "von" => _autor));
  
      } else {
        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                    "emailhead" => _email,
                                                    "hphead" => _hp));
      }
      
      $qryv = db("SELECT * FROM ".$db['votes']." WHERE id = '".$get['vote']."'");
      $getv = _fetch($qryv);

      $toggle = 'collapse';


			$fget = _fetch(db("SELECT s1.intern,s2.id FROM ".$db['f_kats']." AS s1
                         LEFT JOIN ".$db['f_skats']." AS s2 ON s2.`sid` = s1.id
                         WHERE s2.`id` = '".intval($get['kid'])."'"));
				
			if($getv['intern'] == "1") $intern = 'checked="checked"';
          $intern = ''; $intern_kat = '';  
		  if($fget['intern'] == "1") { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };
      if($getv['closed'] == "1") 
		  {
  		  $isclosed = "checked=\"checked\"";
  		  $display = 'none';
        $toggle = 'expand';
		  }
		
		if(empty($get['vote'])) {
		$vote = show($dir."/form_vote", array("head" => _votes_admin_head,
                                              "value" => _button_value_add,
                                              "what" => "&amp;do=add",
                                              "closed" => "",
                                              "question1" => "",
                                              "a1" => "",
                                              "a2" => "",
                                              "a3" => "",
                                              "a4" => "",
                                              "a5" => "",
                                              "a6" => "",
                                              "a7" => "",
                                              "error" => "",
                                              "br1" => "<!--",
                                              "br2" => "-->",
                  					      	  "display" => "none",
                                              "a8" => "",
                                              "a9" => "",
                                              "a10" => "",
                                              "intern" => "",
                                              "tgl" => "expand",
                  					      	  "vote_del" => _forum_vote_del,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));
		} elseif(!empty($get['vote'])) {
        $vote = show($dir."/form_vote", array("head" => _votes_admin_edit_head,
                                              "value" => "edit",
                                              "id" => $getv['id'],
                                              "what" => $what,
                                              "value" => _button_value_edit,
                                              "br1" => "",
                                              "br2" => "",
                                              "tgl" => $toggle,
                      											  "display" => $display,
                                              "question1" => re($getv['titel']),
                                              "a1" => voteanswer("a1", $getv['id']),
                                              "a2" => voteanswer("a2", $getv['id']),
                                              "a3" => voteanswer("a3", $getv['id']),
                                              "a4" => voteanswer("a4", $getv['id']),
                                              "a5" => voteanswer("a5", $getv['id']),
                                              "a6" => voteanswer("a6", $getv['id']),
                                              "a7" => voteanswer("a7", $getv['id']),
                                              "error" => "",
                                              "a8" => voteanswer("a8", $getv['id']),
                                              "a9" => voteanswer("a9", $getv['id']),
                                              "a10" => voteanswer("a10", $getv['id']),
                                              'intern_kat' => $internVisible,
                                              "intern" => $intern,
                                              "isclosed" => $isclosed,
                      											  "vote_del" => _forum_vote_del,
                                              "closed" => _votes_admin_closed,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));

	  }
      $dowhat = show(_forum_dowhat_edit_thread, array("id" => $_GET['id']));
      $index = show($dir."/thread", array("titel" => _forum_edit_thread_head,
                                          "nickhead" => _nick,
                                          "topichead" => _forum_topic,
                                          "subtopichead" => _forum_subtopic,
                                          "emailhead" => _email,
                                          "form" => $form,
                                          "reg" => $get['t_reg'],
                                          "lang" => $language,
                                          "id" => "",
                                          "b1" => $u_b1,
                                          "b2" => $u_b2,
                                          "security" => _register_confirm,
                                          "preview" => _preview,
                                          "ip" => _iplog_info,
                                          "bbcodehead" => _bbcode,
                                          "eintraghead" => _eintrag,
                                          "what" => _button_value_edit,
                                          "dowhat" => $dowhat,
                                          "error" => "",
                                          "posttopic" => re($get['topic']),
                                          "postsubtopic" => re($get['subtopic']),
                                          "postnick" => re($get['t_nick']),
                                          "postemail" => $get['t_email'],
                                          "posthp" => $get['t_hp'],
                                          "admin" => $admin,
                    					  "vote" => $vote,
                                          "posteintrag" => bbcode($get['t_text'],0,1)));
    } else {
      $index = error(_error_wrong_permissions, 1);
    }
  } elseif($_GET['do'] == "editthread") {
    $qry = db("SELECT * FROM ".$db['f_threads']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    if($get['t_reg'] == $userid || permission("forum"))
    {
      if($get['t_reg'] != 0 || permission('forum'))
      {
        $toCheck = empty($_POST['eintrag']);
      } else {
        $toCheck = empty($_POST['topic']) || empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
      }
  
      if($toCheck)
  	  {
        if($get['t_reg'] != 0)
        {
          if(empty($_POST['eintrag'])) $error = _empty_eintrag;
          $form = show("page/editor_regged", array("nick" => autor($get['t_reg']),
                                                   "von" => _autor));
  
        } else {
          if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode; 
          elseif(empty($_POST['topic'])) $error = _empty_topic;
    	    elseif(empty($_POST['nick'])) $error = _empty_nick;
    	    elseif(empty($_POST['email'])) $error = _empty_email;
    	    elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
    	    elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
  
          $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                      "emailhead" => _email,
                                                      "hphead" => _hp));
        }
  
    	  $error = show("errors/errortable", array("error" => $error));
  
        if(permission("forum"))
        {
          if(isset($_POST['sticky'])) $sticky = "checked";
          if(isset($_POST['global'])) $global = "checked";
  
          $admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
                                                  "addsticky" => _forum_admin_addsticky,
                                                  "sticky" => $sticky,
                                                  "addglobal" => _forum_admin_addglobal,
                                                  "global" => $global));
        }
  		$qryv = db("SELECT * FROM ".$db['votes']."
                    WHERE id = '".$get['vote']."'");
      $getv = _fetch($qryv);

			$fget = _fetch(db("SELECT s1.intern,s2.id FROM ".$db['f_kats']." AS s1
                         LEFT JOIN ".$db['f_skats']." AS s2 ON s2.`sid` = s1.id
                         WHERE s2.`id` = '".intval($_GET['kid'])."'"));
				
			if($_POST['intern']) $intern = 'checked="checked"';
          $intern = ''; $intern_kat = '';
		  if($fget['intern'] == "1") { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };
			if($_POST['closed']) $closed = "checked=\"checked\"";
	
			if(empty($_POST['question'])) $display = "none";
			$display = "";
		
	  	$vote = show($dir."/form_vote", array("head" => _votes_admin_head,
											  "value" => _button_value_add,
											  "what" => "&amp;do=add",
											  "question1" => re($_POST['question']),
											  "a1" => $_POST['a1'],
											  "closed" => $closed,
                        "tgl" => "expand",
											  "br1" => "<!--",
											  "br2" => "-->",
											  "display" => $display,
											  "a2" => $_POST['a2'],
											  "a3" => $_POST['a3'],
											  "a4" => $_POST['a4'],
											  "a5" => $_POST['a5'],
											  "a6" => $_POST['a6'],
											  "a7" => $_POST['a7'],
											  "error" => $error,
											  "a8" => $_POST['a8'],
											  "a9" => $_POST['a9'],
											  "a10" => $_POST['a10'],
                                              'intern_kat' => $internVisible,
											  "intern" => $intern,
											  "vote_del" => _forum_vote_del,
											  "interna" => _votes_admin_intern,
											  "question" => _votes_admin_question,
											  "answer" => _votes_admin_answer));

        $dowhat = show(_forum_dowhat_edit_thread, array("id" => $_GET['id']));
  	    $index = show($dir."/thread", array("titel" => _forum_edit_thread_head,
  			  								"nickhead" => _nick,
                                            "subtopichead" => _forum_subtopic,
                                            "topichead" => _forum_topic,
                                            "ip" => _iplog_info,
                                            "form" => $form,
  							  				"bbcodehead" => _bbcode,
                                            "reg" => $_POST['reg'],
                                            "preview" => _preview,
  								  			"emailhead" => _email,
  									  		"id" => "",
                                            "b1" => $u_b1,
                                            "b2" => $u_b2,
                                            "security" => _register_confirm,
                                            "lang" => $language,
                                            "what" => _button_value_edit,
                                            "dowhat" => $dowhat,
                                            "posthp" => $_POST['hp'],
  											"postemail" => $_POST['email'],
  											"postnick" => re($_POST['nick']),
  											"posteintrag" => re_bbcode($_POST['eintrag']),
                                            "posttopic" => re($_POST['topic']),
                                            "postsubtopic" => re($_POST['subtopic']),
  											"error" => $error,
                                            "admin" => $admin,
  						  					"vote" => $vote,
											"eintraghead" => _eintrag));
      } else {
        $qryt = db("SELECT * FROM ".$db['f_threads']."
                    WHERE id = '".intval($_GET['id'])."'");
        $gett = _fetch($qryt);
  		if(!empty($gett['vote']))
	  {
	   $qryv = db("SELECT * FROM ".$db['vote_results']."
                   WHERE vid = '".$gett['vote']."'");
     $getv = _fetch($qryv);
		
	   $vid = $gett['vote'];  

        $upd = db("UPDATE ".$db['votes']."
                   SET `titel`  = '".up($_POST['question'])."',
                       `intern` = '".((int)$_POST['intern'])."',
                       `closed` = '".((int)$_POST['closed'])."'
                   WHERE id = '".$gett['vote']."'");

        $upd1 = db("UPDATE ".$db['vote_results']."
                    SET `sel` = '".up($_POST['a1'])."'
                    WHERE what = 'a1'
                    AND vid = '".$gett['vote']."'");

        $upd2 = db("UPDATE ".$db['vote_results']."
                    SET `sel` = '".up($_POST['a2'])."'
                    WHERE what = 'a2'
                    AND vid = '".$gett['vote']."'");

        for($i=3; $i<=10; $i++)
        {
          if(!empty($_POST['a'.$i.'']))
          {
            if(cnt($db['vote_results'], " WHERE vid = '".$gett['vote']."' AND what = 'a".$i."'") != 0)
            {
              $upd = db("UPDATE ".$db['vote_results']."
                         SET `sel` = '".up($_POST['a'.$i.''])."'
                         WHERE what = 'a".$i."'
                         AND vid = '".$gett['vote']."'");
            } else {
              $ins = db("INSERT INTO ".$db['vote_results']."
                         SET `vid` = '".$gett['vote']."',
                             `what` = 'a".$i."',
                             `sel` = '".up($_POST['a'.$i.''])."'");
            }
          }

          if(cnt($db['vote_results'], " WHERE vid = '".$gett['vote']."' AND what = 'a".$i."'") != 0 && empty($_POST['a'.$i.'']))
          {
            $del = db("DELETE FROM ".$db['vote_results']."
                       WHERE vid = '".$gett['vote']."'
                       AND what = 'a".$i."'");
          }
        }  
		} elseif(empty($gett['vote']) && !empty($_POST['question'])) {
          $qry = db("INSERT INTO ".$db['votes']."
                     SET `datum`  = '".((int)time())."',
                         `titel`  = '".up($_POST['question'])."',
                         `intern` = '".((int)$_POST['intern'])."',
						             `forum`  = 1,
                         `von`    = '".((int)$userid)."'");

          $vid = mysql_insert_id();

          $qry = db("INSERT INTO ".$db['vote_results']."
                    SET `vid`   = '".((int)$vid)."',
                        `what`  = 'a1',
                        `sel`   = '".up($_POST['a1'])."'");

          $qry = db("INSERT INTO ".$db['vote_results']."
                     SET `vid`  = '".((int)$vid)."',
                         `what` = 'a2',
                         `sel`  = '".up($_POST['a2'])."'");

          if(!empty($_POST['a3']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a3',
                           `sel`  = '".up($_POST['a3'])."'");
          }
          if(!empty($_POST['a4']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a4',
                           `sel`  = '".up($_POST['a4'])."'");
          }
          if(!empty($_POST['a5']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a5',
                           `sel`  = '".up($_POST['a5'])."'");
          }
          if(!empty($_POST['a6']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a6',
                           `sel`  = '".up($_POST['a6'])."'");
          }
          if(!empty($_POST['a7']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a7',
                           `sel`  = '".up($_POST['a7'])."'");
          }
          if(!empty($_POST['a8']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a8',
                           `sel`  = '".up($_POST['a8'])."'");
          }
          if(!empty($_POST['a9']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a9',
                           `sel`  = '".up($_POST['a9'])."'");
          }
          if(!empty($_POST['a10']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a10',
                           `sel`  = '".up($_POST['a10'])."'");
          }
		} else { $vid = ""; }
		
		if($_POST['vote_del'] == 1) {
        $qry = db("DELETE FROM ".$db['votes']."
                   WHERE id = '".$gett['vote']."'");

        $qry = db("DELETE FROM ".$db['vote_results']."
                   WHERE vid = '".$gett['vote']."'");

        $voteid = "vid_".$gett['vote'];
        $qry = db("DELETE FROM ".$db['ipcheck']."
                   WHERE what = '".$voteid."'");
		$vid = "";
		}
		
        $editedby = show(_edited_by, array("autor" => autor($userid),
                                           "time" => date("d.m.Y H:i", time())._uhr));
  
    	  $qry = db("UPDATE ".$db['f_threads']."
  	      				 SET `topic`    = '".up($_POST['topic'])."',
                       `subtopic` = '".up($_POST['subtopic'])."',
                       `t_nick`   = '".up($_POST['nick'])."',
                       `t_email`  = '".up($_POST['email'])."',
                       `t_hp`     = '".links($_POST['hp'])."',
                       `t_text`   = '".up($_POST['eintrag'],1)."',
                       `sticky`   = '".((int)$_POST['sticky'])."',
                       `global`   = '".((int)$_POST['global'])."',
					   					 `vote`     = '".$vid."',
                       `edited`   = '".addslashes($editedby)."'
                   WHERE id = '".intval($_GET['id'])."'");

	  $checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".$db['f_abo']." AS s1
	  				  LEFT JOIN ".$db['users']." AS s2 ON s2.id = s1.user
                      WHERE s1.fid = '".((int)$_GET['id'])."'");
  	  while($getabo = _fetch($checkabo))
  	  {
		if($userid != $getabo['user']) 
		{
		  $topic = db("SELECT topic FROM ".$db['f_threads']." WHERE id = '".intval($_GET['id'])."'");
		  $gettopic = _fetch($topic);

		  $subj = show(settings('eml_fabo_tedit_subj'), array("titel" => $title));

 		  $message = show(settings('eml_fabo_tedit'), array("nick" => re($getabo['nick']),
		  										      		"postuser" => fabo_autor($userid),
															"topic" => $gettopic['topic'],
															"titel" => $title,
															"domain" => $httphost,
															"id" => intval($_GET['id']),
															"entrys" => "1",
															"page" => "1",
															"text" => bbcode($_POST['eintrag']),
															"clan" => $clanname));
		
		  sendMail(re($getabo['email']),$subj,$message);
		}
	  }
  
        $index = info(_forum_editthread_successful, "?action=showthread&amp;id=".$gett['id']."");
  
      }
    } else $index = error(_error_wrong_permissions, 1);
  } elseif($_GET['do'] == "add") {
    if(settings("reg_forum") == "1" && $chkMe == "unlogged")
    {
      $index = error(_error_unregistered,1);
    } else {
      if(!ipcheck("fid(".$_GET['kid'].")", $flood_forum))
      {
        if(permission("forum"))
        {
          $admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
                                                  "addsticky" => _forum_admin_addsticky,
                                                  "sticky" => "",
                                                  "addglobal" => _forum_admin_addglobal,
                                                  "global" => ""));
        } else {
          $admin = "";
        }

        $fget = _fetch(db("SELECT s1.intern,s2.id FROM ".$db['f_kats']." AS s1
                       LEFT JOIN ".$db['f_skats']." AS s2 ON s2.`sid` = s1.id
                       WHERE s2.`id` = '".intval($_GET['kid'])."'"));
				$intern = ''; $intern_kat = '';
				if($fget['intern'] == "1") { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };
				
				if(isset($userid))
	      {
		      $form = show("page/editor_regged", array("nick" => autor($userid),
                                                   "von" => _autor));
	      } else {
          $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                      "emailhead" => _email,
                                                      "hphead" => _hp));
        }

        $vote = show($dir."/form_vote", array("head" => _votes_admin_head,
                                              "value" => _button_value_add,
                                              "what" => "&amp;do=add",
                                              "closed" => "",
                                              "question1" => "",
                                              "tgl" => "expand",
                                              "a1" => "",
                                              "a2" => "",
                                              "a3" => "",
                                              "a4" => "",
                                              "a5" => "",
                                              "a6" => "",
                                              "a7" => "",
                                              "error" => "",
                                              "br1" => "<!--",
                                              "br2" => "-->",
					      					  "display" => "none",
                                              "a8" => "",
                                              "a9" => "",
                                              "a10" => "",
                                              'intern_kat' => $internVisible,
                                              "intern" => $intern,
					      					  "vote_del" => _forum_vote_del,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));
											  
        $dowhat = show(_forum_dowhat_add_thread, array("kid" => $_GET['kid']));

        $index = show($dir."/thread", array("titel" => _forum_new_thread_head,
                                            "nickhead" => _nick,
                                            "topichead" => _forum_topic,
                                            "subtopichead" => _forum_subtopic,
                                            "emailhead" => _email,
                                            "id" => $_GET['kid'],
                                            "bbcodehead" => _bbcode,
                                            "lang" => $language,
                                            "reg" => "",
                                            "b1" => $u_b1,
                                            "b2" => $u_b2,
                                            "security" => _register_confirm,
                                            "ip" => _iplog_info,
                                            "preview" => _preview,
                                            "form" => $form,
                                            "eintraghead" => _eintrag,
                                            "what" => _button_value_add,
                                            "dowhat" => $dowhat,
                                            "error" => "",
                                            "posttopic" => "",
                                            "postsubtopic" => "",
                                            "posthp" => "",
                                            "postnick" => "",
                                            "postemail" => "",
                                            "admin" => $admin,
											"vote" => $vote,
                                            "posteintrag" => ""));
      } else {
        $index = error(show(_error_flood_post, array("sek" => $flood_forum)), 1);
      }
    }
  } elseif($_GET['do'] == "addthread") {
		if(settings("reg_forum") == "1" && $chkMe == "unlogged")
		{
			$index = error(_error_have_to_be_logged, 1);
		} else {
			if(isset($userid))
				$toCheck = empty($_POST['eintrag']) || empty($_POST['topic']);
			else
				$toCheck = empty($_POST['topic']) || empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
			if($toCheck)
			{
				if(isset($userid))
				{
					if(empty($_POST['eintrag'])) $error = _empty_eintrag;
					elseif(empty($_POST['topic'])) $error = _empty_topic;
				} else {
					if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode; 
					elseif(empty($_POST['topic'])) $error = _empty_topic;
					elseif(empty($_POST['nick'])) $error = _empty_nick;
					elseif(empty($_POST['email'])) $error = _empty_email;
					elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
					elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
				}
	
				$error = show("errors/errortable", array("error" => $error));
	
				if(permission("forum"))
				{
					if(isset($_POST['sticky'])) $sticky = "checked";
					if(isset($_POST['global'])) $global = "checked";
	
					$admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
																									"addsticky" => _forum_admin_addsticky,
																									"sticky" => $sticky,
																									"addglobal" => _forum_admin_addglobal,
																									"global" => $global));
				} else {
					$admin = "";
				}
	
				if(isset($userid))
				{
					$form = show("page/editor_regged", array("nick" => autor($userid),
																									 "von" => _autor));
				} else {
					$form = show("page/editor_notregged", array("nickhead" => _nick,
																											"emailhead" => _email,
																											"hphead" => _hp));
				}
	
			$fget = _fetch(db("SELECT s1.intern,s2.id FROM ".$db['f_kats']." AS s1
												 LEFT JOIN ".$db['f_skats']." AS s2 ON s2.`sid` = s1.id
												 WHERE s2.`id` = '".intval($_GET['kid'])."'"));
					
			if($_POST['intern']) $intern = 'checked="checked"';
            $intern = ''; $intern_kat = '';
			if($fget['intern'] == 1) { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };
			if($_POST['closed']) $closed = "checked=\"checked\"";
	
			if(!empty($_POST['question'])) $display = "";
			$display = "none";
	
			$vote = show($dir."/form_vote", array("head" => _votes_admin_head,
							"value" => _button_value_add,
							"what" => "&amp;do=add",
							"question1" => re($_POST['question']),
							"a1" => $_POST['a1'],
							"closed" => $closed,
							"br1" => "<!--",
							"br2" => "-->",
							"tgl" => "expand",
							"display" => $display,
							"a2" => $_POST['a2'],
							"a3" => $_POST['a3'],
							"a4" => $_POST['a4'],
							"a5" => $_POST['a5'],
							"a6" => $_POST['a6'],
							"a7" => $_POST['a7'],
							"error" => $error,
							"a8" => $_POST['a8'],
							"a9" => $_POST['a9'],
							"a10" => $_POST['a10'],
							"vote_del" => _forum_vote_del,
                            'intern_kat' => $internVisible,
							"intern" => $intern,
							"interna" => _votes_admin_intern,
							"question" => _votes_admin_question,
							"answer" => _votes_admin_answer));
	
					$dowhat = show(_forum_dowhat_add_thread, array("kid" => $_GET['kid']));
				$index = show($dir."/thread", array("titel" => _forum_new_thread_head,
													"nickhead" => _nick,
																							"reg" => "",
																							"subtopichead" => _forum_subtopic,
																							"topichead" => _forum_topic,
																							"form" => $form,
													"bbcodehead" => _bbcode,
													"emailhead" => _email,
													"id" => $_GET['kid'],
																							"b1" => $u_b1,
																							"b2" => $u_b2,
																							"security" => _register_confirm,
																							"what" => _button_value_add,
																							"preview" => _preview,
																							"lang" => $language,
																							"dowhat" => $dowhat,
																							"posthp" => $_POST['hp'],
												"postemail" => $_POST['email'],
												"postnick" => re($_POST['nick']),
																							"ip" => _iplog_info,
												"posteintrag" => re_bbcode($_POST['eintrag']),
																							"posttopic" => re($_POST['topic']),
																							"postsubtopic" => re($_POST['subtopic']),
												"error" => $error,
																							"admin" => $admin,
												"vote" => $vote,
													"eintraghead" => _eintrag));
			} else {
				if(!empty($_POST['question']))
				{
						$fgetvote = _fetch(db("SELECT s1.intern,s2.id FROM ".$db['f_kats']." AS s1
																	 LEFT JOIN ".$db['f_skats']." AS s2 ON s2.`sid` = s1.id
																	 WHERE s2.`id` = '".intval($_GET['kid'])."'"));
						
						if($fgetvote['intern'] == 1) $ivote = "`intern` = '1',";
						else $ivote = "`intern` = '".((int)$_POST['intern'])."',";
						
						$qry = db("INSERT INTO ".$db['votes']."
											 SET `datum`  = '".((int)time())."',
													 `titel`  = '".up($_POST['question'])."',
													 ".$ivote."
													 `forum`  = 1,
													 `von`    = '".((int)$userid)."'");
	
						$vid = mysql_insert_id();
	
						$qry = db("INSERT INTO ".$db['vote_results']."
											SET `vid`   = '".((int)$vid)."',
													`what`  = 'a1',
													`sel`   = '".up($_POST['a1'])."'");
	
						$qry = db("INSERT INTO ".$db['vote_results']."
											 SET `vid`  = '".((int)$vid)."',
													 `what` = 'a2',
													 `sel`  = '".up($_POST['a2'])."'");
	
						if(!empty($_POST['a3']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a3',
														 `sel`  = '".up($_POST['a3'])."'");
						}
						if(!empty($_POST['a4']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a4',
														 `sel`  = '".up($_POST['a4'])."'");
						}
						if(!empty($_POST['a5']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a5',
														 `sel`  = '".up($_POST['a5'])."'");
						}
						if(!empty($_POST['a6']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a6',
														 `sel`  = '".up($_POST['a6'])."'");
						}
						if(!empty($_POST['a7']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a7',
														 `sel`  = '".up($_POST['a7'])."'");
						}
						if(!empty($_POST['a8']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a8',
														 `sel`  = '".up($_POST['a8'])."'");
						}
						if(!empty($_POST['a9']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a9',
														 `sel`  = '".up($_POST['a9'])."'");
						}
						if(!empty($_POST['a10']))
						{
							$qry = db("INSERT INTO ".$db['vote_results']."
												 SET `vid`  = '".((int)$vid)."',
														 `what` = 'a10',
														 `sel`  = '".up($_POST['a10'])."'");
						}
			} else { $vid = ""; }
						
			$qry = db("INSERT INTO ".$db['f_threads']."
								 SET 	`kid`      = '".((int)$_GET['kid'])."',
												`t_date`   = '".((int)time())."',
												`topic`    = '".up($_POST['topic'])."',
												`subtopic` = '".up($_POST['subtopic'])."',
												`t_nick`   = '".up($_POST['nick'])."',
												`t_email`  = '".up($_POST['email'])."',
												`t_hp`     = '".links($_POST['hp'])."',
												`t_reg`    = '".((int)$userid)."',
												`t_text`   = '".up($_POST['eintrag'],1)."',
												`sticky`   = '".((int)$_POST['sticky'])."',
												`global`   = '".((int)$_POST['global'])."',
												`ip`       = '".$userip."',
												`lp`       = '".((int)time())."',
												`vote`     = '".$vid."',
												`first`	= '1'");
				$thisFID = mysql_insert_id();
				$fid = "fid(".$_GET['kid'].")";
				$qry = db("INSERT INTO ".$db['ipcheck']."
									 SET `ip`   = '".$userip."',
											 `what` = '".$fid."',
											 `time` = '".((int)time())."'");
	
				$update = db("UPDATE ".$db['userstats']."
											SET `forumposts` = forumposts+1
											WHERE `user`       = '".$userid."'");
	
				$index = info(_forum_newthread_successful, "?action=showthread&amp;id=".$thisFID."#p1");
			}
		}
  }
break;
case 'post';
  if($_GET['do'] == "edit")
  {
    $qry = db("SELECT * FROM ".$db['f_posts']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid || permission("forum"))
    {
      if($get['reg'] != 0)
  	  {
  		  $form = show("page/editor_regged", array("nick" => autor($get['reg']),
                                                 "von" => _autor));
  	  } else {
        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                    "emailhead" => _email,
                                                    "hphead" => _hp,
                                                    "postemail" => re($get['email']),
              							    									  "posthp" => re($get['hp']),
              								    								  "postnick" => re($get['nick'])));
      }

      $dowhat = show(_forum_dowhat_edit_post, array("id" => $_GET['id']));
      $index = show($dir."/post", array("titel" => _forum_edit_post_head,
                                        "nickhead" => _nick,
                                        "emailhead" => _email,
                                        "kid" => "",
                                        "id" => $_GET['id'],
                                        "ip" => _iplog_info,
                                        "dowhat" => $dowhat,
                                        "lang" => $language,
                                        "form" => $form,
                                        "zitat" => $zitat,
                                        "preview" => _preview,
                                        "br1" => "<!--",
                                        "br2" => "-->",
                                        "b1" => $u_b1,
                                        "b2" => $u_b2,
                                        "security" => _register_confirm,
                                        "lastpost" => "",
                                        "last_post" => _forum_no_last_post,
                                        "bbcodehead" => _bbcode,
                                        "eintraghead" => _eintrag,
                                        "error" => "",
                                        "what" => _button_value_edit,
                                        "posteintrag" => re_bbcode($get['text'])));
    } else {
      $index = error(_error_wrong_permissions, 1);
    }
  } elseif($_GET['do'] == "editpost") {
    $qry = db("SELECT reg FROM ".$db['f_posts']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);
    if($get['reg'] == $userid || permission("forum"))
    {
      if($get['reg'] != 0 || permission('forum'))
      {
        $toCheck = empty($_POST['eintrag']);
      } else {
        $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
      }

      if($toCheck)
	    {
        if($get['reg'] != 0)
  	    {
          if(empty($_POST['eintrag'])) $error = _empty_eintrag;
  		    $form = show("page/editor_regged", array("nick" => autor($userid),
                                                   "von" => _autor));
  	    } else {
          if(($_POST['secure'] != $_SESSION['sec_'.$dir]) && !isset($userid)) $error = _error_invalid_regcode;
          elseif(empty($_POST['nick'])) $error = _empty_nick;
  		    elseif(empty($_POST['email'])) $error = _empty_email;
  		    elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
  		    elseif(empty($_POST['eintrag']))$error = _empty_eintrag;
          $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                      "emailhead" => _email,
                                                      "hphead" => _hp));
        }

        $error = show("errors/errortable", array("error" => $error));
        $dowhat = show(_forum_dowhat_edit_post, array("id" => $_GET['id']));
        $index = show($dir."/post", array("titel" => _forum_edit_post_head,
		        														  "nickhead" => _nick,
				        													"bbcodehead" => _bbcode,
                                          "preview" => _preview,
					    	    											"emailhead" => _email,
                                          "lang" => $language,
                                          "zitat" => $zitat,
                                          "form" => $form,
                                          "dowhat" => $dowhat,
                                          "b1" => $u_b1,
                                          "b2" => $u_b2,
                                          "security" => _register_confirm,
                                          "what" => _button_value_edit,
                                          "ip" => _iplog_info,
							  	  		    							"id" => $_GET['id'],
                                          "kid" => $_GET['kid'],
                                          "br1" => "<!--",
                                          "br2" => "-->",
									  	  				    			"postemail" => re($get['email']),
										  	  					    	"postnick" => re($get['nick']),
											  	  						  "posteintrag" => re_bbcode($_POST['eintrag']),
   											  	  						"error" => $error,
					    								  	     		"eintraghead" => _eintrag));
      } else {
        $qryp = db("SELECT * FROM ".$db['f_posts']."
                    WHERE id = '".intval($_GET['id'])."'");
        $getp = _fetch($qryp);

        $editedby = show(_edited_by, array("autor" => autor($userid),
                                           "time" => date("d.m.Y H:i", time())._uhr));

        $qry = db("UPDATE ".$db['f_posts']."
                   SET `nick`   = '".up($_POST['nick'])."',
                       `email`  = '".up($_POST['email'])."',
                       `text`   = '".up($_POST['eintrag'],1)."',
                       `hp`     = '".links($_POST['hp'])."',
                       `edited` = '".addslashes($editedby)."'
                   WHERE id = '".intval($_GET['id'])."'");
	  
	  $checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".$db['f_abo']." AS s1
	  				  LEFT JOIN ".$db['users']." AS s2 ON s2.id = s1.user
                      WHERE s1.fid = '".$getp['sid']."'");
  	  while($getabo = _fetch($checkabo))
  	  {
		if($userid != $getabo['user']) 
		{
		  $topic = db("SELECT topic FROM ".$db['f_threads']." WHERE id = '".$getp['sid']."'");
		  $gettopic = _fetch($topic);

			$entrys = cnt($db['f_posts'], " WHERE `sid` = ".$getp['sid']);

			if($entrys == "0") $pagenr = "1";
			else $pagenr = ceil($entrys/$maxfposts);

		  $subj = show(settings('eml_fabo_pedit_subj'), array("titel" => $title));

 		  $message = show(settings('eml_fabo_pedit'), array("nick" => re($getabo['nick']),
		  										         	"postuser" => fabo_autor($userid),
															"topic" => $gettopic['topic'],
															"titel" => $title,
															"domain" => $httphost,
															"id" => $getp['sid'],
															"entrys" => $entrys+1,
															"page" => $pagenr,
															"text" => bbcode($_POST['eintrag']),
															"clan" => $clanname));
		
		  sendMail(re($getabo['email']),$subj,$message);
		}
	  }
        $entrys = cnt($db['f_posts'], " WHERE `sid` = ".$getp['sid']);

        if($entrys == "0") $pagenr = "1";
        else $pagenr = ceil($entrys/$maxfposts);

        $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                                                 "tid" => $getp['sid'],
                                                 "page" => $pagenr));

        $index = info(_forum_editpost_successful, $lpost);
      }
    } else {
      $index = error(_error_wrong_permissions, 1);
    }
  } elseif($_GET['do'] == "add") { 
    if(settings("reg_forum") == "1" && $chkMe == "unlogged")
    {
      $index = error(_error_unregistered,1);
    } else {
      if(!ipcheck("fid(".$_GET['kid'].")", $flood_forum))
      {
        $check = db("SELECT s2.id,s1.intern FROM ".$db['f_kats']." AS s1
                     LEFT JOIN ".$db['f_skats']." AS s2
                     ON s2.sid = s1.id
                     WHERE s2.id = '".intval($_GET['kid'])."'");
        $checks = _fetch($check);
        if(forumcheck($_GET['id'], "closed"))
        {
          $index = error(_error_forum_closed, 1);
        } elseif($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id'])) {
          $index = error(_error_no_access, 1);
        } else {
          if(isset($userid))
          {
	          $postnick = data($userid, "nick");
	          $postemail = data($userid, "email");
          } else {
	          $postnick = "";
	          $postemail = "";
          }
          if($_GET['zitat'])
          {
            $qryzitat = db("SELECT nick,reg,text FROM ".$db['f_posts']."
                            WHERE id = '".intval($_GET['zitat'])."'");
            $getzitat = _fetch($qryzitat);

            if($getzitat['reg'] == "0") $nick = $getzitat['nick'];
            else                        $nick = autor($getzitat['reg']);

            $zitat = zitat($nick, $getzitat['text']);
          } elseif($_GET['zitatt']) {
            $qryzitat = db("SELECT t_nick,t_reg,t_text FROM ".$db['f_threads']."
                            WHERE id = '".intval($_GET['zitatt'])."'");
            $getzitat = _fetch($qryzitat);

            if($getzitat['t_reg'] == "0") $nick = $getzitat['t_nick'];
            else                          $nick = data($getzitat['t_reg'], "nick");

            $zitat = zitat($nick, $getzitat['t_text']);
          } else {
            $zitat = "";
          }

          $dowhat = show(_forum_dowhat_add_post, array("id" => $_GET['id'],
                                                       "kid" => $_GET['kid']));

          $qryl = db("SELECT * FROM ".$db['f_posts']."
                      WHERE kid = '".intval($_GET['kid'])."'
                      AND sid = '".intval($_GET['id'])."'
                      ORDER BY date DESC");
          if(_rows($qryl))
          {
            $getl = _fetch($qryl);

            if(data($getl['reg'], "signatur")) $sig = _sig.bbcode(data($getl['reg'], "signatur"));
            else                               $sig = "";

            if($getl['reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats($getl['reg'], "forumposts")));
            else                    $userposts = "";

            if($getl['reg'] == "0") $onoff = "";
            else                    $onoff = onlinecheck($getl['reg']);

            $text = bbcode($getl['text']);

            if($chkMe == "4") $posted_ip = $getl['ip'];
            else              $posted_ip = _logged;

            $titel = show(_eintrag_titel_forum, array("postid" => (cnt($db['f_posts'], " WHERE sid =".intval($_GET['id']))+1),
				  	  							   				     			"datum" => date("d.m.Y", $getl['date']),
					  	  							   		 			    	"zeit" => date("H:i", $getl['date'])._uhr,
                                                "url" => '#',
                                                "edit" => "",
                                                "delete" => ""));
            if($getl['reg'] != 0)
            {
              $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                          WHERE id = '".$getl['reg']."'");
              $getu = _fetch($qryu);

              $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
              $pn = _forum_pn_preview;
              if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
          		else {
                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
          		}

              if(empty($getu['hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
            } else {
              $icq = "";
              $pn = "";
              $email = show(_emailicon_forum, array("email" => eMailAddr($getl['email'])));
              if(empty($getl['hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $getl['hp']));
            }

            $lastpost = show($dir."/forum_posts_show", array("nick" => cleanautor($getl['reg'], '', $getl['nick'], $getl['email']),
                                                             "postnr" => "",
                                                             "text" => $text,
                                                             "status" => getrank($getl['reg']),
                                                             "avatar" => useravatar($getl['reg']),
                                                             "pn" => $pn,
                                                             "icq" => $icq,
                                                             "hp" => $hp,
                                                             "class" => 'class="commentsRight"',
                                                             "email" => $email,
                                                             "titel" => $titel,
                                                             "p" => ($i+($page-1)*$maxfposts),
                                                             "ip" => $posted_ip,
                                                             "edited" => $getl['edited'],
                                                             "posts" => $userposts,
                                                             "date" => _posted_by.date("d.m.y H:i", $getl['date'])._uhr,
                                                             "signatur" => $sig,
                                                             "zitat" => _forum_zitat_preview,
                                                             "onoff" => $onoff,
                                                             "top" => "",
                                                             "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
          } else {
            $qryt = db("SELECT * FROM ".$db['f_threads']."
                        WHERE kid = '".intval($_GET['kid'])."'
                        AND id = '".intval($_GET['id'])."'");
            $gett = _fetch($qryt);

            if(data($gett['t_reg'], "signatur")) $sig = _sig.bbcode(data($gett['t_reg'], "signatur"));
            else $sig = "";

            if($gett['t_reg'] != "0")
              $userposts = show(_forum_user_posts, array("posts" => userstats($gett['t_reg'], "forumposts")));
            else $userposts = "";

            if($gett['t_reg'] == "0") $onoff = "";
            else                      $onoff = onlinecheck($gett['t_reg']);

            $ftxt = hl($gett['t_text'], $_GET['hl']);
            if($_GET['hl']) $text = bbcode($ftxt['text']);
            else $text = bbcode($gett['t_text']);

            if($chkMe == "4") $posted_ip = $gett['ip'];
            else                 $posted_ip = _logged;

            $titel = show(_eintrag_titel_forum, array("postid" => "1",
				  	  							   				     			"datum" => date("d.m.Y", $gett['t_date']),
					  	  							   		 			    	"zeit" => date("H:i", $gett['t_date'])._uhr,
                                                "url" => '#',
                                                "edit" => "",
                                                "delete" => ""));
            if($gett['t_reg'] != 0)
            {
              $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                          WHERE id = '".$gett['t_reg']."'");
              $getu = _fetch($qryu);

              $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
              $pn = show(_pn_write_forum, array("id" => $gett['t_reg'],
      		  	  												        "nick" => $getu['nick']));
              if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
          		else {
                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
          		}

              if(empty($getu['hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
            } else {
              $icq = "";
              $pn = "";
              $email = show(_emailicon_forum, array("email" => eMailAddr($gett['t_email'])));
              if(empty($gett['t_hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $gett['t_hp']));
            }

            $lastpost = show($dir."/forum_posts_show", array("nick" => cleanautor($gett['t_reg'], '', $gett['t_nick'], $gett['t_email']),
                                                             "postnr" => "",
                                                             "text" => $text,
                                                             "status" => getrank($gett['t_reg']),
                                                             "avatar" => useravatar($gett['t_reg']),
                                                             "pn" => $pn,
                                                             "icq" => $icq,
                                                             "class" => $ftxt['class'],
                                                             "hp" => $hp,
                                                             "email" => $email,
                                                             "titel" => $titel,
                                                             "ip" => $posted_ip,
                                                             "p" => ($i+($page-1)*$maxfposts),
                                                             "edited" => $gett['edited'],
                                                             "posts" => $userposts,
                                                             "date" => _posted_by.date("d.m.y H:i", $gett['t_date'])._uhr,
                                                             "signatur" => $sig,
                                                             "zitat" => "",
                                                             "onoff" => $onoff,
                                                             "top" => "",
                                                             "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
          }

          if(isset($userid))
	        {
		        $form = show("page/editor_regged", array("nick" => autor($userid),
                                                     "von" => _autor));
	        } else {
            $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                        "emailhead" => _email,
                                                        "hphead" => _hp));
          }

          $title = re($gett['topic']).' - '.$title;
          $index = show($dir."/post", array("titel" => _forum_new_post_head,
                                            "nickhead" => _nick,
                                            "emailhead" => _email,
                                            "id" => $_GET['id'],
                                            "kid" => $_GET['kid'],
                                            "zitat" => $zitat,
                                            "last_post" => _forum_lp_head,
                                            "preview" => _preview,
                                            "lang" => $language,
                                            "lastpost" => $lastpost,
                                            "bbcodehead" => _bbcode,
                                            "form" => $form,
                                            "br1" => "",
                                            "b1" => $u_b1,
                                            "b2" => $u_b2,
                                            "security" => _register_confirm,
                                            "ip" => _iplog_info,
                                            "br2" => "",
                                            "what" => _button_value_add,
                                            "kid" => $_GET['kid'],
                                            "id" => $_GET['id'],
                                            "dowhat" => $dowhat,
                                            "eintraghead" => _eintrag,
                                            "error" => "",
                                            "postnick" => $postnick,
                                            "postemail" => $postemail,
                                            "posthp" => $posthp,
                                            "posteintrag" => ""));
        }
      } else {
        $index = error(show(_error_flood_post, array("sek" => $flood_forum)), 1);
      }
    }
  } elseif($_GET['do'] == "addpost") {
		if(_rows(db("SELECT `id` FROM ".$db['f_threads']." WHERE `id` = '".(int)$_GET['id']."'")) == 0)
		{
			$index = error(_id_dont_exist,1);
		} else {
			if(settings("reg_forum") == "1" && $chkMe == "unlogged")
			{
				$index = error(_error_unregistered,1);
			} else {	
				$check = db("SELECT s2.id,s1.intern FROM ".$db['f_kats']." AS s1
										 LEFT JOIN ".$db['f_skats']." AS s2
										 ON s2.sid = s1.id
										 WHERE s2.id = '".intval($_GET['kid'])."'");
				$checks = _fetch($check);
			
				if($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id']))
				exit;
					
				if(isset($userid)) $toCheck = empty($_POST['eintrag']);
				else $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
		
				if($toCheck)
				{
					if(isset($userid))
					{
						if(empty($_POST['eintrag'])) $error = _empty_eintrag;
						$form = show("page/editor_regged", array("nick" => autor($userid),
																										 "von" => _autor));
					} else {
						if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode; 
						elseif(empty($_POST['nick'])) $error = _empty_nick;
						elseif(empty($_POST['email'])) $error = _empty_email;
						elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
						elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
						$form = show("page/editor_notregged", array("nickhead" => _nick,
																												"emailhead" => _email,
																												"hphead" => _hp));
					}
		
					$error = show("errors/errortable", array("error" => $error));
					$dowhat = show(_forum_dowhat_add_post, array("id" => $_GET['id'],
																											 "kid" => $_GET['kid']));
					$qryl = db("SELECT * FROM ".$db['f_posts']."
											WHERE kid = '".intval($_GET['kid'])."'
											AND sid = '".intval($_GET['id'])."'
											ORDER BY date DESC");
					if(_rows($qryl))
					{
						$getl = _fetch($qryl);
		
						if(data($getl['reg'], "signatur")) $sig = _sig.bbcode(data($getl['reg'], "signatur"));
						else $sig = "";
		
						if($getl['reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats($getl['reg'], "forumposts")));
						else $userposts = "";
		
						if($getl['reg'] == "0") $onoff = "";
						else $onoff = onlinecheck($getl['reg']);
		
						$ftxt = hl($getl['text'], $_GET['hl']);
						if($_GET['hl']) $text = bbcode($ftxt['text']);
						else $text = bbcode($getl['text']);
		
						if($chkMe == "4") $posted_ip = $getl['ip'];
						else $posted_ip = _logged;
		
						$titel = show(_eintrag_titel_forum, array("postid" => (cnt($db['f_posts'], " WHERE sid = ".intval($_GET['id']))+1),
																								"datum" => date("d.m.Y", $getl['date']),
																								"zeit" => date("H:i", $getl['date'])._uhr,
																								"url" => '#',
																								"edit" => "",
																								"delete" => ""));
		
						if($getl['reg'] != 0)
						{
							$qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
													WHERE id = '".$getl['reg']."'");
							$getu = _fetch($qryu);
		
							$email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
							$pn = show(_pn_write_forum, array("id" => $getl['reg'],
																								"nick" => $getu['nick']));
							if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
							else {
								$uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
								$icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
							}
		
							if(empty($getu['hp'])) $hp = "";
							else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
						} else {
							$icq = "";
							$pn = "";
							$email = show(_emailicon_forum, array("email" => eMailAddr($getl['email'])));
							if(empty($getl['hp'])) $hp = "";
							else $hp = show(_hpicon_forum, array("hp" => $getl['hp']));
						}
						
						$nick = autor($getl['reg'], '', $getl['nick'], $getl['email']);
						if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
						{
							if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
						}
					
						$lastpost = show($dir."/forum_posts_show", array("nick" => $nick,
																														 "postnr" => "",
																														 "text" => $text,
																														 "status" => getrank($getl['reg']),
																														 "avatar" => useravatar($getl['reg']),
																														 "titel" => $titel,
																														 "pn" => $pn,
																														 "icq" => $icq,
																														 "hp" => $hp,
																														 "class" => $ftxt['class'],
																														 "email" => $email,
																														 "ip" => $posted_ip,
																														 "p" => ($i+($page-1)*$maxfposts),
																														 "edited" => $getl['edited'],
																														 "posts" => $userposts,
																														 "signatur" => $sig,
																														 "zitat" => "",
																														 "onoff" => $onoff,
																														 "top" => "",
																														 "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
					} else {
						$qryt = db("SELECT * FROM ".$db['f_threads']."
												WHERE kid = '".intval($_GET['kid'])."'
												AND id = '".intval($_GET['id'])."'");
						$gett = _fetch($qryt);
		
						if(data($gett['t_reg'], "signatur")) $sig = _sig.bbcode(data($gett['t_reg'], "signatur"));
						else $sig = "";
		
						if($gett['t_reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats($gett['t_reg'], "forumposts")));
						else $userposts = "";
		
						if($gett['t_reg'] == "0") $onoff = "";
						else $onoff = onlinecheck($gett['t_reg']);
		
						$ftxt = hl($gett['t_text'], $_GET['hl']);
						if($_GET['hl']) $text = bbcode($ftxt['text']);
						else $text = bbcode($gett['t_text']);
		
						if($chkMe == "4") $posted_ip = $gett['ip'];
						else $posted_ip = _logged;
		
						if($gett['t_reg'] != 0)
						{
							$qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
													WHERE id = '".$gett['t_reg']."'");
							$getu = _fetch($qryu);
		
							$email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
							$pn = show(_pn_write_forum, array("id" => $gett['t_reg'],
																								"nick" => $getu['nick']));
							if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
							else {
								$uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
								$icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
							}
		
							if(empty($getu['hp'])) $hp = "";
							else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
						} else {
							$icq = "";
							$pn = "";
							$email = show(_emailicon_forum, array("email" => eMailAddr($gett['t_email'])));
							if(empty($gett['t_hp'])) $hp = "";
							else $hp = show(_hpicon_forum, array("hp" => $gett['t_hp']));
						}
						
						$nick = autor($gett['t_reg'], '', $gett['t_nick'], $gett['t_email']);
						if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
						{
							if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
						}
						
						$lastpost = show($dir."/forum_posts_show", array("nick" => $nick,
																														 "postnr" => "",
																														 "text" => $text,
																														 "status" => getrank($gett['t_reg']),
																														 "avatar" => useravatar($gett['t_reg']),
																														 "ip" => $posted_ip,
																														 "pn" => $pn,
																														 "class" => $ftxt['class'],
																														 "icq" => $icq,
																														 "hp" => $hp,
																														 "email" => $email,
																														 "edit" => "",
																														 "p" => ($i+($page-1)*$maxfposts),
																														 "delete" => "",
																														 "edited" => $gett['edited'],
																														 "posts" => $userposts,
																														 "date" => _posted_by.date("d.m.y H:i", $gett['t_date'])._uhr,
																														 "signatur" => $sig,
																														 "zitat" => "",
																														 "onoff" => $onoff,
																														 "top" => "",
																														 "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
					}
		
					$index = show($dir."/post", array("titel" => _forum_new_post_head,
																						"nickhead" => _nick,
																						"bbcodehead" => _bbcode,
																						"emailhead" => _email,
																						"zitat" => $zitat,
																						"what" => _button_value_add,
																						"preview" => _preview,
																						"form" => $form,
																						"br1" => "",
																						"br2" => "",
																						"b1" => $u_b1,
																						"b2" => $u_b2,
																						"security" => _register_confirm,
																						"lang" => $language,
																						"lastpost" => $lastpost,
																						"last_post" => _forum_lp_head,
																						"dowhat" => $dowhat,
																						"id" => $_GET['id'],
																						"ip" => _iplog_info,
																						"kid" => $_GET['kid'],
																						"postemail" => $_POST['email'],
																						"posthp" => $_POST['hp'],
																						"postnick" => re($_POST['nick']),
																						"posteintrag" => re_bbcode($_POST['eintrag']),
																						"error" => $error,
																						"eintraghead" => _eintrag));
				} else {
					$spam = 0;
					$qrydp = db("SELECT * FROM ".$db['f_posts']."
											 WHERE kid = '".intval($_GET['kid'])."'
											 AND sid = '".intval($_GET['id'])."'
											 ORDER BY date DESC
											 LIMIT 1");
					if(_rows($qrydp))
					{
						$getdp = _fetch($qrydp);
					
						if(isset($userid))
						{
							if($userid == $getdp['reg'] && $double_post == 1) $spam = 1;
							else $spam = 0;
						} else {
							if($_POST['nick'] == $getdp['nick'] && $double_post == 1) $spam = 1;
							else $spam = 0;
						}
					} else {
						
						$qrytdp = db("SELECT * FROM ".$db['f_threads']."
									WHERE kid = '".intval($_GET['kid'])."'
									AND id = '".intval($_GET['id'])."'");
						$gettdp = _fetch($qrytdp);
				
						if(isset($userid))
						{
							if($userid == $gettdp['t_reg'] && $double_post == 1) $spam = 2;
							else $spam = 0;
						} else {
							if($_POST['nick'] == $gettdp['t_nick'] && $double_post == 1) $spam = 2;
							else $spam = 0;		
						}				
					}
					
					if($spam == 1) 
					{
						if(isset($userid)) $fautor = autor($userid);
						else $fautor = autor('', '', $_POST['nick'], $_POST['email']);
							
							$text = show(_forum_spam_text, array("autor" => $fautor,
																									 "ltext" => $getdp['text'],
																									 "ntext" => up($_POST['eintrag'],1)));
			
													$qry = db("UPDATE ".$db['f_threads']."
																						 SET `lp` = '".time()."'
									WHERE kid = '".intval($_GET['kid'])."'
									AND id = '".intval($_GET['id'])."'");
					
							$qry = db("UPDATE ".$db['f_posts']."
												 SET `date`   = '".time()."',
														 `text`   = '".$text."'
												 WHERE id = '".$getdp['id']."'");	  
					} elseif($spam == 2) {
						if(isset($userid)) $fautor = autor($userid);
						else $fautor = autor('', '', $_POST['nick'], $_POST['email']);
				
							$text = show(_forum_spam_text, array("autor" => $fautor,
																									 "ltext" => $gettdp['t_text'],
																									 "ntext" => up($_POST['eintrag'],1)));
			
							$qry = db("UPDATE ".$db['f_threads']."
												 SET `lp`   = '".time()."',
												 `t_text`   = '".$text."'
												 WHERE id = '".$gettdp['id']."'");	 	  
				} else {
					$qry = db("INSERT INTO ".$db['f_posts']."
										 SET `kid`   = '".((int)$_GET['kid'])."',
												 `sid`   = '".((int)$_GET['id'])."',
												 `date`  = '".((int)time())."',
												 `nick`  = '".up($_POST['nick'])."',
												 `email` = '".up($_POST['email'])."',
												 `hp`    = '".links($_POST['hp'])."',
												 `reg`   = '".up($userid)."',
												 `text`  = '".up($_POST['eintrag'],1)."',
												 `ip`    = '".$userip."'");	  
		
					$update = db("UPDATE ".$db['f_threads']."
												SET `lp`    = '".((int)time())."',
														`first` = '0'
												WHERE id    = '".intval($_GET['id'])."'");
				}
				
					$fid = "fid(".$_GET['kid'].")";
					$qry = db("INSERT INTO ".$db['ipcheck']."
										 SET `ip`   = '".$userip."',
												 `what` = '".$fid."',
												 `time` = '".((int)time())."'");
		
					$update = db("UPDATE ".$db['userstats']."
												SET `forumposts` = forumposts+1
												WHERE `user`       = '".$userid."'");
		
					$checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".$db['f_abo']." AS s1
									LEFT JOIN ".$db['users']." AS s2 ON s2.id = s1.user
													WHERE s1.fid = '".((int)$_GET['id'])."'");
					while($getabo = _fetch($checkabo))
					{
						if($userid != $getabo['user']) 
						{
							$topic = db("SELECT topic FROM ".$db['f_threads']." WHERE id = '".intval($_GET['id'])."'");
							$gettopic = _fetch($topic);
				
							$entrys = cnt($db['f_posts'], " WHERE `sid` = ".intval($_GET['id']));
				
							if($entrys == "0") $pagenr = "1";
							else $pagenr = ceil($entrys/$maxfposts);
				
							$subj = show(settings('eml_fabo_npost_subj'), array("titel" => $title));
				
							$message = show(settings('eml_fabo_npost'), array("nick" => re($getabo['nick']),
																			"postuser" => fabo_autor($userid),
																			"topic" => $gettopic['topic'],
																			"titel" => $title,
																			"domain" => $httphost,
																			"id" => intval($_GET['id']),
																			"entrys" => $entrys+1,
																			"page" => $pagenr,
																			"text" => bbcode($_POST['eintrag']),
																			"clan" => $clanname));
						
							sendMail(re($getabo['email']),$subj,$message);
						}
					}
		
					$entrys = cnt($db['f_posts'], " WHERE `sid` = ".intval($_GET['id']));
		
					if($entrys == "0") $pagenr = "1";
					else $pagenr = ceil($entrys/$maxfposts);
		
					$lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
																									 "tid" => $_GET['id'],
																									 "page" => $pagenr));
		
					$index = info(_forum_newpost_successful, $lpost);
				}
			}
		}
  } elseif($_GET['do'] == "delete") {
    $qry = db("SELECT * FROM ".$db['f_posts']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid OR permission("forum"))
    {
      $del = db("DELETE FROM ".$db['f_posts']."
                 WHERE id = '".intval($_GET['id'])."'");

      $fposts = userstats($get['reg'], "forumposts")-1;
      $upd = db("UPDATE ".$db['userstats']."
                 SET `forumposts` = '".((int)$fposts)."'
                 WHERE user = '".$get['reg']."'");

      $entrys = cnt($db['f_posts'], " WHERE `sid` = ".$get['sid']);

      if($entrys == "0")
      {
        $pagenr = "1";
        $update = db("UPDATE ".$db['f_threads']."
                      SET `first` = '1'
                      WHERE kid = '".$get['kid']."'");
      } else {
        $pagenr = ceil($entrys/$maxfposts);
      }

      $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                                               "tid" => $get['sid'],
                                               "page" => $pagenr));

      $index = info(_forum_delpost_successful, $lpost);
    }
  }
break;
case 'foption';
  if($_GET['do'] == "fabo")
  {
	if(isset($_POST['f_abo']))
	{
	  $f_abo = db("INSERT INTO ".$db['f_abo']."
					SET `user` = '".((int)$userid)."',
						`fid`  = '".intval($_GET['id'])."',
						`datum`  = '".((int)time())."'");
	} else {
	  $f_abo = db("DELETE FROM ".$db['f_abo']."
				   WHERE user = '".((int)$userid)."'
				   AND fid = '".intval($_GET['id'])."'");
	}
	$index = info(_forum_fabo_do, "?action=showthread&amp;id=".$_GET['id']."");
  } 
break;
case 'admin';
  if(permission("forum"))
  {
    if($_GET['do'] == "mod")
    {
      if(isset($_POST['delete']))
      {
 	    $qryv = db("SELECT * FROM ".$db['f_threads']."
                    WHERE id = '".intval($_GET['id'])."'");
        $getv = _fetch($qryv);
        
        if(!empty($getv['vote']))
		{
		$delvote = db("DELETE FROM ".$db['votes']."
                       WHERE id = '".$getv['vote']."'");

        $delvr = db("DELETE FROM ".$db['vote_results']."
                     WHERE vid = '".$getv['vote']."'");
        $voteid = "vid_".$getv['vote'];
        $delip = db("DELETE FROM ".$db['ipcheck']."
                     WHERE what = '".$voteid."'");
		}	 
        $del = db("DELETE FROM ".$db['f_threads']."
                   WHERE id = '".intval($_GET['id'])."'");
                
        // grab user to reduce post count
        $tmpSid = intval($_GET['id']);
        $userPosts = db('SELECT p.`reg` FROM ' . $db['f_posts'] . ' p WHERE sid = ' . $tmpSid . ' AND p.`reg` != 0');
        $userPostReduction = array();
        while($get = _fetch($userPosts)) {
            if(!isset($userPostReduction[$get['reg']])) {
                $userPostReduction[$get['reg']] = 1;
            } else {
                $userPostReduction[$get['reg']] = $userPostReduction[$get['reg']] + 1;
            }
        }
        foreach($userPostReduction as $key_id => $value_postDecrement) {
            db('UPDATE ' . $db['userstats'] .
                 ' SET `forumposts` = `forumposts` - '. $value_postDecrement .
                 ' WHERE user = ' . $key_id);
        }
        $delp = db("DELETE FROM ".$db['f_posts']."
                    WHERE sid = '" . $tmpSid . "'");
		$delabo = db("DELETE FROM ".$db['f_abo']."
                      WHERE fid = '".intval($_GET['id'])."'"); 
        $index = info(_forum_admin_thread_deleted, "../forum/");
      } else {
        if($_POST['closed'] == "0")
        {
          $open = db("UPDATE ".$db['f_threads']."
                      SET `closed` = '0'
                      WHERE id = '".intval($_GET['id'])."'");
        } elseif($_POST['closed'] == "1") {
          $close = db("UPDATE ".$db['f_threads']."
                       SET `closed` = '1'
                       WHERE id = '".intval($_GET['id'])."'");
        }

        if(isset($_POST['sticky']))
        {
          $sticky = db("UPDATE ".$db['f_threads']."
                        SET `sticky` = '1'
                        WHERE id = '".intval($_GET['id'])."'");
        } else {
          $sticky = db("UPDATE ".$db['f_threads']."
                        SET `sticky` = '0'
                        WHERE id = '".intval($_GET['id'])."'");
        }

        if(isset($_POST['global']))
        {
          $sticky = db("UPDATE ".$db['f_threads']."
                        SET `global` = '1'
                        WHERE id = '".intval($_GET['id'])."'");
        } else {
          $sticky = db("UPDATE ".$db['f_threads']."
                        SET `global` = '0'
                        WHERE id = '".intval($_GET['id'])."'");
        }

        if($_POST['move'] == "lazy")
        {
          $index = info(_forum_admin_modded, "?action=showthread&amp;id=".$_GET['id']."");
        } else {
          $move = db("UPDATE ".$db['f_threads']."
                      SET `kid` = '".$_POST['move']."'
                      WHERE id = '".intval($_GET['id'])."'");
											
					$move = db("UPDATE ".$db['f_posts']."
                      SET `kid` = '".$_POST['move']."'
                      WHERE sid = '".intval($_GET['id'])."'");

          $qrym = db("SELECT s1.kid,s2.kattopic,s2.id
                      FROM ".$db['f_threads']." AS s1
                      LEFT JOIN ".$db['f_skats']." AS s2
                      ON s1.kid = s2.id
                      WHERE s1.id = '".intval($_GET['id'])."'");
          $getm = _fetch($qrym);

          $i_move = show(_forum_admin_do_move, array("kat" => re($getm['kattopic'])));
          $index = info($i_move, "?action=showthread&amp;id=".$_GET['id']."");
        }
      }
    }
  } else {
    $index = error(_error_wrong_permissions, 1);
  }
break;
case 'preview';
  header("Content-type: text/html; charset=utf-8");
  if($_GET['what'] == 'thread')
  {
    if($_GET['do'] == 'editthread')
    {
      $qry = db("SELECT * FROM ".$db['f_threads']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      $get_datum = $get['t_date'];

      if($get['t_reg'] == 0) $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = $get['t_reg'];
      }
      $editedby = show(_edited_by, array("autor" => cleanautor($userid),
                                         "time" => date("d.m.Y H:i", time())._uhr));
      $tID = $get['id'];
    } else {
      $get_datum = time();

      if($chkMe == 'unlogged') $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = $userid;
      }
      $tID = $_GET['kid'];
    }

    $titel = show(_eintrag_titel_forum, array("postid" => "1",
		  								 				     			"datum" => date("d.m.Y", $get_datum),
												 		 			    	"zeit" => date("H:i", $get_datum)._uhr,
                                        "url" => '#',
                                        "edit" => "",
                                        "delete" => ""));
    if($guestCheck)
    {
      $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                  WHERE id = '".$pUId."'");
      $getu = _fetch($qryu);

      $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
      $pn = _forum_pn_preview;
      if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
      else {
        $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
        $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
    	}

      if(empty($getu['hp'])) $hp = "";
      else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
      if(data($pUId, "signatur")) $sig = _sig.bbcode(data($pUId, "signatur"),1);
      else $sig = "";
      $onoff = onlinecheck($userid);
      $userposts = show(_forum_user_posts, array("posts" => userstats($pUId, "forumposts")+1));
    } else {
        $pn = "";
        $icq = "";
        $email = show(_emailicon_forum, array("email" => eMailAddr($_POST['email'])));
        if(empty($_POST['hp'])) $hp = "";
        else $hp = show(_hpicon_forum, array("hp" => links($_POST['hp'])));
      }



    $qryw = db("SELECT s1.kid,s1.topic,s2.kattopic,s2.sid
                FROM ".$db['f_threads']." AS s1
                LEFT JOIN ".$db['f_skats']." AS s2
                ON s1.kid = s2.id
                WHERE s1.id = '".intval($tID)."'");
    $getw = _fetch($qryw);

    $qrykat = db("SELECT name FROM ".$db['f_kats']."
                  WHERE id = '".$getw['sid']."'");
    $kat = _fetch($qrykat);

    $wheres = show(_forum_post_where_preview, array("wherepost" => re($_POST['topic']),
                                                    "wherekat" => re($getw['kattopic']),
                                                    "mainkat" => re($kat['name']),
                                                    "tid" => $_GET['id'],
                                                    "kid" => $getw['kid']));

    if(empty($get['vote'])) $vote = "";
  	else $vote = '<tr><td>'.fvote($get['vote']).'</td></tr>';

    if(!empty($_POST['question '])) $vote = _forum_vote_preview;
    else $vote = "";

    $index = show($dir."/forum_posts", array("head" => _forum_head,
                                             "where" => $wheres,
                                             "admin" => "",
                                             "class" => 'class="commentsRight"',
                                             "nick" => cleanautor($pUId, '', $_POST['nick'], $_POST['email']),
                                             "threadhead" => re($_POST['topic']),
                                             "titel" => $titel,
                                             "postnr" => "1",
                                             "pn" => $pn,
                                             "icq" => $icq,
                                             "hp" => $hp,
                                             "email" => $email,
                                             "posts" => $userposts,
                                             "text" =>  bbcode($_POST['eintrag'],1).$editedby,
                                             "status" => getrank($pUId),
                                             "avatar" => useravatar($pUId),
                                             "edited" => $get['edited'],
                                             "signatur" => $sig,
                                             "date" => _posted_by.date("d.m.y H:i", time())._uhr,
                                             "zitat" => _forum_zitat_preview,
                                             "onoff" => $onoff,
                                             "ip" => $userip.'<br />'._only_for_admins,
                                             "top" => _topicon,
                                             "lpost" => $lpost,
                                             "lp" => "",
                                             "add" => "",
                                             "nav" => nav("","",""),
                      											 "vote" => $vote,
                                             "f_abo" => "",
                                             "show" => $show));
    echo '<table class="mainContent" cellspacing="1" style="margin-top:17px">'.$index.'</table>';
    exit;
  } else {
    if($_GET['do'] == 'editpost')
    {
      $qry = db("SELECT * FROM ".$db['f_posts']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      $get_datum = $get['date'];

      if($get['reg'] == 0) $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = $get['reg'];
      }
      $editedby = show(_edited_by, array("autor" => cleanautor($userid),
                                         "time" => date("d.m.Y H:i", time())._uhr));
      $tID = $get['sid'];
      $cnt = "?";
    } else {
      $get_datum = time();

      if($chkMe == 'unlogged') $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = $userid;
      }
      $tID = $_GET['id'];
      $cnt = cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+2;
    }

    $titel = show(_eintrag_titel_forum, array("postid" => $cnt,
  		  								 				     		"datum" => date("d.m.Y",$get_datum),
  			  								 		 			   	"zeit" => date("H:i",$get_datum)._uhr,
                                        "url" => '#',
                                        "edit" => "",
                                        "delete" => ""));
    if($guestCheck)
    {
      $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                  WHERE id = '".intval($pUId)."'");
      $getu = _fetch($qryu);

      $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
      $pn = _forum_pn_preview;
      if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
      else {
       $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
       $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
      }

      if(empty($getu['hp'])) $hp = "";
      else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
      if(data($pUId, "signatur")) $sig = _sig.bbcode(data($pUId, "signatur"),1);
      else $sig = "";
    } else {
      $icq = "";
      $pn = "";
      $email = show(_emailicon_forum, array("email" => eMailAddr($_POST['email'])));
      if(empty($_POST['hp'])) $hp = "";
      else $hp = show(_hpicon_forum, array("hp" => links($_POST['hp'])));
    }

    $index = show($dir."/forum_posts_show", array("nick" => cleanautor($pUId, '', $_POST['nick'], $_POST['email']),
                                                  "postnr" => "#".($i+($page-1)*$maxfposts),
                                                  "p" => ($i+($page-1)*$maxfposts),
                                                  "class" => 'class="commentsRight"',
                                                  "text" => bbcode($_POST['eintrag'],1).$editedby,
                                                  "pn" => $pn,
                                                  "icq" => $icq,
                                                  "hp" => $hp,
                                                  "email" => $email,
                                                  "status" => getrank($pUId),
                                                  "avatar" => useravatar($pUId),
                                                  "ip" => $userip.'<br />'._only_for_admins,
                                                  "edited" => "",
                                                  "posts" => $userposts,
                                                  "titel" => $titel,
                                                  "signatur" => $sig,
                                                  "zitat" => _forum_zitat_preview,
                                                  "onoff" => $onoff,
                                                  "p" => ""));

    echo '<table class="mainContent" cellspacing="1" style="margin-top:17px">'.$index.'</table>';
    exit;
  }
break;
endswitch;
## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>