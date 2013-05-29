<?php
// Slideshow
function slideshow()
{
    global $db;
    $qry = db("SELECT * FROM ".$db['slideshow']." ORDER BY `pos` ASC LIMIT 4");
    if(_rows($qry) >= 1)
    {
        $pic = ''; $tabs = '';
        while($get = _fetch($qry))
        {
            $target = ($get['target'] == "1" ? ",1" : "");
            $pic .= show("menu/slideshowbild", array("image" => "<img src=\"../inc/images/slideshow/".$get['id'].".jpg\" alt=\"\" />",
                                                     "link" => "'".$get['url']."'".$target,
                                                     "bez" => $get['bez'],
                                                     "text" => $get['desc']));

            $tabs .= '<a href="#" class="slidertabs" id="slider'.$get['id'].'"></a>';
        }

        return show("menu/slideshow", array("pic" => $pic, "tabs" => $tabs));
    }

    return '';
}