<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_ulist;
    $entrys = cnt($db['users']," WHERE level != 0");
    $show_sql = isset($_GET['show']) ? $_GET['show'] : '';

    if($show_sql == "search") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,regdatum
                   FROM ".$db['users']."
                   WHERE nick LIKE '%".$_GET['search']."%'
                   AND level != 0
                   ORDER BY nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "bday") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,regdatum
                   FROM ".$db['users']."
                   WHERE bday LIKE '".date("d", intval($_GET['time'])).".".date("m", intval($_GET['time'])).".____"."'
                   AND level != 0
                   ORDER BY nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "newreg") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,regdatum FROM ".$db['users']."
                   WHERE regdatum > '".$_SESSION['lastvisit']."'
                   AND level != '0'
                   ORDER BY regdatum DESC,nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "lastlogin") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,regdatum FROM ".$db['users']."
                   WHERE level != '0'
                   ORDER BY time DESC,nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "lastreg") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,regdatum FROM ".$db['users']."
                   WHERE level != '0'
                   ORDER BY regdatum DESC,nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "online") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,time FROM ".$db['users']."
                   WHERE level != '0'
                   ORDER BY time DESC,nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "country") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,country FROM ".$db['users']."
                   WHERE level != '0'
                   ORDER BY country,nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "sex") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position FROM ".$db['users']."
                   WHERE level != '0'
                   ORDER BY sex DESC
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif($show_sql == "banned") {
        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position FROM ".$db['users']."
                   WHERE level = '0'
                   ORDER BY nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } elseif(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("nick","bday"))) {
        $tmp_orderby = $_GET['orderby'];
            if($_GET['orderby'] == "bday")
                $_GET['orderby'] = "bday_order";

        $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,STR_TO_DATE(bday, '%d.%m.%Y') AS bday_order FROM ".$db['users']."
                   WHERE level != '0'
                   ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    } else {
          $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,
                   icq,status,position FROM ".$db['users']."
                   WHERE level != '0'
                   ORDER BY level DESC,nick
                   LIMIT ".($page - 1)*config('m_userlist').",".config('m_userlist')."");
    }

    $userliste = '';
    while($get = _fetch($qry)) {
        $email = show(_emailicon, array("email" => eMailAddr($get['email'])));
        $hlsw = empty($get['hlswid']) ? "-" : show(_hlswicon, array("id" => re($get['hlswid']), "img" => "1", "css" => ""));
        $xboxu = empty($get['xboxid']) ? "-" : show(_xboxicon, array("id" => re($get['xboxid']), "img" => "1", "css" => ""));
        $psnu = empty($get['psnid']) ? "-" : show(_psnicon, array("id" => re($get['psnid']), "img" => "1", "css" => ""));
        $originu = empty($get['originid']) ? "-" : show(_originicon, array("id" => re($get['originid']), "img" => "1", "css" => ""));
        $battlenetu = empty($get['battlenetid']) ? "-" : show(_battleneticon, array("id" => re($get['battlenetid']), "img" => "1", "css" => ""));
        $steam = empty($get['steamid']) ? "-" : show(_steamicon, array("steamid" => $get['steamid']));
        $skypename = empty($get['skypename']) ? "-" : "<a href=\"skype:".$get['skypename']."?chat\"><img src=\"http://mystatus.skype.com/smallicon/".$get['skypename']."\" style=\"border: none;\" width=\"16\" height=\"16\" alt=\"".$get['skypename']."\"/></a>";
        $hp = empty($get['hp']) ? "-" : show(_hpicon, array("hp" => $get['hp']));

        $icq = "-";
        if(!empty($get['icq'])) {
            $uin = show(_icqstatus, array("uin" => $get['icq']));
            $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$get['icq'].'" target="_blank">'.$uin.'</a>';
        }

        $sex = "-";
        if($get['sex'] == "1")
            $sex = _maleicon;
        elseif($get['sex'] == "2")
            $sex = _femaleicon;

        $getstatus = $get['status'] ? _aktiv_icon : _inaktiv_icon;
        $status = data("level",$get['id']) > 1 ? $getstatus : "";
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

        $edit = ""; $delete = "";
        if(permission("editusers")) {
            $edit = show("page/button_edit", array("id" => "",
                                                   "action" => "action=admin&amp;edit=".$get['id'],
                                                   "title" => _button_title_edit));
            $edit = str_replace("&amp;id=","",$edit);
            $delete = show("page/button_delete", array("id" => $get['id'],
                                                       "action" => "action=admin&amp;do=delete",
                                                       "title" => _button_title_del));
        }

        $userliste .= show($dir."/userliste_show", array("nick" => autor($get['id'],'','',10),
                                                         "level" => getrank($get['id']),
                                                         "status" => $status,
                                                         "email" => $email,
                                                         "age" => getAge($get['bday']),
                                                         "mf" => $sex,
                                                         "edit" => $edit,
                                                         "delete" => $delete,
                                                         "class" => $class,
                                                         "icq" => $icq,
                                                         "skypename" => $skypename,
                                                         "icquin" => $get['icq'],
                                                         "onoff" => onlinecheck($get['id']),
                                                         "hp" => $hp,
                                                         "steam" => $steam,
                                                         "xboxu" => $xboxu,
                                                         "psnu" => $psnu,
                                                         "originu" => $originu,
                                                         "battlenetu" => $battlenetu,
                                                         "hlsw" => $hlsw));
    }

    $orderby = empty($_GET['orderby']) ? "" : "&orderby".$_GET['orderby'];
    $orderby .= empty($_GET['order']) ? "" : "&order=".$_GET['order'];
    $seiten = nav($entrys,config('m_userlist'),"?action=userlist&show=".$show_sql."".$orderby);
    $edel = permission("editusers") ? '<td class="contentMainTop" colspan="2">&nbsp;</td>' : "";
    $search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : _nick;

    $index = show($dir."/userliste", array("userlistehead" => _userlist,
                                         "nickhead" => _nick,
                                         "normal" => _ulist_normal,
                                         "country" => _ulist_country,
                                         "sex" => _ulist_sex,
                                         "cnt" => $entrys." "._user,
                                         "lastreg" => _ulist_lastreg,
                                         "online" => _ulist_online,
                                         "age" => _ulist_age,
                                         "login" => _ulist_last_login,
                                         "bday" => _ulist_bday,
                                         "sort" => _ulist_sort,
                                         "banned" => _ulist_acc_banned,
                                         "edel" => $edel,
                                         "search" => $search,
                                         "value" => _button_value_search,
                                         "mficon" => _mficon_blank,
                                         "nav" => $seiten,
                                         "statushead" => _status,
                                         "emailicon" => _emailicon_blank,
                                         "addbuddyicon" => _addbuddyicon_blank,
                                         "agehead" => _profil_age,
                                         "icqicon" => _icqicon_blank,
                                         "pnicon" => _pnicon_blank,
                                         "hpicon" => _hpicon_blank,
                                         "xboxicon" => _xboxicon_blank,
                                         "psnicon" => _psnicon_blank,
                                         "originicon" => _originicon_blank,
                                         "battleneticon" => _battleneticon_blank,
                                         "steamicon" => _steamicon_blank,
                                         "hlswicon" => _hlswicon_blank,
                                         "order_nick" => orderby('nick'),
                                         "order_age" => orderby('bday'),
                                         "show" => $userliste));
}