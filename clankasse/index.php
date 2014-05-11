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
switch ($action):
    default:
        if(!$chkMe || $chkMe < 2)
            $index = error(_error_wrong_permissions, 1);
        else {
            $get_settings = settings(array('k_inhaber','k_nr','k_blz','k_bank','iban','bic','k_waehrung','k_vwz'));
            $entrys = cnt($db['clankasse']);
            $qry = db("SELECT id,pm,betrag,member,transaktion,datum FROM ".$db['clankasse']."
                      ".orderby_sql(array("betrag","transaktion","datum","member"), 'ORDER BY datum DESC')."
                       LIMIT ".($page - 1)*config('m_clankasse').",".config('m_clankasse')."");
            while ($get = _fetch($qry)) {
                $betrag = $get['betrag'];
                $betrag = str_replace(".",",",$betrag);
                $pm = show(($get['pm'] == "0" ? _clankasse_plus : _clankasse_minus),
                            array("betrag" => $betrag,"w" => $get_settings['k_waehrung']));

                $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                              "title" => _button_title_edit,
                                                              "action" => "action=admin&amp;do=edit"));

                $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                                  "title" => _button_title_del,
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
            }

            $getp = sum($db['clankasse'], ' WHERE pm = 0', 'betrag');
            $getc = sum($db['clankasse'], ' WHERE pm = 1', 'betrag');
            $ges = $getp - $getc;
            $ges = @round($ges,2);
            $ges = str_replace(".",",",$ges);
            $gesamt = show(($getp < $getc ? _clankasse_summe_minus : _clankasse_summe_plus),
                            array("summe" => $ges, "w" => $get_settings['k_waehrung']));

            $qrys = db("SELECT tbl1.id,tbl1.nick,tbl2.user,tbl2.payed
                        FROM ".$db['users']." AS tbl1
                        LEFT JOIN ".$db['c_payed']." AS tbl2 ON tbl2.user = tbl1.id
                        WHERE tbl1.listck = '1'
                        OR tbl1.level = '4'
                        ".orderby_sql(array("payed"), orderby_sql(array("nick"), 'ORDER BY tbl1.nick', 'tbl1'), 'tbl2'));
            $showstatus = '';
            while($gets = _fetch($qrys)) {
                if($gets['user']) {
                    if($gets['payed'] >= time())
                        $status = show(_clankasse_status_payed, array("payed" => date("d.m.Y", $gets['payed'])));
                    elseif(date("d.m.Y", $gets['payed']) == date("d.m.Y", time()))
                        $status = show(_clankasse_status_today, array());
                    else
                        $status = show(_clankasse_status_notpayed, array("payed" => date("d.m.Y", $gets['payed'])));
                }
                else
                    $status = show(_clankasse_status_noentry, array());

                if(permission("clankasse")) $edit = show(_admin_ck_edit, array("id" => $gets['id']));
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $showstatus .= show($dir."/status", array("nick" => autor($gets['id']),
                                                          "status" => $status,
                                                          "class" => $class,
                                                          "edit" => $edit));
            }

            $seiten = nav($entrys,config('m_clankasse'),"?action=nav");
            $new = permission("clankasse") ? _clankasse_new : '';
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
                                                   "inhaber" => $get_settings['k_inhaber'],
                                                   "kontonr" => $get_settings['k_nr'],
                                                   "new" => $new,
                                                   "blz" => $get_settings['k_blz'],
                                                   "iban" => $get_settings['iban'],
                                                   "bic" => $get_settings['bic'],
                                                   "bank" => $get_settings['k_bank'],
                                                   "vwz" => $get_settings['k_vwz'],
                                                   "summe" => $gesamt,
                                                   "seiten" => $seiten));
        }
    break;
    case 'admin':
        if(permission("clankasse")) {
            if ($do == "new") {
                $qry = db("SELECT kat FROM ".$db['c_kats'].""); $trans = '';
                while($get = _fetch($qry)) {
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
                                                 "value" => _button_value_add,
                                                 "dropdown_date" => $dropdown_date,
                                                 "einzahlung" => _clankasse_einzahlung,
                                                 "auszahlung" => _clankasse_auszahlung,
                                                 "trans" => $trans,
                                                 "w" => settings("k_waehrung"),
                                                 "sonstiges" => _clankasse_sonstiges,
                                                 "member" => _member,
                                                 "transaktion" => _clankasse_ctransaktion,
                                                 "minus" => _clankasse_admin_minus,
                                                 "post" => time()));

            } elseif($do == "add") {
                if(!$_POST['t'] OR !$_POST['m'])
                    $index = error(_error_clankasse_empty_datum, 1);
                elseif($_POST['transaktion'] == "lazy")
                    $index = error(_error_clankasse_empty_transaktion, 1);
                elseif(!$_POST['betrag'])
                    $index = error(_error_clankasse_empty_betrag, 1);
                else {
                    $betrag = $_POST['betrag'];
                    $betrag = preg_replace("#,#iUs",".",$betrag);
                    $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
                    db("INSERT INTO ".$db['clankasse']."
                        SET `datum`        = '".((int)$datum)."',
                            `member`       = '".$_POST['member']."',
                            `transaktion`  = '".up($_POST['transaktion'])."',
                            `pm`           = '".((int)$_POST['pm'])."',
                            `betrag`       = '".up($betrag)."'");

                    $index = info(_clankasse_saved, "../clankasse/");
                }
            } elseif ($do == "delete" && isset($_GET['id'])) {
                db("DELETE FROM ".$db['clankasse']." WHERE id = ".intval($_GET['id']));
                $index = info(_clankasse_deleted, "../clankasse/");
            } elseif ($do == "update" && isset($_GET['id'])) {
                if(!$_POST['datum'])
                    $index = error(_error_clankasse_empty_datum, 1);
                elseif(!$_POST['betrag'])
                    $index = error(_error_clankasse_empty_betrag, 1);
                elseif(!$_POST['transaktion'])
                    $index = error(_error_clankasse_empty_transaktion, 1);
                else
                {
                    db("UPDATE ".$db['clankasse']."
                        SET `datum`        = '".((int)$_POST['datum'])."',
                            `transaktion`  = '".up($_POST['transaktion'])."',
                            `pm`           = '".((int)$_POST['pm'])."',
                            `betrag`       = '".up($_POST['betrag'])."'
                        WHERE id = ".intval($_POST['id']));

                    $index = info(_clankasse_edited, "../clankasse/");
                }
            } elseif ($do == "edit") {
                $get = db("SELECT * FROM ".$db['clankasse']." WHERE id = '".intval($_GET['id'])."'",false,true);
                $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
                                                            "month" => dropdown("month",date("m",$get['datum'])),
                                                            "year" => dropdown("year",date("Y",$get['datum']))));

                $psel = ($get['pm'] == "0" ? 'selected="selected"' : '');
                $msel = ($get['pm'] == "1" ? 'selected="selected"' : '');
                $qryk = db("SELECT * FROM ".$db['c_kats'].""); $trans = '';
                while($getk = _fetch($qryk)) {
                    $sel = ($getk['kat'] == $get['transaktion'] ? 'selected="selected"' : '');
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
                                                  "trans" => $trans,
                                                  "w" => settings("k_waehrung"),
                                                  "evonan" => re($get['member']),
                                                  "sum" => re($get['betrag']),
                                                  "einzahlung" => _clankasse_einzahlung,
                                                  "auszahlung" => _clankasse_auszahlung,
                                                  "sonstiges" => _clankasse_sonstiges,
                                                  "member" => _member,
                                                  "transaktion" => _clankasse_ctransaktion,
                                                  "minus" => _clankasse_admin_minus,
                                                  "post" => time()));
            } elseif($do == "editck") {
                if(!$_POST['t'] OR !$_POST['m'])
                    $index = error(_error_clankasse_empty_datum, 1);
                elseif($_POST['transaktion'] == "lazy")
                    $index = error(_error_clankasse_empty_transaktion, 1);
                elseif(!$_POST['betrag'])
                    $index = error(_error_clankasse_empty_betrag, 1);
                else {
                    $betrag = $_POST['betrag'];
                    $betrag = preg_replace("#,#iUs",".",$betrag);
                    $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

                    db("UPDATE ".$db['clankasse']."
                        SET `datum`        = '".((int)$datum)."',
                            `member`       = '".up($_POST['member'])."',
                            `transaktion`  = '".up($_POST['transaktion'])."',
                            `pm`           = '".((int)$_POST['pm'])."',
                            `betrag`       = '".up($betrag)."'
                        WHERE id = '".intval($_GET['id'])."'");

                    $index = info(_clankasse_edited, "../clankasse/");
                }
            } elseif($do == "paycheck") {
                $qry = db("SELECT payed FROM ".$db['c_payed']." WHERE user = '".intval($_GET['id'])."'");
                if(_rows($qry))
                {
                    $get = _fetch($qry);
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
                $qry = db("SELECT payed FROM ".$db['c_payed']." WHERE user = '".intval($_GET['id'])."'");
                $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
                if(_rows($qry))
                {
                    db("UPDATE ".$db['c_payed']."
                        SET `payed` = '".((int)$datum)."'
                        WHERE user = '".intval($_GET['id'])."'");
                } else {
                    db("INSERT INTO ".$db['c_payed']."
                        SET `user`  = '".((int)$_GET['id'])."',
                            `payed` = '".((int)$datum)."'");
                }

                $index = info(_info_clankass_status_edited, "../clankasse/");
            }
        }
        else
            $index = error(_error_wrong_permissions, 1);
    break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);
