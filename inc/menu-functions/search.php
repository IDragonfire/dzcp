<?php
//-> globale Suche
function search()
{
  return show("menu/search", array("submit" => _button_value_search,
                                   "searchword" => (empty($_GET['searchword']) ? _search_word : up($_GET['searchword']))));
}