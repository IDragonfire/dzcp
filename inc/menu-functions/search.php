<?php
//-> globale Suche
function search()
{
  return show("menu/search", array("submit" => _button_value_search,
                                   "search" => (empty($_GET['search']) ? _search_word : up($_GET['search']))));
}
?>
