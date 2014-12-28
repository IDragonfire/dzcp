<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_lobby;
    if($chkMe) {
        $can_erase = false;

        //Get Userinfos
        $lastvisit = $_SESSION['lastvisit'];

        /** Neue Foreneintraege anzeigen */
        $qrykat = db("SELECT s1.id,s2.kattopic,s1.intern,s2.id FROM ".$db['f_kats']." AS s1
                      LEFT JOIN ".$db['f_skats']." AS s2
                      ON s1.id = s2.sid
                      ORDER BY s1.kid,s2.kattopic");

        $forumposts = '';
        if(_rows($qrykat) >= 1) {
            while($getkat = _fetch($qrykat)) {
                unset($nthread);
                unset($post);
                unset($forumposts_show);

                if(fintern($getkat['id'])) {
                    $qrytopic = db("SELECT lp,id,topic,first,sticky FROM ".$db['f_threads']."
                                    WHERE kid = '".$getkat['id']."'
                                    AND lp > ".$lastvisit."
                                    ORDER BY lp DESC
                                    LIMIT 150");
                    if(_rows($qrytopic) >= 1) {
                        $forumposts_show = '';
                        while($gettopic = _fetch($qrytopic)) {
                            $lp = ""; $cnt = "";
                            $count = cnt($db['f_posts'], " WHERE date > ".$lastvisit." AND sid = '".$gettopic['id']."'");
                            $lp = cnt($db['f_posts'], " WHERE sid = '".$gettopic['id']."'");

                            if($count == 0) {
                                $cnt = 1;
                                $pagenr = 1;
                                $post = "";
                            } elseif($count == 1) {
                                $cnt = 1;
                                $pagenr = ceil($lp/config('m_fposts'));
                                $post = _new_post_1;
                            } else {
                                $cnt = $count;
                                $pagenr = ceil($lp/config('m_fposts'));
                                $post = _new_post_2;
                            }

                            $nthread = $gettopic['first'] == 1 ? _no_new_thread : _new_thread;

                            if(check_new($gettopic['lp'])) {
                                $intern = ($getkat['intern'] != 1 ? '' : '<span class="fontWichtig">'._internal.':</span>&nbsp;&nbsp;&nbsp;');
                                $wichtig = ($gettopic['sticky'] != 1 ? '' : '<span class="fontWichtig">'._sticky.':</span> ');

                                $date = (date("d.m.")==date("d.m.",$gettopic['lp']))
                                  ? '['.date("H:i",$gettopic['lp']).']'
                                  : date("d.m.",$gettopic['lp']).' ['.date("H:i",$gettopic['lp']).']';

                                $can_erase = true;
                                $forumposts_show .= '&nbsp;&nbsp;'.$date. show(_user_new_forum, array("cnt" => $cnt,
                                                                                                      "tid" => $gettopic['id'],
                                                                                                      "thread" => re($gettopic['topic']),
                                                                                                      "intern" => $intern,
                                                                                                      "wichtig" => $wichtig,
                                                                                                      "post" => $post,
                                                                                                      "page" => $pagenr,
                                                                                                      "nthread" => $nthread,
                                                                                                      "lp" => $lp +1));
                            }
                        }
                    }

                    if(!empty($forumposts_show))
                        $forumposts .= '<div style="padding:4px;padding-left:0"><span class="fontBold">'.$getkat['kattopic'].'</span></div>'.$forumposts_show;
                }
            }
        }

        /** Neue Clanwars anzeigen */
        $qrycw = db("SELECT s1.*,s2.icon FROM ".$db['cw']." AS s1
                     LEFT JOIN ".$db['squads']." AS s2
                     ON s1.squad_id = s2.id
                     ORDER BY s1.datum");
        $cws = '';
        if(_rows($qrycw) >= 1) {
            while($getcw = _fetch($qrycw)) {
                if(!empty($getcw) && check_new($getcw['datum'])) {
                    $check = cnt($db['cw'], " WHERE datum >".$lastvisit."");

                    if($check == 1) {
                        $cnt = 1;
                        $eintrag = _new_eintrag_1;
                    } else {
                        $cnt = $check;
                        $eintrag = _new_eintrag_2;
                    }

                    $can_erase = true;
                    $cws .= show(_user_new_cw, array("datum" => date("d.m. H:i", $getcw['datum'])._uhr,
                                                     "id" => $getcw['id'],
                                                     "icon" => $getcw['icon'],
                                                     "gegner" => re($getcw['clantag'])));
                }
            }
        }

        /** Neue Registrierte User anzeigen */
        $getu = db("SELECT id,regdatum FROM ".$db['users']." ORDER BY id DESC",false,true); $user = '';
        if(!empty($getu) && check_new($getu['regdatum'])) {
            $check = cnt($db['users'], " WHERE regdatum > ".$lastvisit."");

            if($check == 1) {
                $cnt = 1;
                $eintrag = _new_users_1;
            } else {
                $cnt = $check;
                $eintrag = _new_users_2;
            }

            $can_erase = true;
            $user = show(_user_new_users, array("cnt" => $cnt,
                                                "eintrag" => $eintrag));
        }

        /** Neue Eintruage im Guastebuch anzeigen */
        $permission_gb = permission("gb"); $activ = "";
        if(!$permission_gb && settings('gb_activ'))
            $activ = "WHERE public = 1";

        $gb = '';
        $getgb = db("SELECT id,datum FROM ".$db['gb']." ".$activ." ORDER BY id DESC",false,true);
        if(!empty($getgb) && check_new($getgb['datum'])) {
            $cntgb = "";
            if(!$permission_gb && settings('gb_activ'))
                $cntgb = "AND public = 1";

            $check = cnt($db['gb'], " WHERE datum > ".$lastvisit." ".$cntgb."");

            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_eintrag_1;
            } else {
                $cnt = $check;
                $eintrag = _new_eintrag_2;
            }

            $can_erase = true;
            $gb = show(_user_new_gb, array("cnt" => $cnt,
                                           "eintrag" => $eintrag));
        }

        /** Neue Eintruage im User Guastebuch anzeigen */
        $getmember = db("SELECT id,datum FROM ".$db['usergb']." WHERE user = '".$userid."' ORDER BY datum DESC",false,true);

        $membergb = '';
        if(!empty($getmember) && check_new($getmember['datum'])) {
            $check = cnt($db['usergb'], " WHERE datum > ".$lastvisit." AND user = '".$userid."'");
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_eintrag_1;
            } else {
                $cnt = $check;
                $eintrag = _new_eintrag_2;
            }

            $can_erase = true;
            $membergb = show(_user_new_membergb, array("cnt" => $cnt,
                                                       "id" => $userid,
                                                       "eintrag" => $eintrag));
        }

        /** Neue Private Nachrichten anzeigen */
        $getmsg = db("SELECT id,an,datum FROM ".$db['msg']."
                      WHERE an = '".$userid."'
                      AND readed = 0
                      AND see_u = 0
                      ORDER BY datum DESC",false,true);

        $check = cnt($db['msg'], " WHERE an = '".$userid."' AND readed = 0 AND see_u = 0");
        if($check == 1)
            $mymsg = show(_lobby_mymessage, array("cnt" => 1));
        else if($check >= 1) {
            $mymsg = show(_lobby_mymessages, array("cnt" => $check));
        } else
            $mymsg = show(_lobby_no_mymessages, array());

        /** Neue News anzeigen */
        if($chkMe >= 2) {
            $qrynews = db("SELECT id,datum FROM ".$db['news']."
                           WHERE public = 1
                           AND datum <= ".time()."
                           ORDER BY id DESC");
        } else {
            $qrynews = db("SELECT id,datum FROM ".$db['news']."
                           WHERE public = 1
                           AND intern = 0
                           AND datum <= ".time()."
                           ORDER BY id DESC");
        }

        $news = '';
        if(_rows($qrynews) >= 1) {
            while($getnews  = _fetch($qrynews)) {
                if(check_new($getnews['datum'])) {
                    $check = cnt($db['news'], " WHERE datum > ".$lastvisit." AND public = 1");
                    $cnt = $check == "1" ? "1" : $check;
                    $can_erase = true;
                    $news = show(_user_new_news, array("cnt" => $cnt, "eintrag" => _lobby_new_news));
                }
            }
        }

        /** Neue News comments anzeigen */
        $qrycheckn = db("SELECT id,titel FROM ".$db['news']." WHERE public = 1 AND datum <= ".time().""); $newsc = '';
        if(_rows($qrycheckn) >= 1) {
            while($getcheckn = _fetch($qrycheckn)) {
                $getnewsc = db("SELECT id,news,datum FROM ".$db['newscomments']." WHERE news = '".$getcheckn['id']."' ORDER BY datum DESC",false,true);
                if(check_new($getnewsc['datum'])) {
                    $check = cnt($db['newscomments'], " WHERE datum > ".$lastvisit." AND news = '".$getnewsc['news']."'");
                    if($check == "1") {
                        $cnt = "1";
                        $eintrag = _lobby_new_newsc_1;
                    } else if($check >= 2) {
                        $cnt = $check;
                        $eintrag = _lobby_new_newsc_2;
                    }

                    if($check) {
                        $can_erase = true;
                        $newsc .= show(_user_new_newsc, array("cnt" => $cnt,
                                                              "id" => $getnewsc['news'],
                                                              "news" => re($getcheckn['titel']),
                                                              "eintrag" => $eintrag));
                    }
                }
            }
        }

        /** Neue Clanwars comments anzeigen */
        $qrycheckcw = db("SELECT id FROM ".$db['cw']); $cwcom = '';
        if(_rows($qrycheckcw) >= 1) {
            while($getcheckcw = _fetch($qrycheckcw)) {
                $getcwc = db("SELECT id,cw,datum FROM ".$db['cw_comments']." WHERE cw = '".$getcheckcw['id']."' ORDER BY datum DESC",false,true);
                if(!empty($getcwc) && check_new($getcwc['datum']))
                {
                    $check = cnt($db['cw_comments'], " WHERE datum > ".$lastvisit." AND cw = '".$getcwc['cw']."'");
                    if($check == 1) {
                      $cnt = 1;
                      $eintrag = _lobby_new_cwc_1;
                    } else {
                      $cnt = $check;
                      $eintrag = _lobby_new_cwc_2;
                    }

                    $can_erase = true;
                    $cwcom .= show(_user_new_clanwar, array("cnt" => $cnt,
                                                            "id" => $getcwc['cw'],
                                                            "eintrag" => $eintrag));
                }
            }
        }

        /** Neue Votes anzeigen */
        if(permission("votes")) {
            $getnewv = db("SELECT datum FROM ".$db['votes']."
                           WHERE forum = 0
                           ORDER BY datum DESC",false,true);
        } else {
            $getnewv = db("SELECT datum FROM ".$db['votes']."
                           WHERE intern = 0
                           AND forum = 0
                           ORDER BY datum DESC",false,true);
        }

        $newv = '';
        if(!empty($getnewv) && check_new($getnewv['datum'])) {
            $check = cnt($db['votes'], " WHERE datum > ".$lastvisit." AND forum = 0");
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_vote_1;
            } else {
                $cnt = $check;
                $eintrag = _new_vote_2;
            }

            $can_erase = true;
            $newv = show(_user_new_votes, array("cnt" => $cnt,
                                                "eintrag" => $eintrag));
        }

        /** Kalender Events anzeigen */
        $getkal = db("SELECT * FROM ".$db['events']." WHERE datum > '".time()."' ORDER BY datum",false,true);
        $nextkal = '';
        if(!empty($getkal) && check_new($getkal['datum'])) {
            if(date("d.m.Y",$getkal['datum']) == date("d.m.Y", time())) {
              $nextkal = show(_userlobby_kal_today, array("time" => mktime(0,0,0,date("m",$getkal['datum']), date("d",$getkal['datum']),date("Y",$getkal['datum'])),
                                                          "event" => $getkal['title']));
            } else {
              $nextkal = show(_userlobby_kal_not_today, array("time" => mktime(0,0,0,date("m",$getkal['datum']), date("d",$getkal['datum']),date("Y",$getkal['datum'])),
                                                              "date" => date("d.m.Y", $getkal['datum']),
                                                              "event" => $getkal['title']));
            }
        }

        /** Neue Awards anzeigen */
        $getaw = db("SELECT id,postdate FROM ".$db['awards']." ORDER BY id DESC",false,true); $awards = '';
        if(!empty($getaw) && check_new($getaw['postdate'])) {
            $check = cnt($db['awards'], " WHERE postdate > ".$lastvisit);
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_awards_1;
            } else {
                $cnt = $check;
                $eintrag = _new_awards_2;
            }

            $can_erase = true;
            $awards = show(_user_new_awards, array("cnt" => $cnt,
                                                   "eintrag" => $eintrag));
        }

        /** Neue Rankings anzeigen */
        $getra = db("SELECT id,postdate FROM ".$db['rankings']." ORDER BY id DESC",false,true);
        $rankings = '';
        if(!empty($getra) && check_new($getra['postdate'])) {
            $check = cnt($db['rankings'], " WHERE postdate > ".$lastvisit);
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_rankings_1;
            } else {
                $cnt = $check;
                $eintrag = _new_rankings_2;
            }

            $can_erase = true;
            $rankings = show(_user_new_rankings, array("cnt" => $cnt,
                                                       "eintrag" => $eintrag));
        }

        /** Neue Artikel anzeigen */
        $qryart = db("SELECT id,datum FROM ".$db['artikel']." WHERE public = 1 ORDER BY id DESC"); $artikel = '';
        if(_rows($qryart) >= 1) {
            while($getart  = _fetch($qryart)) {
                if(check_new($getart['datum'])) {
                    $check = cnt($db['artikel'], " WHERE datum > ".$lastvisit." AND public = 1");
                    if($check == "1") {
                          $cnt = "1";
                          $eintrag = _lobby_new_art_1;
                    } else {
                          $cnt = $check;
                          $eintrag = _lobby_new_art_2;
                    }

                    $can_erase = true;
                    $artikel = show(_user_new_art, array("cnt" => $cnt,
                                                         "eintrag" => $eintrag));
                }
            }
        }

        /** Neue Artikel Comments anzeigen */
        $qrychecka = db("SELECT id FROM ".$db['artikel']." WHERE public = 1"); $artc = '';
        if(_rows($qrychecka) >= 1) {
            while($getchecka = _fetch($qrychecka)) {
                $getartc = db("SELECT id,artikel,datum FROM ".$db['acomments']."
                               WHERE artikel = '".$getchecka['id']."'
                               ORDER BY datum DESC",false,true);

                if(!empty($getartc) && check_new($getartc['datum'])) {
                    $check = cnt($db['acomments'], " WHERE datum > ".$lastvisit." AND artikel = '".$getartc['artikel']."'");
                    if($check == "1") {
                        $cnt = "1";
                        $eintrag = _lobby_new_artc_1;
                    } else {
                        $cnt = $check;
                        $eintrag = _lobby_new_artc_2;
                    }

                    $can_erase = true;
                    $artc .= show(_user_new_artc, array("cnt" => $cnt,
                                                        "id" => $getartc['artikel'],
                                                        "eintrag" => $eintrag));
                }
            }
        }

        /** Neue Bilder in der Gallery anzeigen */
        $getgal = db("SELECT id,datum FROM ".$db['gallery']." ORDER BY id DESC",false,true); $gal = '';
        if(!empty($getgal) && check_new($getgal['datum'])) {
            $check = cnt($db['gallery'], " WHERE datum > ".$lastvisit);
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_gal_1;
            } else {
                $cnt = $check;
                $eintrag = _new_gal_2;
            }

            $can_erase = true;
            $gal = show(_user_new_gallery, array("cnt" => $cnt,
                                                 "eintrag" => $eintrag));
        }

        /** Neue Aways anzeigen */
        $qryawayn = db("SELECT * FROM ".$db['away']." ORDER BY id"); $away_new = '';
        if(_rows($qryawayn) >= 1) {
            $awayn = '';
            while($getawayn = _fetch($qryawayn)) {
                if(check_new($getawayn['date']) && data('level') >= 2) {
                    $awayn .= show(_user_away_new, array("id" => $getawayn['id'],
                                                         "user" => autor($getawayn['userid']),
                                                         "ab" => date("d.m.y",$getawayn['start']),
                                                         "wieder" => date("d.m.y",$getawayn['end']),
                                                         "what" => $getawayn['titel']));
                }
            }

            $can_erase = true;
            $away_new = show(_user_away, array("naway" => _lobby_away_new,
                                               "away" => $awayn));
        }

        /** Alle Aways anzeigen */
        $qryawaya = db("SELECT * FROM ".$db['away']." WHERE start <= '".time()."' AND end >= '".time()."' ORDER BY start"); $away_now = "";
        if(_rows($qryawaya) >= 1) {
            $awaya = "";
            while($getawaya = _fetch($qryawaya)) {
                if(_rows($qryawaya) && data('level') >= 2) {
                    $wieder = '';
                    if($getawaya['end'] > time())
                        $wieder = _away_to2.' <b>'.date("d.m.y",$getawaya['end']).'</b>';

                    if(date("d.m.Y",$getawaya['end']) == date("d.m.Y",time()))
                        $wieder = _away_today;

                    $awaya .= show(_user_away_now, array("id" => $getawaya['id'],
                                                         "user" => autor($getawaya['userid']),
                                                         "wieder" => $wieder,
                                                         "what" => $getawaya['titel']));
                }
            }

            $away_now = show(_user_away_currently, array("ncaway" => _lobby_away,
                                                         "caway" => $awaya));
        }

        /** Neue Forum Topics anzeigen */
        $qryft = db("SELECT s1.t_text,s1.id,s1.topic,s1.kid,s2.kattopic,s3.intern,s1.sticky
                     FROM ".$db['f_threads']." s1, ".$db['f_skats']." s2, ".$db['f_kats']." s3
                     WHERE s1.kid = s2.id
                     AND s2.sid = s3.id
                     ORDER BY s1.lp DESC
                     LIMIT 10");
        $ftopics = '';
        if(_rows($qryft) >= 1) {
            while($getft = _fetch($qryft)) {
                if(fintern($getft['kid'])) {
                    $lp = cnt($db['f_posts'], " WHERE sid = '".$getft['id']."'");
                    $pagenr = ceil($lp/config('m_fposts'));
                    $page = ($pagenr == 0 ? 1 : $pagenr);
                    $getp = db("SELECT text FROM ".$db['f_posts']."
                                WHERE kid = '".$getft['kid']."'
                                AND sid = '".$getft['id']."'
                                ORDER BY date DESC
                                LIMIT 1",false,true);

                    $text = strip_tags(!empty($getp) ? $getp['text'] : $getft['t_text']);
                    $intern = $getft['intern'] != 1 ? "" : '<span class="fontWichtig">'._internal.':</span>';
                    $wichtig = $getft['sticky'] != 1 ? '' : '<span class="fontWichtig">'._sticky.':</span> ';
                    $ftopics .= show($dir."/userlobby_forum", array("id" => $getft['id'],
                                                                    "pagenr" => $page,
                                                                    "p" => $lp +1,
                                                                    "intern" => $intern,
                                                                    "wichtig" => $wichtig,
                                                                    "lpost" => cut(re($text), 100),
                                                                    "kat" => re($getft['kattopic']),
                                                                    "titel" => re($getft['topic']),
                                                                    "kid" => $getft['kid']));
                }
            }
        }

        // Userlevel
        if(($lvl = data("level")) == 1) $mylevel = _status_user;
        elseif($lvl == 2) $mylevel = _status_trial;
        elseif($lvl == 3) $mylevel = _status_member;
        elseif($lvl == 4) $mylevel = _status_admin;
        
        if(empty($ftopics))
            $ftopics = '<tr><td colspan="2" class="contentMainSecond">'._no_entrys.'</td></tr>';

        $erase = $can_erase ? _user_new_erase : '';
        $index = show($dir."/userlobby", array("userlobbyhead" => _userlobby,
                                               "erase" => $erase,
                                               "pic" => useravatar(),
                                               "mynick" => autor($userid),
                                               "myrank" => getrank($userid),
                                               "myposts" => userstats("forumposts"),
                                               "mylogins" => userstats("logins"),
                                               "myhits" => userstats("hits"),
                                               "mymsg" => $mymsg,
                                               "mylevel" => $mylevel,
                                               "puser" => _user,
                                               "plevel" => _admin_user_level,
                                               "plogins" => _profil_logins,
                                               "phits" => _profil_pagehits,
                                               "prank" => _profil_position,
                                               "pposts" => _profil_forenposts,
                                               "nkal" => _kalender,
                                               "kal" => $nextkal,
                                               "nart" => _artikel,
                                               "art" => $artikel,
                                               "nartc" => _lobby_artikelc,
                                               "artc" => $artc,
                                               "board" => _forum,
                                               "threads" => _forum_thread,
                                               "rankings" => $rankings,
                                               "nrankings" => _lobby_rankings,
                                               "awards" => $awards,
                                               "nawards" => _lobby_awards,
                                               "nforum" => _lobby_forum,
                                               "ftopics" => $ftopics,
                                               "lastforum" => _last_forum,
                                               "forum" => $forumposts,
                                               "nvotes" => _lobby_votes,
                                               "ncwcom" => _cw_comments_head,
                                               "cwcom" => $cwcom,
                                               "ngal" => _lobby_gallery,
                                               "gal" => $gal,
                                               "votes" => $newv,
                                               "cws" => $cws,
                                               "ncws" => _lobby_cw,
                                               "nnewsc" => _lobby_newsc,
                                               "newsc" => $newsc,
                                               "ngb" => _lobby_gb,
                                               "gb" => $gb,
                                               "nuser" => _lobby_user,
                                               "user" => $user,
                                               "nmgb" => _lobby_membergb,
                                               "mgb" => $membergb,
                                               "nmsg" => _msg,
                                               "nnews" => _lobby_news,
                                               "news" => $news,
                                               "away_new" => $away_new,
                                               "away_now" => $away_now,
                                               "neuerungen" => _lobby_new));
    }
    else
        $index = error(_error_have_to_be_logged, 1);
}