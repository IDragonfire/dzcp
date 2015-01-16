<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(defined('_Upload')) {
    if(permission('partners')) {
        $infos = show(_upload_partners_info, array("userpicsize" => config('upicsize')));
        $index = show($dir."/upload", array("uploadhead" => _upload_partners_head,
                                            "file" => _upload_file,
                                            "name" => "file",
                                            "action" => "?action=partners&amp;do=upload",
                                            "upload" => _button_value_upload,
                                            "info" => _upload_info,
                                            "infos" => $infos));
        if($do == "upload") {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];

            if(!$tmpname)
                $index = error(_upload_no_data, 1);
            else {
                if(move_uploaded_file($tmpname, basePath."/banner/partners/".$_FILES['file']['name']))
                    $index = info(_info_upload_success, "../admin/?admin=partners&amp;do=add");
                else
                    $index = error(_upload_error, 1);
            }
        }
    }
}