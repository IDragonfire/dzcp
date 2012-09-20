<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "links";
$where = _site_links;

## SECTIONS ##
switch (isset($_GET['action']) ? $_GET['action'] : ""):
default:
	$show="";
	$qry = db("SELECT * FROM ".$db['links']." ORDER BY banner DESC");
	while($get = _fetch($qry))
  	{
      	$banner = ($get['banner'] ? show(_links_bannerlink, array("id" => $get['id'], "banner" => re($get['text']))) : show(_links_textlink, array("id" => $get['id'], "text" => str_replace('http://','',re($get['url'])))));
    	$show .= show($dir."/links_show", array("beschreibung" => bbcode($get['beschreibung']),
                                            	"hits" => $get['hits'],
                                            	"hit" => _hits,
                                            	"banner" => $banner),$get['id'].'_links',true);
  	}

	$index = show($dir."/links", array("head" => _links_head, "show" => $show));
break;
case 'link';
	$get = db("SELECT url,id,hits FROM ".$db['links']." WHERE `id` = '".((int)$_GET['id'])."'",false,true);
	
	if(count_clicks('link',$get['id']))
		db("UPDATE ".$db['links']." SET `hits` = ".($get['hits'] + 1)." WHERE `id` = '".$get['id']."'");
		
	header("Location: ".$get['url']);
break;
endswitch;

## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);

## OUTPUT BUFFER END ##
gz_output();
?>
