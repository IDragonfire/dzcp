<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._clear_head;
      if($do == "clear")
      {
        if(empty($_POST['days']))
        {
          $show = error(_clear_error_days,1);
        } else {
          $time = time()-($_POST['days']*24*60*60);
          if(isset($_POST['news']))
          {
            $del = db("DELETE FROM ".$db['news']."
                       WHERE datum <= '".intval($time)."'");
            $del = db("DELETE FROM ".$db['newscomments']."
                       WHERE datum <= '".intval($time)."'");
          }
          if(isset($_POST['away']))
          {
            $del = db("DELETE FROM ".$db['away']."
                       WHERE date <= '".intval($time)."'");
          }
          if(isset($_POST['forum']))
          {
            $qry = db("SELECT id FROM ".$db['f_threads']."
                       WHERE t_date <= '".intval($time)."'
                       AND sticky != 1");
            while($get = _fetch($qry))
            {
              $del = db("DELETE FROM ".$db['f_threads']."
                         WHERE id = '".intval($get['id'])."'");
              $del = db("DELETE FROM ".$db['f_posts']."
                         WHERE sid = '".intval($get['id'])."'");
            }
          }
          $show = info(_clear_deleted, "../admin/");
        }
      } else {
        $show = show($dir."/clear", array("head" => _clear_head,
                                          "away" => _clear_away,
                                          "news" => _clear_news,
                                          "forum" => _clear_forum,
                                          "value" => _button_value_clear,
                                          "misc" => _clear_misc,
                                          "days" => _clear_days,
                                          "what" => _clear_what,
                                          "c_days" => "",
                                          "forum_info" => _clear_forum_info));
      }