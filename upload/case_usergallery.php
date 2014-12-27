<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_Upload')) {
    if($chkMe >= 1) {
        switch ($do) {
            case 'upload':
                $tmpname = $_FILES['file']['tmp_name'];
                $name = $_FILES['file']['name'];
                $type = $_FILES['file']['type'];
                $size = $_FILES['file']['size'];

                if(!$tmpname)
                    $index = error(_upload_no_data, 1);
                elseif($size > config('upicsize')."000")
                    $index = error(_upload_wrong_size, 1);
                elseif(cnt($db['usergallery'], " WHERE user = ".$userid) == config('m_gallerypics'))
                    $index = error(_upload_over_limit, 2);
                elseif(file_exists(basePath."/inc/images/uploads/usergallery/".$userid."_".$_FILES['file']['name']))
                    $index = error(_upload_file_exists, 1);
                else {
                    if(move_uploaded_file($tmpname, basePath."/inc/images/uploads/usergallery/".$userid."_".strtolower($_FILES['file']['name']))) {
                        db("INSERT INTO ".$db['usergallery']."
                            SET `user`         = '".intval($userid)."',
                                `beschreibung` = '".up($_POST['beschreibung'])."',
                                `pic`          = '".up(strtolower($_FILES['file']['name']))."'");

                        $index = info(_info_upload_success, "../user/?action=editprofile&show=gallery");
                    } else
                        $index = error(_upload_error, 1);
                }
            break;
            case 'edit':
                $get = db("SELECT id,user,pic,beschreibung FROM ".$db['usergallery']." WHERE id = '".intval($_GET['gid'])."'",false,true);
                if($get['user'] == $userid) {
                    $infos = show(_upload_usergallery_info, array("userpicsize" => config('upicsize')));
                    $index = show($dir."/usergallery_edit", array("uploadhead" => _upload_head_usergallery,
                                                                  "file" => _upload_file,
                                                                  "showpic" => img_size("inc/images/uploads/usergallery/".$get['user']."_".$get['pic']),
                                                                  "id" => $get['id'],
                                                                  "showbeschreibung" => re($get['beschreibung']),
                                                                  "name" => "file",
                                                                  "upload" => _button_value_edit,
                                                                  "beschreibung" => _upload_beschreibung,
                                                                  "info" => _upload_info,
                                                                  "infos" => $infos));
                }
                else
                    $index = error(_error_wrong_permissions, 1);
            break;
            case 'editfile':
                $tmpname = $_FILES['file']['tmp_name'];
                $name = $_FILES['file']['name'];
                $type = $_FILES['file']['type'];
                $size = $_FILES['file']['size'];

                $endung = explode(".", $_FILES['file']['name']);
                $endung = strtolower($endung[count($endung)-1]);

                $get = db("SELECT pic FROM ".$db['usergallery']." WHERE id = ".intval($_POST['id']),false,true); $pic = '';
                if(!empty($_FILES['file']['size'])) {
                    if(file_exists(basePath."/inc/images/uploads/usergallery/".$userid."_".$get['pic']))
                        @unlink(basePath."/inc/images/uploads/usergallery/".$userid."_".$get['pic']);

                    @unlink(show(_gallery_edit_unlink, array("img" => $get['pic'], "user" => $userid)));
                    if(!move_uploaded_file($tmpname, basePath."/inc/images/uploads/usergallery/".$userid."_".$_FILES['file']['name'])) {
                        $index = error(_upload_error, 1);
                        break;
                    }

                    if(empty($index))
                        $pic = "`pic` = '".$_FILES['file']['name']."',";
                }

                if(empty($index)) {
                    db("UPDATE ".$db['usergallery']."
                        SET ".$pic."`beschreibung` = '".up($_POST['beschreibung'])."'
                        WHERE id = '".intval($_POST['id'])."'
                        AND `user` = '".intval($userid)."'");

                    $index = info(_edit_gallery_done, "../user/?action=editprofile&show=gallery");
                }
            break;
            default:
                $infos = show(_upload_usergallery_info, array("userpicsize" => config('upicsize')));
                $index = show($dir."/usergallery", array("uploadhead" => _upload_head_usergallery,
                                                         "file" => _upload_file,
                                                         "name" => "file",
                                                         "upload" => _button_value_upload,
                                                         "beschreibung" => _upload_beschreibung,
                                                         "info" => _upload_info,
                                                         "infos" => $infos));
            break;
        }
    }
}