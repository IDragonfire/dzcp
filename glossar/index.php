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
$dir = "glossar";
$where = _glossar;
$use_glossar = false;

## SECTIONS ##
$a = ''; $glword = '';
if(!empty($_GET['word'])) {
    $a = substr($_GET['word'],0,1);
    $glword = "WHERE word = '".up($_GET['word'])."' OR word LIKE '".up($a)."%' ";
} else if(!empty($_GET['bst']) && $_GET['bst'] != 'all') {
    $glword = "WHERE word LIKE '".up($_GET['bst'])."%' ";
    $a = $_GET['bst'];
}

$qry = db("SELECT * FROM ".$db['glossar']." ".$glword." ORDER BY word");
while($get = _fetch($qry)) {
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

    if(isset($_GET['word']) && $_GET['word'] == $get['word'])
        $class = 'highlightSearchTarget';

    $show .= show($dir."/glossar_show", array("word" => re($get['word']),
                                              "class" => $class,
                                              "glossar" => bbcode(re($get['glossar']))));
}

$bst = array(_all,"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"); $abc = '';
foreach ($bst as $bst_s) {
    $bclass = (empty($a) && ($bst_s) == _all || strtolower($bst_s) == strtolower($a)) ? 'fontWichtig' : '';
    $ret = ($bst_s == _all) ? '?bst=all' : "?bst=".$bst_s;
    $abc .= "<a href=\"".$ret."\" title=\"".$bst_s."\"><span class=\"".$bclass."\">".$bst_s."</span></a> ";
}

$index = show($dir."/glossar", array("head" => _glossar_head,
                                     "word" => _glossar_bez,
                                     "bez" => _glossar_erkl,
                                     "abc" => $abc,
                                     "show" => $show));
## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);