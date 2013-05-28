<?php
// Image Gallerie
function gallerie()
{
  global $db;
  $qry = db("SELECT * FROM ".$db['gallery']."
             ORDER BY id DESC LIMIT 4");
  while($get = _fetch($qry)){
	  $imgArr = array();
	  $files = get_files("../gallery/images/");
	  foreach($files AS $file)
  	  {
    	if(intval($file) == $get['id']) array_push($imgArr, $file);
  	  }
	  $cnt = 0;
      for($i=0; $i<count($files); $i++)
      {
        if(preg_match("#^".$get['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($files[$i]))!=FALSE)
        {
          $cnt++;
        }
      }
	   $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['kat'])).'\', \''._gal_pics.'\', \''.$cnt.'\')" onmouseout="DZCP.hideInfo()"';
	  $gallery .= show("menu/gallerie", array("info" => '<p><b>'.jsconvert(re($get['kat'])).'</b></p><p>'._gal_pics.$cnt.'</p>',
											  "image" => $imgArr[0],
											  "kat" => re($get['kat']),
											  "info" => $info,
											  "id" => $get['id']));
  }

  return empty($gallery) ? '<center>No Pictures Added</center>' : $gallery;
}