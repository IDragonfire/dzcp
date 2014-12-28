<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit();
$where = $where.': '._server_admin_head;

switch ($do) {
    case 'menu':
        $get = db("SELECT `navi`,`game`,`id` FROM `".$db['server']."` WHERE `id` = ".intval($_GET['id']).";",false,true);
        if($get['game'] != 'nope') {
            db("UPDATE `".$db['server']."` SET `navi` = ".($get['navi'] ? 0 : 1)." WHERE `id` = ".$get['id'].";");
            
            if(!mysqli_persistconns)
                $mysql->close(); //MySQL
                
            header("Location: ?admin=server");
        } else {
            $show = error(_server_isnt_live);
        }
    break;
    case 'edit':
        $get = db("SELECT * FROM `".$db['server']."` WHERE `id` = ".intval($_GET['id']).";",false,true);
        $custom_icon = '<option value="">'._custom_game_icon_none.'</option>';
        $files = get_files(basePath.'/inc/images/gameicons/custom/',false,true,$picformat);
        if(count($files) >= 1) {
            foreach($files as $file) {
                $sel = ($file == $get['custom_icon'] ? 'selected="selected"' : '');
                $custom_icon .= show(_select_field, array("value" => $file, "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)), "sel" => $sel));
            }
        }

        $show = show($dir."/server_edit", array("sip" => re($get['ip']),
                                                "sname" => re($get['name']),
                                                "id" => $_GET['id'],
                                                "sport" => $get['port'],
                                                "qport" => $get['qport'],
                                                "games" => listgames($get['game']),
                                                "spwd" => $get['pwd'],
                                                "custom_icon" => $custom_icon));

    break;
    case 'editserver':
        if(empty($_POST['ip']) || empty($_POST['port'])) {
            $show = error(_empty_ip);
        } else if(empty($_POST['name'])) {
            $show = error(_empty_servername);
        } else {
            if($_POST['status'] == "lazy") $game = "";
            else $game = "`game` = '".up($_POST['status'])."',";
            $get = db("SELECT `ip`,`port`,`game` FROM `".$db['server']."` WHERE `id` = ".intval($_GET['id']).";",false,true);
            $cache_hash = md5($get['ip'].':'.$get['port'].'_'.$get['game']);
            $cache->delete('server_'.$cache_hash);

            db("UPDATE `".$db['server']."`
                     SET `ip`         = '".up($_POST['ip'])."',
                         `port`       = ".intval($_POST['port']).",
                         `qport`      = '".up($_POST['qport'])."',
                         `name`       = '".up($_POST['name'])."',
                         `custom_icon`= '".up($_POST['custom_game_icon'])."',
                         ".$game."
                         `pwd`        = '".up($_POST['pwd'])."'
                     WHERE id = ".intval($_GET['id']).";");

            $show = info(_server_admin_edited, "?admin=server");
        }
    break;
    case 'delete':
        $get = db("SELECT `ip`,`port`,`game`,`name` FROM `".$db['server']."` WHERE `id` = ".intval($_GET['id']).";",false,true);
        $cache_hash = md5($get['ip'].':'.$get['port'].'_'.$get['game']);
        $cache->delete('server_'.$cache_hash);
        db("DELETE FROM `".$db['server']."` WHERE `id` = ".intval($_GET['id']).";");
        
        $show = info(show(_server_admin_deleted,array('host' => $get['name'])), "?admin=server");
    break;
    case 'new':
        $custom_icon = '<option value="">'._custom_game_icon_none.'</option>';
        $files = get_files(basePath.'/inc/images/gameicons/custom/',false,true,$picformat);
        if(count($files) >= 1) {
            foreach($files as $file) {
                $custom_icon .= show(_select_field, array("value" => $file, "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)), "sel" => ''));
            }
        }

        $show = show($dir."/server_add", array("games" => listgames(),"custom_icon" => $custom_icon));
    break;
    case 'add':
        if(empty($_POST['ip']) || empty($_POST['port'])) {
            $show = error(_empty_ip);
        } else if($_POST['game'] == "lazy") {
            $show = error(_empty_game);
        } else if(empty($_POST['name'])) {
            $show = error(_empty_servername);
        } else {
            db("INSERT INTO `".$db['server']."`
                     SET `ip`         = '".up($_POST['ip'])."',
                         `port`       = ".intval($_POST['port']).",
                         `qport`      = '".up($_POST['qport'])."',
                         `name`       = '".up($_POST['name'])."',
                         `pwd`        = '".up($_POST['pwd'])."',
                         `custom_icon`= '".up($_POST['custom_game_icon'])."',
                         `game`       = '".up($_POST['status'])."'");

            $show = info(_server_admin_added, "?admin=server");
        }
    break;
    default:
        $color = 0; $show_servers = '';
        $qry = db("SELECT `id`,`ip`,`port`,`pwd`,`name`,`game`,`navi`,`custom_icon` FROM `".$db['server']."` ORDER BY id;");
        while($get = _fetch($qry)) {
            $gameicon = show(_gameicon, array("icon" => 'unknown.gif'));
            if(!empty($get['custom_icon'])) {
                if(file_exists(basePath.'/inc/images/gameicons/custom/'.$get['custom_icon'])) {
                    $gameicon = show(_gameicon, array('icon' => $get['custom_icon']));
                }
            } else {
                foreach($picformat AS $end) {
                    if(file_exists(basePath.'/inc/images/gameicons/'.$get['game'].'.'.$end)) {
                        $gameicon = show(_gameicon, array('icon' => $get['game'].'.'.$end));
                        break;
                    }
                }
            }

            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=server&amp;do=edit", "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=server&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_server));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $menu = ($get['navi'] ? show(_server_menu_icon_no, array("id" => $get['id'])) : show(_server_menu_icon_yes, array("id" => $get['id'])));
            $show_servers .= show($dir."/server_show", array("gameicon" => $gameicon,
                                                             "serverip" => re($get['ip']).":".$get['port'],
                                                             "serverpwd" => re($get['pwd']),
                                                             "menu" => $menu,
                                                             "edit" => $edit,
                                                             "name" => re($get['name']),
                                                             "class" => $class,
                                                             "delete" => $delete));
        }

        if(empty($show_servers)) {
            $show_servers = show(_no_entrys_yet, array("colspan" => "4"));
        }

        $show = show($dir."/server", array("show" => $show_servers));
    break;
}
