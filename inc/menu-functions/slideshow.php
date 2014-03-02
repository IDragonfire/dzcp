<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Slideshow
 */
function slideshow() {
    global $db;
    $qry = db("SELECT `id`,`desc`,`showbez`,`bez`,`url` FROM ".$db['slideshow']." ORDER BY `pos` ASC LIMIT 4");
    if(_rows($qry) >= 1) {
        $pic = ''; $tabs = '';
        while($get = _fetch($qry)) {
            if(empty($get['desc']) && !$get['showbez'])
                $slideroverlay = '';
            else if(!empty($get['desc']) && !$get['showbez'])
                $slideroverlay = '<div class="slideroverlay"><span>'.bbcode(wrap(re($get['desc']))).'</span></div>';
            else
                $slideroverlay = '<div class="slideroverlay"><h2>'.bbcode(wrap(re($get['bez']))).'</h2><span>'.bbcode(wrap(re($get['desc']))).'</span></div>';

            $target = ($get['target'] == "1" ? ",1" : "");
            $pic .= show("menu/slideshowbild", array("image" => "<img src=\"../inc/images/slideshow/".$get['id'].".jpg\" alt=\"\" />",
                                                     "link" => "'".$get['url']."'".$target,
                                                     "bez" => re(cut($get['bez'],32)),
                                                     "text" => $slideroverlay));

            $tabs .= '<a href="#" class="slidertabs" id="slider'.$get['id'].'"></a>';
        }

        return show("menu/slideshow", array("pic" => $pic, "tabs" => $tabs));
    }

    return '';
}