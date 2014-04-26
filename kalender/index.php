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
$where = _site_kalender;
$title = $pagetitle." - ".$where."";
$dir = "kalender";
## SECTIONS ##
switch ($action):
default:
  if(isset($_POST['monat'])) $monat = ((int)$_POST['monat']);
  elseif(isset($_GET['m']))  $monat = ((int)$_GET['m']);
  else $monat = date("m");

  if(isset($_POST['jahr'])) $jahr = ((int)$_POST['jahr']);
  elseif(isset($_GET['y'])) $jahr = ((int)$_GET['y']);
  else $jahr = date("Y");

  for($i = 1; $i <= 12; $i++)
  {
    if($monat == $i) $sel = 'selected="selected"';
    else $sel = "";

    $mname = array("1" => _jan,
                   "2" => _feb,
                   "3" => _mar,
                   "4" => _apr,
                   "5" => _mai,
                   "6" => _jun,
                   "7" => _jul,
                   "8" => _aug,
                   "9" => _sep,
                   "10" => _okt,
                   "11" => _nov,
                   "12" => _dez);

    $month .= show(_select_field, array("value" => cal($i),
                                        "sel" => $sel,
                                        "what" => $mname[$i]));
  }

  for( $i = date("Y")-5; $i < date("Y")+3; $i++)
  {
    if($jahr == $i) $sel = 'selected="selected"';
    else $sel = "";

    $year .= show(_select_field, array("value" => $i,
                                       "sel" => $sel,
                                       "what" => $i));
  }

  $ktoday = mktime(0,0,0,date("n"),date("d"),date("Y"));
  $i = 1;
  while($i <= 31 && checkdate($monat, $i, $jahr))
  {
    unset($data);
    for($iw = 1; $iw <= 7; $iw++)
    {
      unset($bdays, $cws, $infoBday, $infoCW, $infoEvent);
      $datum = mktime(0,0,0,$monat,$i,$jahr);
      $wday = getdate($datum);
      $wday = $wday['wday'];

      if(!$wday) $wday = 7;

      if($wday != $iw)
      {
        $data .= '<td class="calDay"></td>';
      } else {
        $qry = db("SELECT id,bday,nick FROM ".$db['users']."
                   WHERE bday LIKE '".cal($i).".".$monat.".____"."'");
        if(_rows($qry))
        {
          while($get = _fetch($qry)) $infoBday .='&lt;img src=../inc/images/bday.gif class=icon alt= /&gt;'.'&nbsp;'.jsconvert(_kal_birthday.rawautor($get['id'])).'<br />';

          $info = ' onmouseover="DZCP.showInfo(\''.$infoBday.'\')" onmouseout="DZCP.hideInfo()"';
          $bdays = '<a href="../user/?action=userlist&amp;show=bday&amp;time='.$datum.'"'.$info.'><img src="../inc/images/bday.gif" alt="" /></a>';
        } else {
          $bdays = "";
        }

          $qry = db("SELECT datum,gegner FROM ".$db['cw']."
                     WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".cal($i).".".$monat.".".$jahr."'");
        if(_rows($qry))
        {
          while($get = _fetch($qry)) $infoCW .= '&lt;img src=../inc/images/cw.gif class=icon alt= /&gt;'.'&nbsp;'.jsconvert(_kal_cw.re($get['gegner'])).'<br />';

          $info = ' onmouseover="DZCP.showInfo(\''.$infoCW.'\')" onmouseout="DZCP.hideInfo()"';
          $cws = '<a href="../clanwars/?action=kalender&amp;time='.$datum.'"'.$info.'><img src="../inc/images/cw.gif" alt="" /></a>';
        } else {
          $cws = "";
        }

        $qry = db("SELECT datum,title FROM ".$db['events']."
                   WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".cal($i).".".$monat.".".$jahr."'");
        if(_rows($qry))
        {
          while($get = _fetch($qry)) $infoEvent .='&lt;img src=../inc/images/event.gif class=icon alt= /&gt;'.'&nbsp;'.jsconvert(_kal_event.re($get['title'])).'<br />';

          $info = ' onmouseover="DZCP.showInfo(\''.$infoEvent.'\')" onmouseout="DZCP.hideInfo()"';
          $event = '<a href="?action=show&amp;time='.$datum.'"'.$info.'><img src="../inc/images/event.gif" alt="" /></a>';
        } else {
          $event = "";
        }

        $events = $bdays." ".$cws." ".$event;


        if($_GET['hl'] == $i) $day = '<span class="fontMarked">'.cal($i).'</span>';
        else $day = cal($i);

        if(!checkdate($monat, $i, $jahr))
        {
          $data .= '<td class="calDay"></td>';
        } elseif($datum == $ktoday) {
          $data .= show($dir."/day", array("day" => $day,
                                           "event" => $events,
                                           "class" => "calToday"));
        } else {
           $data .= show($dir."/day", array("day" => $day,
                                            "event" => $events,
                                            "class" => "calDay"));
        }
        $i++;
      }
    }
    $show .= "<tr>".$data."</tr>";
  }

  $index = show($dir."/kalender", array("monate" => $month,
                                        "jahr" => $year,
                                        "show" => $show,
                                        "what" => _button_value_show,
                                        "montag" => _montag,
                                        "dienstag" => _dienstag,
                                        "mittwoch" => _mittwoch,
                                        "donnerstag" => _donnerstag,
                                        "freitag" => _freitag,
                                        "samstag" => _samstag,
                                        "sonntag" => _sonntag,
                                        "head" => _kalender_head));
break;
case 'show';
  $qry = db("SELECT * FROM ".$db['events']."
             WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".date("d.m.Y",intval($_GET['time']))."'
             ORDER BY datum");
  while($get = _fetch($qry))
  {
    if(permission("editkalender"))
    {
      $edit = show("page/button_edit", array("id" => $get['id'],
                                             "action" => "action=admin&amp;do=edit",
                                             "title" => _button_title_edit));
    } else {
      $edit = "";
    }

    $events .= show($dir."/event_show", array("event" => _kalender_event,
                                              "time" => _kalender_uhrzeit,
                                              "edit" => $edit,
                                              "show_time" => date("H:i", $get['datum'])._uhr,
                                              "show_event" => bbcode($get['event']),
                                              "show_title" => re($get['title'])));
  }

  $head = show(_kalender_events_head, array("datum" => date("d.m.Y",$_GET['time'])));
  $index = show($dir."/event", array("head" => $head,
                                     "events" => $events));
break;
case 'admin';
  header("Location: ../admin/?admin=kalender&do=edit&id=".$_GET['id']);
break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();