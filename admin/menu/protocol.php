<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       rootmenu
// Rechte:    $chkMe == 4
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._protocol;
    if($chkMe == 4)
    {
      if($_GET['do'] == 'deletesingle')
      {
        $del = db("DELETE FROM ".$db['ipcheck']."
                   WHERE id = '".$_GET['id']."'");

        header("Location: ".$_SERVER['HTTP_REFERER']);
      } elseif($_GET['do'] == 'delete') {
        $del = db("DELETE FROM ".$db['ipcheck']."
                   WHERE time != 0");

        $show = info(_protocol_deleted,'?admin=protocol');
      } else {
        if(!empty($_GET['sip']))
        {
          $search = "WHERE ip = '".$_GET['sip']."' AND time != 0 AND what NOT REGEXP 'vid_'";
          $swhat = $_GET['sip'];
        } else {
          $search = "WHERE time != 0 AND what NOT REGEXP 'vid_'";
          $swhat = _info_ip;
        }

        $maxprot = 30;
        $entrys = cnt($db['ipcheck'], $search);
        $qry = db("SELECT * FROM ".$db['ipcheck']."
                   ".$search."
                   ORDER BY id DESC
                   LIMIT ".($page - 1)*$maxprot.",".$maxprot."");
        while($get = _fetch($qry))
        {
          $action = "";
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $date = date("d.m.y H:i", $get['time'])._uhr;
          $delete = show("page/button_delete", array("id" => $get['id'],
                                                     "action" => "admin=protocol&amp;do=deletesingle",
                                                     "title" => _button_title_del));

          if(preg_match("#\(#",$get['what']))
          {
            $a = preg_replace("#^(.*?)\((.*?)\)#is","$1",$get['what']);
            $wid = preg_replace("#^(.*?)\((.*?)\)#is","$2",$get['what']);

            if($a == 'fid')
              $action = 'wrote in <b>board</b>';
            elseif($a == 'ncid')
              $action = 'wrote <b>comment</b> in <b>news</b> with <b>ID</b> '.$wid;
            elseif($a == 'artid')
              $action = 'wrote <b>comment</b> in <b>article</b> with <b>ID</b> '.$wid;
            elseif($a == 'vid')
              $action = 'voted <b>poll</b> with <b>ID '.$wid.'</b>';
            elseif($a == 'mgbid')
              $action = autor($wid).' got a userbook entry';
            elseif($a == 'cwid')
              $action = 'wrote <b>comment</b> in <b>clanwar</b> with <b>ID</b> '.$wid;
            elseif($a == 'createuser') {
              $ids = explode("_", $wid);
              $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' <b>added</b> user '.autor($ids[1]);
            } elseif($a == 'upduser') {
              $ids = explode("_", $wid);
              $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' <b>edited</b> user '.autor($ids[1]);
            } elseif($a == 'deluser') {
              $ids = explode("_", $wid);
              $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' <b>deleted</b> user';
            } elseif($a == 'ident') {
              $ids = explode("_", $wid);
              $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' took <b>identity</b> from user '.autor($ids[1]);
            } elseif($a == 'logout')
              $action = autor($wid).' <b>logged out</b>';
            elseif($a == 'login')
              $action = autor($wid).' <b>logged in</b>';
            elseif($a == 'trypwd')
              $action = 'failed to <b>reset password</b> from '.autor($wid);
            elseif($a == 'pwd')
              $action = '<b>reseted password</b> from '.autor($wid);
            elseif($a == 'reg')
              $action = autor($wid).' <b>signed up</b>';
            elseif($a == 'trylogin')
              $action = 'failed to <b>login</b> in '.autor($wid).'`s account';
            else $action = '<b style="color:red">undefined:</b> <b>'.$a.'</b>';
          } else {
            if($get['what'] == 'gb')
              $action = 'wrote in <b>guestbook</b>';
            elseif($get['what'] == 'shout')
              $action = 'wrote in <b>shoutbox</b>';
            else $action = '<b style="color:red">undefined:</b> <b>'.$a.'</b>';
          }

          $show .= show($dir."/protocol_show", array("datum" => $date,
                                                     "class" => $class,
                                                     "delete" => $delete,
                                                     "user" => $get['ip'],
                                                     "action" => $action
                                                    ));
        }

        if(!empty($_GET['sip'])) $sip = "&amp;sip=".$_GET['sip'];

        $show = show($dir."/protocol", array("show" => $show,
                                             "date" => _datum,
                                             "del" => _button_title_del_protocol,
                                             "action" => _protocol_action,
                                             "protocol" => _protocol,
                                             "user" => _info_ip,
                                             "value" => _button_value_search,
                                             "search" => $swhat,
                                             "nav" => nav($entrys,$maxprot,"?admin=protocol".$sip)
                                             ));
      }
    } else {
      $show = error(_error_wrong_permissions, 1);
    }
?>