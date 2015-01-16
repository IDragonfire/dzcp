<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(defined('_Upload')) {
    if(permission('news') || permission('artikel')) {
        if(isset($_GET['edit']))
            $action = "?action=newskats&amp;do=upload&edit=".$_GET['edit']."";
        else
            $action = "?action=newskats&amp;do=upload";

        $infos = show(_upload_usergallery_info, array("userpicsize" => config('upicsize')));
        $index = show($dir."/upload", array("uploadhead" => _upload_newskats_head,
                                            "file" => _upload_file,
                                            "name" => "file",
                                            "action" => $action,
                                            "upload" => _button_value_upload,
                                            "info" => _upload_info,
                                            "infos" => "-"));

        if($do == "upload") {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];

            if(!$tmpname)
                $index = error(_upload_no_data, 1);
            else if($size > config('upicsize')."000")
                $index = error(_upload_wrong_size, 1);
            else {
                if(move_uploaded_file($tmpname, basePath."/inc/images/newskat/".$_FILES['file']['name'])) {
                    if(isset($_GET['edit']))
                        $index = info(_info_upload_success, "../admin/?admin=news&amp;do=edit&amp;id=".$_GET['edit']."");
                    else
                        $index = info(_info_upload_success, "../admin/?admin=news&amp;do=add");
                }
                else
                    $index = error(_upload_error, 1);
            }
        }
    }
}