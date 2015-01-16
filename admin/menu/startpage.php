<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

switch ($do) {
    case 'delete':
        db("DELETE FROM ".$db['startpage']." WHERE id = '".intval($_GET['id'])."'");
        $show = info(_admin_startpage_deleted, "?index=admin&amp;admin=startpage");
    break;
    case 'edit':
        $get = db("SELECT * FROM `".$db['startpage']."` WHERE id = '".intval($_GET['id'])."'",false,true); $error = '';
        if(isset($_POST['name']) && isset($_POST['url']) && isset($_POST['level']))
        {
            if(empty($_POST['name']))
                $error = _admin_startpage_no_name;
            else if(empty($_POST['url']))
                $error = _admin_startpage_no_url;
            else
            {
                db("UPDATE `".$db['startpage']."` SET `name` = '".up($_POST['name'])."', `url` = '".up($_POST['url'])."', `level` = '".intval($_POST['level'])."' WHERE id = '".intval($_GET['id'])."'");
                $show = info(_admin_startpage_editd, "?index=admin&amp;admin=startpage");
            }
        }

        if(empty($show)) {
            $selu = $get['level'] == 1 ? 'selected="selected"' : '';
            $selt = $get['level'] == 2 ? 'selected="selected"' : '';
            $selm = $get['level'] == 3 ? 'selected="selected"' : '';
            $sela = $get['level'] == 4 ? 'selected="selected"' : '';
            $elevel = show(_elevel_startpage_select, array("selu" => $selu,
                                                           "selt" => $selt,
                                                           "selm" => $selm,
                                                           "sela" => $sela,
                                                           "ruser" => _status_user,
                                                           "trial" => _status_trial,
                                                           "member" => _status_member,
                                                           "admin" => _status_admin));
            
            $show = show($dir."/startpage_form", array("head" => _admin_startpage_edit,
                                                        "do" => "edit&amp;id=".$_GET['id'],
                                                        "name" => (isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : re($get['name'])),
                                                        "url" => (isset($_POST['url']) ? $_POST['url'] : re($get['url'])),
                                                        "level" => $elevel,
                                                        "what" => _button_value_edit,
                                                        "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : "")));
        }
    break;
    case 'new':
        $error = '';
        if(isset($_POST['name']) && isset($_POST['url']) && isset($_POST['level'])) {
            if(empty($_POST['name']))
                $error = _admin_startpage_no_name;
            else if(empty($_POST['url']))
                $error = _admin_startpage_no_url;
            else {
                db("INSERT INTO `".$db['startpage']."` SET `name` = '".up($_POST['name'])."', `url` = '".up($_POST['url'])."', `level` = '".intval($_POST['level'])."'");
                $show = info(_admin_startpage_added, "?index=admin&amp;admin=startpage");
            }
        }

        if(empty($show)) {
            $elevel = show(_elevel_startpage_select, array("selu" => '',
                                                           "selt" => '',
                                                           "selm" => '',
                                                           "sela" => '',
                                                           "ruser" => _status_user,
                                                           "trial" => _status_trial,
                                                           "member" => _status_member,
                                                           "admin" => _status_admin));
            
            $show = show($dir."/startpage_form", array("head" => _admin_startpage_add_head, "do" => "new", "name" => (isset($_POST['name']) ? $_POST['name'] : ''),
            "url" => (isset($_POST['url']) ? $_POST['url'] : ''), "level" => $elevel, "what" => _button_value_add, "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : "")));
        }
    break;
    default:
        $sql = db("SELECT * FROM `".$db['startpage']."`;"); $color = 0; $show = '';
        while($get = _fetch($sql))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=startpage&amp;do=edit", "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=startpage&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_entry));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/startpage_show", array("edit" => $edit, "name" => re($get['name']), "url" => re($get['url']), "class" => $class, "delete" => $delete));
        }

        if(empty($show))
            $show = show(_no_entrys_yet, array("colspan" => "4"));

        $show = show($dir."/startpage", array("show" => $show, "add" => _dl_new_head, "edit" => _editicon_blank, "delete" => _deleteicon_blank));
    break;
}