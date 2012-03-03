<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "impressum";
$where = _site_impressum;
## SECTIONS ##

  $index = show($dir."/impressum", array("head" => _impressum_head,
                                         "domain" => _impressum_domain,
                                         "autor" => _impressum_autor,
                                         "disclaimer_head" => _impressum_disclaimer,
                                         "disclaimer" => _impressum_txt,
                                         "show_domain" => $i_domain,
                                         "show_autor" => bbcode($i_autor)));

## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>
