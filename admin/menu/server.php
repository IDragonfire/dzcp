<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('editserver')
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._server_admin_head;
    if(!permission("editserver"))
    {
      $show = error(_error_wrong_permissions, 1);
    } else {
      $qry = db("SELECT * FROM ".$db['server']."
                 ORDER BY id");
      while($get = _fetch($qry))
      {
        $gameicon = show(_gameicon, array("icon" => re($get['game'])));
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "admin=server&amp;do=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "admin=server&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => convSpace(_confirm_del_server)));
                                                          
        $mapdl = '<a href="?admin=server&amp;do=mapdl&amp;id='.re($get['id']).'" onclick="return(DZCP.del(\''._mapdl_download.'\'))"><img src="../inc/images/download.gif" alt="" /></a>';
                                                          
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

        if($get['navi'] == "0") $menu = show(_server_menu_icon_yes, array("id" => $get['id']));
        else  $menu = show(_server_menu_icon_no, array("id" => $get['id']));

        $show_ .= show($dir."/server_show", array("gameicon" => $gameicon,
                                                  "serverip" => re($get['ip']).":".$get['port'],
                                                  "serverpwd" => re($get['pwd']),
                                                  "menu" => $menu,
                                                  "mapdl" => $mapdl,
                                                  "edit" => $edit,
                                                  "name" => re($get['name']),
                                                  "class" => $class,
                                                  "delete" => $delete));
      }

      $show = show($dir."/server", array("game" => _game,
                                         "serveradmin_head" => _server_admin_head,
                                         "sip" => _server_ip,
                                         "servermaps_head" => _server_admin_servermaps_head,
                                         "mapupload" => _server_admin_maps,
                                         "ftp_path" => re($settings['bl_path']),
                                         "legende" => _legende,
                                         "value" => _button_value_save,
                                         "yesno" => _server_menu_icon_yesno,
                                         "legendemenu" => _server_legendemenu,
                                         "ftp_url" => re($settings['ftp_host']),
                                         "ftp_login" => re($settings['ftp_login']),
                                         "ftp_pwd" => re($settings['ftp_pwd']),
                                         "ts_port" => (empty($settings['ts_port']) ? '' : $settings['ts_port']),
                                         "ts_ip" => re($settings['ts_ip']),
                                         "ts_sport" => (empty($settings['ts_sport']) ? '' : $settings['ts_sport']),
                                         "ts_width" => $settings['ts_width'],
                                         "ts_version" => ($settings['ts_version'] == 3 ? ' selected="selected"' : ''),
                                         "ts_showsettings" => ($settings['ts_version'] == 3 ? '' : ' style="display:none;"'),
                                         "ts_checkcustomicon" => ($settings['ts_customicon'] == 1 ? ' selected="selected"' : ''),
                                         "ts_checkshowchannel" => ($settings['ts_showchannel'] == 1 ? ' selected="selected"' : ''),
                                         "ts_showchannel" => _ts_settings_showchannels,
                                         "ts_customicon" => _ts_settings_customicon,
                                         "ts_showchannel_desc" => _ts_settings_showchannels_desc,
                                         "on" => _on,
                                         "off" => _off,
                                         "name" => _server_name,
                                         "mapdl" => '<img src="../inc/images/download.gif" alt="" />',
                                         "mapdownload" => _legend_map_download,
                                         "menu" => _yesno,
                                         "pwd" => _pwd,
                                         "sport" => _ts_sport,
                                         "width" => _ts_width,
                                         "ts_head" => _teamspeak,
                                         "teamspeak" => _server_ip,
                                         "add" => _admin_server_new,
                                         "no_status" => _no_live_status,
                                         "show" => $show_,
                                         "edit" => _editicon_blank,
                                         "delete" => _deleteicon_blank));

      if($_GET['do'] == "ts")
      {
		switch (((int)$_POST['ts_version'])) {
        case "3": //TS3
        	$tsport = 9987;
            $tsqport = 10011;
        break;
        default: //TS2
        	$tsport = 8767;
        	$tsqport = 51234;
        break;
        }
		$tsport = empty($_POST['ts_port']) ? $tsport : $_POST['ts_port'];
		$tsqport = empty($_POST['ts_sport']) ? $tsqport : $_POST['ts_sport'];
        $qry = db("UPDATE ".$db['settings']."
                   SET `ts_port`    	= '".((int)$tsport)."',
                       `ts_sport`  		= '".((int)$tsqport)."',
                       `ts_width`   	= '".((int)$_POST['ts_width'])."',
                       `ts_version` 	= '".((int)$_POST['ts_version'])."',
                       `ts_ip`      	= '".up($_POST['ts_ip'])."',
                       `ts_customicon`  = '".((int)$_POST['ts_customicon'])."',
                       `ts_showchannel` = '".((int)$_POST['ts_showchannel'])."'
                   WHERE id = 1");

        $show = info(_config_server_ts_updated,"?admin=server");

      } elseif($_GET['do'] == "menu") {
        $qrys = db("SELECT * FROM ".$db['server']."
                    WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qrys);

        if($get['navi'] == "1")
        {
          $qry = db("UPDATE ".$db['server']."
                     SET `navi` = '0'
                     WHERE id = '".intval($_GET['id'])."'");
        } else {
          if($get['status'] != "nope")
          {
            $qry = db("UPDATE ".$db['server']."
                       SET `navi` = '1'
                       WHERE id = '".intval($_GET['id'])."'");


          } else {
            $show = error(_server_isnt_live,1);
            exit;
          }
        }
        $show = header("Location: ?admin=server");
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".$db['server']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $files = get_files('../inc/images/gameicons/',false,true);
        for($i=0; $i<count($files); $i++)
        {
          if($files[$i] == $get['game']) $sel = "selected=\"selected\"";
          else $sel = "";

          if(preg_match("=\.gif|.jpg|.png=Uis",$files[$i]))
            $game .= show(_select_field, array("value" => $files[$i],
                                               "what" => strtoupper(preg_replace("#\.(.*?)$#","",$files[$i])),
                                               "sel" => $sel));
        }

        $show = show($dir."/server_edit", array("edithead" => _admin_server_edit,
                                                "ip" => _server_ip,
                                                "sip" => re($get['ip']),
                                                "name" => _server_name,
                                                "sname" => re($get['name']),
                                                "id" => $_GET['id'],
                                                "sport" => $get['port'],
                                                "port" => _server_admin_qport,
                                                "qport" => $get['qport'],
                                                "pwd" => _pwd,
                                                "games" => sgames($get['status']),
                                                "no_status" => _admin_server_nostatus,
                                                "status" => _admin_status,
                                                "no_status" => _no_live_status,
                                                "status_info" => _admin_status_info,
                                                "value" => _button_value_edit,
                                                "nothing" => _nothing,
                                                "spwd" => $get['pwd'],
                                                "game" => _game,
                                                "sgame" => $game));
      } elseif($_GET['do'] == 'mapdl') {
        echo 'not supported since 2012';
        exit;
      } elseif($_GET['do'] == 'downloadmap') {
        echo 'not supported since 2012';
        exit;
      } elseif($_GET['do'] == "editserver") {
        if(empty($_POST['ip']) || empty($_POST['port']))
        {
          $show = error(_empty_ip,1);
        } elseif(empty($_POST['name']))
        {
          $show = error(_empty_servername,1);
        } else {
          if($_POST['game'] == "lazy") $game = "";
          else $game = "`game` = '".up($_POST['game'])."',";

          if($_POST['status'] == "lazy") $status = "";
          else $status = "`status` = '".up($_POST['status'])."',";

          $qry = db("UPDATE ".$db['server']."
                     SET `ip`         = '".up($_POST['ip'])."',
                         `port`       = '".((int)$_POST['port'])."',
                         `qport`      = '".up($_POST['qport'])."',
                         `name`       = '".up($_POST['name'])."',
                         ".$game."
                         ".$status."
                         `pwd`        = '".up($_POST['pwd'])."'
                     WHERE id = '".intval($_GET['id'])."'");

          $show = info(_server_admin_edited, "?admin=server");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".$db['server']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_server_admin_deleted, "?admin=server");

      } elseif($_GET['do'] == "new") {
        $files = get_files('../inc/images/gameicons/',false,true,array('gif','jpg','png'));
        for($i=0; $i<count($files); $i++)
        {
            $game .= show(_select_field, array("value" => $files[$i],
                                               "what" => strtoupper(preg_replace("#\.(.*?)$#","",$files[$i])),
                                               "sel" => ""));
        }

        $show = show($dir."/server_add", array("newhead" => _admin_server_new,
                                               "ip" => _server_ip,
                                               "pwd" => _pwd,
                                               "value" => _button_value_add,
                                               "games" => sgames(),
                                               "no_status" => _no_live_status,
                                               "game" => _game,
                                               "port" => _server_admin_qport,
                                               "status" => _admin_status,
                                               "status_info" => _admin_status_info,
                                               "name" => _server_name,
                                               "sgame" => $game));
      } elseif($_GET['do'] == "add")
      {
        if(empty($_POST['ip']) || empty($_POST['port']))
        {
          $show = error(_empty_ip,1);
        } elseif($_POST['game'] == "lazy") {
          $show = error(_empty_game,1);
        } elseif(empty($_POST['name'])) {
          $show = error(_empty_servername,1);
        } else {
          $qry = db("INSERT INTO ".$db['server']."
                     SET `ip`         = '".up($_POST['ip'])."',
                         `port`       = '".((int)$_POST['port'])."',
                         `qport`      = '".up($_POST['qport'])."',
                         `name`       = '".up($_POST['name'])."',
                         `pwd`        = '".up($_POST['pwd'])."',
                         `game`       = '".up($_POST['game'])."',
                         `status`     = '".up($_POST['status'])."'");

          $show = info(_server_admin_added, "?admin=server");
        }
      }
    }
?>