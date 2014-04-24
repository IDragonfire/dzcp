<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_clankasse;
$title = $pagetitle." - ".$where."";
$dir = "clankasse";
## SECTIONS ##
$w = settings("k_waehrung");
switch ($action):
default:
  if(!$chkMe || $chkMe < 2)
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
    $entrys = cnt($db['clankasse']);
    if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("betrag","transaktion","datum","member"))) {
            $sub_orderby = "";
            if($_GET['orderby'] == "betrag") $sub_orderby = "pm ".$_GET['order'].",";
           $qry = db("SELECT * FROM ".$db['clankasse']."
                       ORDER BY ".$sub_orderby.mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."
                       LIMIT ".($page - 1)*config('m_clankasse').",".config('m_clankasse')."");

    }
    else { $qry = db("SELECT * FROM ".$db['clankasse']."
                      ORDER BY datum DESC
                      LIMIT ".($page - 1)*config('m_clankasse').",".config('m_clankasse')."");
    }
    while ($get = _fetch($qry))
    {
      $betrag = $get['betrag'];
      $betrag = str_replace(".",",",$betrag);

      if($get['pm'] == "0")
      {
        $pm = show(_clankasse_plus, array("betrag" => $betrag,
                                          "w" => $w));
      } else {
        $pm = show(_clankasse_minus, array("betrag" => $betrag,
                                           "w" => $w));
      }

      $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                   "title" => _button_title_edit,
                                                   "action" => "action=admin&amp;do=edit"));
      $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                       "title" => _button_title_delete,
                                                       "action" => "action=admin&amp;do=delete",
                                                       "del" => convSpace(_confirm_del_entry)));

      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

      $show .= show($dir."/clankasse_show", array("betrag" => $pm,
                                                  "id" => $get['id'],
                                                  "class" => $class,
                                                  "for" => re($get['member']),
                                                  "transaktion" => re($get['transaktion']),
                                                  "delete" => $delete,
                                                  "edit" => $edit,
                                                  "datum" => date("d.m.Y",$get['datum'])));
      $i--;
    }

    $qryp = db("SELECT sum(betrag) AS gesamt
                FROM ".$db['clankasse']."
                WHERE pm = 0");
    $getp = _fetch($qryp);

    $qryc = db("SELECT sum(betrag) AS gesamt
                FROM ".$db['clankasse']."
                WHERE pm = 1");
    $getc = _fetch($qryc);

    $ges = $getp['gesamt'] - $getc['gesamt'];
    $ges = @round($ges,2);
    $ges = str_replace(".",",",$ges);

    if($getp['gesamt'] < $getc['gesamt'])
    {
      $gesamt = show(_clankasse_summe_minus, array("summe" => $ges,
                                                   "w" => $w));
    } else {
      $gesamt = show(_clankasse_summe_plus, array("summe" => $ges,
                                                  "w" => $w));
    }

   if(permission("clankasse")) $new = _clankasse_new;

    if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("nick","payed"))) {
            $qrys = db("SELECT tbl1.id,tbl1.nick,tbl2.user,tbl2.payed
               FROM ".$db['users']." AS tbl1
               LEFT JOIN ".$db['c_payed']." AS tbl2 ON tbl2.user = tbl1.id
               WHERE tbl1.listck = '1'
               OR tbl1.level = '4'
               ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."");

    }
    else {
        $qrys = db("SELECT tbl1.id,tbl1.nick,tbl2.user,tbl2.payed
               FROM ".$db['users']." AS tbl1
               LEFT JOIN ".$db['c_payed']." AS tbl2 ON tbl2.user = tbl1.id
               WHERE tbl1.listck = '1'
               OR tbl1.level = '4'
               ORDER BY tbl1.nick");
    }
    while($gets = _fetch($qrys))
    {
      if($gets['user'])
      {
        if($gets['payed'] >= time())
        {
          $status = show(_clankasse_status_payed, array("payed" => date("d.m.Y", $gets['payed'])));
        } elseif(date("d.m.Y", $gets['payed']) == date("d.m.Y", time())) {
          $status = show(_clankasse_status_today, array());
        } else {
          $status = show(_clankasse_status_notpayed, array("payed" => date("d.m.Y", $gets['payed'])));
        }
      } else {
        $status = show(_clankasse_status_noentry, array());
      }

      if(permission("clankasse")) $edit = show(_admin_ck_edit, array("id" => $gets['id']));
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

      $showstatus .= show($dir."/status", array("nick" => autor($gets['id']),
                                                "status" => $status,
                                                "class" => $class,
                                                "edit" => $edit));
    }
    $qry = db("SELECT k_inhaber,k_nr,k_blz,k_bank,iban,bic,k_waehrung,k_vwz FROM ".$db['settings']."");
    $get = _fetch($qry);

    $seiten = nav($entrys,config('m_clankasse'),"?action=nav");
    $index = show($dir."/clankasse", array("show" => $show,
                                           "showstatus" => $showstatus,
                                           "clankasse_head" => _clankasse_head,
                                           "server_head" => _clankasse_server_head,
                                           "kinhaber" => _clankasse_inhaber,
                                           "knr" => _clankasse_nr,
                                           "kblz" => _clankasse_blz,
                                           "kbank" => _clankasse_bank,
                                           "kvwz" => _clankasse_vwz,
                                           "cfor" => _clankasse_for,
                                           "cdatum" => _datum,
                                           "ctransaktion" => _clankasse_ctransaktion,
                                           "cbetrag" => _clankasse_cbetrag,
                                           "cakt" => _clankasse_cakt,
                                           "edit" => _editicon_blank,
                                           "delete" => _deleteicon_blank,
                                           "didpayed" => _clankasse_didpayed,
                                           "nick" => _nick,
                                           "status" => _clankasse_status_status,
                                           "order_nick" => orderby('nick'),
                                           "order_status" => orderby('payed'),
                                           "order_cdatum" => orderby('datum'),
                                           "order_ctransaktion" => orderby('transaktion'),
                                           "order_cfor" => orderby('member'),
                                           "order_cbetrag" => orderby('betrag'),
                                           "inhaber" => $get['k_inhaber'],
                                           "kontonr" => $get['k_nr'],
                                           "new" => $new,
                                           "blz" => $get['k_blz'],
                                           "iban" => $get['iban'],
                                           "bic" => $get['bic'],
                                           "bank" => $get['k_bank'],
                                           "vwz" => $get['k_vwz'],
                                           "summe" => $gesamt,
                                           "seiten" => $seiten,
                                           "beitrag" => $beitrag));
  }
break;
case 'admin':
  if(permission("clankasse"))
  {
    if ($do == "new")
    {
      $qry = db("SELECT * FROM ".$db['c_kats']."");
      while($get = _fetch($qry))
      {
        $trans .= show(_select_field, array("value" => re($get['kat']),
                                            "sel" => "",
                                            "what" => re($get['kat'])));
      }

      $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                          "month" => dropdown("month",date("m",time())),
                                                     "year" => dropdown("year",date("Y",time()))));

      $index = show($dir."/new", array("newhead" => _clankasse_head_new,
                                       "betrag" => _clankasse_cbetrag,
                                       "datum" => _datum,
                                       "vonan" => _clankasse_for,
                                       "thisyear" => date("Y"),
                                       "beitrag" => _clankasse_sbeitrag,
                                       "miete" => _clankasse_smiete,
                                       "value" => _button_value_add,
                                       "dropdown_date" => $dropdown_date,
                                       "ssonstiges" => _clankasse_ssonstiges,
                                       "einzahlung" => _clankasse_einzahlung,
                                       "auszahlung" => _clankasse_auszahlung,
                                       "trans" => $trans,
                                       "sponsor" => _clankasse_ssponsor,
                                       "sonstiges" => _clankasse_sonstiges,
                                       "member" => _member,
                                       "transaktion" => _clankasse_ctransaktion,
                                       "minus" => _clankasse_admin_minus,
                                       "post" => time()));

    } elseif ($do == "add") {
      if(!$_POST['t'] OR !$_POST['m'])
      {
        $index = error(_error_clankasse_empty_datum, 1);
      } elseif($_POST['transaktion'] == "lazy") {
        $index = error(_error_clankasse_empty_transaktion, 1);
      } elseif(!$_POST['betrag']) {
        $index = error(_error_clankasse_empty_betrag, 1);
      } else {
        $betrag = $_POST['betrag'];
        $betrag = preg_replace("#,#iUs",".",$betrag);
        $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

        $qry = db("INSERT INTO ".$db['clankasse']."
                   SET `datum`        = '".((int)$datum)."',
                       `member`       = '".$_POST['member']."',
                       `transaktion`  = '".up($_POST['transaktion'])."',
                       `pm`           = '".((int)$_POST['pm'])."',
                       `betrag`       = '".up($betrag)."'");

        $index = info(_clankasse_saved, "../clankasse/");
      }
    } elseif ($do == "delete" && $_GET['id']) {
      $qry = db("DELETE FROM ".$db['clankasse']."
                 WHERE id = ".intval($_GET['id']));

      $index = info(_clankasse_deleted, "../clankasse/");
    } elseif ($do == "update" && $_POST['id']) {
      if(!$_POST['datum'])
      {
        $index = error(_error_clankasse_empty_datum, 1);
      } elseif(!$_POST['betrag']) {
          $index = error(_error_clankasse_empty_betrag, 1);
      } elseif(!$_POST['transaktion']) {
          $index = error(_error_clankasse_empty_transaktion, 1);
      } else {
          $res = db("UPDATE ".$db['clankasse']."
                     SET `datum`        = '".((int)$_POST['datum'])."',
                         `transaktion`  = '".up($_POST['transaktion'])."',
                         `pm`           = '".((int)$_POST['pm'])."',
                         `betrag`       = '".up($_POST['betrag'])."'
                     WHERE id = ".intval($_POST['id']));

            $index = info(_clankasse_edited, "../clankasse/");
      }
    } elseif ($do == "edit") {
      $qry = db("SELECT * FROM ".$db['clankasse']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
                                                             "month" => dropdown("month",date("m",$get['datum'])),
                                                    "year" => dropdown("year",date("Y",$get['datum']))));

      if($get['pm'] == "0") $psel = 'selected="selected"';
      else $msel = 'selected="selected"';

      $qryk = db("SELECT * FROM ".$db['c_kats']."");
      while($getk = _fetch($qryk))
      {
        if($getk['kat'] == $get['transaktion']) $sel = 'selected="selected"';
        else $sel = "";

        $trans .= show(_select_field, array("value" => re($getk['kat']),
                                            "sel" => $sel,
                                            "what" => re($getk['kat'])));
      }
      $index = show($dir."/edit", array("newhead" => _clankasse_head_edit,
                                        "betrag" => _clankasse_cbetrag,
                                        "datum" => _datum,
                                        "vonan" => _clankasse_for,
                                        "dropdown_date" => $dropdown_date,
                                        "id" => $_GET['id'],
                                        "psel" => $psel,
                                        "msel" => $msel,
                                        "value" => _button_value_edit,
                                        "bsel" => $bsel,
                                        "misel" => $misel,
                                        "ssel" => $ssel,
                                        "spsel" => $spsel,
                                        "trans" => $trans,
                                        "evonan" => re($get['member']),
                                        "sum" => re($get['betrag']),
                                        "beitrag" => _clankasse_sbeitrag,
                                        "miete" => _clankasse_smiete,
                                        "ssonstiges" => _clankasse_ssonstiges,
                                        "einzahlung" => _clankasse_einzahlung,
                                        "auszahlung" => _clankasse_auszahlung,
                                        "sponsor" => _clankasse_ssponsor,
                                        "sonstiges" => _clankasse_sonstiges,
                                        "member" => _member,
                                        "transaktion" => _clankasse_ctransaktion,
                                        "minus" => _clankasse_admin_minus,
                                        "post" => time()));
    } elseif($do == "editck") {
      if(!$_POST['t'] OR !$_POST['m'])
      {
        $index = error(_error_clankasse_empty_datum, 1);
      } elseif($_POST['transaktion'] == "lazy") {
        $index = error(_error_clankasse_empty_transaktion, 1);
      } elseif(!$_POST['betrag']) {
        $index = error(_error_clankasse_empty_betrag, 1);
      } else {
        $betrag = $_POST['betrag'];
        $betrag = preg_replace("#,#iUs",".",$betrag);
        $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

        $qry = db("UPDATE ".$db['clankasse']."
                   SET `datum`        = '".((int)$datum)."',
                       `member`       = '".up($_POST['member'])."',
                       `transaktion`  = '".up($_POST['transaktion'])."',
                       `pm`           = '".((int)$_POST['pm'])."',
                       `betrag`       = '".up($betrag)."'
                   WHERE id = '".intval($_GET['id'])."'");

            $index = info(_clankasse_edited, "../clankasse/");
      }
    } elseif($do == "paycheck") {
      $qry = db("SELECT payed FROM ".$db['c_payed']."
                 WHERE user = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      if(_rows($qry))
      {
        $tag = date("d", $get['payed']);
        $monat = date("m", $get['payed']);
        $jahr = date("Y", $get['payed']);
      } else {
        $tag = date("d", time());
        $monat = date("m", time());
        $jahr = date("Y", time());
      }
      $index = show($dir."/paycheck", array("id" => $_GET['id'],
                                            "head" => _clankasse_edit_paycheck,
                                            "user" => _user,
                                            "value" => _button_value_edit,
                                            "payed_till" => _clankasse_payed_till,
                                            "puser" => autor($_GET['id']),
                                            "t" => $tag,
                                            "m" => $monat,
                                            "j" => $jahr));
    } elseif($do == "editpaycheck") {
      $qry = db("SELECT payed FROM ".$db['c_payed']."
                 WHERE user = '".intval($_GET['id'])."'");

      $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
      if(_rows($qry))
      {
        $update = db("UPDATE ".$db['c_payed']."
                      SET `payed` = '".((int)$datum)."'
                      WHERE user = '".intval($_GET['id'])."'");
      } else {
        $insert = db("INSERT INTO ".$db['c_payed']."
                      SET `user`  = '".((int)$_GET['id'])."',
                          `payed` = '".((int)$datum)."'");
      }
      $index = info(_info_clankass_status_edited, "../clankasse/");
    }
  } else {
    $index = error(_error_wrong_permissions, 1);
  }
break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();