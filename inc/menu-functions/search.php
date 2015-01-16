<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: Globale Suche
 */
function search() {
    return show("menu/search", array("submit" => _button_value_search, "searchword" => (empty($_GET['searchword']) ? _search_word : up($_GET['searchword']))));
}