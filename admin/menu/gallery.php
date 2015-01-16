<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;
switch ($do) {
    case 'step2':
        if(empty($_POST['gallery']))
            $show = error(_error_gallery,1);
        else {
            $addfile = '';
            for($i=1;$i<=$_POST['anzahl'];$i++) {
                $addfile .= show($dir."/form_gallery_addfile", array("file" => _gallery_image, "i" => $i));
            }

            db("INSERT INTO ".$db['gallery']." SET `kat` = '".up($_POST['gallery'])."',
                                                   `intern`   = '".intval($_POST['intern'])."',
                                                   `beschreibung`   = '".up($_POST['beschreibung'])."',
                                                   `datum`          = '".time()."'");

            $show = show($dir."/form_gallery_step2", array("head" => _gallery_admin_head,
                                                           "what" => re($_POST['gallery']),
                                                           "addfile" => $addfile,
                                                           "id" => _insert_id(),
                                                           "do" => "add",
                                                           "dowhat" => _button_value_add,
                                                           "anzahl" => $_POST['anzahl'],
                                                           "gal" => _subgallery_head));
        }
    break;
    case 'add':
        $galid = $_GET['id'];
        $anzahl = $_POST['anzahl'];
        for($i=1;$i<=$anzahl;$i++) {
            $tmp = $_FILES['file'.$i]['tmp_name'];
            $type = $_FILES['file'.$i]['type'];
            $end = explode(".", $_FILES['file'.$i]['name']);
            $end = $end[count($end)-1];
            $imginfo = getimagesize($tmp);

            if($_FILES['file'.$i]) {
                if(($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg" || $type == "image/png") && $imginfo[0])
                    move_uploaded_file($tmp, basePath."/gallery/images/".$galid."_".str_pad($i, 3, '0', STR_PAD_LEFT).".".strtolower($end));
            }
        }

        $show = info(_gallery_added, "?admin=gallery");
    break;
    case 'delgal':
        db("DELETE FROM ".$db['gallery']." WHERE id = '".intval($_GET['id'])."'");
        $files = get_files(basePath."/gallery/images/",false,true,$picformat);
        foreach ($files as $file) {
            if(preg_match("#".$_GET['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                $res = preg_match("#".$_GET['id']."_(.*)#",$file,$match);
                if(file_exists(basePath."/gallery/images/".$_GET['id']."_".$match[1]))
                    @unlink(basePath."/gallery/images/".$_GET['id']."_".$match[1]);
            }
        }

        $show = info(_gallery_deleted, "?admin=gallery");
    break;
    case 'delete':
        $pic = $_GET['pic'];
        $file_d = explode('.',$pic);
        $files = get_files(basePath."/gallery/images/",false,true,$picformat);
        foreach ($files as $file) {
            $file_exp_minimize = explode('_minimize_',$file);
            $file_exp = explode('.',$file);
            if($file_exp_minimize[0] == $file_d[0] || $file_exp[0] == $file_d[0])
                @unlink(basePath."/gallery/images/".$file);
        }

        $res = preg_match("#(.*)_(.*?).(gif|GIF|JPG|jpg|JPEG|jpeg|png)#",$pic,$pid);
        $show = info(_gallery_pic_deleted, "../gallery/?action=show&amp;id=".$pid[1]."");
    break;
    case 'edit':
        $get = db("SELECT * FROM ".$db['gallery']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $show = show($dir."/form_gallery_edit", array("head" => _gallery_admin_edit,
                                                      "gallery" => _gallery_gallery,
                                                      "intern" => _internal,
                                                      "beschr" => _beschreibung,
                                                      "value" => _button_value_edit,
                                                      "id" => $get['id'],
                                                      "e_gal" => re($get['kat']),
                                                      "e_intern" => $get['intern'] ? 'checked="checked"' : '',
                                                      "e_beschr" => re($get['beschreibung'])));
    break;
    case 'editgallery':
        db("UPDATE ".$db['gallery']." SET `kat` = '".up($_POST['gallery'])."',
                                          `intern` = '".intval($_POST['intern'])."',
                                          `beschreibung` = '".up($_POST['beschreibung'])."'
                                      WHERE id = '".intval($_GET['id'])."'");

        $show = info(_gallery_edited, "?admin=gallery");
    break;
    case 'new':
        $get = db("SELECT * FROM ".$db['gallery']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $option = '';
        for($i=1;$i<=100;$i++) {
            $option .= "<option value=\"".$i."\">".$i."</option>";
        }

        $show = show($dir."/form_gallery_new", array("head" => _gallery_admin_edit,
                                                     "count" => _gallery_count_new,
                                                     "gallery" => _subgallery_head,
                                                     "value" => _error_fwd,
                                                     "gal" => re($get['kat']),
                                                     "id" => $get['id'],
                                                     "option" => $option));
    break;
    case 'editstep2':
        $get = db("SELECT * FROM ".$db['gallery']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $addfile = '';
        for($i=1;$i<=$_POST['anzahl'];$i++) {
            $addfile .= show($dir."/form_gallery_addfile", array("file" => _gallery_image, "i" => $i));
        }

        $show = show($dir."/form_gallery_step2", array("head" => _gallery_admin_edit,
                                                       "what" => re($get['kat']),
                                                       "do" => "editpics",
                                                       "addfile" => $addfile,
                                                       "id" => $get['id'],
                                                       "dowhat" => _button_value_edit,
                                                       "anzahl" => $_POST['anzahl'],
                                                       "gal" => _subgallery_head));
    break;
    case 'editpics':
        $galid = $_GET['id'];
        $anzahl = $_POST['anzahl'];
        $files = get_files(basePath."/gallery/images/",false,true,$picformat); $cnt = 0;
        foreach ($files as $file) {
            if(preg_match("#".$galid."_(.*?).(gif|GIF|JPG|jpg|JPEG|jpeg|png)#",$file)!=FALSE)
                $cnt++;
        }

        for($i=1;$i<=$anzahl;$i++) {
            $tmp = $_FILES['file'.$i]['tmp_name'];
            $type = $_FILES['file'.$i]['type'];
            $end = explode(".", $_FILES['file'.$i]['name']);
            $end = $end[count($end)-1];
            $imginfo = getimagesize($tmp);

            if($_FILES['file'.$i]) {
                if(($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg" || $type == "image/png") && $imginfo[0])
                    move_uploaded_file($tmp, basePath."/gallery/images/".$galid."_".str_pad($i+$cnt, 3, '0', STR_PAD_LEFT).".".strtolower($end));
            }
        }

        $show = info(_gallery_new, "?admin=gallery");
    break;
    case 'addnew':
        $option ='';
        for($i=1;$i<=100;$i++) {
            $option .= "<option value=\"".$i."\">".$i."</option>";
        }

        $show = show($dir."/form_gallery", array("head" => _gallery_admin_head,
                                                 "gallery" => _gallery_gallery,
                                                 "intern" => _internal,
                                                 "beschr" => _beschreibung,
                                                 "value" => _error_fwd,
                                                 "count" => _gallery_count,
                                                 "option" => $option));
    break;
    default:
        $qry = db("SELECT * FROM ".$db['gallery']." ORDER BY id DESC");
        while($get = _fetch($qry))
        {
            $files = get_files(basePath."/gallery/images/",false,true,$picformat,false,array(),'minimize'); $cnt = 0;
            foreach ($files as $file) {
                if(preg_match("#^".$get['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!=FALSE)
                    $cnt++;
            }

            $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                          "action" => "admin=gallery&amp;do=edit",
                                                          "title" => _button_title_edit));

            $del = show("page/button_delete_single", array("id" => $get['id'],
                                                           "action" => "admin=gallery&amp;do=delgal",
                                                           "title" => _button_title_del,
                                                           "del" => convSpace(_confirm_del_gallery)));

            $new = show(_gal_newicon, array("id" => $get['id'], "titel" => _button_value_newgal));
            $cntpics = $cnt == 1 ? _gallery_image : _gallery_images;

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/gallery_show", array("link" => re($get['kat']),
                                                     "class" => $class,
                                                     "del" => $del,
                                                     "edit" => $edit,
                                                     "new" => $new,
                                                     "images" => $cntpics,
                                                     "id" => $get['id'],
                                                     "beschreibung" => bbcode($get['beschreibung']),
                                                     "cnt" => $cnt));
        }

        if(empty($show))
            $show = '<tr><td class="contentMainSecond">'._no_entrys.'</td></tr>';

        $show = show($dir."/gallery",array("show" => $show,
                                           "head" => _gallery_head,
                                           "add" => _gallery_show_admin));
    break;
}