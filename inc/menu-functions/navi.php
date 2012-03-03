<?php
function navi($kat)
{
  global $db,$chkMe,$userid,$designpath;

    if($k = _fetch(db("SELECT `level` FROM ".$db['navi_kats']." WHERE `placeholder` = '".up($kat)."'")))
    {
      $intern = ($chkMe >= 2) ? '' : " AND s1.`internal` = '0'";
      $permissions = ($kat == 'nav_admin' && admin_perms($userid)) ? "" : $intern." AND ".intval($chkMe)." >= '".intval($k['level'])."'";
      $qry = db("SELECT s1.* FROM ".$db['navi']." AS s1 LEFT JOIN ".$db['navi_kats']." AS s2 ON s1.kat = s2.placeholder
                 WHERE s1.kat = '".up($kat)."' AND s1.`shown` = '1' ".$permissions."
                 ORDER BY s1.pos");
      while($get = _fetch($qry))
      {
        if($get['type'] == 0) $link = '';
        elseif($get['type'] == 1 || $get['type'] == 2 || $get['type'] == 3) {
        
          $name = ($get['wichtig'] == 1) ? '<span class="fontWichtig">'.navi_name(re($get['name'])).'</span>' : navi_name(re($get['name']));
          $target = ($get['target'] == 1) ? '_blank' : '_self';
  
          if(file_exists($designpath.'/menu/'.$get['kat'].'.html')) {
            $link = show("menu/".$get['kat']."", array("target" => $target,
                                                       "href" => re($get['url']),
                                                       "title" => strip_tags($name),
                                                       "css" => ucfirst(str_replace('nav_', '', re($get['kat']))),
                                                       "link" => $name));
          } else {   
            $link = show("menu/nav_link", array("target" => $target,
                                                "href" => re($get['url']),
                                                "title" => strip_tags($name),      
                                                "css" => ucfirst(str_replace('nav_', '', re($get['kat']))),                                        
                                                "link" => $name));
          }
          $table = strstr($link, '<tr>') ? true : false; 
        }
        $navi .= $link;
      }
    }

  return empty($navi) ? '' : ($table ? '<table class="navContent" cellspacing="0">'.$navi.'</table>' : $navi);
}
?>
