<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

$where = $where.': '._slider;

switch ($do) {
    case 'new':
        $qry = db("SELECT `pos`,`bez` FROM `".$db['slideshow']."` ORDER BY `pos` ASC;"); $positions = '';
        while($get = _fetch($qry)) {
            $positions .= show(_select_field, array("value" => $get['pos']+1,
                                                    "what" => _nach.': '.re($get['bez']),
                                                    "sel" => ""));
        }

        $show = show($dir."/slideshow_form", array("id" => "",
                                                   "error" => "",
                                                   "do" => "add",
                                                   "head" => _slider_admin_add,
                                                   "value" => _button_value_add,
                                                   "tdesc" => '',
                                                   "v_bezeichnung" => "",
                                                   "v_pos_none" => "",
                                                   "v_position" => $positions,
                                                   "v_url" => "http://",
                                                   "selected" => "",
                                                   "selected_txt" => 'selected="selected"',
                                                   "v_pic" => ""));
    break;
    case 'add':
        if(empty($_FILES['bild']['tmp_name']) || empty($_POST['bez']) || empty($_POST['url']) || $_POST['url'] == "http://") {
            if(!$_FILES['bild']['tmp_name'])
                $error = _slider_admin_error_nopic;
            else if(empty($_POST['bez']))
                $error = _slider_admin_error_empty_bezeichnung;
            else if(empty($_POST['url']) || empty($_POST['url']) || $_POST['url'] == "http://")
                $error = _slider_admin_error_empty_url;

            $error = show("errors/errortable", array("error" => $error));
            $selected = (isset($_POST['target']) && $_POST['target'] ? 'selected="selected"' : '');
            $selected_txt = (isset($_POST['showbez']) && $_POST['showbez'] ? 'selected="selected"' : '');

            $qry = db("SELECT `pos`,`bez` FROM `".$db['slideshow']."` ORDER BY `pos` ASC;"); $positions = '';
            while($get = _fetch($qry)) {
                $posid = ($get['pos']+1);
                $positions .= show(_select_field, array("value" => $posid,
                        "what" => _nach.': '.re($get['bez']),
                        "sel" => (isset($_POST['position']) && $_POST['position'] == $posid ? 'selected="selected"' : '')));
            }

            $show = show($dir."/slideshow_form", array("id" => "",
                                                       "error" => $error,
                                                       "do" => "add",
                                                       "head" => _slider_admin_add,
                                                       "value" => _button_value_add,
                                                       "tdesc" => $_POST['desc'],
                                                       "v_bezeichnung" => $_POST['bez'],
                                                       "v_pos_none" => "",
                                                       "v_position" => $positions,
                                                       "v_url" => $_POST['url'],
                                                       "selected" => $selected,
                                                       "selected_txt" => $selected_txt,
                                                       "v_pic" => ""));
        } else {
            $sign = ($_POST['position'] == '1' || $_POST['position'] == '2' ? ">= " : "> ");
            db("UPDATE `".$db['slideshow']."` SET `pos` = pos+1 WHERE `pos` ".$sign." ".intval($_POST['position']));

            if(strpos($_POST['url'], 'www.') !== false)
                $_POST['url'] = links($_POST['url']);

            db("INSERT INTO `".$db['slideshow']."` SET `pos` = ".intval($_POST['position']).",
                                                       `bez` = '".up($_POST['bez'])."',
                                                       `showbez` = ".intval($_POST['showbez']).",
                                                       `desc` = '".up($_POST['desc'])."',
                                                       `url`  = '".up($_POST['url'])."',
                                                       `target` = ".intval($_POST['target'])."");


            if(isset($_FILES['bild']['tmp_name']) && !empty($_FILES['bild']['tmp_name'])) {
                $tmpname = $_FILES['bild']['tmp_name'];
                $endung = explode(".", $_FILES['bild']['name']);
                $endung = strtolower($endung[count($endung)-1]);
                @copy($tmpname, basePath."/inc/images/slideshow/"._insert_id().".".strtolower($endung));
                @unlink($tmpname);
            }

            $show = info(_slider_admin_add_done, "?admin=slideshow");
        }
    break;
    case 'edit':
        $get = db("SELECT * FROM ".$db['slideshow']." WHERE `id` = '".intval($_GET['id'])."'",false,true);

        $qrypos = db("SELECT `pos`,`bez` FROM `".$db['slideshow']."` WHERE `id` != '".intval($get['id'])."' ORDER BY `pos` ASC");
        while($getpos = _fetch($qrypos)) {
            $posid = ($getpos['pos']+1);
            $positions .= show(_select_field, array("value" => $posid,
                                                    "what" => _nach.': '.$getpos['bez'],
                                                    "sel" => ($get['position'] == $posid ? 'selected="selected"' : '')));
        }

        $selected = ($get['target'] ? 'selected="selected"' : '');
        $selected_txt = ($get['showbez'] ? 'selected="selected"' : '');

        $image = '';
        foreach($picformat as $endung) {
            if(file_exists(basePath."/inc/images/slideshow/".$get['id'].".".$endung)) {
                $image = "inc/images/slideshow/".$get['id'].".".$endung;
                break;
            }
        }

        $show = show($dir."/slideshow_form", array("id" => re($get['id']),
                                                   "error" => "",
                                                   "do" => "editdo",
                                                   "head" => _slider_admin_edit,
                                                   "value" => _button_value_edit,
                                                   "tdesc" => re($get['desc']),
                                                   "v_bezeichnung" => re($get['bez']),
                                                   "v_pos_none" => _slider_position_lazy,
                                                   "v_position" => $positions,
                                                   "v_url" => re($get['url']),
                                                   "selected" => $selected,
                                                   "selected_txt" => $selected_txt,
                                                   "v_pic" => img_size($image)."<br />"));
    break;
    case 'editdo':
        if(empty($_POST['bez']) || empty($_POST['url']) || $_POST['url'] == "http://") {
            if(empty($_POST['bez']))
                $error = _slider_admin_error_empty_bezeichnung;
            else if(empty($_POST['url']) || $_POST['url'] == "http://")
                $error = _slider_admin_error_empty_url;

            $error = show("errors/errortable", array("error" => $error));
            $selected = ($_POST['target'] ? 'selected="selected"' : '');
            $selected_txt = ($_POST['showbez'] ? 'selected="selected"' : '');

            $image = '';
            foreach($picformat as $endung) {
                if(file_exists(basePath."/inc/images/slideshow/".$_POST['id'].".".$endung)) {
                    $image = "inc/images/slideshow/".$_POST['id'].".".$endung;
                    break;
                }
            }

            $show = show($dir."/slideshow_form", array("id" => re($_POST['id']),
                                                       "error" => $error,
                                                       "do" => "editdo",
                                                       "head" => _slider_admin_edit,
                                                       "value" => _button_value_edit,
                                                       "tdesc" => $_POST['desc'],
                                                       "v_bezeichnung" => $_POST['bez'],
                                                       "v_pos_none" => _slider_position_lazy,
                                                       "v_position" => $positions,
                                                       "v_url" => $_POST['url'],
                                                       "selected" => $selected,
                                                       "selected_txt" => $selected_txt,
                                                       "v_pic" => img_size($image)."<br />"));
        } else {
            $pos = "";
            if($_POST['position'] != "lazy") {
                $sign = ($_POST['position'] == '1' || $_POST['position'] == '2' ? ">= " : "> ");
                db("UPDATE `".$db['slideshow']."` SET `pos` = pos+1 WHERE `pos` ".$sign." '".intval($_POST['position'])."'");
                $pos = " `pos` = ".intval($_POST['position']).", ";
            }

            if(strpos($_POST['url'], 'www.') !== false)
                $_POST['url'] = links($_POST['url']);

            db("UPDATE `".$db['slideshow']."` SET".$pos."
                      `bez` = '".up($_POST['bez'])."',
                      `showbez` = ".intval($_POST['showbez']).",
                      `url` = '".up($_POST['url'])."',
                      `desc` = '".up($_POST['desc'])."',
                      `target` = ".intval($_POST['target'])."
                WHERE `id` = ".intval($_POST['id']));

            if(isset($_FILES['bild']['tmp_name']) && !empty($_FILES['bild']['tmp_name'])) {
                $files = get_files(basePath."/inc/images/slideshow/",false,true,$picformat);
                foreach ($files as $file) {
                    $file_exp_minimize = explode('_minimize_',$file);
                    $file_exp = explode('.',$file);
                    if($file_exp_minimize[0] == $_POST['id'] || $file_exp[0] == $_POST['id'])
                        @unlink(basePath."/inc/images/slideshow/".$file);
                }

                $tmpname = $_FILES['bild']['tmp_name'];
                $endung = explode(".", $_FILES['bild']['name']);
                $endung = strtolower($endung[count($endung)-1]);
                @copy($tmpname, basePath."/inc/images/slideshow/"._insert_id().".".strtolower($endung));
                @unlink($tmpname);
            }

            $show = info(_slider_admin_edit_done, "?admin=slideshow");
        }
    break;
    case 'delete':
        db("DELETE FROM `".$db['slideshow']."` WHERE `id` = ".intval($_GET['id']));
        $files = get_files(basePath."/inc/images/slideshow/",false,true,$picformat);
        foreach ($files as $file) {
            $file_exp_minimize = explode('_minimize_',$file);
            $file_exp = explode('.',$file);
            if($file_exp_minimize[0] == $_GET['id'] || $file_exp[0] == $_GET['id'])
                @unlink(basePath."/inc/images/slideshow/".$file);
        }

        $show = info(_slider_admin_del_done, "?admin=slideshow");
    break;
    default:
        $qry = db("SELECT `id`,`bez` FROM `".$db['slideshow']."` ORDER BY `pos` ASC"); $entry = '';
        while($get = _fetch($qry)) {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                          "action" => "admin=slideshow&amp;do=edit",
                                                          "title" => _button_title_edit));

            $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                              "action" => "admin=slideshow&amp;do=delete",
                                                              "title" => _button_title_del,
                                                              "del" => convSpace(_slider_admin_del)));

            $entry .= show($dir."/slideshow_show", array("id" => $get['id'],
                                                         "class" => $class,
                                                         "bez" => $get['bez'],
                                                         "edit" => $edit,
                                                         "del" => $delete));
        }

        if(empty($entry))
            $entry = '<tr><td colspan="3" class="contentMainSecond">'._no_entrys.'</td></tr>';

        $show = show($dir."/slideshow", array("show" => $entry));
    break;
}