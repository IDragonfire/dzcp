<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

if(defined('_Upload')) {
    if(permission("editsquads")) {
        $set_action = isset($_GET['id']) ? "&amp;edit=1&amp;id=".$_GET['id'] : "";
        $infos = show(_upload_usergallery_info, array("userpicsize" => config('upicsize')));
        $index = show($dir."/upload", array("uploadhead" => _upload_icons_head,
                                            "file" => _upload_file,
                                            "name" => "file",
                                            "action" => "?action=squads&amp;do=upload".$set_action,
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
            else if($size > config('upicsize')."000")
                $index = error(_upload_wrong_size, 1);
            else {
                if(move_uploaded_file($tmpname, basePath."/inc/images/gameicons/".$_FILES['file']['name'])) {
                    $link_to = isset($_GET['edit']) && isset($_GET['id']) ? "edit&id=".$_GET['id'] : "add";
                    $index = info(_info_upload_success, "../admin/?admin=squads&amp;do=".$link_to);
                }
                else
                    $index = error(_upload_error, 1);
            }
        }
    }
}