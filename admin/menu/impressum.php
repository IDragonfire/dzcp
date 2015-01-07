<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._config_impressum_head;

      $qry = db("SELECT i_domain,i_autor FROM ".$db['settings']."");
      $get = _fetch($qry);

      $show_ = show($dir."/form_impressum", array("idomain" => _config_impressum_domains,
                                                  "domain" => re($get['i_domain']),
                                                  "bbcode" => bbcode("seitenautor"),
                                                  "iautor" => _config_impressum_autor,
                                                  "postautor" => re_bbcode($get['i_autor'])));

      $show = show($dir."/imp", array("head" => _config_impressum_head,
                                      "what" => "impressum",
                                      "value" => _button_value_edit,
                                      "show" => $show_));
      if($do == "update")
      {
        $qry = db("UPDATE ".$db['settings']."
                   SET `i_autor` = '".up($_POST['seitenautor'])."',
                       `i_domain` = '".up($_POST['domain'])."'
                   WHERE id = 1");

        $show = info(_config_set, "?admin=impressum");
      }