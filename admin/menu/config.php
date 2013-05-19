<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       rootmenu
// Rechte:    $chkMe == 4
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._config_global_head;
    if($chkMe != 4)
    {
      $show = error(_error_wrong_permissions, 1);
    } else {
      if($_GET['do'] == "update")
      {
        if($_POST)
        {
          $qry = db("UPDATE ".$db['config']."
                     SET `upicsize`           = '".((int)$_POST['m_upicsize'])."',
                         `m_gallerypics`      = '".((int)$_POST['m_gallerypics'])."',
                         `m_usergb`           = '".((int)$_POST['m_usergb'])."',
                         `m_artikel`          = '".((int)$_POST['m_artikel'])."',
                         `m_adminartikel`     = '".((int)$_POST['m_adminartikel'])."',
                         `m_clanwars`         = '".((int)$_POST['m_clanwars'])."',
                         `m_awards`           = '".((int)$_POST['m_awards'])."',
                         `allowhover`         = '".((int)$_POST['ahover'])."',
                         `securelogin`        = '".((int)$_POST['securelogin'])."',
                         `m_clankasse`        = '".((int)$_POST['m_clankasse'])."',
                         `m_userlist`         = '".((int)$_POST['m_userlist'])."',
                         `m_banned`           = '".((int)$_POST['m_banned'])."',
                         `m_adminnews`        = '".((int)$_POST['m_adminnews'])."',
                         `l_servernavi`       = '".((int)$_POST['l_servernavi'])."',
                         `l_shoutnick`        = '".((int)$_POST['l_shoutnick'])."',
                         `m_gb`               = '".((int)$_POST['m_gb'])."',
                         `m_fthreads`         = '".((int)$_POST['m_fthreads'])."',
                         `m_fposts`           = '".((int)$_POST['m_fposts'])."',
                         `gallery`            = '".((int)$_POST['m_gallery'])."',
                         `m_news`             = '".((int)$_POST['m_news'])."',
                         `m_shout`            = '".((int)$_POST['m_shout'])."',
                         `m_comments`         = '".((int)$_POST['m_comments'])."',
                         `m_archivnews`       = '".((int)$_POST['m_archivnews'])."',
                         `maxwidth`           = '".((int)$_POST['maxwidth'])."',
                         `f_forum`            = '".((int)$_POST['f_forum'])."',
                         `f_cwcom`            = '".((int)$_POST['f_cwcom'])."',
                         `f_gb`               = '".((int)$_POST['f_gb'])."',
                         `f_artikelcom`       = '".((int)$_POST['f_artikelcom'])."',
                         `f_membergb`         = '".((int)$_POST['f_membergb'])."',
                         `f_shout`            = '".((int)$_POST['f_shout'])."',
                         `f_newscom`          = '".((int)$_POST['f_newscom'])."',
                         `l_newsadmin`        = '".((int)$_POST['l_newsadmin'])."',
                         `l_shouttext`        = '".((int)$_POST['l_shouttext'])."',
                         `l_newsarchiv`       = '".((int)$_POST['l_newsarchiv'])."',
                         `l_forumtopic`       = '".((int)$_POST['l_forumtopic'])."',
                         `l_forumsubtopic`    = '".((int)$_POST['l_forumsubtopic'])."',
                         `l_clanwars`         = '".((int)$_POST['l_clanwars'])."',
                         `m_lnews`            = '".((int)$_POST['m_lnews'])."',
                         `m_lartikel`         = '".((int)$_POST['m_lartikel'])."',
                                                  `m_events`           = '".((int)$_POST['m_events'])."',
                         `m_topdl`            = '".((int)$_POST['m_topdl'])."',
                         `m_ftopics`          = '".((int)$_POST['m_ftopics'])."',
                         `m_cwcomments`       = '".((int)$_POST['m_cwcomments'])."',
                         `m_lwars`            = '".((int)$_POST['m_lwars'])."',
                         `m_lreg`             = '".((int)$_POST['m_lreg'])."',
                         `m_nwars`            = '".((int)$_POST['m_nwars'])."',
                         `l_topdl`            = '".((int)$_POST['l_topdl'])."',
                         `l_ftopics`          = '".((int)$_POST['l_ftopics'])."',
                         `l_lreg`             = '".((int)$_POST['l_lreg'])."',
                         `l_lnews`            = '".((int)$_POST['l_lnews'])."',
                         `l_lartikel`         = '".((int)$_POST['l_lartikel'])."',
                         `l_lwars`            = '".((int)$_POST['l_lwars'])."',
                         `teamrow`            = '".((int)$_POST['teamrow'])."',
                         `shout_max_zeichen`  = '".((int)$_POST['zeichen'])."',
                         `maxshoutarchiv`     = '".((int)$_POST['m_shouta'])."',
                                                  `m_away`             = '".((int)$_POST['m_away'])."',
                                                  `direct_refresh`     = '".((int)$_POST['direct_refresh'])."',
                                                  `cache_teamspeak`    = '".((int)$_POST['cache_teamspeak'])."',
                                                  `cache_server`       = '".((int)$_POST['cache_server'])."',
                         `l_nwars`            = '".((int)$_POST['l_nwars'])."'
                     WHERE id = 1");

          $qry = db("UPDATE ".$db['settings']."
                     SET `clanname`           = '".up($_POST['clanname'])."',
                         `pagetitel`          = '".up($_POST['pagetitel'])."',
                         `pfad`               = '".up($_POST['pfad'])."',
                         `badwords`           = '".up($_POST['badwords'])."',
                         `gmaps_key`          = '".up($_POST['gmaps_key'])."',
                         `gmaps_who`          = '".((int)$_POST['gmaps_who'])."',
                         `language`           = '".$_POST['language']."',
                         `gametiger`          = '".$_POST['gametiger']."',
                         `regcode`            = '".((int)$_POST['regcode'])."',
                                                 `forum_vote`         = '".((int)$_POST['forum_vote'])."',
                         `reg_forum`          = '".((int)$_POST['reg_forum'])."',
                         `reg_artikel`        = '".((int)$_POST['reg_artikel'])."',
                         `reg_shout`          = '".((int)$_POST['reg_shout'])."',
                         `reg_cwcomments`     = '".((int)$_POST['reg_cwcomments'])."',
                         `squadtmpl`          = '".((int)$_POST['squadtmpl'])."',
                         `counter_start`      = '".((int)$_POST['counter_start'])."',
                         `reg_newscomments`   = '".((int)$_POST['reg_nc'])."',
                         `reg_dl`             = '".((int)$_POST['reg_dl'])."',
                         `eml_reg_subj`       = '".up($_POST['eml_reg_subj'])."',
                         `eml_pwd_subj`       = '".up($_POST['eml_pwd_subj'])."',
                         `eml_nletter_subj`   = '".up($_POST['eml_nletter_subj'])."',
                                                 `eml_pn_subj`	      = '".up($_POST['eml_pn_subj'])."',
                                                 `double_post`	      = '".((int)$_POST['double_post'])."',
                                                 `gb_activ`	      		= '".((int)$_POST['gb_activ'])."',
                                                 `eml_fabo_npost_subj`   = '".up($_POST['eml_fabo_npost_subj'])."',
                                                 `eml_fabo_tedit_subj`   = '".up($_POST['eml_fabo_tedit_subj'])."',
                                                 `eml_fabo_pedit_subj`   = '".up($_POST['eml_fabo_pedit_subj'])."',
                         `eml_reg`            = '".up($_POST['eml_reg'])."',
                         `eml_pwd`            = '".up($_POST['eml_pwd'])."',
                         `eml_nletter`        = '".up($_POST['eml_nletter'])."',
                                                 `eml_pn`        	  = '".up($_POST['eml_pn'])."',
                                                 `eml_fabo_npost`     = '".up($_POST['eml_fabo_npost'])."',
                                                 `eml_fabo_tedit`     = '".up($_POST['eml_fabo_tedit'])."',
                                                 `eml_fabo_pedit`     = '".up($_POST['eml_fabo_pedit'])."',
                         `mailfrom`           = '".up($_POST['mailfrom'])."',
                         `tmpdir`             = '".up($_POST['tmpdir'])."',
                         `persinfo`           = '".((int)$_POST['persinfo'])."',
                         `wmodus`             = '".((int)$_POST['wmodus'])."',
                         `balken_cw`          = '".up($_POST['balken_cw'])."',
                         `balken_vote`        = '".up($_POST['balken_vote'])."',
                         `balken_vote_menu`   = '".up($_POST['balken_vote_menu'])."',
                         `urls_linked`   = '".up($_POST['urls_linked'])."'
                     WHERE id = 1");

          if(!empty($_POST['gmaps_key']))
          {
            $qry = db("SELECT id,city,country FROM ".$db['users']."
                       WHERE city != '' AND `gmaps_koord` = ''
                       ORDER BY id DESC");
            while($get = _fetch($qry))
            {
              $cc .= 'getCord(\''.re($get['city']).'\',\''.re($get['country']).'\',\''.$get['id'].'\');'."\r\n";
            }

            $show = '<script language="javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$_POST['gmaps_key'].'" type="text/javascript"></script>
            <script language="javascript" type="text/javascript">
                function getCord(city,country,id)
                {
                  var geocoder = new GClientGeocoder();
                      geocoder.setCache(null);
                  var address = city+\', \'+country;

                  geocoder.getLatLng(address,
                    function(point)
                    {
                      if(point)
                      {
                        $.post(\'index.php?admin=config&do=port&id=\'+id, \'koord=\'+point);
                      }
                    }
                  );
                }
                '.$cc.'
                </script>
          ';
          }

          $show .= info(_config_set, "?admin=config", 10);
        }
      } else if($_GET['do']=='port') {
        $qry = db("UPDATE ".$db['users']."
                   SET `gmaps_koord` = '".up($_POST['koord'])."'
                   WHERE id = '".$_GET['id']."'");
         exit;
      } else {
        $qry = db("SELECT * FROM ".$db['config']."");
        $get = _fetch($qry);

        $qrys = db("SELECT * FROM ".$db['settings']."");
        $gets = _fetch($qrys);

        $files = get_files('../inc/lang/languages/',false,true,array('php'));
        for($i=0; $i<count($files); $i++)
        {
          if($gets['language'] == $files[$i]) $sel = "selected=\"selected\"";
          else $sel = "";

          $lng = preg_replace("#.php#", "",$files[$i]);

          $lang .= show(_select_field, array("value" => $lng,
                                             "what" => $lng,
                                             "sel" => $sel));
        }

        $tmps = get_files('../inc/_templates_/',true);
        for($i=0; $i<count($tmps); $i++)
        {
          if($gets['tmpdir'] == $tmps[$i]) $selt = "selected=\"selected\"";
          else $selt = "";

          $tmpldir .= show(_select_field, array("value" => $tmps[$i],
                                                "what" => $tmps[$i],
                                                "sel" => $selt));
        }

        if($gets['regcode'] == "1") $selyes = "selected=\"selected\"";
        else $selno = "selected=\"selected\"";

        if($gets['reg_forum'] == 1)        $selr_forum = "selected=\"selected\"";
        if($gets['reg_newscomments'] == 1) $selr_nc = "selected=\"selected\"";
        if($gets['reg_dl'] == 1)           $selr_dl = "selected=\"selected\"";
        if($gets['reg_artikel'] == 1)      $selr_artikel = "selected=\"selected\"";
        if($gets['reg_cwcomments'] == 1)   $selr_cwc = "selected=\"selected\"";
        if($gets['reg_shout'] == 1)        $selr_shout = "selected=\"selected\"";
        if($gets['wmodus'] == 1)           $selwm = "selected=\"selected\"";
        if($gets['squadtmpl'] == 2)        $selsq = "selected=\"selected\"";
        if($gets['persinfo'] == 0)         $selr_pi = "selected=\"selected\"";
        if($get['securelogin'] == 1)       $sel_sl = "selected=\"selected\"";
        if($get['allowhover'] == 1)        $selh_all = "selected=\"selected\"";
        if($get['allowhover'] == 2)        $selh_cw = "selected=\"selected\"";
        if($gets['gmaps_who'] == 1)        $sel_gm = "selected=\"selected\"";
                if($gets['double_post'] == 1)      $sel_dp = "selected=\"selected\"";
                if($gets['forum_vote'] == 1)       $sel_fv = "selected=\"selected\"";
                if($gets['gb_activ'] == 1)         $sel_gba = "selected=\"selected\"";
                                if($gets['urls_linked'] == 1)      $sel_url = "selected=\"selected\"";
        $wysiwyg = '_word';

        $show_ = show($dir."/form_config", array("limits" => _config_c_limits,
                                                 "limits_what" => _config_c_limits_what,
                                                 "c_m_usergb" => _config_c_usergb,
                                                 "c_m_clankasse" => _config_c_clankasse,
                                                 "c_m_userlist" => _config_c_userlist,
                                                 "c_m_banned" => _config_c_banned,
                                                 "c_m_adminnews" => _config_c_adminnews,
                                                 "c_m_shout" => _config_c_shout,
                                                 "gmaps_key" => _config_gmaps_key,
                                                 "c_gmaps_key" => re($gets['gmaps_key']),
                                                 "sel_refresh" => ($get['direct_refresh'] == 1 ? ' selected="selected"' : ''),
                                                 "direct_refresh" => _config_direct_refresh,
                                                 "direct_refresh_info" => _config_direct_refresh_info,
                                                 "sel_gm" => $sel_gm,
                                                 "seconds" => _seconds,
                                                 "c_m_zeichen" => _zeichen,
                                                 "max" => _max,
                                                 "cache_info" => _config_cache_info,
                                                 "cache_teamspeak" => $get['cache_teamspeak'],
                                                 "cache_server" => $get['cache_server'],
                                                 "gmaps_key_info" => _gmaps_key_info,
                                                 "c_eml_reg_subj" => $gets['eml_reg_subj'],
                                                 "c_eml_pwd_subj" => $gets['eml_pwd_subj'],
                                                 "c_eml_nletter_subj" => $gets['eml_nletter_subj'],
                                                                                                 "c_eml_pn_subj" => $gets['eml_pn_subj'],
                                                                                                 "c_eml_fabo_npost_subj" => $gets['eml_fabo_npost_subj'],
                                                                                                 "c_eml_fabo_tedit_subj" => $gets['eml_fabo_tedit_subj'],
                                                 "c_eml_fabo_pedit_subj" => $gets['eml_fabo_pedit_subj'],
                                                                                                 "c_eml_reg" => txtArea($gets['eml_reg']),
                                                 "c_eml_pwd" => txtArea($gets['eml_pwd']),
                                                 "c_eml_nletter" => txtArea($gets['eml_nletter']),
                                                                                                 "c_eml_pn" => txtArea($gets['eml_pn']),
                                                                                                 "c_eml_fabo_npost" => txtArea($gets['eml_fabo_npost']),
                                                                                                 "c_eml_fabo_tedit" => txtArea($gets['eml_fabo_tedit']),
                                                                                                 "c_eml_fabo_pedit" => txtArea($gets['eml_fabo_pedit']),
                                                 "eml_head" => _admin_eml_head,
                                                 "eml_reg_subj" => _admin_reg_subj,
                                                 "eml_pwd_subj" => _admin_pwd_subj,
                                                 "eml_nletter_subj" => _admin_nletter_subj,
                                                                                                 "eml_pn_subj" => _admin_pn_subj,
                                                                                                 "eml_fabo_npost_subj" => _admin_fabo_npost_subj,
                                                                                                 "eml_fabo_tedit_subj" => _admin_fabo_tedit_subj,
                                                                                                 "eml_fabo_pedit_subj" => _admin_fabo_pedit_subj,
                                                 "eml_reg" => _admin_reg,
                                                 "eml_pwd" => _admin_pwd,
                                                 "gmaps_who" => _admin_gmaps_who,
                                                 "gmaps_who_mem" => _gmaps_who_mem,
                                                 "gmaps_who_all" => _gmaps_who_all,
                                                 "eml_nletter" => _admin_nletter,
                                                                                                 "eml_pn" => _admin_pn,
                                                                                                 "eml_fabo_npost" => _admin_fabo_npost,
                                                                                                 "eml_fabo_tedit" => _admin_fabo_tedit,
                                                                                                 "eml_fabo_pedit" => _admin_fabo_pedit,
                                                 "eml_info" => _admin_eml_info,
                                                 "main_info" => _main_info,
                                                 "c_m_comments" => _config_c_comments,
                                                 "c_m_archivnews" => _config_c_archivnews,
                                                 "c_m_gb" => _config_c_gb,
                                                 "c_m_fthreads" => _config_c_fthreads,
                                                 "c_m_fposts" => _config_c_fposts,
                                                 "c_m_news" => _config_c_news,
                                                 "tmpdir" => $tmpldir,
                                                 "c_m_clanwars" => _config_c_clanwars,
                                                 "c_m_lnews" => _config_c_lnews,
                                                 "c_m_lartikel" => _config_c_lartikel,
                                                 "c_m_topdl" => _config_c_topdl,
                                                 "c_m_ftopics" => _config_c_ftopics,
                                                 "c_m_lwars" => _config_c_lwars,
                                                 "c_m_nwars" => _config_c_nwars,
                                                                                                  "c_m_events" => _config_c_events,
                                                 "c_m_gallerypics" => _config_c_gallerypics,
                                                 "c_m_upicsize" => _config_c_upicsize,
                                                 "c_m_gallery" => _config_c_gallery,
                                                 "c_l_servernavi" => _config_c_servernavi,
                                                 "c_tmpdir" => _config_tmpdir,
                                                 "c_maxwidth" => _config_maxwidth,
                                                 "maxwidth_info" => _config_maxwidth_info,
                                                 "maxwidth" => $get['maxwidth'],
                                                 "l_servernavi" => $get['l_servernavi'],
                                                 "gallery" => _config_info_gallery,
                                                 "upicsize" => _config_c_upicsize_what,
                                                 "gallerypics" => _config_c_gallerypics_what,
                                                 "c_regcode" => _config_c_regcode,
                                                 "regcode_what" => _config_c_regcode_what,
                                                 "show_regcode" => _show,
                                                 "c_mailfrom" => _config_mailfrom,
                                                 "mailfrom_info" => _config_mailfrom_info,
                                                 "mailfrom" => re($gets['mailfrom']),
                                                 "selpi" => $selr_pi,
                                                 "on" => _on,
                                                 "off" => _off,
                                                 "c_l_reguser" => _config_lreg,
                                                 "c_m_reguser" => _config_lreg,
                                                 "l_lreg" => $get['l_lreg'],
                                                 "m_lreg" => $get['m_lreg'],
                                                 "selr_shout" => $selr_shout,
                                                 "reg_shout" => _config_c_shout,
                                                 "persinfo" => _pers_info,
                                                 "persinfo_info" => _pers_info_info,
                                                 "show_no_regcode" => _dont_show,
                                                 "c_m_awards" => _config_c_awards,
                                                 "c_l_shoutnick" => _c_l_shoutnick,
                                                 "badword_head" => _admin_config_badword,
                                                 "badword_info" => _admin_config_badword_info,
                                                 "badwords" => re($gets['badwords']),
                                                 "l_shoutnick" => $get['l_shoutnick'],
                                                 "m_awards" => $get['m_awards'],
                                                 "selr_cwc" => $selr_cwc,
                                                 "reg_cw" => _cw_comments,
                                                 "f_cwcom" => $get['f_cwcom'],
                                                 "selyes" => $selyes,
                                                 "selno" => $selno,
                                                 "regcode" => $gets['regcode'],
                                                 "m_gallery" => $get['gallery'],
                                                 "m_lnews" => $get['m_lnews'],
                                                 "m_lartikel" => $get['m_lartikel'],
                                                 "m_ftopics" => $get['m_ftopics'],
                                                 "m_lwars" => $get['m_lwars'],
                                                 "m_nwars" => $get['m_nwars'],
                                                                                                  "m_events" => $get['m_events'],
                                                 "m_topdl" => $get['m_topdl'],
                                                 "m_usergb" => $get['m_usergb'],
                                                 "m_clankasse" => $get['m_clankasse'],
                                                 "m_userlist" => $get['m_userlist'],
                                                 "m_banned" => $get['m_banned'],
                                                 "m_adminnews" => $get['m_adminnews'],
                                                 "m_shout" => $get['m_shout'],
                                                 "m_shouta" => $get['maxshoutarchiv'],
                                                 "zeichen" => $get['shout_max_zeichen'],
                                                 "m_comments" => $get['m_comments'],
                                                 "m_cwcomments" => $get['m_cwcomments'],
                                                 "m_archivnews" => $get['m_archivnews'],
                                                 "m_gb" => $get['m_gb'],
                                                 "m_fthreads" => $get['m_fthreads'],
                                                 "m_fposts" => $get['m_fposts'],
                                                 "m_clanwars" => $get['m_clanwars'],
                                                 "m_news" => $get['m_news'],
                                                 "m_gallerypics" => $get['m_gallerypics'],
                                                 "m_upicsize" => $get['upicsize'],
                                                 "counter_start" => _counter_start,
                                                 "c_start" => $gets['counter_start'],
                                                 "selsq" => $selsq,
                                                 "squadtmpl" => _admin_squadtemplate,
                                                 "squadtmpl_info" => _admin_squadtemplate_info,
                                                 "c_start_info" => _counter_start_info,
                                                 "floods" => _config_c_floods,
                                                 "floods_what" => _config_c_floods_what,
                                                 "c_f_forum" => _config_c_forum,
                                                 "c_f_gb" => _config_c_gb,
                                                 "c_f_membergb" => _config_c_usergb,
                                                 "c_f_shout" => _config_c_shout,
                                                 "c_zeichen" => _config_zeichen,
                                                 "zeichen_info" => _config_zeichen_info,
                                                 "c_m_shouta" => _config_shoutarchiv,
                                                 "c_f_newscom" => _config_c_comments,
                                                 "f_forum" => $get['f_forum'],
                                                 "f_gb" => $get['f_gb'],
                                                 "f_membergb" => $get['f_membergb'],
                                                 "f_shout" => $get['f_shout'],
                                                 "f_newscom" => $get['f_newscom'],
                                                 "length" => _config_c_length,
                                                 "length_what" => _config_c_length_what,
                                                 "c_l_newsadmin" => _config_c_newsadmin,
                                                 "c_l_shouttext" => _config_c_shouttext,
                                                 "c_l_newsarchiv" => _config_c_newsarchiv,
                                                 "c_l_forumtopic" => _config_c_forumtopic,
                                                 "c_l_forumsubtopic" => _config_c_forumsubtopic,
                                                 "c_l_topdl" => _config_c_topdl,
                                                 "c_l_ftopics" => _config_c_ftopics,
                                                 "c_l_lnews" => _config_c_lnews,
                                                 "c_l_lartikel" => _config_c_lartikel,
                                                 "c_m_artikel" => _config_c_martikel,
                                                 "c_m_adminartikel" => _config_c_madminartikel,
                                                                                                 "c_m_away" => _config_c_away,
                                                 "m_artikel" => $get['m_artikel'],
                                                 "m_adminartikel" => $get['m_adminartikel'],
                                                                                                  "m_away" => $get['m_away'],
                                                 "wmodus" => _wartungsmodus_head,
                                                 "wmodus_info" => _wartungsmodus_info,
                                                 "c_wmodus" => $gets['wmodus'],
                                                 "selwm" => $selwm,
                                                 "c_l_lwars" => _config_c_lwars,
                                                 "c_l_nwars" => _config_c_nwars,
                                                 "c_l_clanwars" => _config_c_lcws,
                                                 "l_clanwars" => $get['l_clanwars'],
                                                 "l_newsadmin" => $get['l_newsadmin'],
                                                 "l_shouttext" => $get['l_shouttext'],
                                                 "l_newsarchiv" => $get['l_newsarchiv'],
                                                 "l_forumtopic" => $get['l_forumtopic'],
                                                 "l_forumsubtopic" => $get['l_forumsubtopic'],
                                                 "l_topdl" => $get['l_topdl'],
                                                 "l_ftopics" => $get['l_ftopics'],
                                                 "l_lnews" => $get['l_lnews'],
                                                 "l_lartikel" => $get['l_lartikel'],
                                                 "l_lwars" => $get['l_lwars'],
                                                 "l_nwars" => $get['l_nwars'],
                                                 "main" => _config_c_main,
                                                 "c_clanname" => _config_c_clanname,
                                                 "c_pagetitel" => _config_c_pagetitel,
                                                 "c_pfad" => _config_c_pfad,
                                                 "pfadlink" => _config_c_pfadlink,
                                                 "c_language" => _config_c_language,
                                                 "c_gametiger" => _config_c_gametiger,
                                                 "pfad" => re($gets['pfad']),
                                                 "clanname" => re($gets['clanname']),
                                                 "pagetitel" => re($gets['pagetitel']),
                                                 "nothing" => _nothing,
                                                 "lang" => $lang,
                                                 "ja" => _yes,
                                                 "nein" => _no,
                                                 "hover" => _config_hover,
                                                 "seclogin" => _config_seclogin,
                                                 "standard" => _config_hover_standard,
                                                 "all_on" => _config_hover_all,
                                                 "cw_only" => _config_hover_cw,
                                                                                                 "fotum_vote" => _config_fotum_vote,
                                                                                                 "fotum_vote_info" => _config_fotum_vote_info,
                                                                                                 "double_post" => _config_double_post,
                                                                                                 "gbactiv" => _config_gb_activ,
                                                                                                 "gb_activ_info" => _config_gb_activ_info,
                                                                                                 "sel_fv" => $sel_fv,
                                                 "sel_sl" => $sel_sl,
                                                                                                 "sel_dp" => $sel_dp,
                                                                                                 "sel_gba" => $sel_gba,
                                                 "selh_all" => $selh_all,
                                                 "selh_cw" => $selh_cw,
                                                 "selr_nc" => $selr_nc,
                                                 "selr_forum" => $selr_forum,
                                                 "selr_dl" => $selr_dl,
                                                 "selr_artikel" => $selr_artikel,
                                                 "reg_head" => _admin_reg_head,
                                                 "reg_info" => _admin_reg_info,
                                                 "reg_forum" => _forum,
                                                 "reg_nc" => _admin_nc,
                                                 "reg_dl" => _dl,
                                                 "teamrow" => _config_c_teamrow,
                                                 "teamrow_info" => _config_c_teamrow_info,
                                                 "c_teamrow" => $get['teamrow'],
                                                 "reg_artikel" => _reg_artikel,
                                                 "c_f_artikelcom" => _reg_artikel,
                                                 "f_artikelcom" => $get['f_artikelcom'],
                                                 "balken" => _config_head_balken,
                                                 "balken_cw_head" => _config_balken_cw,
                                                 "balken_info" => _config_balken_info,
                                                 "balken_vote_head" => _config_balken_vote_head,
                                                 "balken_vote_menu_head" => _config_balken_vote_menu_head,
                                                 "balken_cw" => $gets['balken_cw'],
                                                 "balken_vote" => $gets['balken_vote'],
                                                 "balken_vote_menu" => $gets['balken_vote_menu'],
                                                 'urls_linked' => _config_url_linked_head,
                                                 'urls_linked_info' => _urls_linked_info,
                                                 "sel_url" => $sel_url));

        $show = show($dir."/form", array("head" => _config_global_head,
                                         "what" => "config",
                                         "value" => _button_value_config,
                                         "show" => $show_));
      }
    }
?>