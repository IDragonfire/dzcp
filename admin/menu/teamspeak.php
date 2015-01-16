<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit();
$where = $where.': '._server_admin_head;

switch ($do) {
    case 'default_server':
        $qry = db("SELECT `id` FROM `".$db['ts']."` WHERE `default_server` = 1;");
        if(_rows($qry)) {
            while($get = _fetch($qry)) { 
                db("UPDATE `".$db['ts']."` SET `default_server` = 0 WHERE `id` = ".$get['id'].";"); 
            }
        }

        db("UPDATE `".$db['ts']."` SET `default_server` = 1 WHERE `id` = ".intval($_GET['id']).";");
        
        if(!mysqli_persistconns)
            $mysql->close(); //MySQL
        
        header("Location: ?admin=teamspeak");
    break;
    case 'menu':
        $ts3_menu = db("SELECT id FROM ".$db['ts']." WHERE `show_navi` = 1",false,true);
        $qry = db("SELECT id FROM ".$db['ts']." WHERE `show_navi` = 1");
        if(_rows($qry)) {
            while($get = _fetch($qry)) { 
                db("UPDATE `".$db['ts']."` SET `show_navi` = 0 WHERE `id` = ".$get['id'].";"); 
            }
        }
        
        if($ts3_menu['id'] != intval($_GET['id'])) {
            db("UPDATE `".$db['ts']."` SET `show_navi` = 1 WHERE `id` = ".intval($_GET['id']).";");
        }
        
        if(!mysqli_persistconns)
            $mysql->close(); //MySQL
        
        header("Location: ?admin=teamspeak");
    break;
    case 'delete':
        $get = db("SELECT `host_ip_dns`,`server_port` FROM `".$db['ts']."` WHERE `id` = ".intval($_GET['id'])." LIMIT 1",false,true);
        $ip_port = TS3Renderer::tsdns(re($get['host_ip_dns']));
        $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : $get['host_ip_dns']);
        $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : $get['server_port']);
        $cache->delete('teamspeak_'.md5($host.':'.$port));

        db("DELETE FROM `".$db['ts']."` WHERE `id` = ".intval($_GET['id']).";");
        $show = info(show(_server_admin_deleted,array('host'=>$host.':'.$port)), "?admin=teamspeak");
    break;
    case 'edit':
        $error = ''; $show = '';
        if(isset($_POST['ip']))
        {
            if(empty($_POST['ip']))
                $error = _ts_empty_ip_dns;
            else if(empty($_POST['port']))
                $error = _ts_empty_port;
            else if(empty($_POST['sport']))
                $error = _ts_empty_qport;

            if(empty($error))
            {
                if(isset($_POST['defaults']))
                {
                    $qry = db("SELECT id FROM ".$db['ts']." WHERE `default_server` = 1");
                    if(_rows($qry))
                    {
                        while($get = _fetch($qry))
                        { db("UPDATE ".$db['ts']." SET `default_server` = '0' WHERE `id` = ".$get['id'].";"); }
                    }
                }

                db("UPDATE ".$db['ts']." SET `host_ip_dns` = '".up($_POST['ip'])."',
                                                  `server_port` = '".intval($_POST['port'])."',
                                                  `query_port` = '".intval($_POST['sport'])."',
                                                    `customicon` = '".intval($_POST['customicon'])."',
                                                    `showchannel` = '".intval($_POST['showchannel'])."',
                                                  `default_server` = ".(isset($_POST['defaults']) ? '1' : '0')."
                                                  WHERE `id` = ".intval($_GET['id']).";");

                $ip_port = TS3Renderer::tsdns(up($_POST['ip']));
                $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : up($_POST['ip']));
                $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : intval($_POST['port']));
                $cache->delete('teamspeak_'.md5($host.':'.$port));
                $show = info(_config_ts_updated,"?admin=teamspeak");
            }
        }

        if(empty($show))
        {
            $get = db("SELECT * FROM ".$db['ts']." WHERE `id` = ".intval($_GET['id']).";",false,true);
            $show = show($dir."/teamspeak_edit", array('id' => intval($_GET['id']),
                                                       'error' => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
                                                       'ip' => (isset($_POST['ip']) ? $_POST['ip'] : $get['host_ip_dns']),
                                                       'port' => (isset($_POST['port']) ? $_POST['port'] : $get['server_port']),
                                                       'sport' => (isset($_POST['sport']) ? $_POST['sport'] : $get['query_port']),
                                                       'fport' => (isset($_POST['fport']) ? $_POST['fport'] : $get['file_port']),
                                                       'selected_showchannel' => (isset($_POST['showchannel']) ? 'selected="selected"' : $get['showchannel'] ? 'selected="selected"' : ''),
                                                       'checked_defaults' => (isset($_POST['defaults']) ? 'checked="checked"' : $get['default_server'] ? 'checked="checked"' : ''),
                                                       'selected_customicon' => (isset($_POST['customicon']) ? 'selected="selected"' : $get['customicon'] ? 'selected="selected"' : '')));
        }
    break;
    case 'new':
        $error = '';
        if(isset($_POST['ip']))
        {
            if(empty($_POST['ip']))
                $error = _ts_empty_ip_dns;
            else if(empty($_POST['port']))
                $error = _ts_empty_port;
            else if(empty($_POST['sport']))
                $error = _ts_empty_qport;

            if(empty($error))
            {
                if(isset($_POST['defaults']))
                {
                    $qry = db("SELECT id FROM ".$db['ts']." WHERE `default_server` = 1");
                    if(_rows($qry))
                    {
                        while($get = _fetch($qry))
                        { db("UPDATE ".$db['ts']." SET `default_server` = '0' WHERE `id` = ".$get['id'].";"); }
                    }
                }

                db("INSERT INTO ".$db['ts']." SET `host_ip_dns` = '".up($_POST['ip'])."',
                                                       `server_port` = '".intval($_POST['port'])."',
                                                       `query_port` = '".intval($_POST['sport'])."',
                                                       `customicon` = '".intval($_POST['customicon'])."',
                                                       `showchannel` = '".intval($_POST['showchannel'])."',
                                                       `default_server` = ".(isset($_POST['defaults']) ? '1' : '0').",
                                                       `show_navi` = 0");

                $show = info(_config_ts_added,"?admin=teamspeak");
            }
        }

        if(empty($show))
            $show = show($dir."/teamspeak_add", array('error' => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
                                                      'ip' => (isset($_POST['ip']) ? $_POST['ip'] : ''),
                                                      'port' => (isset($_POST['port']) ? $_POST['port'] : '9987'),
                                                      'sport' => (isset($_POST['sport']) ? $_POST['sport'] : '10011'),
                                                      'selected_showchannel' => (isset($_POST['showchannel']) ? $_POST['showchannel'] == '1' ? 'selected="selected"' : '' : ''),
                                                      'checked_defaults' => (isset($_POST['defaults']) ? 'checked="checked"' : ''),
                                                      'selected_customicon' => (isset($_POST['customicon']) ? $_POST['customicon'] == '1' ? 'selected="selected"' : '' : '')));
    break;
    default:
        $qry = db("SELECT * FROM ".$db['ts']." ORDER BY id"); $color = 1;
        while($get = _fetch($qry))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'],"action" => "admin=teamspeak&amp;do=edit","title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "admin=teamspeak&amp;do=delete","title" => _button_title_del,"del" => _confirm_del_server));

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $menu = (!$get['show_navi'] ? show(_teamspeak_menu_icon_yes, array("id" => $get['id'])) : show(_teamspeak_menu_icon_no, array("id" => $get['id'])));
            $default = ($get['default_server'] ? show(_teamspeak_default_icon_yes, array("id" => $get['id'])) : show(_teamspeak_default_icon_no, array("id" => $get['id'])));
            $show .= show($dir."/teamspeak_show", array("serverip" => cut(re($get['host_ip_dns']),26,true),
                                                        "serverport" => $get['server_port'],
                                                        "serverqport" => $get['query_port'],
                                                        "menu" => $menu,
                                                        "default" => $default,
                                                        "edit" => $edit,
                                                        "class" => $class,
                                                        "delete" => $delete));
        }

        if(empty($show))
            $show = show(_no_entrys_yet, array("colspan" => "4"));

        $show = show($dir."/teamspeak", array("show" => $show));
    break;
}
