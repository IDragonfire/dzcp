<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath . "/inc/config.php");
include(basePath . "/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_upload;
$title = $pagetitle . " - " . $where . "";
$dir   = "upload";
## SECTIONS ##
if (!isset($_GET['action']))
    $action = "";
else
    $action = $_GET['action'];

switch ($action):
    default:
        if (!permission("editsquads")) {
            $index = error(_error_wrong_permissions, 1);
        } else {
            $infos = show(_upload_usergallery_info, array(
                "userpicsize" => $upicsize
            ));
            
            $index = show($dir . "/upload", array(
                "uploadhead" => _upload_icons_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=upload",
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => $infos
            ));
        }
        break;
    case 'partners';
        if ($chkMe == 4) {
            if (!permission("editserver")) {
                $index = error(_error_wrong_permissions, 1);
            } else {
                $infos = show(_upload_partners_info, array(
                    "userpicsize" => $upicsize
                ));
                
                $index = show($dir . "/upload", array(
                    "uploadhead" => _upload_partners_head,
                    "file" => _upload_file,
                    "name" => "file",
                    "action" => "?action=partners&amp;do=upload",
                    "upload" => _button_value_upload,
                    "info" => _upload_info,
                    "infos" => $infos
                ));
                if ($_GET['do'] == "upload") {
                    $tmpname = $_FILES['file']['tmp_name'];
                    $name    = $_FILES['file']['name'];
                    $type    = $_FILES['file']['type'];
                    $size    = $_FILES['file']['size'];
                    
                    
                    if (!$tmpname) {
                        $index = error(_upload_no_data, 1);
                    } else {
                        copy($tmpname, basePath . "/banner/partners/" . $_FILES['file']['name'] . "");
                        @unlink($_FILES['file']['tmp_name']);
                        
                        $index = info(_info_upload_success, "../admin/?admin=partners&amp;do=add");
                    }
                }
            }
        } else {
            $index = error(_error_wrong_permissions, 1);
        }
        break;
    case 'upload';
        if (!permission("editsquads")) {
            $show = error(_error_wrong_permissions, 1);
        } else {
            $tmpname = $_FILES['file']['tmp_name'];
            $name    = $_FILES['file']['name'];
            $type    = $_FILES['file']['type'];
            $size    = $_FILES['file']['size'];
            
            
            if (!$tmpname) {
                $index = error(_upload_no_data, 1);
            } elseif ($size > $upicsize . "000") {
                $index = error(_upload_wrong_size, 1);
            } else {
                copy($tmpname, basePath . "/inc/images/gameicons/" . $_FILES['file']['name'] . "");
                @unlink($_FILES['file']['tmp_name']);
                
                $index = info(_info_upload_success, "../admin/?admin=squads&amp;do=add");
            }
        }
        break;
    case 'newskats';
        if (permission('news') || permission('artikel')) {
            $infos = show(_upload_usergallery_info, array(
                "userpicsize" => $upicsize
            ));
            
            if (isset($_GET['edit']))
                $action = "?action=newskats&amp;do=upload&edit=" . $_GET['edit'] . "";
            else
                $action = "?action=newskats&amp;do=upload";
            
            $index = show($dir . "/upload", array(
                "uploadhead" => _upload_newskats_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => $action,
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => "-"
            ));
            if ($_GET['do'] == "upload") {
                $tmpname   = $_FILES['file']['tmp_name'];
                $name      = $_FILES['file']['name'];
                $type      = $_FILES['file']['type'];
                $size      = $_FILES['file']['size'];
                $imageinfo = @getimagesize($tmpname);
                
                if (!$tmpname) {
                    $index = error(_upload_no_data, 1);
                } elseif ($size > $upicsize . "000") {
                    $index = error(_upload_wrong_size, 1);
                } else {
                    copy($tmpname, basePath . "/inc/images/newskat/" . $_FILES['file']['name'] . "");
                    @unlink($_FILES['file']['tmp_name']);
                    
                    if (isset($_GET['edit']))
                        $index = info(_info_upload_success, "../admin/?admin=news&amp;do=edit&amp;id=" . $_GET['edit'] . "");
                    else
                        $index = info(_info_upload_success, "../admin/?admin=news&amp;do=add");
                }
            }
        } else {
            $index = error(_error_wrong_permissions, 1);
        }
        break;
    case 'taktiken';
        if (!permission("edittactics")) {
            $index = error(_error_wrong_permissions, 1);
        } else {
            $infos = show(_upload_usergallery_info, array(
                "userpicsize" => 100
            ));
            
            $index = show($dir . "/upload", array(
                "uploadhead" => _upload_taktiken_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=taktiken&amp;do=upload",
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => $infos
            ));
            
            if ($_GET['do'] == "upload") {
                $tmpname = $_FILES['file']['tmp_name'];
                $name    = $_FILES['file']['name'];
                $type    = $_FILES['file']['type'];
                $size    = $_FILES['file']['size'];
                
                
                if (!$tmpname) {
                    $index = error(_upload_no_data, 1);
                } elseif ($size > 1000000) {
                    $index = error(_upload_wrong_size, 1);
                } else {
                    copy($tmpname, basePath . "/inc/images/uploads/taktiken/" . $_FILES['file']['name'] . "");
                    @unlink($_FILES['file']['tmp_name']);
                    
                    $index = info(_info_upload_success, "../taktik/");
                }
            }
        }
        break;
    case 'userpic';
        if ($chkMe != 'unlogged') {
            $infos = show(_upload_userpic_info, array(
                "userpicsize" => $upicsize
            ));
            
            $index = show($dir . "/upload", array(
                "uploadhead" => _upload_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=userpic&amp;do=upload",
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => $infos
            ));
            if ($_GET['do'] == "upload") {
                $tmpname = $_FILES['file']['tmp_name'];
                $name    = $_FILES['file']['name'];
                $type    = $_FILES['file']['type'];
                $size    = $_FILES['file']['size'];
                
                
                $endung = explode(".", $_FILES['file']['name']);
                $endung = strtolower($endung[count($endung) - 1]);
                
                if (!$tmpname) {
                    $index = error(_upload_no_data, 1);
                } elseif ($size > $upicsize . "000") {
                    $index = error(_upload_wrong_size, 1);
                } else {
                    foreach ($picformat as $tmpendung) {
                        if (file_exists(basePath . "/inc/images/uploads/userpics/" . $userid . "." . $tmpendung)) {
                            @unlink(basePath . "/inc/images/uploads/userpics/" . $userid . "." . $tmpendung);
                        }
                    }
                    copy($tmpname, basePath . "/inc/images/uploads/userpics/" . $userid . "." . strtolower($endung) . "");
                    @unlink($_FILES['file']['tmp_name']);
                    
                    $index = info(_info_upload_success, "../user/?action=editprofile");
                }
            } elseif ($_GET['do'] == "deletepic") {
                foreach ($picformat as $tmpendung) {
                    if (file_exists(basePath . "/inc/images/uploads/userpics/" . $userid . "." . $tmpendung)) {
                        @unlink(basePath . "/inc/images/uploads/userpics/" . $userid . "." . $tmpendung);
                        $index = info(_delete_pic_successful, "../user/?action=editprofile");
                    }
                }
            }
        } else {
            $index = error(_error_wrong_permissions, 1);
        }
        break;
    case 'avatar';
        if ($chkMe != 'unlogged') {
            $infos = show(_upload_userava_info, array(
                "userpicsize" => $upicsize
            ));
            
            $index = show($dir . "/upload", array(
                "uploadhead" => _upload_ava_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=avatar&amp;do=upload",
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => $infos
            ));
            if ($_GET['do'] == "upload") {
                $tmpname = $_FILES['file']['tmp_name'];
                $name    = $_FILES['file']['name'];
                $type    = $_FILES['file']['type'];
                $size    = $_FILES['file']['size'];
                
                
                $endung = explode(".", $_FILES['file']['name']);
                $endung = strtolower($endung[count($endung) - 1]);
                
                if (!$tmpname) {
                    $index = error(_upload_no_data, 1);
                } elseif ($size > $upicsize . "000") {
                    $index = error(_upload_wrong_size, 1);
                } else {
                    foreach ($picformat as $tmpendung) {
                        if (file_exists(basePath . "/inc/images/uploads/useravatare/" . $userid . "." . $tmpendung)) {
                            @unlink(basePath . "/inc/images/uploads/useravatare/" . $userid . "." . $tmpendung);
                        }
                    }
                    copy($tmpname, basePath . "/inc/images/uploads/useravatare/" . $userid . "." . strtolower($endung));
                    @unlink($_FILES['file']['tmp_name']);
                    
                    $index = info(_info_upload_success, "../user/?action=editprofile");
                }
            } elseif ($_GET['do'] == "delete") {
                foreach ($picformat as $tmpendung) {
                    if (file_exists(basePath . "/inc/images/uploads/useravatare/" . $userid . "." . $tmpendung)) {
                        @unlink(basePath . "/inc/images/uploads/useravatare/" . $userid . "." . $tmpendung);
                        $index = info(_delete_pic_successful, "../user/?action=editprofile");
                    }
                }
            }
        } else {
            $index = error(_error_wrong_permissions, 1);
        }
        break;
    case 'usergallery';
        if ($chkMe != 'unlogged') {
            $infos = show(_upload_usergallery_info, array(
                "userpicsize" => $upicsize
            ));
            
            $index = show($dir . "/usergallery", array(
                "uploadhead" => _upload_head_usergallery,
                "file" => _upload_file,
                "name" => "file",
                "upload" => _button_value_upload,
                "beschreibung" => _upload_beschreibung,
                "info" => _upload_info,
                "infos" => $infos
            ));
            
            if ($_GET['do'] == "upload") {
                $tmpname = $_FILES['file']['tmp_name'];
                $name    = $_FILES['file']['name'];
                $type    = $_FILES['file']['type'];
                $size    = $_FILES['file']['size'];
                
                
                if (!$tmpname) {
                    $index = error(_upload_no_data, 1);
                } elseif ($size > $upicsize . "000") {
                    $index = error(_upload_wrong_size, 1);
                } elseif (cnt($db['usergallery'], " WHERE user = " . $userid) == $maxgallerypics) {
                    $index = error(_upload_over_limit, 2);
                } elseif (file_exists(basePath . "/inc/images/uploads/usergallery/" . $userid . "_" . $_FILES['file']['name'])) {
                    $index = error(_upload_file_exists, 1);
                } else {
                    copy($tmpname, basePath . "/inc/images/uploads/usergallery/" . $userid . "_" . $_FILES['file']['name']);
                    @unlink($_FILES['file']['tmp_name']);
                    
                    $qry = db("INSERT INTO " . $db['usergallery'] . "
                   SET `user`         = '" . ((int) $userid) . "',
                       `beschreibung` = '" . up($_POST['beschreibung'], 1) . "',
                       `pic`          = '" . up($_FILES['file']['name']) . "'");
                    
                    $index = info(_info_upload_success, "../user/?action=editprofile&show=gallery");
                }
            } elseif ($_GET['do'] == "edit") {
                $qry = db("SELECT * FROM " . $db['usergallery'] . "
                 WHERE id = '" . intval($_GET['gid']) . "'");
                $get = _fetch($qry);
                
                if ($get['user'] == $userid) {
                    $infos = show(_upload_usergallery_info, array(
                        "userpicsize" => $upicsize
                    ));
                    
                    $index = show($dir . "/usergallery_edit", array(
                        "uploadhead" => _upload_head_usergallery,
                        "file" => _upload_file,
                        "showpic" => img_size("inc/images/uploads/usergallery/" . $get['user'] . "_" . $get['pic']),
                        "id" => $_GET['gid'],
                        "showbeschreibung" => re($get['beschreibung']),
                        "name" => "file",
                        "upload" => _button_value_edit,
                        "beschreibung" => _upload_beschreibung,
                        "info" => _upload_info,
                        "infos" => $infos
                    ));
                } else {
                    $index = error(_error_wrong_permissions, 1);
                }
            } elseif ($_GET['do'] == "editfile") {
                $tmpname = $_FILES['file']['tmp_name'];
                $name    = $_FILES['file']['name'];
                $type    = $_FILES['file']['type'];
                $size    = $_FILES['file']['size'];
                
                $endung = explode(".", $_FILES['file']['name']);
                $endung = strtolower($endung[count($endung) - 1]);
                
                $qry = db("SELECT pic FROM " . $db['usergallery'] . "
                 WHERE id = '" . intval($_POST['id']) . "'");
                $get = _fetch($qry);
                
                if (!empty($_FILES['file']['size'])) {
                    $unlinkgallery = show(_gallery_edit_unlink, array(
                        "img" => $get['pic'],
                        "user" => $userid
                    ));
                    @unlink($unlinkgallery);
                    
                    copy($tmpname, basePath . "/inc/images/uploads/usergallery/" . $userid . "_" . $_FILES['file']['name']);
                    @unlink($_FILES['file']['tmp_name']);
                    
                    $pic = "`pic` = '" . $_FILES['file']['name'] . "',";
                }
                
                $qry = db("UPDATE " . $db['usergallery'] . "
                 SET " . $pic . "
                     `beschreibung` = '" . up($_POST['beschreibung'], 1) . "'
                 WHERE id = '" . intval($_POST['id']) . "'
                 AND `user` = '" . ((int) $userid) . "'");
                
                $index = info(_edit_gallery_done, "../user/?action=editprofile&show=gallery");
            }
        } else {
            $index = error(_error_wrong_permissions, 1);
        }
        break;
endswitch;
## SETTINGS ##
$time_end = generatetime();
$time     = round($time_end - $time_start, 4);
page($index, $title, $where, $time);
## OUTPUT BUFFER END ##
gz_output();
?>