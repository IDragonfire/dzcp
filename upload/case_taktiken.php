<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

if(defined('_Upload')) {
    if(permission("edittactics")) {
        $infos = show(_upload_usergallery_info, array("userpicsize" => 100));
        $index = show($dir."/upload", array("uploadhead" => _upload_taktiken_head,
                                            "file" => _upload_file,
                                            "name" => "file",
                                            "action" => "?action=taktiken&amp;do=upload",
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
            else if($size > 1000000)
                $index = error(_upload_wrong_size, 1);
            else {
                if(move_uploaded_file($tmpname, basePath."/inc/images/uploads/taktiken/".$_FILES['file']['name']))
                    $index = info(_info_upload_success, "../taktik/");
                else
                    $index = error(_upload_error, 1);
            }
        }
    }
}