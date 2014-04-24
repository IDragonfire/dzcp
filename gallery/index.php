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
$where = _site_gallery;
$title = $pagetitle." - ".$where."";
$dir = "gallery";

## SECTIONS ##
switch ($action):
    default:
        $intern = !permission('galleryintern') ? " WHERE intern = '0'" : "";
        $qry = db("SELECT * FROM ".$db['gallery'].$intern." ORDER BY id DESC");
        if(_rows($qry)) {
            while($get = _fetch($qry)) {
                $imgArr = array();
                $files = get_files("images/",false,true,array('png','jpg','gif'),false,array(),'minimize');

                foreach($files AS $file) {
                    if(intval($file) == $get['id'])
                        array_push($imgArr, $file);
                }

                $cnt = 0;
                for($i=0; $i<count($files); $i++) {
                    if(preg_match("#^".$get['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($files[$i]))!=FALSE)
                        $cnt++;
                }

                $cntpics = $cnt == 1 ? _gallery_image : _gallery_images;
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $show .= show($dir."/gallery_show", array("link" => re($get['kat']),
                                                          "class" => $class,
                                                          "images" => $cntpics,
                                                          "image" => $imgArr[0],
                                                          "id" => $get['id'],
                                                          "beschreibung" => bbcode(re($get['beschreibung'])),
                                                          "cnt" => $cnt));

            }
        }
        else
            $show = show(_no_entrys_yet, array("colspan" => "10"));

        $index = show($dir."/gallery",array("show" => $show, "head" => _gallery_head));
    break;
    case 'show';
        $get = db("SELECT * FROM ".$db['gallery']." WHERE id = '".intval($_GET['id'])."'",false,true);
        if(!permission('galleryintern') && $get['intern']) {
            $index = error(_error_no_access);
            break;
        }

        $files = get_files("images/",false,true,array('png','jpg','gif'),false,array(),'minimize');
        $t = 1; $cnt = 0;
        foreach ($files as $file) {
            if(preg_match("#^".$_GET['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!=FALSE) {
                $tr1 = ""; $tr2 = "";

                if($t == 0 || $t == 1)
                    $tr1 = "<tr>";

                if($t == config('gallery')) {
                    $tr2 = "</tr>";
                    $t = 0;
                }

                $del = "";
                if(permission("gallery")) {
                    $del = show("page/button_delete_gallery", array("id" => "",
                                                                    "action" => "admin=gallery&amp;do=delete&amp;pic=".$file,
                                                                    "title" => _button_title_del,
                                                                    "del" => convSpace(_confirm_del_galpic)));
                }

                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $show .= show($dir."/show_gallery", array("img" => gallery_size($file),
                                                          "tr1" => $tr1,
                                                          "max" => config('gallery'),
                                                          "width" => intval(round(100/config('gallery'))),
                                                          "del" => $del,
                                                          "tr2" => $tr2));
                $t++; $cnt++;
            }
        }

        $end = '';
        if(is_float($cnt/config('gallery'))) {
            for($e=$t; $e<=config('gallery'); $e++) {
                $end .= '<td class="contentMainFirst"></td>';
            }

            $end = $end."</tr>";
        }

        $index = show($dir."/show", array("gallery" => re($get['kat']),
                                          "show" => $show,
                                          "beschreibung" => bbcode(re($get['beschreibung'])),
                                          "end" => $end,
                                          "back" => _gal_back,
                                          "head" => _subgallery_head));
    break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();