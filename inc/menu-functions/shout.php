<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: Shoutbox
 */
function shout($ajax = 0) {
    global $db,$userid,$chkMe;

    $qry = db("SELECT `id`,`text`,`datum`,`nick`,`email` FROM ".$db['shout']." ORDER BY id DESC LIMIT ".config('m_shout'));
    $i = 1; $color = 0; $show = '';
    while ($get = _fetch($qry)) {
        $class = ($color % 2) ? "navShoutContentFirst" : "navShoutContentSecond"; $color++;

        $delete = "";
        if(permission("shoutbox"))
            $delete = '<a href="../shout/?action=admin&amp;do=delete&amp;id='.$get['id'].'" onclick="return(DZCP.del(\''._confirm_del_shout.'\'))"><img src="../inc/images/delete_small.gif" title="'._button_title_del.'" alt="'._button_title_del.'" /></a>';

        $is_num = preg_match("#\d#", re($get['email']));

        if($is_num && !check_email(re($get['email'])))
            $nick = autor(re($get['email']), "navShout",'','',config('l_shoutnick'));
        else
            $nick = CryptMailto(re($get['email']),_email_navShout,array('nick' => $get['nick'], 'nick_cut' => cut($get['nick'], config('l_shoutnick'))));

        $show .= show("menu/shout_part", array("nick" => $nick,
                                               "datum" => date("j.m.Y H:i", $get['datum'])._uhr,
                                               "text" => bbcode(wrap(re($get['text']), config('l_shouttext')),false,false,false,true),
                                               "class" => $class,
                                               "del" => $delete));
        $i++;
    }

    $dis = ''; $dis1 = ''; $only4reg = ''; $sec = ''; $form = '';
    if(settings('reg_shout') == 1 && !$chkMe) {
        $dis = ' style="text-align:center;cursor:wait" disabled="disabled"';
        $dis1 = ' style="cursor:wait;color:#888" disabled="disabled"';
        $only4reg = _shout_must_reg;
    } else {
        if(!$chkMe) {
            $form = show("menu/shout_form", array("dis" => $dis));
            $sec = show("menu/shout_antispam", array("help" => _login_secure_help, "dis" => $dis));
        } else
            $form = autor($userid, "navShout",'','',config('l_shoutnick'));
    }

    $add = show("menu/shout_add", array("form" => $form,
                                        "t_zeichen" => _zeichen,
                                        "noch" => _noch,
                                        "dis1" => $dis1,
                                        "dis" => $dis,
                                        "only4reg" => $only4reg,
                                        "security" => $sec,
                                        "zeichen" => config('shout_max_zeichen')));

    $shout = show("menu/shout", array("shout" => $show,
                                      "shoutbox" => _shoutbox_head,
                                      "archiv" => _shoutbox_archiv,
                                      "add" => $add));

    return empty($ajax) ? '<table class="navContent" cellspacing="0">'.$shout.'</table>' : $show;
}