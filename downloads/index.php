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
if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];

switch ($action):
default:  
  $qry = db("SELECT * FROM ".$db['dl_kat']."
             ORDER BY name");
  $t = 1;
  $cnt = 0;
  while($get = _fetch($qry))
  {
    if(isset($_GET['kat'])) $kid = " WHERE id = '".intval($_GET['kat'])."'";
    else                    $kid = "";
      
    $qrydl = db("SELECT * FROM ".$db['downloads']."
                 WHERE kat = '".$get['id']."'
                 ORDER BY download");
    $show = "";
    if(_rows($qrydl))
    {
      $display = "none";
      $img = "expand";
      while($getdl = _fetch($qrydl))
      {
        if($_GET['hl'] == $getdl['id'])
        {
          $display = "";
          $img = "collapse";
          $download = highlight(re($getdl['download']));
        } else $download = re($getdl['download']);
      
        $link = show(_downloads_link, array("id" => $getdl['id'],
                                            "download" => $download,
																						"titel" => re($getdl['download']),
                                            "target" => $target));
                                            
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
  
        $show .= show($dir."/downloads_show", array("class" => $class,
                                                    "link" => $link,
                                                    "kid" => $get['id'],
                                                    "display" => $display,
                                                    "beschreibung" => bbcode($getdl['beschreibung']),
                                                    "hits" => $getdl['hits'])); 
      }
    
      $cntKat = cnt($db['downloads'], " WHERE kat = '".$get['id']."'");
    
      if(cnt($db['downloads'], "WHERE kat = '".$get['id']."'") == 1)  $dltitel = _dl_file;
      else $dltitel = _site_stats_files;
    
    
      $kat = show(_dl_titel, array("id" => $get['id'],
                                   "icon" => $moreicon,
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
  if(settings("reg_dl") == 1 && $chkMe == "unlogged")
  {
    $index = error(_error_unregistered);
  } else {
    $qry = db("SELECT * FROM ".$db['downloads']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    $file = preg_replace("#added...#Uis", "files/", $get['url']);
    if(strpos($get['url'],"../") != 0) $rawfile = @basename($file);
    else                                   $rawfile = re($get['download']);

    $size = @filesize($file);
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
    
    if(strlen(@round(($size/1048576)*$get['hits'],0)) >= 4)
      $traffic = @round(($size/1073741824)*$get['hits'],2).' GB';
    else $traffic = @round(($size/1048576)*$get['hits'],2).' MB';
    
    $getfile = show(_dl_getfile, array("file" => $rawfile));
    
    if($size == false)
    {
      $dlsize = $traffic = 'n/a';
      $br1 = '<!--';
      $br2 = '-->';
    } else {
      $dlsize = $size_mb.' MB ('.$size_kb.' KB)';
      $br1 = '';
      $br2 = '';
    }
    if(empty($get['date']))
    {
      if($size == false) $date = 'n/a';
      else $date = date("d.m.Y H:i",@filemtime($file))._uhr;
    } else $date = date("d.m.Y H:i",$get['date'])._uhr;
    
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
break;
case 'getfile';
    if(settings("reg_dl") == "1" && $chkMe == "unlogged")
        $index = error(_error_unregistered,1);
    else 
    {
        $get = db("SELECT url,id FROM ".$db['downloads']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $file = preg_replace("#added...#Uis", "", $get['url']);
  
        if(preg_match("=added...=Uis",$get['url']) != FALSE)
            $dlFile = "files/".$file;
        else 
            $dlFile = $get['url'];

        if(count_clicks('download',$get['id']))
            db("UPDATE ".$db['downloads']." SET `hits` = hits+1, `last_dl` = '".time()."' WHERE id = '".$get['id']."'"); 
    
        //download file
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
?> 