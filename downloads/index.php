<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_dl;
$title = $pagetitle." - ".$where."";
$dir = "downloads";

## SECTIONS ##
switch ($action):
    default:
        $qry = db("SELECT * FROM ".$db['dl_kat']." ORDER BY name");
        $t = 1; $cnt = 0; $kats = '';
        while($get = _fetch($qry)) {
            $kid = isset($_GET['kat']) ? " WHERE id = '".intval($_GET['kat'])."'" : "";
            $intern =  permission('dlintern') ? "" : " AND intern = '0'";
            $qrydl = db("SELECT * FROM ".$db['downloads']."
                         WHERE kat = '".$get['id']."'".$intern."
                         ORDER BY download");

            $show = "";
            if(_rows($qrydl)) {
                $display = "none"; $img = "expand"; $color = 0;
                while($getdl = _fetch($qrydl)) {
                    if(isset($_GET['hl']) && $_GET['hl'] == $getdl['id']) {
                        $display = "";
                        $img = "collapse";
                        $download = highlight(re($getdl['download']));
                    }
                    else
                        $download = re($getdl['download']);

                    $link = show(_downloads_link, array("id" => $getdl['id'],
                                                        "download" => $download,
                                                        "titel" => re($getdl['download'])));

                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

                    $show .= show($dir."/downloads_show", array("class" => $class,
                                                                "link" => $link,
                                                                "kid" => $get['id'],
                                                                "display" => $display,
                                                                "beschreibung" => bbcode($getdl['beschreibung']),
                                                                "hits" => $getdl['hits']));
                }

                $cntKat = cnt($db['downloads'], " WHERE kat = '".$get['id']."'");
                $dltitel = cnt($db['downloads'], "WHERE kat = '".$get['id']."'") == 1 ? _dl_file : _site_stats_files;
                $kat = show(_dl_titel, array("id" => $get['id'],
                                             "file" => $dltitel,
                                             "cnt" => $cntKat,
                                             "name" => re($get['name'])));

                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $kats .= show($dir."/download_kats", array("kat" => $kat,
                                                           "class" => $class,
                                                           "kid" => $get['id'],
                                                           "img" => $img,
                                                           "download" => _dl_file,
                                                           "hits" => _hits,
                                                           "show" => $show,
                                                           "display" => $display));
            }
        }

        $index = show($dir."/downloads", array("kats" => $kats,
                                               "head" => _downloads_head));
    break;
    case 'download';
        if(settings("reg_dl") && !$chkMe)
            $index = error(_error_unregistered);
        else {
            $qry = db("SELECT * FROM ".$db['downloads']."
                       WHERE id = '".intval($_GET['id'])."'");

            if(_rows($qry)) {
                $get = _fetch($qry);
                if(!permission('dlintern') && $get['intern']) {
                    $index = error(_error_no_access);
                    break;
                }

                $file = preg_replace("#added...#Uis", "files/", $get['url']);
                if(strpos($get['url'],"../") != 0) $rawfile = @basename($file);
                else $rawfile = re($get['download']);

                $size = 0;
                if(file_exists($file))
                    $size = filesize($file);

                $size_mb = 0; $size_kb = 0; $speed_modem = 0; $speed_isdn = 0; $speed_dsl256 = 0;
                $speed_dsl512 = 0; $speed_dsl1024 = 0; $speed_dsl2048 = 0; $speed_dsl3072 = 0;
                $speed_dsl6016 = 0; $speed_dsl16128 = 0;
                if($size) {
                    $size_mb = @round($size/1048576,2);
                    $size_kb = @round($size/1024,2);

                    $speed_modem = @round(($size/1024)/(56/8)/60,2);
                    $speed_isdn = @round(($size/1024)/(128/8)/60,2);
                    $speed_dsl256 = @round(($size/1024)/(256/8)/60,2);
                    $speed_dsl512 = @round(($size/1024)/(512/8)/60,2);
                    $speed_dsl1024 = @round(($size/1024)/(1024/8)/60,2);
                    $speed_dsl2048 = @round(($size/1024)/(2048/8)/60,2);
                    $speed_dsl3072 = @round(($size/1024)/(3072/8)/60,2);
                    $speed_dsl6016 = @round(($size/1024)/(6016/8)/60,2);
                    $speed_dsl16128 = @round(($size/1024)/(16128/8)/60,2);
                }

                if(strlen(@round(($size/1048576)*$get['hits'],0)) >= 4)
                    $traffic = @round(($size/1073741824)*$get['hits'],2).' GB';
                else
                    $traffic = @round(($size/1048576)*$get['hits'],2).' MB';

                $getfile = show(_dl_getfile, array("file" => $rawfile));

                if(!$size) {
                    $dlsize = $traffic = 'n/a';
                    $br1 = '<!--';
                    $br2 = '-->';
                } else {
                    $dlsize = $size_mb.' MB ('.$size_kb.' KB)';
                    $br1 = '';
                    $br2 = '';
                }

                $date = 'n/a';
                if(empty($get['date']))
                    $date = date("d.m.Y H:i",@filemtime($file))._uhr;
                else
                    $date = date("d.m.Y H:i",$get['date'])._uhr;

                $lastdate = date("d.m.Y H:i",$get['last_dl'])._uhr;
                $index = show($dir."/info", array("head" => _dl_info,
                                                  "headd" => _dl_info2,
                                                  "getfile" => $getfile,
                                                  "dl_file" => _dl_file,
                                                  "dl_besch" => _dl_besch,
                                                  "dl_size" => _dl_size,
                                                  "dl_speed" => _dl_speed,
                                                  "dl_traffic" => _dl_traffic,
                                                  "dl_loaded" => _dl_loaded,
                                                  "dl_date" => _dl_date,
                                                  "last_date" => _download_last_date,
                                                  "br1" => $br1,
                                                  "br2" => $br2,
                                                  "date" => $date,
                                                  "lastdate" => $lastdate,
                                                  "id" => $_GET['id'],
                                                  "dlname" => re($get['download']),
                                                  "loaded" => $get['hits'],
                                                  "traffic" => $traffic,
                                                  "speed_modem" => $speed_modem,
                                                  "speed_isdn" => $speed_isdn,
                                                  "speed_dsl256" => $speed_dsl256,
                                                  "speed_dsl512" => $speed_dsl512,
                                                  "speed_dsl1024" => $speed_dsl1024,
                                                  "speed_dsl2048" => $speed_dsl2048,
                                                  "speed_dsl3072" => $speed_dsl3072,
                                                  "speed_dsl6016" => $speed_dsl6016,
                                                  "speed_dsl16128" => $speed_dsl16128,
                                                  "size" => $dlsize,
                                                  "besch" => bbcode($get['beschreibung']),
                                                  "file" => $rawfile));
            }
            else
                $index = error(_id_dont_exist,1);
        }
    break;
    case 'getfile';
        if(settings("reg_dl") && !$chkMe)
            $index = error(_error_unregistered,1);
        else {
            $get = db("SELECT url FROM ".$db['downloads']."
                       WHERE id = '".intval($_GET['id'])."'",false,true);

            $file = preg_replace("#added...#Uis", "", $get['url']);
            if(preg_match("=added...=Uis",$get['url']) != FALSE)
                $dlFile = "files/".$file;
            else
                $dlFile = $get['url'];

            db("UPDATE ".$db['downloads']." SET `hits` = hits+1, `last_dl` = '".time()."' WHERE id = '".intval($_GET['id'])."'");

            ## download file ##
            header("Location: ".$dlFile);
        }
    break;
endswitch;

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);

## OUTPUT BUFFER END ##
gz_output();