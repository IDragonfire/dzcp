<?php
function random_gallery()
{
  global $db;

  $imgArr = array();
  $files = get_files('../gallery/images/',false,true);
  $get = _fetch(db("SELECT * FROM ".$db['gallery']." ORDER BY RAND()"));
  foreach($files AS $file)
  {
    if(intval($file) == $get['id']) array_push($imgArr, $file);
  }

  shuffle($imgArr);
  if(!empty($imgArr[0]))
  {
    $gallery = show("menu/random_gallery", array("image" => $imgArr[0],
                                                 "id"    => $get['id'],
          							                         "kat"   => re($get['kat'])));
  }

  return empty($gallery) ? '' : '<table class="navContent" cellspacing="0">'.$gallery.'</table>';
}
?>
