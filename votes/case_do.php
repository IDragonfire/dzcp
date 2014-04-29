<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_Votes')) {
    if(isset($_GET['what']) && $_GET['what'] == "vote") {
        if(empty($_POST['vote']))
            $index = error(_vote_no_answer);
        else {
            $get = db("SELECT * FROM ".$db['votes']." WHERE id = '".intval($_GET['id'])."'",false,true);
            if($get['intern']) {
                $ipcheck = db("SELECT ip FROM ".$db['ipcheck']." WHERE what = 'vid_".intval($_GET['id'])."'",false,true);
                if($ipcheck['ip'] == $userid)
                    $index = error(_error_voted_again,1);
                else if($get['closed'])
                    $index = error(_error_vote_closed,1);
                else {
                    db("UPDATE ".$db['userstats']." SET `votes` = votes+1 WHERE user = '".$userid."'");
                    db("UPDATE ".$db['vote_results']." SET `stimmen` = stimmen+1 WHERE id = '".intval($_POST['vote'])."'");

                    setIpcheck("vid_".intval($_GET['id']));
                    setIpcheck("vid(".intval($_GET['id']).")");

                    if(!isset($_GET['ajax']))
                        $index = info(_vote_successful, "?show=".$_GET['id']."");
                }
            } else {
                if(ipcheck("vid_".$_GET['id']))
                    $index = error(_error_voted_again,1);
                else if($get['closed'])
                    $index = error(_error_vote_closed,1);
                else {
                    if($userid >= 1)
                        db("UPDATE ".$db['userstats']." SET `votes` = votes+1 WHERE user = '".$userid."'");

                    db("UPDATE ".$db['vote_results']." SET `stimmen` = stimmen+1 WHERE id = ".intval($_POST['vote']));

                    setIpcheck("vid_".intval($_GET['id']));
                    setIpcheck("vid(".intval($_GET['id']).")");

                    if(!isset($_GET['ajax']))
                        $index = info(_vote_successful, "?show=".$_GET['id']."");
                }
            }

            $cookie = $userid >= 1 ? $userid : "voted";
            cookie::put('vid_'.$_GET['id'], $cookie);
        }

        if(isset($_GET['ajax']))
        {
            header("Content-type: text/html; charset=utf-8");
            require_once(basePath.'/inc/menu-functions/vote.php');
            echo '<table class="navContent" cellspacing="0">'.vote(1).'</table>';

            if(!mysqli_persistconns)
                $mysql->close(); //MySQL

            exit();
        }
    }

    if(isset($_GET['what']) && $_GET['what'] == "fvote")
    {
        if(empty($_POST['vote']))
            $index = error(_vote_no_answer);
        else {
            $get = db("SELECT * FROM ".$db['votes']." WHERE id = '".intval($_GET['id'])."'",false,true);

            if(ipcheck("vid_".$_GET['id']))
                $index = error(_error_voted_again,1);
            else if($get['closed'])
                $index = error(_error_vote_closed,1);
            else {
                if($userid >= 1)
                    db("UPDATE ".$db['userstats']." SET `votes` = votes+1 WHERE user = '".$userid."'");

                db("UPDATE ".$db['vote_results']." SET `stimmen` = stimmen+1 WHERE id = '".intval($_POST['vote'])."'");

                setIpcheck("vid_".intval($_GET['id']));
                setIpcheck("vid(".intval($_GET['id']).")");

                if(!isset($_GET['fajax']))
                    $index = info(_vote_successful, "../forum/?action=showthread&amp;kid=".$_POST['kid']."&amp;id=".$_POST['fid']."");
            }
        }

        $cookie = $userid >= 1 ? $userid : "voted";
        cookie::put('vid_'.$_GET['id'], $cookie);
    }

    if(isset($_GET['fajax']))
    {
        require_once(basePath.'/inc/menu-functions/fvote.php');
        header("Content-type: text/html; charset=utf-8");
        echo fvote($_GET['id'], 1);

        if(!mysqli_persistconns)
            $mysql->close(); //MySQL

        exit();
    }
}