<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_taktiken;
$title = $pagetitle." - ".$where."";
$dir = "taktik";
$wysiwyg = '';

## SECTIONS ##
switch(isset($_GET['action']) ? $_GET['action'] : ''):
    default:
        if($chkMe == "unlogged" || $chkMe < 2)
            $index = error(_error_wrong_permissions, 1);
        else 
        {
            $qry = db("SELECT id,datum,map,spart,sparct,standardt,standardct,autor FROM ".$db['taktik']." ORDER BY id DESC");
            $color = 1; $show = '';
            while ($get = _fetch($qry))
            {
                $sparct = (!empty($get['sparct']) ? show(_taktik_spar_ct, array("id" => $get['id'])) : '');
                $spart = (!empty($get['spart']) ? show(_taktik_spar_t, array("id" => $get['id'])) : '');
                $standardct = (!empty($get['standardct']) ? show(_taktik_standard_ct, array("id" => $get['id'])) : '');
                $standardt = (!empty($get['standardt']) ? show(_taktik_standard_t, array("id" => $get['id'])) : '');
                
                $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=do&amp;what=edit", "title" => _button_title_edit));
                $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "action=do&amp;what=delete", "title" => _button_title_del, "del" => convSpace(_confirm_del_taktik)));
                
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $show .= show($dir."/taktiken_show", array("map" => re($get['map']),
                                                           "id" => $get['id'],
                                                           "class" => $class,
                                                           "standard_t" => $standardt,
                                                           "standard_ct" => $standardct,
                                                           "spar_t" => $spart,
                                                           "edit" => $edit,
                                                           "delete" => $delete,
                                                           "spar_ct" => $sparct,
                                                           "autor" => autor($get['autor'])));
            }
    
            $index = show($dir."/taktiken", array("show" => $show,
                                                  "taktik_head" => _taktik_head,
                                                  "new_taktik" => _taktik_new,
                                                  "upload" => _taktik_upload,
                                                  "map" => _map,
                                                  "edit" => _editicon_blank,
                                                  "delete" => _deleteicon_blank,
                                                  "t" => _taktik_t,
                                                  "ct" => _taktik_ct,
                                                  "autor" => _autor));
        }
    break;
    case 'spar':
        if ($chkMe < 2)
            $index = error(_error_wrong_permissions, 1);
        else
        {
            if(isset($_GET['what']) && !empty($_GET['what']))
            {
                $get = db("SELECT * FROM ".$db['taktik']." WHERE id = ".intval($_GET['id']),false,true);
                if($_GET['what'] == "ct")
                {
                    $what = _taktik_tspar_ct;
                    $show = bbcode($get['sparct']);
                } 
                else if($_GET['what'] == "t") 
                {
                    $what = _taktik_tspar_t;
                    $show = bbcode($get['spart']);
                }
    
                $posted = show(_taktik_posted, array("autor" => autor($get['autor']), "datum" => date("d.m.Y", $get['datum'])));
                $headline = show(_taktik_headline, array("what" => $what, "map" => re($get['map'])));
                $index = show($dir."/taktik", array("id" => $_GET['id'], "posted" => $posted, "headline" => $headline, "show" => $show));
            }
            else
                $index = info(_taktik_added, "../taktik/");
        }
    break;
    case 'standard':
        if ($chkMe < "2")
            $index = error(_error_wrong_permissions, 1);
        else 
        {
            if(isset($_GET['what']) && !empty($_GET['what']))
            {
                $get = db("SELECT * FROM ".$db['taktik']." WHERE id = ".intval($_GET['id']),false,true);
                if($_GET['what'] == "ct")
                {
                    $what = _taktik_tstandard_ct;
                    $show = bbcode($get['standardct']);
                } 
                else if($_GET['what'] == "t") 
                {
                    $what = _taktik_tstandard_t;
                    $show = bbcode($get['standardt']);
                }
    
                $posted = show(_taktik_posted, array("autor" => autor($get['autor']), "datum" => date("d.m.Y", $get['datum'])));
                $headline = show(_taktik_headline, array("what" => $what, "map" => re($get['map'])));
                $index = show($dir."/taktik", array("id" => $_GET['id'], "posted" => $posted, "headline" => $headline, "show" => $show));
            }
            else
                $index = info(_taktik_added, "../taktik/");
        }
    break;
    case 'do':
        if(!permission("edittactics"))
            $index = error(_error_wrong_permissions, 1);
        else 
        {
            $wysiwyg = '_word';
            switch(isset($_GET['what']) ? $_GET['what'] : '')
            {
                case 'new':
                    $index = show($dir."/new", array("date" => date("d.m.Y"),
                                                     "autor" => autor($userid),
                                                     "tautor" => _autor,
                                                     "value" => _button_value_add,
                                                     "map" => _map,
                                                     "choose" => _taktik_choose,
                                                     "spar_ct" => _taktik_tspar_ct,
                                                     "spar_t" => _taktik_tspar_t,
                                                     "standard_t" => _taktik_tstandard_t,
                                                     "standard_ct" => _taktik_tstandard_ct,
                                                     "newtaktik_head" => _taktik_new_head));
                break;
                case 'add':
                    if(!isset($_POST['map']) || empty($_POST['map']))
                        $index = error(_error_taktik_empty_map, 1);
                    else 
                    {
                        db("INSERT INTO ".$db['taktik']." SET 
                           `datum`      = '".((int)time())."',
                           `map`        = '".up($_POST['map'])."',
                           `spart`      = '".up($_POST['spart'], 1)."',
                           `sparct`     = '".up($_POST['sparct'], 1)."',
                           `standardt`  = '".up($_POST['standardt'], 1)."',
                           `standardct` = '".up($_POST['standardct'], 1)."',
                           `autor`      = '".((int)$userid)."'");
                    
                            $index = info(_taktik_added, "../taktik/");
                    }
                break;
                case 'delete':
                    if(isset($_GET['id']) && !empty($_GET['id']))
                    {
                        db("DELETE FROM ".$db['taktik']." WHERE id = ".intval($_GET['id']));
                        $index = info(_taktik_deleted, "../taktik/");
                    }
                break;
                case 'edit':
                    if(isset($_GET['id']) && !empty($_GET['id']))
                    {
                        $get = db("SELECT * FROM ".$db['taktik']." WHERE id = ".intval($_GET['id']),false,true);
                        $index = show($dir."/edit", array("id" => $_GET['id'],
                                                          "map" => re($get['map']),
                                                          "autor" => autor($get['autor']),
                                                          "value" => _button_value_edit,
                                                          "tautor" => _autor,
                                                          "tmap" => _map,
                                                          "choose" => _taktik_choose,
                                                          "spar_tct" => _taktik_tspar_ct,
                                                          "spar_tt" => _taktik_tspar_t,
                                                          "standard_tt" => _taktik_tstandard_t,
                                                          "standard_tct" => _taktik_tstandard_ct,
                                                          "edit_head" => _taktik_edit_head,
                                                          "standard_t" => re($get['standardt']),
                                                          "standard_ct" => re($get['standardct']),
                                                          "spar_ct" => re($get['sparct']),
                                                          "spar_t" => re($get['spart'])));
                    }
                break;
                case 'update':
                    if(isset($_POST['id']) && !empty($_POST['id']))
                    {
                        if(!isset($_POST['map']) || empty($_POST['map']))
                            $index = error(_error_taktik_empty_map, 1);
                        else 
                        {
                            db("UPDATE ".$db['taktik']." SET 
                               `map`        = '".up($_POST['map'])."',
                               `sparct`     = '".up($_POST['sparct'], 1)."',
                               `spart`      = '".up($_POST['spart'], 1)."',
                               `standardct` = '".up($_POST['standardct'], 1)."',
                               `standardt`  = '".up($_POST['standardt'], 1)."'
                                WHERE id = ".intval($_POST['id']));
                        
                            $index = info(_error_taktik_edited, "../taktik/");
                        }
                    }
                break;
            }
        }
    break;
endswitch;

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time,$wysiwyg);

## OUTPUT BUFFER END ##
gz_output();
?>