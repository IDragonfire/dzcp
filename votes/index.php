<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_votes;
$title = $pagetitle." - ".$where."";
$dir = "votes";
## SECTIONS ##
if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];

switch ($action):
default:
    $fvote = '';
    if($forum_vote == 0)
        $fvote = 'forum = 0';

    $whereIntern = ' AND intern = 0';
    $order = 'datum DESC';
    if(permission('votes'))
    {
        $whereIntern = '';
        $order = 'intern DESC';
    }

    $qry = db('SELECT * FROM '.$db['votes'].' WHERE '.$fvote.$whereIntern.' ORDER BY ' . $order);

  while($get = _fetch($qry)) {
    $qryv = db('SELECT * FROM ' . $db['vote_results'] .
                ' WHERE vid = ' . (int) $get['id'] .
                ' ORDER BY id');
    $results = '';
    $check = '';
    $stimmen = sum($db['vote_results']," WHERE vid = '".$get['id']."'","stimmen");
    $vid = 'vid_' . (int) $get['id'];
    if($get['intern'] == 1) {
      $showVoted = '';
        $check = db('SELECT * FROM ' . $db['ipcheck'] .
                     ' WHERE what = "' . $vid .
                     '" AND ip = ' . (int) $userid . '');

        $ipcheck = _rows($check) == 1;
        $intern = _votes_intern;
      } else {
        $ipcheck = false;
        $intern = '';
      }
    $hostIpcheck = ipcheck($vid);
    while($getv = _fetch($qryv)) {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      if($hostIpcheck || $ipcheck || isset($_COOKIE[$prev."vid_".$get['id']]) || $get['closed'] == 1) {
        $percent = @round($getv['stimmen']/$stimmen*100,2);
        $rawpercent = @round($getv['stimmen']/$stimmen*100,0);

        $balken = show(_votes_balken, array("width" => $rawpercent));

        $result_head = _votes_results_head;
        $votebutton = "";
        $results .= show($dir."/votes_results", array("answer" => re($getv['sel']),
                                                      "percent" => $percent,
                                                      "lng_stimmen" => _votes_stimmen,
                                                      "class" => $class,
                                                      "stimmen" => $getv['stimmen'],
                                                      "balken" => $balken));
      } else {
        $result_head = _votes_results_head_vote;
        $votebutton = '<input id="voteSubmit_'.$get['id'].'" type="submit" value="'._button_value_vote.'" class="submit" />';
        $results .= show($dir."/votes_vote", array("id" => $getv['id'],
                                                   "answer" => re($getv['sel']),
                                                   "class" => $class));
      }
    }

    if($get['intern'] == 1 && $stimmen != 0 && ($get['von'] == $userid || permission('votes'))) {
        $showVoted = ' <a href="?action=showvote&amp;id=' . (int) $get['id'] .
                     '"><img src="../inc/images/lupe.gif" alt="" title="' .
                     _show_who_voted . '" class="icon" /></a>';
    }

    if(($_GET['action'] == "show" && $get['id'] == $_GET['id']) || isset($_GET['show']) && $get['id'] == $_GET['show'])
    {
      $moreicon = "collapse";
      $display = "";
    } else {
      $moreicon = "expand";
      $display = "none";
    }

        if($get['forum'] == 1) $ftitel = re($get['titel']).' (Forum)';
        else $ftitel = re($get['titel']);

        $titel = show(_votes_titel, array("titel" => $ftitel,
                                      "vid" => $get['id'],
                                      "icon" => $moreicon,
                                      "intern" => $intern));

    if($get['closed'] == 1) $closed = _closedicon_votes;
    else                    $closed = "";

    $class = ($color2 % 2) ? "contentMainSecond" : "contentMainFirst"; $color2++;
    $show .= show($dir."/votes_show", array("datum" => date("d.m.Y", $get['datum']),
                                            "titel" => $titel,
                                            "vid" => $get['id'],
                                            "display" => $display,
                                            "result_head" => $result_head,
                                            "results" => $results,
                                            "show" => $showVoted,
                                            "closed" => $closed,
                                            "autor" => autor($get['von']),
                                            "menu" => $menu,
                                            "class" => $class,
                                            "votebutton" => $votebutton,
                                            "stimmen" => $stimmen));
  }

  $index = show($dir."/votes", array("head" => _votes_head,
                                     "show" => $show,
                                     "titel" => _titel,
                                     "autor" => _autor,
                                     "datum" => _datum,
                                     "stimmen" => _votes_stimmen));
break;
case 'showvote';
  $qry = db("SELECT * FROM ".$db['votes']."
             WHERE id = '".intval($_GET['id'])."'");
  $get = _fetch($qry);

  if($get['intern'] == 1)
  {
    $qryv = db("SELECT * FROM ".$db['ipcheck']."
                WHERE what = 'vid_".$get['id']."'
                ORDER BY time DESC");
    while($getv = _fetch($qryv))
    {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $show .= show($dir."/voted_show", array("user" => autor($getv['ip']),
                                              "date" => date("d.m.y H:i",$getv['time'])._uhr,
                                              "class" => $class
                                              ));
    }

    $index = show($dir."/voted", array("head" => _voted_head,
                                       "user" => _user,
                                       "date" => _datum,
                                       "show" => $show
                                       ));
  } else {
    $index = error(_error_vote_show,1);
  }
break;
case 'do';
  if($_GET['what'] == "vote")
  {
    if(empty($_POST['vote']))
    {
      $index = error(_vote_no_answer);
    } else {
      $qry = db("SELECT * FROM ".$db['votes']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      if($get['intern'] == 1)
      {
        $vid = "vid_".$_GET['id'];
        $check = db("SELECT * FROM ".$db['ipcheck']."
                     WHERE what = '".$vid."' ");
        $ipcheck = _fetch($check);

        if($ipcheck['ip'] == $userid)
        {
          $index = error(_error_voted_again,1);
        } elseif($get['closed'] == 1)
        {
          $index = error(_error_vote_closed,1);
        } else {
          $update = db("UPDATE ".$db['userstats']."
                        SET `votes` = votes+1
                        WHERE user = '".$userid."'");

          $qry = db("UPDATE ".$db['vote_results']."
                     SET `stimmen` = stimmen+1
                     WHERE id = '".intval($_POST['vote'])."'");

          $qry = db("INSERT INTO ".$db['ipcheck']."
                     SET `ip`   = '".$userid."',
                         `what` = '".$vid."',
                         `time` = '".time()."'");

          $vid2 = "vid(".$_GET['id'].")";
          $ins2 = db("INSERT INTO ".$db['ipcheck']."
                      SET `ip`   = '".$userip."',
                          `what` = '".$vid2."',
                          `time` = '".time()."'");

          if(!isset($_GET['ajax'])) $index = info(_vote_successful, "?action=show&amp;id=".$_GET['id']."");
        }
      } else {
        if(ipcheck("vid_".$_GET['id'])) $index = error(_error_voted_again,1);
        elseif($get['closed'] == 1)     $index = error(_error_vote_closed,1);
        else {
          if(isset($userid))
          {
            $time = $userid;
            $update = db("UPDATE ".$db['userstats']."
                          SET `votes` = votes+1
                          WHERE user = '".$userid."'");
          } else $time = "0";

          $qry = db("UPDATE ".$db['vote_results']."
                     SET `stimmen` = stimmen+1
                     WHERE id = '".intval($_POST['vote'])."'");

          $vid = "vid_".$_GET['id']."";
          $ins = db("INSERT INTO ".$db['ipcheck']."
                     SET `ip`   = '".$userip."',
                         `what` = '".$vid."',
                         `time` = '".time()."'");

          $vid2 = "vid(".$_GET['id'].")";
          $ins2 = db("INSERT INTO ".$db['ipcheck']."
                      SET `ip`   = '".$userip."',
                          `what` = '".$vid2."',
                          `time` = '".time()."'");

          if(!isset($_GET['ajax'])) $index = info(_vote_successful, "?action=show&amp;id=".$_GET['id']."");
        }
        if(isset($userid)) $cookie = $userid;
        else $cookie = "voted";
      }
      set_cookie($prev."vid_".$_GET['id'],$cookie);
    }
  }

  if($_GET['ajax'] == 1)
  {
    header("Content-type: text/html; charset=utf-8");
    include(basePath.'/inc/menu-functions/vote.php');
    echo '<table class="navContent" cellspacing="0">'.vote(1).'</table>';
    exit;
  }

  if($_GET['what'] == "fvote")
  {
    if(empty($_POST['vote']))
    {
      $index = error(_vote_no_answer);
    } else {
      $qry = db("SELECT * FROM ".$db['votes']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      if(ipcheck("vid_".$_GET['id'])) $index = error(_error_voted_again,1);
      elseif($get['closed'] == 1)     $index = error(_error_vote_closed,1);
      else {
        if(isset($userid))
        {
          $time = $userid;
          $update = db("UPDATE ".$db['userstats']."
                        SET `votes` = votes+1
                        WHERE user = '".$userid."'");
        } else $time = "0";

        $qry = db("UPDATE ".$db['vote_results']."
                   SET `stimmen` = stimmen+1
                   WHERE id = '".intval($_POST['vote'])."'");

        $vid = "vid_".$_GET['id']."";
        $ins = db("INSERT INTO ".$db['ipcheck']."
                   SET `ip`   = '".$userip."',
                       `what` = '".$vid."',
                       `time` = '".time()."'");

        $vid2 = "vid(".$_GET['id'].")";
        $ins2 = db("INSERT INTO ".$db['ipcheck']."
                    SET `ip`   = '".$userip."',
                        `what` = '".$vid2."',
                        `time` = '".time()."'");

        if(!isset($_GET['fajax'])) $index = info(_vote_successful, "forum/?action=showthread&amp;kid=".$_POST['kid']."&amp;id=".$_POST['fid']."");
      }
      if(isset($userid)) $cookie = $userid;
      else $cookie = "voted";
    }
      set_cookie($prev."vid_".$_GET['id'],$cookie);
  }

  if($_GET['fajax'] == 1)
  {
    include_once(basePath.'/inc/menu-functions/fvote.php');
    header("Content-type: text/html; charset=utf-8");
    echo fvote($_GET['id'], 1);
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
