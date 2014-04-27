<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Events
 */
function events() {
    global $db;

    $qry = db("SELECT `id`,`datum`,`title`,`event` FROM ".$db['events']."
               WHERE `datum` > ".time()."
               ORDER BY datum
               LIMIT ".config('m_events')."");

    $eventbox = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['title'])).'\', \''._kalender_uhrzeit.';'._datum.'\', \''.date("H:i", $get['datum'])._uhr.';'.date("d.m.Y", $get['datum']).'\')" onmouseout="DZCP.hideInfo()"';
            $events = show(_next_event_link, array("datum" => date("d.m.",$get['datum']),
                                                   "timestamp" => $get['datum'],
                                                   "event" => $get['title'],
                                                   "info" => $info));

            $eventbox .= show("menu/event", array("events" => $events,
			                                      "info" => $info));
        }
    }

    return empty($eventbox) ? '<center style="margin:2px 0">'._no_events.'</center>' : '<table class="navContent" cellspacing="0">'.$eventbox.'</table>';;
}