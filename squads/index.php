<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_member;
$title = $pagetitle." - ".$where."";
$dir = "squads";

## SECTIONS ##
  if(!empty($_GET['showsquad'])) header('Location: ?action=shows&id='.intval($_GET['showsquad']));
  else if(!empty($_GET['show'])) header('Location: ?action=shows&id='.intval($_GET['show']));

  switch(strtolower($action)):
    case 'shows';
      $get = _fetch(db("SELECT * FROM ".$db['squads']." WHERE `id` = '".intval($_GET['id'])."'"));
      $qrym = db("SELECT s1.user,s1.squad,s2.id,s2.nick,s2.icq,s2.email,s2.hlswid,s2.rlname,
                         s2.steamid,s2.level,s2.bday,s2.hp,s3.posi,s4.pid
                  FROM ".$db['squaduser']." AS s1
                  LEFT JOIN ".$db['users']." AS s2
                  ON s2.id=s1.user
                  LEFT JOIN ".$db['userpos']." AS s3
                  ON s3.squad=s1.squad AND s3.user=s1.user
                  LEFT JOIN ".$db['pos']." AS s4
                  ON s4.id=s3.posi
                  WHERE s1.squad='".intval($_GET['id'])."'
                  ORDER BY s4.pid, s2.nick");

      $member = "";
      $t = 1;
      $c = 1;
      while($getm = _fetch($qrym))
      {
        $cntall = cnt($db['squaduser'], " WHERE squad= '".$get['id']."'");

        if($getm['icq'] == 0)
        {
          $icq = "-";
          $icqnr = "&nbsp;";
          } else {
          $icq = show(_icqstatus, array("uin" => $getm['icq']));
          $icqnr = $getm['icq'];
        }

        $steam = (!empty($getm['steamid']) && steam_enable ? '<div id="infoSteam_'.md5(re($getm['steamid'])).'"><div style="width:100%;text-align:center"><img src="../inc/images/ajax-loader-mini.gif" alt="" /></div><script language="javascript" type="text/javascript">DZCP.initDynLoader("infoSteam_'.md5(re($getm['steamid'])).'","steam","&steamid='.re($getm['steamid']).'");</script></div>' : '-');
        $class = ($color % 2) ? "contentMainFirst" : "contentMainSecond"; $color++;
        $nick = autor($getm['user'],'','','','','&amp;sq='.$getm['squad']);

        if(!empty($getm['rlname']))
        {
          $real = explode(" ", re($getm['rlname']));
          $nick = '<b>'.$real[0].' &#x93;</b> '.$nick.' <b>&#x94; '.$real[1].'</b>';
        }


        $member .= show($dir."/squads_member", array("icqs" => $icq,
                                                     "icq" => $icqnr,
                                                     "emails" => eMailAddr($getm['email']),
                                                     "id" => $getm['user'],
													 "psteam" => _steam,
                                                     "steam" => $steam,
                                                     "class" => $class,
                                                     "nick" => $nick,
                                                     "onoff" => onlinecheck($getm['id']),
                                                     "posi" => getrank($getm['id'],$getm['squad']),
                                                     "pic" => userpic($getm['id'],60,80)));
      }

      $squad = re($get['name']); $style = '';
      foreach($picformat AS $end)
      {
        if(file_exists(basePath.'/inc/images/squads/'.intval($get['id']).'.'.$end))
        {
          $style = 'padding:0;';
          $squad = '<img src="../inc/images/squads/'.intval($get['id']).'.'.$end.'" alt="'.re($get['name']).'" />';
          break;
        }
      }

      $index = show($dir."/squads_full", array("member" => (empty($member) ? _member_squad_no_entrys : $member),
                                               "desc" => empty($get['beschreibung']) ? '' : '<tr><td class="contentMainSecond">'.bbcode($get['beschreibung']).'</td></tr>',
                                               "squad" => $squad,
                                               "style" => $style,
                                               "back" => _error_back,
                                               "id"   => intval($_GET['id'])));
    break;
    default;
      $qry = db("SELECT * FROM ".$db['squads']." WHERE team_show = 1 ORDER BY pos");
      while($get = _fetch($qry))
      {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $squad = show(_gameicon, array("icon" => $get['icon'])).' '.re($get['name']); $style = '';

        foreach($picformat AS $end)
        {
          if(file_exists(basePath.'/inc/images/squads/'.intval($get['id']).'.'.$end))
          {
            $style = 'text-align:center;padding:0';
            $squad = '<img src="../inc/images/squads/'.intval($get['id']).'.'.$end.'" alt="'.re($get['name']).'" />';
            break;
          }
        }

        $show .= show($dir."/squads_show", array("id" => $get['id'],
                                                 "squad" => $squad,
                                                 "style" => $style,
                                                 "class" => $class,
                                                 "beschreibung" => bbcode($get['beschreibung']),
                                                 "squadname" => re($get['name'])
                                                 ));
      }

      $cntm = db("SELECT * FROM ".$db['squaduser']." GROUP BY user");
      $weare = show(_member_squad_weare, array("cm" => _rows($cntm),
                                               "cs" => cnt($db['squads'], "WHERE team_show = 1")));

      $index = show($dir."/squads", array("squadhead" => _member_squad_head,
                                          "weare" => $weare,
                                          "show" => $show));
    break;
  endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);