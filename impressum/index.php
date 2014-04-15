<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$dir = "impressum";
$where = _site_impressum;
## SECTIONS ##

  $index = show($dir."/impressum", array("head" => _impressum_head,
                                         "domain" => _impressum_domain,
                                         "autor" => _impressum_autor,
                                         "disclaimer_head" => _impressum_disclaimer,
                                         "disclaimer" => _impressum_txt,
                                         "show_domain" => settings('i_domain'),
                                         "show_autor" => bbcode(settings('i_autor'))));

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where."";
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();