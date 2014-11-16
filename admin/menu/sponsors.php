<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._config_sponsors;
      if($do == "new")
      {

        $qry = db("SELECT * FROM ".$db['sponsoren']."
                   ORDER BY pos");
        while($get = _fetch($qry))
        {
          $positions .= show(_select_field, array("value" => $get['pos']+1,
                                                  "sel" => "",
                                                  "what" => _nach.' '.re($get['name'])));
          $posname = $get['name'];
        }

        $show = show($dir."/form_sponsors", array("head" => _sponsors_admin_head,
                                                                          "error" => "",
                                                     "name" => _sponsors_admin_name,
                                                                          "sname" => "",
                                                                          "link" => _links_link,
                                                                          "slink" => "",
                                                                          "beschreibung" => _beschreibung,
                                                                          "sbeschreibung" => "",
                                                                          "site" => _sponsors_admin_site,
                                                                          "addsite" => _sponsors_admin_addsite,
                                                                          "schecked" => "",
                                                                          "snone" => "none",
                                                                          "add_site" => _sponsors_admin_add_site,
                                                                          "upload" => _sponsors_admin_upload,
                                                                          "url" => _sponsors_admin_url,
                                                                          "site_link" => "",
                                                                          "sitepic" => "",
                                                                                                    "banner" => _sponsors_admin_banner,
                                                                          "addbanner" => _sponsors_admin_addbanner,
                                                                          "bchecked" => "",
                                                                          "bnone" => "none",
                                                                          "add_banner" => _sponsors_admin_add_banner,
                                                                          "banner_link" => "",
                                                                          "bannerpic" => "",
                                                                                                    "box" => _sponsors_admin_box,
                                                                          "addbox" => _sponsors_admin_addbox,
                                                                          "xchecked" => "",
                                                                          "xnone" => "none",
                                                                          "add_box" => _sponsors_admin_add_box,
                                                                          "box_link" => "",
                                                                          "boxpic" => "",
                                                                                                    "pos" => _position,
                                                                          "first" => _admin_first,
                                                                          "positions" => $positions,
                                                                          "posname" => $posname,
                                                                          "what" => _button_value_add,
                                                     "do" => "add"));
      } elseif($do == "add") {
        if(empty($_POST['name']) || empty($_POST['link']) || empty($_POST['beschreibung']))
        {
          if(empty($_POST['beschreibung'])) $error = show("errors/errortable", array("error" => _sponsors_empty_beschreibung));
              if(empty($_POST['link']))         $error = show("errors/errortable", array("error" => _sponsors_empty_link));
              if(empty($_POST['name']))         $error = show("errors/errortable", array("error" => _sponsors_empty_name));

          $pos = db("SELECT pos,name FROM ".$db['sponsoren']."
                     ORDER BY pos");
          while($getpos = _fetch($pos))
          {
            if($getpos['name'] != $_POST['posname'])
            {
              $mpos = db("SELECT pos FROM ".$db['sponsoren']."
                          WHERE name != '".$_POST['posname']."'
                          AND pos = '".intval(($_POST['position']-1))."'");
              $mp = _fetch($mpos);

              if($getpos['pos'] == $mp['pos']) $sel = 'selected="selected"';
              else $sel = '';

              $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                      "what" => _nach.' '.re($getpos['name']),
                                                      "sel" => $sel));
            }
          }

            if(isset($_POST['site']))
            {
              $schecked = 'checked="checked"';
              $snone = "";
            } else {
              $schecked = "";
              $snone = "none";
            }
            if(isset($_POST['banner']))
            {
              $bchecked = 'checked="checked"';
              $bnine = "";
            } else {
              $bchecked = "";
              $bnone = "none";
            }
            if(isset($_POST['box']))
            {
              $xchecked = 'checked="checked"';
              $xnone = "";
            } else {
              $xchecked = "";
              $xnone = "none";
            }

            $show = show($dir."/form_sponsors", array("head" => _sponsors_admin_head,
                                                         "error" => $error,
                                                      "name" => _sponsors_admin_name,
                                                      "sname" => $_POST['name'],
                                                      "link" => _links_link,
                                                      "slink" => $_POST['link'],
                                                      "beschreibung" => _beschreibung,
                                                      "sbeschreibung" => re($_POST['beschreibung']),
                                                      "site" => _sponsors_admin_site,
                                                      "addsite" => _sponsors_admin_addsite,
                                                      "schecked" => $schecked,
                                                      "snone" => $snone,
                                                      "add_site" => _sponsors_admin_add_site,
                                                      "upload" => _sponsors_admin_upload,
                                                      "url" => _sponsors_admin_url,
                                                      "site_link" => $_POST['slink'],
                                                      "sitepic" => "",
                                                            "banner" => _sponsors_admin_banner,
                                                      "addbanner" => _sponsors_admin_addbanner,
                                                      "bchecked" => $bchecked,
                                                      "bnone" => $bnone,
                                                      "add_banner" => _sponsors_admin_add_banner,
                                                      "banner_link" => $_POST['blink'],
                                                      "bannerpic" => "",
                                                            "box" => _sponsors_admin_box,
                                                      "addbox" => _sponsors_admin_addbox,
                                                      "xchecked" => $xchecked,
                                                      "xnone" => $xnone,
                                                      "add_box" => _sponsors_admin_add_box,
                                                      "box_link" => $_POST['xlink'],
                                                        "boxpic" => "",
                                                            "pos" => _position,
                                                        "first" => _admin_first,
                                                        "positions" => $positions,
                                                        "posname" => $_POST['posname'],
                                                      "what" => _button_value_add,
                                                      "do" => "add"));


        } else {
          if($_POST['position'] == 1 || $_POST['position'] == 2) $sign = ">= ";
          else $sign = "> ";

          $posi = db("UPDATE ".$db['sponsoren']."
                      SET `pos` = pos+1
                      WHERE pos ".$sign." '".intval($_POST['position'])."'");

          $qry = db("INSERT INTO ".$db['sponsoren']."
                     SET `name`         = '".up($_POST['name'])."',
                                     `link`         = '".links($_POST['link'])."',
                                     `beschreibung` = '".up($_POST['beschreibung'])."',
                                     `site`         = '".intval($_POST['site'])."',
                                     `slink`        = '".$_POST['slink']."',
                                     `banner`       = '".intval($_POST['banner'])."',
                         `blink`        = '".$_POST['blink']."',
                         `box`           = '".intval($_POST['box'])."',
                         `xlink`         = '".up($_POST['xlink'])."',
                                     `pos`            = '".intval($_POST['position'])."'");

          $id = _insert_id();

          $tmp1 = $_FILES['sdata']['tmp_name'];
          $type1 = $_FILES['sdata']['type'];
          $end1 = explode(".", $_FILES['sdata']['name']);
          $end1 = strtolower($end1[count($end1)-1]);

          if(!empty($tmp1))
          {
            $img1 = getimagesize($tmp1);
                        if($type1 == "image/gif" || $type1 == "image/png" || $type1 == "image/jpeg" || !$img1[0])
            {
              @copy($tmp1, basePath."/banner/sponsors/site_".$id.".".strtolower($end1));
              @unlink($_FILES['sdata']['tmp_name']);
            }
                    db("UPDATE ".$db['sponsoren']." SET `send` = '".$end1."' WHERE id = '".intval($id)."'");
          }

                  $tmp2 = $_FILES['bdata']['tmp_name'];
          $type2 = $_FILES['bdata']['type'];
          $end2 = explode(".", $_FILES['bdata']['name']);
          $end2 = strtolower($end2[count($end2)-1]);
          $img2 = getimagesize($tmp2);
          if(!empty($tmp2))
          {
            if($type2 == "image/gif" || $type2 == "image/png" || $type2 == "image/jpeg" || !$img2[0])
            {
              @copy($tmp2, basePath."/banner/sponsors/banner_".$id.".".strtolower($end2));
              @unlink($_FILES['bdata']['tmp_name']);
            }
                    db("UPDATE ".$db['sponsoren']." SET `bend` = '".$end2."' WHERE id = '".intval($id)."'");
          }

                  $tmp3 = $_FILES['xdata']['tmp_name'];
          $type3 = $_FILES['xdata']['type'];
          $end3 = explode(".", $_FILES['xdata']['name']);
          $end3 = strtolower($end3[count($end3)-1]);

          if(!empty($tmp3))
          {
            $img3 = getimagesize($tmp3);
                        if($type3 == "image/gif" || $type3 == "image/png" || $type3 == "image/jpeg" || !$img3[0])
            {
              @copy($tmp3, basePath."/banner/sponsors/box_".$id.".".strtolower($end3));
              @unlink($_FILES['xdata']['tmp_name']);
            }
                    db("UPDATE ".$db['sponsoren']." SET `xend` = '".$end3."' WHERE id = '".intval($id)."'");
          }

          $show = info(_sponsor_added, "?admin=sponsors");
        }
      } elseif($do == "edit") {

        $qry = db("SELECT * FROM ".$db['sponsoren']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

          $pos = db("SELECT pos,name FROM ".$db['sponsoren']."
                     ORDER BY pos");
          while($getpos = _fetch($pos))
          {
            if($getpos['name'] != $get['name'])
            {
              $mpos = db("SELECT pos FROM ".$db['sponsoren']."
                          WHERE name != '".$get['name']."'
                          AND pos = '".intval(($get['pos']-1))."'");
              $mp = _fetch($mpos);

              if($getpos['pos'] == $mp['pos']) $sel = 'selected="selected"';
              else $sel = '';

              $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                      "what" => _nach.' '.re($getpos['name']),
                                                      "sel" => $sel));
              $posname = $getpos['name'];
            }
          }

        if($get['site'] == 1)
        {
          $schecked = 'checked="checked"';
          $snone = "";
        } else {
          $schecked = "";
          $snone = "none";
        }
        if($get['banner'] == 1)
        {
          $bchecked = 'checked="checked"';
          $bnone = "";
        } else {
          $bchecked = "";
          $bnone = "none";
        }
        if($get['box'] == 1)
        {
          $xchecked = 'checked="checked"';
          $xnone = "";
        } else {
          $xchecked = "";
          $xnone = "none";
        }

    foreach($picformat AS $end)
    {
      if(file_exists(basePath.'/banner/sponsors/site_'.$get['id'].'.'.$end))
            {
                $sitepic = '<img src="../banner/sponsors/site_'.$get['id'].'.'.$end.'" alt="" width="50%" />';
          break;
            }
    }

    foreach($picformat AS $end)
    {
            if(file_exists(basePath.'/banner/sponsors/banner_'.$get['id'].'.'.$end))
            {
                $bannerpic = '<img src="../banner/sponsors/banner_'.$get['id'].'.'.$end.'" alt="" width="50%" />';
                break;
            }
    }

    foreach($picformat AS $end)
    {
            if(file_exists(basePath.'/banner/sponsors/box_'.$get['id'].'.'.$end))
            {
                $boxpic = '<img src="../banner/sponsors/box_'.$get['id'].'.'.$end.'" alt="" width="50%" />';
                break;
            }
    }

         $show = show($dir."/form_sponsors", array("head" => _sponsors_admin_head,
                                                   "error" => "",
                                                   "name" => _sponsors_admin_name,
                                                   "sname" => $get['name'],
                                                   "link" => _links_link,
                                                   "slink" => $get['link'],
                                                   "beschreibung" => _beschreibung,
                                                   "sbeschreibung" => re($get['beschreibung']),
                                                   "site" => _sponsors_admin_site,
                                                   "addsite" => _sponsors_admin_addsite,
                                                   "schecked" => $schecked,
                                                   "snone" => $snone,
                                                   "add_site" => _sponsors_admin_add_site,
                                                   "upload" => _sponsors_admin_upload,
                                                   "url" => _sponsors_admin_url,
                                                   "site_link" => $get['slink'],
                                                   "sitepic" => $sitepic,
                                                     "banner" => _sponsors_admin_banner,
                                                   "addbanner" => _sponsors_admin_addbanner,
                                                   "bchecked" => $bchecked,
                                                   "bnone" => $bnone,
                                                   "add_banner" => _sponsors_admin_add_banner,
                                                   "banner_link" => $get['blink'],
                                                   "bannerpic" => $bannerpic,
                                                     "box" => _sponsors_admin_box,
                                                   "addbox" => _sponsors_admin_addbox,
                                                   "xchecked" => $xchecked,
                                                   "xnone" => $xnone,
                                                   "add_box" => _sponsors_admin_add_box,
                                                   "box_link" => $get['xlink'],
                                                     "boxpic" => $boxpic,
                                                   "pos" => _position,
                                                   "first" => _admin_first,
                                                   "positions" => $positions,
                                                   "posname" => $posname,
                                                   "what" => _button_value_edit,
                                                   "do" => "editsponsor&amp;id=".$_GET['id'].""));
      } elseif($do == "editsponsor") {
      if(empty($_POST['name']) || empty($_POST['link']) || empty($_POST['beschreibung']))
      {
      if(empty($_POST['beschreibung'])) $error = show("errors/errortable", array("error" => _sponsors_empty_beschreibung));
          if(empty($_POST['link']))         $error = show("errors/errortable", array("error" => _sponsors_empty_link));
          if(empty($_POST['name']))         $error = show("errors/errortable", array("error" => _sponsors_empty_name));

          $qry = db("SELECT * FROM ".$db['sponsoren']."
                     WHERE id = '".intval($_GET['id'])."'");
          $get = _fetch($qry);

          $pos = db("SELECT pos,name FROM ".$db['sponsoren']."
                     ORDER BY pos");
          while($getpos = _fetch($pos))
          {
            if($getpos['name'] != $get['name'])
            {
              $mpos = db("SELECT pos FROM ".$db['sponsoren']."
                          WHERE name != '".$get['name']."'
                          AND pos = '".intval(($_POST['position']-1))."'");
              $mp = _fetch($mpos);

              if($getpos['pos'] == $mp['pos']) $sel = 'selected="selected"';
              else $sel = '';

              $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                      "what" => _nach.' '.re($getpos['name']),
                                                      "sel" => $sel));
              $posname = $getpos['name'];
            }
          }

            if(isset($_POST['site']))
            {
              $schecked = 'checked="checked"';
              $snone = "";
            } else {
              $schecked = "";
              $snone = "none";
            }
            if(isset($_POST['banner']))
            {
              $bchecked = 'checked="checked"';
              $bnone = "";
            } else {
              $bchecked = "";
              $bnine = "none";
            }
            if(isset($_POST['box']))
            {
              $xchecked = 'checked="checked"';
              $xnone = "";
            } else {
              $xchecked = "";
              $xnone = "none";
            }

            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/banner/sponsors/site_'.$get['id'].'.'.$end))
                {
                    $sitepic = '<img src="../banner/sponsors/site_'.$get['id'].'.'.$end.'" alt="" width="50%" />';
                    break;
                }
            }

            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/banner/sponsors/banner_'.$get['id'].'.'.$end))
                {
                    $bannerpic = '<img src="../banner/sponsors/banner_'.$get['id'].'.'.$end.'" alt="" width="50%" />';
                    break;
                }
            }

            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/banner/sponsors/box_'.$get['id'].'.'.$end))
                {
                    $boxpic = '<img src="../banner/sponsors/box_'.$get['id'].'.'.$end.'" alt="" width="50%" />';
                    break;
                }
            }

             $show = show($dir."/form_sponsors", array("head" => _sponsors_admin_head,
                                                       "error" => $error,
                                                       "name" => _sponsors_admin_name,
                                                       "sname" => $_POST['name'],
                                                       "link" => _links_link,
                                                       "slink" => $_POST['link'],
                                                       "beschreibung" => _beschreibung,
                                                       "sbeschreibung" => re($_POST['beschreibung']),
                                                       "site" => _sponsors_admin_site,
                                                       "addsite" => _sponsors_admin_addsite,
                                                       "schecked" => $schecked,
                                                       "snone" => $snone,
                                                       "add_site" => _sponsors_admin_add_site,
                                                       "upload" => _sponsors_admin_upload,
                                                       "url" => _sponsors_admin_url,
                                                       "site_link" => $_POST['slink'],
                                                       "sitepic" => $sitepic,
                                                         "banner" => _sponsors_admin_banner,
                                                       "addbanner" => _sponsors_admin_addbanner,
                                                       "bchecked" => $bchecked,
                                                       "bnone" => $bnone,
                                                       "add_banner" => _sponsors_admin_add_banner,
                                                       "banner_link" => $_POST['blink'],
                                                       "bannerpic" => $bannerpic,
                                                         "box" => _sponsors_admin_box,
                                                       "addbox" => _sponsors_admin_addbox,
                                                       "xchecked" => $xchecked,
                                                       "xnone" => $xnone,
                                                       "add_box" => _sponsors_admin_add_box,
                                                       "box_link" => $_POST['xlink'],
                                                       "boxpic" => $boxpic,
                                                         "pos" => _position,
                                                        "first" => _admin_first,
                                                        "positions" => $positions,
                                                       "posname" => $_POST['posname'],
                                                       "what" => _button_value_edit,
                                                       "do" => "editsponsor&amp;id=".$_GET['id'].""));


        } else {
          $ask = db("SELECT pos FROM ".$db['sponsoren']."
                     WHERE id = '".intval($_GET['id'])."'");
          $get = _fetch($ask);

          if($_POST['position'] != $get['pos'])
          {
            if($_POST['position'] == 1 || $_POST['position'] == 2) $sign = ">= ";
            else $sign = "> ";

            $posi = db("UPDATE ".$db['sponsoren']."
                        SET `pos` = pos+1
                        WHERE pos ".$sign." '".intval($_POST['position'])."'");
          }

          if($_POST['position'] == "lazy") $newpos = "";
          else $newpos = "`pos` = '".intval($_POST['position'])."'";

            $qry = db("UPDATE ".$db['sponsoren']."
                       SET      `name`         = '".up($_POST['name'])."',
                             `link`         = '".links($_POST['link'])."',
                             `beschreibung` = '".up($_POST['beschreibung'])."',
                             `site`         = '".intval($_POST['site'])."',
                             `slink`        = '".$_POST['slink']."',
                             `banner`       = '".intval($_POST['banner'])."',
                             `blink`        = '".$_POST['blink']."',
                             `box`           = '".intval($_POST['box'])."',
                             `xlink`         = '".up($_POST['xlink'])."',
                             ".$newpos."
                       WHERE id = '".intval($_GET['id'])."'");

          $id = intval($_GET['id']);

                  $tmp1 = $_FILES['sdata']['tmp_name'];
          $type1 = $_FILES['sdata']['type'];
          $end1 = explode(".", $_FILES['sdata']['name']);
          $end1 = strtolower($end1[count($end1)-1]);

          if(!empty($tmp1))
          {
            $img1 = getimagesize($tmp1);
                        if($type1 == "image/gif" || $type1 == "image/png" || $type1 == "image/jpeg" || !$img1[0])
            {
                          if(file_exists(basePath."/banner/sponsors/site_".$id.".gif"))
                @unlink(basePath."/banner/sponsors/site_".$id.".gif");
              elseif(file_exists(basePath."/banner/sponsors/site_".$id.".jpg"))
                @unlink(basePath."/banner/sponsors/site_".$id.".jpg");
                          elseif(file_exists(basePath."/banner/sponsors/site_".$id.".png"))
                @unlink(basePath."/banner/sponsors/site_".$id.".png");

              @copy($tmp1, basePath."/banner/sponsors/site_".$id.".".strtolower($end1));
              @unlink($_FILES['sdata']['tmp_name']);
            }
                    db("UPDATE ".$db['sponsoren']." SET `send` = '".$end1."' WHERE id = '".intval($id)."'");
          }

                  $tmp2 = $_FILES['bdata']['tmp_name'];
          $type2 = $_FILES['bdata']['type'];
          $end2 = explode(".", $_FILES['bdata']['name']);
          $end2 = strtolower($end2[count($end2)-1]);

          if(!empty($tmp2))
          {
            $img2 = getimagesize($tmp2);
                        if($type2 == "image/gif" || $type2 == "image/png" || $type2 == "image/jpeg" || !$img2[0])
            {
              if(file_exists(basePath."/banner/sponsors/banner_".$id.".gif"))
                @unlink(basePath."/banner/sponsors/banner_".$id.".gif");
              elseif(file_exists(basePath."/banner/sponsors/banner_".$id.".jpg"))
                @unlink(basePath."/banner/sponsors/banner_".$id.".jpg");
                          elseif(file_exists(basePath."/banner/sponsors/banner_".$id.".png"))
                @unlink(basePath."/banner/sponsors/banner_".$id.".png");

                          @copy($tmp2, basePath."/banner/sponsors/banner_".$id.".".strtolower($end2));
              @unlink($_FILES['bdata']['tmp_name']);
            }
                    db("UPDATE ".$db['sponsoren']." SET `bend` = '".$end2."' WHERE id = '".intval($id)."'");
          }

                  $tmp3 = $_FILES['xdata']['tmp_name'];
          $type3 = $_FILES['xdata']['type'];
          $end3 = explode(".", $_FILES['xdata']['name']);
          $end3 = strtolower($end3[count($end3)-1]);

          if(!empty($tmp3))
          {
            $img3 = getimagesize($tmp3);
                        if($type3 == "image/gif" || $type3 == "image/png" || $type3 == "image/jpeg" || !$img3[0])
            {
              if(file_exists(basePath."/banner/sponsors/box_".$id.".gif"))
                @unlink(basePath."/banner/sponsors/box_".$id.".gif");
              elseif(file_exists(basePath."/banner/sponsors/box_".$id.".jpg"))
                @unlink(basePath."/banner/sponsors/box_".$id.".jpg");
                          elseif(file_exists(basePath."/banner/sponsors/box_".$id.".png"))
                @unlink(basePath."/banner/sponsors/box_".$id.".png");

                          @copy($tmp3, basePath."/banner/sponsors/box_".$id.".".strtolower($end3));
              @unlink($_FILES['xdata']['tmp_name']);
            }
                    db("UPDATE ".$db['sponsoren']." SET `xend` = '".$end3."' WHERE id = '".intval($id)."'");
          }

          $show = info(_sponsor_edited, "?admin=sponsors");
        }
      } elseif($do == "delete") {
        $qry = db("DELETE FROM ".$db['sponsoren']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_sponsor_deleted, "?admin=sponsors");
      } else {
        $qry = db("SELECT * FROM ".$db['sponsoren']."
                   ORDER BY pos");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=sponsors&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=sponsors&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_link)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show .= show($dir."/sponsors_show", array("link" => cut(re($get['link']),40),
                                                       "class" => $class,
                                                       "name" => $get['name'],
                                                       "edit" => $edit,
                                                       "delete" => $delete));
        }

        $show = show($dir."/sponsors", array("head" => _sponsor_head,
                                               "show" => $show,
                                             "sname" => _sponsor_name,
                                             "slink" => _links_link,
                                               "add" => _sponsors_admin_add));
      }