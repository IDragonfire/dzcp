<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;
$where = $where.': '._server_admin_head;

switch ($do)
{
    case 'ts':
        switch (intval($_POST['ts_version'])) {
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
        db("UPDATE ".$db['settings']." SET `ts_port`        = '".intval($tsport)."',
                                           `ts_sport`          = '".intval($tsqport)."',
                                           `ts_width`       = '".intval($_POST['ts_width'])."',
                                           `ts_version`     = '".intval($_POST['ts_version'])."',
                                           `ts_ip`          = '".up($_POST['ts_ip'])."',
                                           `ts_customicon`  = '".intval($_POST['ts_customicon'])."',
                                           `ts_showchannel` = '".intval($_POST['ts_showchannel'])."'
                                       WHERE id = 1");

        $show = info(_config_server_ts_updated,"?admin=server");
    break;
    case 'menu':
        $get = db("SELECT * FROM ".$db['server']." WHERE id = '".intval($_GET['id'])."'",false,true);
        if($get['navi'])
            db("UPDATE ".$db['server']." SET `navi` = '0' WHERE id = '".intval($_GET['id'])."'");
        else {
            if($get['status'] != "nope")
                db("UPDATE ".$db['server']." SET `navi` = '1' WHERE id = '".intval($_GET['id'])."'");
            else {
                $show = error(_server_isnt_live,1);

                if(!mysqli_persistconns)
                    $mysql->close(); //MySQL

                exit();
            }
        }

        $show = header("Location: ?admin=server");
    break;
    case 'edit':
        $get = db("SELECT * FROM ".$db['server']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $files = get_files(basePath.'/inc/images/gameicons/',false,true,$picformat); $game = '';
        foreach($files as $file) {
            if($file == 'unknown.gif') continue;
            $sel = ($file == $get['game']) ? 'selected="selected"' : '';
            if(preg_match("=\.gif|.jpg|.png=Uis",$file))
                $game .= show(_select_field, array("value" => $file,
                                                   "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)),
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
                                                "value" => _button_value_edit,
                                                "nothing" => _nothing,
                                                "spwd" => $get['pwd'],
                                                "game" => _game,
                                                "sgame" => $game));
    break;
    case 'editserver':
        if(empty($_POST['ip']) || empty($_POST['port']))
            $show = error(_empty_ip,1);
        elseif(empty($_POST['name']))
            $show = error(_empty_servername,1);
        else {
            $game = ($_POST['game'] == "lazy") ? "" : "`game` = '".up($_POST['game'])."',";
            $status =  ($_POST['status'] == "lazy") ? "" : "`status` = '".up($_POST['status'])."',";
            db("UPDATE ".$db['server']." SET `ip` = '".up($_POST['ip'])."',
                                             `port` = '".intval($_POST['port'])."',
                                             `qport` = '".up($_POST['qport'])."',
                                             `name`  = '".up($_POST['name'])."',
                                             ".$game."
                                             ".$status."
                                             `pwd` = '".up($_POST['pwd'])."'
                                         WHERE id = '".intval($_GET['id'])."'");

            $show = info(_server_admin_edited, "?admin=server");
        }
    break;
    case 'delete':
        db("DELETE FROM ".$db['server']." WHERE id = '".intval($_GET['id'])."'");
        $show = info(_server_admin_deleted, "?admin=server");
    break;
    case 'new':
        $files = get_files(basePath.'/inc/images/gameicons/',false,true,$picformat); $game = '';
        foreach ($files as $file) {
            if($file == 'unknown.gif')
                continue;

            if(preg_match("=\.gif|.jpg|.png=Uis",$file)!=FALSE)
                $game .= show(_select_field, array("value" => $file,
                                                   "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)),
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
                                               "name" => _server_name,
                                               "sgame" => $game));
    break;
    case 'add':
        if(empty($_POST['ip']) || empty($_POST['port']))
            $show = error(_empty_ip,1);
        elseif($_POST['game'] == "lazy")
            $show = error(_empty_game,1);
        elseif(empty($_POST['name']))
            $show = error(_empty_servername,1);
        else {
            db("INSERT INTO ".$db['server']." SET `ip` = '".up($_POST['ip'])."',
                                              `port` = '".intval($_POST['port'])."',
                                              `qport`= '".up($_POST['qport'])."',
                                              `name` = '".up($_POST['name'])."',
                                              `pwd`  = '".up($_POST['pwd'])."',
                                              `game` = '".up($_POST['game'])."',
                                              `status` = '".up($_POST['status'])."'");

            $show = info(_server_admin_added, "?admin=server");
        }
    break;
    default:
        $qry = db("SELECT * FROM ".$db['server']." ORDER BY id"); $show_ = '';
        while($get = _fetch($qry)) {
            $gameicon = show(_gameicon, array("icon" => re($get['game'])));
            $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                          "action" => "admin=server&amp;do=edit",
                                                          "title" => _button_title_edit));

            $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                              "action" => "admin=server&amp;do=delete",
                                                              "title" => _button_title_del,
                                                              "del" => convSpace(_confirm_del_server)));

            if(!$get['navi'])
                $menu = show(_server_menu_icon_yes, array("id" => $get['id']));
            else
                $menu = show(_server_menu_icon_no, array("id" => $get['id']));

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show_ .= show($dir."/server_show", array("gameicon" => $gameicon,
                                                      "serverip" => re($get['ip']).":".$get['port'],
                                                      "serverpwd" => re($get['pwd']),
                                                      "menu" => $menu,
                                                      "edit" => $edit,
                                                      "name" => re($get['name']),
                                                      "class" => $class,
                                                      "delete" => $delete));
        }

        $show = show($dir."/server", array("game" => _game,
                                           "serveradmin_head" => _server_admin_head,
                                           "sip" => _server_ip,
                                           "legende" => _legende,
                                           "value" => _button_value_save,
                                           "yesno" => _server_menu_icon_yesno,
                                           "legendemenu" => _server_legendemenu,
                                           "ts_port" => settings('ts_port'),
                                           "ts_ip" => re(settings('ts_ip')),
                                           "ts_sport" => settings('ts_sport'),
                                           "ts_width" => settings('ts_width'),
                                           "ts_version" => (settings('ts_version') == 3 ? ' selected="selected"' : ''),
                                           "ts_showsettings" => (settings('ts_version') == 3 ? '' : ' style="display:none;"'),
                                           "ts_checkcustomicon" => (settings('ts_customicon') == 1 ? ' selected="selected"' : ''),
                                           "ts_checkshowchannel" => (settings('ts_showchannel') == 1 ? ' selected="selected"' : ''),
                                           "ts_showchannel" => _ts_settings_showchannels,
                                           "ts_customicon" => _ts_settings_customicon,
                                           "ts_showchannel_desc" => _ts_settings_showchannels_desc,
                                           "on" => _on,
                                           "off" => _off,
                                           "name" => _server_name,
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
    break;
}
