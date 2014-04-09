<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "glossar";
$where = _glossar;
## SECTIONS ##
switch ($action):
default:
  if(!empty($_GET['word']))
  {
    $a = substr($_GET['word'],0,1);

    $glword = "WHERE word = '".up($_GET['word'])."'
               OR word LIKE '".up($a)."%' ";
  } elseif(!empty($_GET['bst']) && $_GET['bst'] != 'all') {
    $glword = "WHERE word LIKE '".up($_GET['bst'])."%' ";
    $a = $_GET['bst'];
  }

  $qry = db("SELECT * FROM ".$db['glossar']." ".$glword." ORDER BY word");
  while($get = _fetch($qry))
  {
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

    if($_GET['word'] == $get['word']) $class = 'highlightSearchTarget';

    $show .= show($dir."/glossar_show", array("word" => re($get['word']),
                                              "class" => $class,
                                              "glossar" => bbcode($get['glossar'])));
  }
  $bst = array(_all,"A","B","C","D","E","F","G","H","I","J","K",
               "L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
  for($i=0;$i<count($bst);$i++)
  {
    $bclass = (empty($a) && ($bst[$i]) == _all || strtolower($bst[$i]) == strtolower($a)) ? 'fontWichtig' : '';
    $ret = ($bst[$i] == _all) ? '?bst=all' : "?bst=".$bst[$i];

    $abc .= "<a href=\"".$ret."\" title=\"".$bst[$i]."\"><span class=\"".$bclass."\">".$bst[$i]."</span></a> ";
  }

  $index = show($dir."/glossar", array("head" => _glossar_head,
                                       "word" => _glossar_bez,
                                       "bez" => _glossar_erkl,
                                       "abc" => $abc,
                                       "show" => $show
                                      ));
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