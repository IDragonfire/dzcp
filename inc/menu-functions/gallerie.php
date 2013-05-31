<?php
// Image Gallerie
function gallerie()
{
    global $db,$picformat;
    $get = _fetch(db("SELECT id,kat FROM ".$db['gallery']." ORDER BY RAND()")); $gallery = '';
    $files = get_files(basePath.'/gallery/images/',false,true,$picformat,"#^".$get['id']."_(.*)#");
    $cnt = count($files);
    if($files && count($files) >= 1)
    {
        shuffle($files); $files = limited_array($files,1,4);
        foreach($files as $file)
        {
            if(!empty($file))
            {
                $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['kat'])).'\', \''._gal_pics.'\', \''.$cnt.'\')" onmouseout="DZCP.hideInfo()"';
                $gallery .= show("menu/gallerie", array("info" => '<p><b>'.jsconvert(re($get['kat'])).'</b></p><p>'._gal_pics.$cnt.'</p>',
                                                       "image" => $file,
                                                       "kat" => re($get['kat']),
                                                       "info" => $info,
                                                       "id" => $get['id']));
            }
        }
    }

    return empty($gallery) ? '<center>No Pictures Added</center>' : $gallery;
}