<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;
$where = $where.': '._member_admin_header;

switch ($do) {
    case "add":
        $qrynav = db("SELECT s2.*, s1.name AS katname, s1.placeholder
                      FROM ".$db['navi_kats']."
                      AS s1 LEFT JOIN ".$db['navi']."
                      AS s2 ON s1.`placeholder` = s2.`kat`
                      ORDER BY s1.name, s2.pos");

        $navigation = ''; $thiskat = '';
        while($getnav = _fetch($qrynav)) {
            if($thiskat != $getnav['kat']) {
                $navigation .= '<option class="dropdownKat" value="lazy">'.re($getnav['katname']).'</option>
                                <option value="'.re($getnav['placeholder']).'-1">-> '._admin_first.'</option>';
            }

            $thiskat = $getnav['kat'];
            $navigation .= empty($getnav['name']) ? '' : '<option value="'.re($getnav['placeholder']).'-'.($getnav['pos']+1).'">'._nach.' -> '.navi_name(re($getnav['name'])).'</option>';
        }

        $qry = db("SELECT * FROM ".$db['squads']." ORDER BY pos");
        $positions = '';
        while($get = _fetch($qry)) {
            $positions .= show(_select_field, array("value" => $get['pos']+1,
                                                    "sel" => "",
                                                    "what" => _nach.' '.re($get['name'])));
        }

        $files = get_files('../inc/images/gameicons/',false,true,array('gif','png','jpg'));
        $gameicons = '';
        foreach ($files as $file) {
            $gameicons .= show(_select_field, array("value" => $file,
                                                    "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)),
                                                    "sel" => ""));
        }

        $show = show($dir."/squads_add", array("memberadminaddheader" => _member_admin_add_header,
                                               "squad" => _member_admin_squad,
                                               "pos" => _position,
                                               "value" => _button_value_add,
                                               "icon" => _member_admin_icon,
                                               "info" => _admin_squad_show_info,
                                               "status" => _status,
                                               "aktiv"  => _sq_aktiv,
                                               "inaktiv" => _sq_inaktiv,
                                               "logo" => _team_logo,
                                               "banner" => _sq_banner,
                                               "desc" => _dl_besch,
                                               "sstatus" => _sq_sstatus,
                                               "cstatus" => "",
                                               "navi" => _admin_squads_nav,
                                               "first" => _admin_first,
                                               "show" => _show,
                                               "dontshow" => _dont_show,
                                               "upload" => _member_admin_icon_upload,
                                               "gameicons" => $gameicons,
                                               "positions" => $positions,
                                               "check_show" => _button_value_show,
                                               "roster" => _admin_sqauds_roster,
                                               "selj" => 1,
                                               "self" => 1,
                                               "allow" => _allow,
                                               "deny" => _deny,
                                               "squads_joinus" => _squads_joinus,
                                               "squads_fightus" => _squads_fightus,
                                               "navigation" => $navigation,
                                               "nav_info" => _admin_squads_nav_info,
                                               "no_navi" => _admin_squads_no_navi,
                                               "teams" => _admin_squads_teams,
                                               "game" => _member_admin_game));
    break;

    case 'addsquad':
        if(empty($_POST['squad']))
            $show = error(_admin_squad_no_squad, 1);
        elseif(empty($_POST['game']))
            $show = error(_admin_squad_no_game, 1);
        else
        {
            if($_POST['position'] == 1 || $_POST['position'] == 2) $sign = ">= ";
            else $sign = "> ";

            db("UPDATE ".$db['squads']." SET `pos` = pos+1 WHERE pos ".$sign." '".intval($_POST['position'])."'");
            db("INSERT INTO ".$db['squads']."
                SET `name`         = '".up($_POST['squad'])."',
                    `game`         = '".up($_POST['game'])."',
                    `icon`         = '".up($_POST['icon'])."',
                    `beschreibung` = '".up($_POST['beschreibung'])."',
                    `shown`        = '".(isset($_POST['show']) ? ((int)$_POST['show']) : 0)."',
                    `navi`         = '".((int)$_POST['roster'])."',
                    `team_show`    = '".((int)$_POST['team_show'])."',
                    `team_joinus`  = '".((int)$_POST['team_joinus'])."',
                    `team_fightus` = '".((int)$_POST['team_fightus'])."',
                    `status`       = '".(isset($_POST['status']) ? ((int)$_POST['status']) : 0)."',
                    `pos`          = '".((int)$_POST['position'])."'");

            $insert_id = _insert_id();

            if($_POST['navi'] != "lazy") {
                if($_POST['navi'] == "1" || "2") $signnav = ">= ";
                else $signnav = "> ";

                $kat = preg_replace('/-(\d+)/','',$_POST['navi']);
                $pos = preg_replace("=nav_(.*?)-=","",$_POST['navi']);

                db("UPDATE ".$db['navi']." SET `pos` = pos+1 WHERE pos ".$signnav." '".intval($pos)."'");
                db("INSERT INTO ".$db['navi']."
                    SET `pos`   = '".((int)$pos)."',
                        `kat`       = '".up($kat)."',
                        `name`      = '".up($_POST['squad'])."',
                        `url`       = '../squads/?action=shows&amp;id=".$insert_id."',
                        `shown`     = '1',
                        `type`      = '2'");
            }

            $tmp = $_FILES['banner']['tmp_name'];
            $type = $_FILES['banner']['type'];
            $end = explode(".", $_FILES['banner']['name']);
            $end = strtolower($end[count($end)-1]);

            if(!empty($tmp))
            {
                $img = getimagesize($tmp);
                if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])
                    move_uploaded_file($tmp, basePath."/inc/images/squads/".$insert_id.".".strtolower($end));
            }

            $tmp = $_FILES['logo']['tmp_name'];
            $type = $_FILES['logo']['type'];
            $end = explode(".", $_FILES['logo']['name']);
            $end = strtolower($end[count($end)-1]);

            if(!empty($tmp))
            {
                $img = getimagesize($tmp);
                if($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg" || !$img[0])
                    move_uploaded_file($tmp, basePath."/inc/images/squads/".$insert_id."_logo.".strtolower($end));
            }

            $show = info(_admin_squad_add_successful, "?admin=squads");
        }
    break;


    case 'editsquad':
        if(empty($_POST['squad']))
            $show = error(_admin_squad_no_squad, 1);
        elseif(empty($_POST['game']))
        $show = error(_admin_squad_no_game, 1);
        else {
            $get = db("SELECT pos FROM ".$db['squads']."
                    WHERE id = '".intval($_GET['id'])."'",false,true);

            if($_POST['position'] != $get['pos']) {
                if($_POST['position'] == 1 || $_POST['position'] == 2) $sign = ">= ";
                else $sign = "> ";
                db("UPDATE ".$db['squads']." SET `pos` = pos+1 WHERE pos ".$sign." '".intval($_POST['position'])."'");
            }

            if($_POST['position'] == "lazy") $newpos = "";
            else $newpos = "`pos` = '".((int)$_POST['position'])."',";

            if($_POST['icon'] == "lazy") $newicon = "";
            else $newicon = "`icon` = '".up($_POST['icon'])."',";

            db("UPDATE ".$db['squads']."
                SET `name`          = '".up($_POST['squad'])."',
                    `game`          = '".up($_POST['game'])."',
                    ".$newpos."
                    ".$newicon."
                    `beschreibung` = '".up($_POST['beschreibung'])."',
                    `shown`        = '".(isset($_POST['show']) ? ((int)$_POST['show']) : 0)."',
                    `navi`         = '".((int)$_POST['roster'])."',
                    `team_show`    = '".((int)$_POST['team_show'])."',
                    `team_joinus`  = '".((int)$_POST['team_joinus'])."',
                    `team_fightus` = '".((int)$_POST['team_fightus'])."',
                    `status`       = '".(isset($_POST['status']) ? ((int)$_POST['status']) : 0)."'
                WHERE id = '".intval($_GET['id'])."'");

            if(isset($_POST['navi']) && $_POST['navi'] != "lazy") {
                $qry = db("SELECT * FROM ".$db['navi']." WHERE url = '../squads/?action=shows&amp;id=".intval($_GET['id'])."'");
                if(_rows($qry)) {
                    $get = _fetch($qry);
                    if($_POST['navi'] == "1" || "2") $sign = ">= ";
                    else $sign = "> ";

                    $kat = preg_replace('/-(\d+)/','',$_POST['navi']);
                    $pos = preg_replace("=nav_(.+)-=","",$_POST['navi']);

                    db("UPDATE ".$db['navi']." SET pos = pos+1 WHERE pos ".$sign." '".intval($pos)."'");
                    db("UPDATE ".$db['navi']." SET `pos` = '".((int)$pos)."',
                                                   `kat`       = '".up($kat)."',
                                                   `name`      = '".up($_POST['squad'])."',
                                                   `url`       = '../squads/?action=shows&amp;id=".intval($_GET['id'])."'
                                               WHERE id = '".intval($get['id'])."'");
                } else {
                    if($_POST['navi'] == "1" || "2") $signnav = ">= ";
                    else $signnav = "> ";

                    $kat = preg_replace('/-(\d+)/','',$_POST['navi']);
                    $pos = preg_replace("=nav_(.*?)-=","",$_POST['navi']);

                    db("UPDATE ".$db['navi']." SET `pos` = pos+1 WHERE pos ".$signnav." '".intval($pos)."'");

                    db("INSERT INTO ".$db['navi']."
                        SET `pos`       = '".((int)$pos)."',
                            `kat`       = '".up($kat)."',
                            `name`      = '".up($_POST['squad'])."',
                            `url`       = '../squads/?action=shows&amp;id=".intval($_GET['id'])."',
                            `shown`     = '1',
                            `type`      = '2'");
                }
            } else {
                $qry = db("SELECT * FROM ".$db['navi']." WHERE url = '../squads/?action=shows&amp;id=".intval($_GET['id'])."'");

                if(_rows($qry))
                    db("DELETE FROM ".$db['navi']." WHERE url = '../squads/?action=shows&amp;id=".intval($_GET['id'])."'");
            }

            //Banner
            $tmp = $_FILES['banner']['tmp_name'];
            $type = $_FILES['banner']['type'];
            $end = explode(".", $_FILES['banner']['name']);
            $end = strtolower($end[count($end)-1]);

            if(!empty($tmp)) {
                $img = getimagesize($tmp);
                foreach($picformat AS $end1) {
                    if(file_exists(basePath.'/inc/images/squads/'.intval($_GET['id']).'.'.$end1)) {
                        @unlink(basePath.'/inc/images/squads/'.intval($_GET['id']).'.'.$end1);
                        break;
                    }
                }

                if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])
                    move_uploaded_file($tmp, basePath."/inc/images/squads/".intval($_GET['id']).".".strtolower($end));
            }

            //Logo
            $tmp = $_FILES['logo']['tmp_name'];
            $type = $_FILES['logo']['type'];
            $end = explode(".", $_FILES['logo']['name']);
            $end = strtolower($end[count($end)-1]);

            if(!empty($tmp)) {
                $img = getimagesize($tmp);
                foreach($picformat AS $end1) {
                    if(file_exists(basePath.'/inc/images/squads/'.intval($_GET['id']).'_logo.'.$end1)) {
                        @unlink(basePath.'/inc/images/squads/'.intval($_GET['id']).'_logo.'.$end1);
                        break;
                    }
                }

                if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])
                    move_uploaded_file($tmp, basePath."/inc/images/squads/".intval($_GET['id'])."_logo.".strtolower($end));
            }

            $show = info(_admin_squad_edit_successful, "?admin=squads");
        }
    break;

    case 'delete':
        db("DELETE FROM ".$db['squads']." WHERE id = '".intval($_GET['id'])."'");
        db("DELETE FROM ".$db['navi']." WHERE url = '../squads/?action=shows&amp;id=".intval($_GET['id'])."'");

        //Remove Pic
        foreach($picformat as $tmpendung) {
            if(file_exists(basePath."/inc/images/squads/".intval($_GET['id']).".".$tmpendung))
                @unlink(basePath."/inc/images/squads/".intval($_GET['id']).".".$tmpendung);
        }

        foreach($picformat as $tmpendung) {
            if(file_exists(basePath."/inc/images/squads/".intval($_GET['id'])."_logo.".$tmpendung))
                @unlink(basePath."/inc/images/squads/".intval($_GET['id'])."_logo.".$tmpendung);
        }

        //Remove minimize
        $files = get_files(basePath."/inc/images/squads/",false,true,$picformat);
        foreach ($files as $file) {
            if(preg_match("#".intval($_GET['id'])."(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                $res = preg_match("#".intval($_GET['id'])."_(.*)#",$file,$match);
                if(file_exists(basePath."/inc/images/squads/".intval($_GET['id'])."_".$match[1]))
                    @unlink(basePath."/inc/images/squads/".intval($_GET['id'])."_".$match[1]);
            }
        }

        $files = get_files(basePath."/inc/images/squads/",false,true,$picformat);
        foreach ($files as $file) {
            if(preg_match("#".intval($_GET['id'])."(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                $res = preg_match("#".intval($_GET['id'])."_logo_(.*)#",$file,$match);
                if(file_exists(basePath."/inc/images/squads/".intval($_GET['id'])."_logo_".$match[1]))
                    @unlink(basePath."/inc/images/squads/".intval($_GET['id'])."_logo_".$match[1]);
            }
        }

        $show = info(_admin_squad_deleted, "?admin=squads");
    break;

    case 'edit':
        $get = db("SELECT * FROM ".$db['squads']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $pos = db("SELECT pos,name FROM ".$db['squads']." ORDER BY pos"); $positions = '';
        while($getpos = _fetch($pos))
        {
            if($getpos['name'] != $get['name']) {
                $mp = db("SELECT pos FROM ".$db['squads']."
                          WHERE id != '".intval($get['id'])."'
                          AND pos = '".intval(($get['pos']-1))."'",false,true);

                $sel = $getpos['pos'] == $mp['pos'] ? 'selected="selected"' : '' ;
                $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                        "what" => _nach.' '.re($getpos['name']),
                                                        "sel" => $sel));
            }
        }

        $qrynav = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".$db['navi_kats']." AS s1 LEFT JOIN ".$db['navi']." AS s2 ON s1.`placeholder` = s2.`kat`
                      ORDER BY s1.name, s2.pos");
        $i = 1; $thiskat = ''; $navigation = '';
        while($getnav = _fetch($qrynav)) {
            if($thiskat != $getnav['kat']) {
                $navigation .= '<option class="dropdownKat" value="lazy">'.re($getnav['katname']).'</option>
                <option value="'.re($getnav['placeholder']).'-1">-> '._admin_first.'</option>';
            }

            $thiskat = $getnav['kat'];
            $sel[$i] = ($getnav['url'] == '../squads/?action=shows&amp;id='.intval($_GET['id'])) ? 'selected="selected"' : '';
            $navigation .= empty($getnav['name']) ? '' : '<option value="'.re($getnav['placeholder']).'-'.($getnav['pos']+1).'" '.$sel[$i].'>'._nach.' -> '.navi_name(re($getnav['name'])).'</option>';
            $i++;
        }

        $sshown = $get['shown'] ? 'checked="checked"' : '';
        $roster = $get['navi'] ? 'selected="selected"' : '';
        $status = $get['status'] ? 'selected="selected"' : '';
        $team_show = $get['team_show'] ? 'selected="selected"' : '';
        $team_joinus = $get['team_joinus'] ? 'selected="selected"' : '';
        $team_fightus= $get['team_fightus'] ? 'selected="selected"' : '';

        $files = get_files('../inc/images/gameicons/',false,true,array('gif','png','jpg')); $gameicons = '';
        foreach ($files as $file)
        {
            $sel = $file == $get['icon'] ? 'selected="selected"' : '';
            $gameicons .= show(_select_field, array("value" => $file,
                                                    "sel" => $sel,
                                                    "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file))));
        }

        $image = ''; $logoimage = '';
        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/squads/'.intval($_GET['id']).'.'.$end))
            {
                $image = '<img src="../inc/images/squads/'.intval($_GET['id']).'.'.$end.'" width="200" alt="" onmouseover="DZCP.showInfo(\'<tr><td><img src=../inc/images/squads/'.intval($_GET['id']).'.'.$end.' alt= /></tr></td>\')" onmouseout="DZCP.hideInfo()" /><br />';
                break;
            }
        }

        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/squads/'.intval($_GET['id']).'_logo.'.$end))
            {
                $logoimage = '<img src="../inc/images/squads/'.intval($_GET['id']).'_logo.'.$end.'" height="60" alt="" onmouseover="DZCP.showInfo(\'<tr><td><img src=../inc/images/squads/'.intval($_GET['id']).'_logo.'.$end.' alt= /></tr></td>\')" onmouseout="DZCP.hideInfo()" /><br />';
                break;
            }
        }

        $show = show($dir."/squads_edit", array("memberadminaddheader" => _member_admin_edit_header,
                                                "squad" => _member_admin_squad,
                                                "id" => intval($_GET['id']),
                                                "pos" => _position,
                                                "icon" => _member_admin_icon,
                                                "gameicons" => $gameicons,
                                                "logo" => _team_logo,
                                                "value" => _button_value_edit,
                                                "status" => _status,
                                                "aktiv"  => _sq_aktiv,
                                                "inaktiv" => _sq_inaktiv,
                                                "sstatus" => _sq_sstatus,
                                                "banner" => _sq_banner,
                                                "image" => $image,
                                                "logoimage" => $logoimage,
                                                "desc" => _dl_besch,
                                                "beschreibung" => re_bbcode($get['beschreibung']),
                                                "cstatus" => $status,
                                                "first" => _admin_first,
                                                "info" => _admin_squad_show_info,
                                                "navi" => _admin_squads_nav,
                                                "upload" => show(_member_admin_icon_upload_edit,array('id' => intval($_GET['id']))),
                                                "sshown" => $sshown,
                                                "nothing" => _nothing,
                                                "selr" => $roster,
                                                "selt" => $team_show,
                                                "navigation" => $navigation,
                                                "roster" => _admin_sqauds_roster,
                                                "navigation" => $navigation,
                                                "nav_info" => _admin_squads_nav_info,
                                                "no_navi" => _admin_squads_no_navi,
                                                "teams" => _admin_squads_teams,
                                                "show" => _show,
                                                "dontshow" => _dont_show,
                                                "ssquad" => re($get['name']),
                                                "selj" => $team_joinus,
                                                "self" => $team_fightus,
                                                "allow" => _allow,
                                                "deny" => _deny,
                                                "squads_joinus" => _squads_joinus,
                                                "squads_fightus" => _squads_fightus,
                                                "sgame" => re($get['game']),
                                                "positions" => $positions,
                                                "check_show" => _button_value_show,
                                                "game" => _member_admin_game));
    break;

    default:
        $qry = db("SELECT * FROM ".$db['squads']." ORDER BY pos"); $squads = '';
        while($get = _fetch($qry))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'],
                    "action" => "admin=squads&amp;do=edit",
                    "title" => _button_title_edit));

            $delete = show("page/button_delete_single", array("id" => $get['id'],
                    "action" => "admin=squads&amp;do=delete",
                    "title" => _button_title_del,
                    "del" => convSpace(_confirm_del_team)));

            $icon = show(_gameicon, array("icon" => $get['icon']));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $squads .= show($dir."/squads_show", array("squad" => '<a href="../squads/?action=shows&amp;id='.$get['id'].'" style="display:block">'.re($get['name']).'</a>',
                    "game" => re($get['game']),
                    "icon" => $icon,
                    "edit" => $edit,
                    "class" => $class,
                    "delete" => $delete));
        }

        $show = show($dir."/squads", array("memberadminheader" => _member_admin_header,
                "squad" => _member_admin_squad,
                "game" => _member_admin_game,
                "delete" => _deleteicon_blank,
                "edit" => _editicon_blank,
                "add" => _member_admin_add_header,
                "squads" => $squads));
    break;
}