<?php
//-> counter output
function counter()
{
    global $db, $today, $counter_start, $useronline, $where, $isSpider;
    
    if (!$isSpider) {
        $qry2day = db("SELECT visitors FROM " . $db['counter'] . " 
                   WHERE today = '" . $today . "'");
        if (_rows($qry2day)) {
            $get2day = _fetch($qry2day);
            $v_today = $get2day['visitors'];
        } else {
            $v_today = 0;
        }
        
        $gestern = time() - 86400;
        
        $tag       = date("j", $gestern);
        $monat     = date("n", $gestern);
        $jahr      = date("Y", $gestern);
        $yesterday = $tag . "." . $monat . "." . $jahr;
        
        $qryyday = db("SELECT visitors FROM " . $db['counter'] . " 
                   WHERE today = '" . $yesterday . "'");
        
        if (_rows($qryyday)) {
            $getyday = _fetch($qryyday);
            $yDay    = $getyday['visitors'];
        } else
            $yDay = 0;
        
        $qrystats = db("SELECT SUM(visitors) AS allvisitors, 
                           MAX(visitors) AS maxvisitors, 
                           MAX(maxonline) AS maxonline, 
                           AVG(visitors) AS avgvisitors, 
                           SUM(visitors) AS allvisitors 
                    FROM " . $db['counter'] . "");
        $getstats = _fetch($qrystats);
        
        
        if (abs(online_reg()) != 0) {
            $qryo = db("SELECT id FROM " . $db['users'] . " 
                  WHERE time+'" . $useronline . "'>'" . time() . "' 
                  AND online = 1 
                  ORDER BY nick");
            while ($geto = _fetch($qryo)) {
                $kats .= fabo_autor($geto['id']) . ';';
                $text .= jsconvert(getrank($geto['id'])) . ';';
            }
            
            $info = 'onmouseover="DZCP.showInfo(\'' . _online_head . '\', \'' . $kats . '\', \'' . $text . '\')" onmouseout="DZCP.hideInfo()"';
        }
        
        $counter = show("menu/counter", array(
            "v_today" => $v_today,
            "v_yesterday" => $yDay,
            "v_all" => $getstats['allvisitors'] + $counter_start,
            "v_perday" => round($getstats['avgvisitors'], 2),
            "v_max" => $getstats['maxvisitors'],
            "g_online" => abs(online_guests($where) - online_reg()),
            "u_online" => abs(online_reg()),
            "info" => $info,
            "v_online" => $getstats['maxonline'],
            "head_online" => _head_online,
            "head_visits" => _head_visits,
            "head_max" => _head_max,
            "user" => _cnt_user,
            "guests" => _cnt_guests,
            "today" => _cnt_today,
            "yesterday" => _cnt_yesterday,
            "all" => _cnt_all,
            "percentperday" => _cnt_pperday,
            "perday" => _cnt_perday,
            "online" => _cnt_online
        ));
        
        return '<table class="navContent" cellspacing="0">' . $counter . '</table>';
    }
}
?> 
                              "online" => _cnt_online));

    return '<table class="navContent" cellspacing="0">'.$counter.'</table>';
  }
}
?>
