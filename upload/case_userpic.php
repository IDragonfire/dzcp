<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(defined('_Upload')) {
    if($chkMe >= 1 && $userid) {
        switch($do) {
            case 'upload':
                $tmpname = $_FILES['file']['tmp_name'];
                $name = $_FILES['file']['name'];
                $type = $_FILES['file']['type'];
                $size = $_FILES['file']['size'];

                $endung = explode(".", $_FILES['file']['name']);
                $endung = strtolower($endung[count($endung)-1]);

                if(!$tmpname)
                    $index = error(_upload_no_data, 1);
                else if($size > config('upicsize')."000")
                    $index = error(_upload_wrong_size, 1);
                else {
                    foreach($picformat as $tmpendung) {
                        if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$tmpendung))
                            @unlink(basePath."/inc/images/uploads/userpics/".$userid.".".$tmpendung);
                    }

                    if(move_uploaded_file($tmpname, basePath."/inc/images/uploads/userpics/".$userid.".".strtolower($endung)))
                        $index = info(_info_upload_success, "../user/?action=editprofile");
                    else
                        $index = error(_upload_error, 1);
                }
            break;
            case 'deletepic':
                foreach($picformat as $tmpendung) {
                    if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$tmpendung))
                        @unlink(basePath."/inc/images/uploads/userpics/".$userid.".".$tmpendung);
                }

                $index = info(_delete_pic_successful, "../user/?action=editprofile");
            break;
            default:
                $infos = show(_upload_userpic_info, array("userpicsize" => config('upicsize')));
                $index = show($dir."/upload", array("uploadhead" => _upload_head,
                                                    "file" => _upload_file,
                                                    "name" => "file",
                                                    "action" => "?action=userpic&amp;do=upload",
                                                    "upload" => _button_value_upload,
                                                    "info" => _upload_info,
                                                    "infos" => $infos));
            break;
        }
    }
}