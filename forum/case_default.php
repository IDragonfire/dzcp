<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_Forum')) {
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

        $lpost = "-"; $lpdate = "";
        if(cnt($db['f_threads'], " WHERE kid = '".$gets['id']."'"))
        {
           $lpost = '';
           if($getlt['first'] == "1") {
              $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlt['t_reg'], '', $getlt['t_nick'], $getlt['t_email']),
                                                        "date" => date("d.m.y H:i", $getlt['t_date'])._uhr));

              $lpdate = $getlt['t_date'];
            } elseif($getlt['first'] == "0") {
              $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlp['reg'], '', $getlp['nick'], re($getlp['email'])),
                                                        "date" => date("d.m.y H:i", $getlp['date'])._uhr));
              $lpdate = $getlp['date'];
            }
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

  $show_top = '';
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
    if(!$chkMe) $nick = "<center>"._forum_nobody_is_online."</center>";
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
}