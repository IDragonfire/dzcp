<?php
//-> Last News
function l_news() {
    global $db, $maxlnews, $lnews, $allowHover;
    if (!permission("intnews"))
        $int = "AND intern = 0";
    $qry = db("SELECT id,titel,autor,datum,kat,public,timeshift FROM " . $db['news'] . "
               WHERE public = 1
                             AND datum <= " . time() . "
                     " . $int . "
               ORDER BY id DESC
               LIMIT " . $maxlnews . "");
    
    while ($get = _fetch($qry)) {
        $qrykat = db("SELECT kategorie FROM " . $db['newskat'] . "
                    WHERE id = '" . $get['kat'] . "'");
        $getkat = _fetch($qrykat);
        
        if ($allowHover == 1)
            $info = 'onmouseover="DZCP.showInfo(\'' . jsconvert(re($get['titel'])) . '\', \'' . _datum . ';' . _autor . ';' . _news_admin_kat . ';' . _comments_head . '\', \'' . date("d.m.Y H:i", $get['datum']) . _uhr . ';' . fabo_autor($get['autor']) . ';' . jsconvert(re($getkat['kategorie'])) . ';' . cnt($db['newscomments'], "WHERE news = '" . $get['id'] . "'") . '\')" onmouseout="DZCP.hideInfo()"';
        
        $l_news .= show("menu/last_news", array(
            "id" => $get['id'],
            "titel" => re(cut($get['titel'], $lnews)),
            "datum" => date("d.m.Y", $get['datum']),
            "info" => $info
        ));
    }
    return empty($l_news) ? '' : '<table class="navContent" cellspacing="0">' . $l_news . '</table>';
}

?>
