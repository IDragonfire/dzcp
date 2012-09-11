<?php
## Charset ##
define('_charset', 'iso-8859-1');

## ADDED FOR 1.6 Final
define('_config_version', 'Mod/AddOns verwalten');
define('_v_head', 'Mod/AddOn Versionen verwalten');
define('_v_name', 'Mod/AddOn Name');
define('_v_own', 'eigene Version');
define('_v_server', 'aktuelle Version');
define('_config_v_add', 'Mod/AddOn hinzuf&uuml;gen');
define('_v_server_hover', 'Versionspr&uuml;fung auf');
define('_v_unknown', 'unbekannt');
define('_v_fsockopen_not_allowed', 'fsockopen nicht erlaubt');
define('_v_no_data', 'Daten nicht abgerufen');
define('_v_get_data', 'Daten abrufen');
define('_v_server_id', 'Mod/AddOn ID auf Server');
define('_v_download_link', 'Mod/AddOn Download Link');
define('_v_added', 'Mod/AddOn hinzugef&uuml;gt');
define('_v_edited', 'Mod/AddOn erfolgreich bearbeitet');
define('_confirm_del_version', 'Es wird nur der Eintrag aus dieser Liste gel&ouml;scht, wirklich l&ouml;schen?');
define('_v_no_server', 'Kein Kontrollserver');

define('_search_con_or', 'mind. ein Wort muss vorhanden sein');
define('_search_con_and', 'absolute &Uuml;bereinstimmung');
define('_search_con_andb', 'alle W&ouml;rter m&uuml;ssen vorhanden sein');
define('_search_website','restliche Internetseite');
define('_search_for_area_forum','Forum-Suchbereich');

define("_dzcp_vcheck", 'Der DZCP Versions Checker informiert dich &uuml;ber neue DZCP Updates und zeigt dir, ob DZCP.de erreichbar ist um deine Version mit der aktuellen zu vergleichen.<br><br><span class=fontBold>Beschreibung:</span><br><font color=#17D427>Gr&uuml;n:</font> Verbindung zu DZCP.de erfolgreich, Up to Date!<br><font color=#FFFF00>Gelb:</font> Keine Verbindung zu DZCP.de m&ouml;glich<br><font color=#FF0000>Rot:</font> Es ist ein neues Update verf&uuml;gbar!');
define("_debug_on", 'Debug Modus ist aktiviert!');

## Navigation / Server ##
define('_navi_gsv_players_online', 'Online');
define('_navi_gsv_on_the_game', 'Spieler');
define('_navi_gsv_view_players', 'Spieler anzeigen');
define('_navi_gsv_game', 'Spiel');
define('_navi_gsv_no_name_available', 'Server name nicht vorhanden');        
define('_navi_gsv_no_players_available', 'Keine Spieler online');
define('_navi_gsv_password', 'Passwort');

## ADDED / REDEFINED FOR 1.5 Final
define('_id_dont_exist', 'Die von dir angegebene ID existiert nicht!');
define('_perm_editts', 'Teamspeak Server verwalten');
define('_config_url_linked_head', 'URLs verlinken');
define('_ts_settings_customicon', 'Eigene Icons runterladen');
define('_ts_settings_showchannels', 'Nur Channels mit Usern anzeigen');
define('_ts_settings_showchannels_desc', 'Wenn dies eingeschaltet ist werden nur Channels angezeigt in denen auch User sind.');

## ADDED / REDEFINED FOR 1.5.2
define('_button_title_del_account', 'User-Account l&ouml;schen');
define('_confirm_del_account', 'Moechtest du wirklich dein Benutzeraccount loeschen');
define('_profil_del_account', 'Account l&ouml;schen');
define('_profil_del_admin', '<b>L&ouml;schen nicht m&ouml;glich!</b>');
define('_info_account_deletet', 'Dein Account wurde erfolgreich gel&ouml;scht');
define('_news_get_timeshift', "Zeitversetzte News?");
define('_news_timeshift_from', "News Anzeigen ab:");
define('_config_gb_activ', 'G&auml;stebuch');
define('_config_gb_activ_info', '<center>Definiert, ob ein Eintrag zun&auml;chst von einem Admin freigegeben werden muss.</center>');
define('_legend_map_download', 'Map-Screenshots von dzcp.de herunterladen');
define('_map_download_success', 'Die Map-Screenshots wurden erfolgreich heruntergeladen!');
define('_mapdl_download', 'Sollen Map-Screenshots zu diesem Server gesucht und heruntergeladen werden?');
define('_error_mapdl_nomap', 'Zu diesem Spiel konnten derzeit leider keine Map-Screenshots gefunden werden.<br />Falls du welche der Community zur Verf&uuml;gung stellen m&ouml;chtest, so maile diese bitte an webmaster@dzcp.de.');
define('_error_mapdl_connection', 'Es konnte keine korrekte Verbindung zum Gameserver aufgebaut werden.');
define('_error_mapdl_server', 'Dieser Gameserver existiert nicht!');
define('_mapdl_loading', 'Lade Maps von dzcp.de');
define('_placeholder', 'Template Platzhalter');
define('_menu_kats_head', 'Menu Kategorien');
define('_menu_add_kat', 'Neue Menu Kategorie hinzuf&uuml;gen');
define('_confirm_del_menu', 'Soll die Kategorie wirklich gel&ouml;scht werden?');
define('_menu_edit_kat', 'Menu Kategorie editieren');
define('_menukat_updated', 'Die Menu Kategorie wurde erfolgreich editiert!');
define('_menukat_inserted', 'Die Menu Kategorie wurde erfolgreich hinzugef&uuml;gt!');
define('_menukat_deleted', 'Die Menu Kategorie wurde erfolgreich gel&ouml;scht!');
define('_menu_visible', 'sichtbar f&uuml;r Status');
define('_menu_kat_info', 'Die CSS-Klassen f&uuml;r die Links werden automatisch vom Template Platzhalter abgeleitet.<br />z.B. f&uuml;r den Platzhalter <i>[nav_main]</i> lautet die CSS-Klasse <i>a.navMain</i>');
define('_admin_sqauds_roster', 'Team-Roster');
define('_admin_squads_nav_info', 'Hiermit wird ein Direktlink in die Navigation gesetzt, welcher zur Vollansicht des Teams f&uuml;hrt.');
define('_admin_squads_teams', 'Team-Show');
define('_admin_squads_no_navi', 'Nicht einf&uuml;gen');
define('_seconds', 'Sekunden');
define('_config_cache_info', 'Hier k&ouml;nenn die Intervalle festgelegt werden, in der der Teamspeak- oder Gameserver neu abgefragt werden. Darunter werden die Daten aus dem Cache gelesen.');
define('_config_direct_refresh', 'Direkte Weiterleitung');
define('_config_direct_refresh_info', 'Wenn aktiviert, wird nach einer Aktion (z.B. Eintr&auml;ge in Forum, News, etc) direkt weitergeleitet, anstatt eine Infonachricht auszugeben.');
define('_cw_reset_button', 'Admin: Spielerstatus zur&uuml;cksetzen');
define('_cw_players_reset', 'Der Spielerstatus wurde erfolgreich zur&uuml;ckgesetzt!');
define('_eintrag_titel_forum', '<a href="[url]" title="Diesen Beitrag anzeigen"><span class="fontBold">#[postid]</span></a> am [datum] um [zeit]  [edit] [delete]');
define('_eintrag_titel', '<span class="fontBold">#[postid]</span> am [datum] um [zeit]  [edit] [delete]');
## ADDED / REDEFINED FOR 1.5.1
define('_config_double_post', 'Forum Doppelpost');
define('_config_fotum_vote', 'Forum-Vote');
define('_config_fotum_vote_info', '<center>Zeigt die Forum-Votes auch unter Umfragen an.</center>');
## ADDED / REDEFINED FOR 1.5
define('_side_membermap', 'Mitgliederkarte');
define('_installdir', "<tr><td colspan=\"15\" class=\"contentMainFirst\"><br /><center><b>Achtung! Sicherheitsrisiko!!</b><br><br>Bitte l&ouml;sche zuerst den Ordner <b>'/_installer'</b> von deinem Webspace.</center><br /></td></tr>");
define('_no_ts', 'kein Teamspeak eingetragen');
define('_search_sites', 'Unterseiten');
define('_search_results', 'Suchergebnisse');
define('_config_useradd_head', 'User anlegen');
define('_config_adduser', 'User hinzuf&uuml;gen');
define('_uderadd_info', 'Der User wurde erfolgreich hinzugef&uuml;gt');
define('_useradd_head', 'Neuen User anlegen');
define('_useradd_about', 'Userdetails');
define('_login_lostpwd', 'Passwort vergessen');
define('_login_signup', 'Registrieren');
define('_config_links', 'Links');
define('_no_server_navi', 'kein Server eingetragen');
define('_vote_menu_no_vote', 'keine Umfrage eingetragen');
define('_no_top_match', 'kein Top Match eingetragen');
define('_team_logo', 'Team Logo');
define('_cw_logo', 'Gegner Logo');
define('_cw_screenshot', 'Screenshot');
define('_cw_admin_top_setted', 'Der Clanwar wurde erfolgreich als Top Match eingetragen!');
define('_cw_admin_top_unsetted', 'Der Clanwar wurde erfolgreich als Top Match ausgetragen!');
define('_cw_admin_top_set', 'Als Top Match eintragen');
define('_cw_admin_top_unset', 'Als Top Match austragen');
define('_sq_banner', 'Teambanner');
define('_forum_abo_title', 'Thread abbonieren');
define('_forum_vote', 'Umfrage');
define('_admin_user_clanhead_info', 'Die Rechte hier k&ouml;nnen <u>zus&auml;tzlich</u> zu den Rechten der User-R&auml;nge vergeben werden.');
define('_config_positions_boardrights', 'interne Forenrechte');
define('_perm_awards', 'Awards verwalten');
define('_perm_clankasse', 'Clankasse verwalten');
define('_perm_contact', 'Kontakt Formular empfangen');
define('_perm_editkalender', 'Kalendereintr&auml;ge  verwalten');
define('_perm_editserver', 'Server verwalten');
define('_perm_edittactics', 'Taktiken verwalten');
define('_perm_forum', 'Foren Admin');
define('_perm_gb', 'G&auml;stebuch Admin');
define('_perm_links', 'Links verwalten');
define('_perm_newsletter', 'Newsletter verschicken');
define('_perm_rankings', 'Rankings verwalten');
define('_perm_serverliste', 'Serverliste verwalten');
define('_perm_votesadmin', 'Umfragen verwalten');
define('_perm_artikel', 'Artikel verwalten');
define('_perm_clanwars', 'Clanwars verwalten');
define('_perm_downloads', 'Downloads verwalten');
define('_perm_editor', 'Seitenverwaltung');
define('_perm_editsquads', 'Teams verwalten');
define('_perm_editusers', 'darf User editieren');
define('_perm_gallery', 'Gallerien verwalten');
define('_perm_glossar', 'Glossar verwalten');
define('_perm_intnews', 'interne News lesen');
define('_perm_joinus', 'JoinUs Formular empfangen');
define('_perm_receivecws', 'FightUs Formular empfangen');
define('_perm_news', 'Newsverwaltung');
define('_perm_shoutbox', 'Shoutbox Admin');
define('_perm_votes', 'interne Umfragen einsehen');
define('_perm_gs_showpw', 'Gameserver Passwort einsehen');
define('_config_positions_rights', 'Rechte');
define('_admin_pos', 'User R&auml;nge');
define('_awaycal', 'Abwesenheitsliste');
define('_clear_away', 'Abwesenheitsliste mit einbeziehen?');
define('_config_sponsors', 'Sponsoren');
define('_sponsors_admin_head', 'Sponsoren');
define('_sponsors_admin_add', 'Sponsor hinzuf&uuml;gen');
define('_sponsor_added', 'Sponsor erfolgreich hinzugef&uuml;gt!');
define('_sponsor_edited', 'Sponsor erfolgreich editiert!');
define('_sponsor_deleted', 'Sponsor erfolgreich gel&ouml;scht!');
define('_sponsor_name', 'Sponsor');
define('_sponsors_admin_name', 'Name');
define('_sponsors_admin_site', 'Sponsorenseite');
define('_sponsors_admin_addsite', 'Auf Sponsorenseite');
define('_sponsors_admin_add_site', 'Der Banner wird auf der Sponsorenseite angezeigt');
define('_sponsors_admin_upload', 'Bild-Upload');
define('_sponsors_admin_url', 'Oder: Bild-URL');
define('_sponsors_admin_banner', 'Rotation Banner');
define('_sponsors_admin_addbanner', 'In Rotations-Banner');
define('_sponsors_admin_add_banner', 'Der Banner wird oben in den Rotations-Banner aufgenommen');
define('_sponsors_admin_box', 'Sponsoren-Box');
define('_sponsors_admin_addbox', 'In Sponsoren-Box');
define('_sponsors_admin_add_box', 'Der Banner wird in der Sponsoren-Box angezeigt');
define('_sponsors_empty_name', 'Bitte den Namen des Sponsors angeben!');
define('_sponsors_empty_beschreibung', 'Du musst eine Beschreibung angeben!');
define('_sponsors_empty_link', 'Du musst eine Linkadresse angeben!');
define('_site_away', 'Abwesenheitskalender');
define('_away_list', 'Abwesenheitsliste');
define('_config_c_away', 'Abwesenheitsliste');
define('_away_status_new', '<b><font color=orange>Hinzugef&uuml;gt</font></b>');
define('_away_status_now', '<b><font color=green>Aktuell</font></b>');
define('_away_status_done', '<b><font color=red>Abgelaufen</font></b>');
define('_away_new', 'Melden');
define('_away_empty_titel', 'Bitte einen Grund angeben');
define('_away_empty_reason', 'Bitte ein Kommentar angeben');
define('_away_error_1', 'Das Enddatum darf nicht gleich sein wie das Startdatum!');
define('_away_error_2', 'Das Startdatum ist gr&ouml;ﬂer als das Enddatum!');
define('_away_to', 'bis');
define('_away_to2', 'zum');
define('_away_head', 'Abwesenheitsliste');
define('_away_new_head', 'Abwesenheit eintragen');
define('_away_reason', 'Grund');
define('_away_successful_added', 'Der Abwesenheitseintrag wurden erfolgreich hinzugef&uuml;gt!');
define('_away_on', 'um');
define('_away_info_head', 'Abwesenheits Info von');
define('_away_addon', 'Eingetragen am');
define('_away_formto', 'Von - Bis:');
define('_away_back', 'zur&uuml;ck zur Liste');
define('_away_edit_head', 'Abwesenheit editieren');
define('_away_successful_del', 'Der Abwesenheitseintrag wurde erfolgreich gel&ouml;scht!');
define('_away_successful_edit', 'Der Abwesenheitseintrag wurde erfolgreich bearbeitet!');
define('_away_no_entry', '<tr><td align="center" class="contentMainFirst" colspan="10"><span class="smallfont">kein Abwesenheitseintrag vorhanden!</span></td></tr>');
define('_lobby_away', 'Derzeit abwesend');
define('_lobby_away_new', 'Abwesenheitsmeldung');
define('_user_away', '<tr><td class="contentMainTop" width="25%" valign="top"><span class="fontBold">[naway]:</span></td><td class="contentMainFirst" width="75%">[away]</td>
</tr>');
define('_user_away_currently', '<tr><td class="contentMainTop" width="25%" valign="top"><span class="fontBold">[ncaway]:</span></td><td class="contentMainFirst" width="75%">[caway]</td></tr>
');
define('_user_away_new', '[user] - <b>Grund:</b> <a href="../away/?action=info&id=[id]">[what]</a><br />&nbsp;&nbsp;Abwesend vom [ab] bis [wieder]<br />');
define('_user_away_now', '[user] - <b>Grund:</b> <a href="../away/?action=info&id=[id]">[what]</a><br />&nbsp;&nbsp;noch bis [wieder] abwesend<br />');
define('_away_today', 'einschlie&szlig;lich <b>Heute</b>');
define('_public', 'ver&ouml;ffentlichen');
define('_non_public', 'nicht ver&ouml;ffentlichen');
define('_no_public', '<b>unver&ouml;ffentlicht</b>');
define('_no_events', 'keine Events geplant');
define('_config_c_events', 'Men&uuml;: Events');
define('_news_send', 'News einsenden');
define('_news_send_source', 'Quelle');
define('_news_send_titel', 'Newsvorschlag von [nick]');
define('_news_send_note', 'Mitteilung o. Hinweis f&uuml;r die Redaktion');
define('_news_send_done', 'Vielen Dank! Die News wurde erfolgreich an die Redaktion weitergeleitet');
define('_news_send_description', 'Liebe Besucher,<br /><br />mit dem folgenden Formular ist es m&ouml;glich im Netz gefundene, oder selbst erstellte News an uns zu senden. Der von Dir ausgef&uuml;llte Formularinhalt wird dann mittels eines Verteilers an unsere Redakteure weitergeleitet. Bitte bedenke, dass wir jede Einsendung aufbereiten und evtl. genauere Details recherchieren m&uuml;ssen, um die gewohnte Qualit&Auml;t unserer News beizubehalten. Dies f&Auml;llt uns nat&uuml;rlich leichter, wenn Deine Einsendung bereits viele Einzelheiten aufweist und selbst formulierte Texte beinhaltet. Meldungen die lediglich 1:1 von anderen Seiten kopiert wurden, erschweren unsere Arbeit und verhindern nicht selten eine Ver&ouml;ffentlichung der Einsendung auf unserer Hauptseite.<br /><br />Nat&uuml;rlich sind wir &uuml;ber jede von Dir eingesendete News dankbar und freuen uns &uuml;ber das Engagement unserer Besucher.<br /><br />Vielen Dank im Voraus.<br /><br />Dein Redaktions-Team');
define('_contact_text_sendnews', '
[nick] hat uns ein Newsvorschlag eingesendet!<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontBold">Nick:</span> [nick]<p>&nbsp;</p>
<span class="fontBold">Email:</span> [email]<p>&nbsp;</p>
<span class="fontBold">Quelle:</span> [hp]<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontBold">Titel:</span> [titel]<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontUnder"><span class="fontBold">News:</span></span><p>&nbsp;</p>[text]<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontUnder"><span class="fontBold">Mitteilung oder Hinweis:</span></span><p>&nbsp;</p>[info]');

define('_msg_sendnews_user', '
<tr>
  <td align="center" class="contentMainTop"><span class="fontBold">Damit die anderen Redakteure wissen, dass du diese News ver&ouml;ffentlichen wirst,<br />klicke bitte auf den nachfolgenden Button. Danke</span></td>
</tr>
<tr>
  <td align="center" class="contentMainTop">
    <form action="" method="get" onsubmit="sendMe()">
      <input type="hidden" name="action" value="msg" />
      <input type="hidden" name="do" value="sendnewsdone" />
      <input type="hidden" name="id" value="[id]" />
	  <input type="hidden" name="datum" value="[datum]" />
      <input id="contentSubmit" type="submit" class="submit" value="Best&auml;tigen" />
    </form>
  </td>
</tr>');
define('_msg_sendnews_done', '
<tr>
  <td align="center" class="contentMainTop"><span class="fontRed">Diese News wird/wurde bereits von [user] bearbeitet!!!</span></td>
</tr>');
define('_send_news_done', 'Vielen Dank f&uuml;r das Best&auml;tigen und das einstellen des Newsvoschlags!');
define('_msg_all_leader', "alle Leader & Co-Leader");
define('_msg_leader', "Squad-Leader");
define('_pos_nletter', 'Diese Position in Newsletter an Squadleader und Co-Leader mit einbeziehen');
define('_clankasse_vwz', 'Verwendungszweck');
define('_pwd2', 'Passwort wiederhohlen');
define('_wrong_pwd', 'Die eingegebenen Passw&ouml;rter stimmen nicht &uuml;berein');
define('_info_reg_valid_pwd', 'Du hast dich erfolgreich registriert und kannst dich nun mit deinen Zugangsdaten einloggen!<br /><br />Deine Zugangsdaten wurden dir zur Sicherheit noch an die Emailadresse [email] gesendet.');
define('_profil_pnmail', 'Email bei neuen Nachrichten');
define('_admin_pn_subj', 'Betreff: PN-Email');
define('_admin_pn', 'PN-Email Template');
define('_admin_fabo_npost_subj', 'Betreff: ForenAbo Neuer Post');
define('_admin_fabo_pedit_subj', 'Betreff: ForenAbo Post editiert');
define('_admin_fabo_tedit_subj', 'Betreff: ForenAbo Thread editiert');
define('_admin_fabo_npost', 'ForenAbo Neuer Post Template');
define('_admin_fabo_pedit', 'ForenAbo Post editiert Template');
define('_admin_fabo_tedit', 'ForenAbo Thread editiert Template');
define('_foum_fabo_checkbox', 'Diesen Thread abonnieren und per E-Mail &uuml;ber neue Posts benachrichtigt werden?');
define('_forum_fabo_do', 'E-Mail Benachrichtigung erfolgreich ge&auml;ndert!');
define('_user_link_fabo', '[nick]');
define('_forum_vote_del', 'Umfrage l&ouml;schen');
define('_forum_vote_preview', 'Hier erscheint dann die Umfrage');
define('_forum_spam_text', '[ltext]<p>&nbsp;</p><p>&nbsp;</p><span class="fontBold">Nachtrag von </span>[autor]:<p>&nbsp;</p>[ntext]');
####################################################################################
define('_cw_screens_info', 'Nur jpg oder gif Dateien!');
define('_config_config', 'Allgemeine Einstellungen');
define('_config_dladmin', 'Downloads');
define('_config_editor', 'Seitenverwaltung');
define('_config_konto', 'Clankasse');
define('_config_dl', 'Downloadkategorien');
define('_config_nletter', 'Newsletter');
define('_config_protocol', 'Adminprotokoll');
define('_config_serverlist', 'Serverliste');
define('_partnerbuttons_textlink', 'Textlink');
define('_config_forum_subkats_add', '
    <form action="" method="get" onsubmit="DZCP.submitButton()">
      <input type="hidden" name="admin" value="forum" />
      <input type="hidden" name="do" value="newskat" />
      <input type="hidden" name="id" value="[id]" />
      <input id="contentSubmit" type="submit" class="submit" value="Neue Unterkategorie hinzuf&uuml;gen" />
    </form>
');
define('_msg_answer', '
    <form action="" method="get" onsubmit="DZCP.submitButton()">
      <input type="hidden" name="action" value="msg" />
      <input type="hidden" name="do" value="answer" />
      <input type="hidden" name="id" value="[id]" />
      <input id="contentSubmit" type="submit" class="submit" value="Antworten" />
    </form>');
define('_user_new_erase', '<form method="get" action="" onsubmit="DZCP.submitButton()"><input type="hidden" name="action" value="erase" /><input id="contentSubmit" type="submit" name="submit" class="submit" value="tempor&auml;re Neuerungen l&ouml;schen" /></form>');
define('_klapptext_server_link', '<a href="javascript:DZCP.toggle(\'[id]\')"><img src="../inc/images/[moreicon].gif" alt="" id="img[id]">[link]</a>');
define('_profile_add', '<form action="" method="get" onsubmit="return(DZCP.submitButton())">
      <input type="hidden" name="admin" value="profile" />
      <input type="hidden" name="do" value="add" />
      <input id="contentSubmit" type="submit" class="submit" value="Neues Profilfeld hinzuf&uuml;gen" />
    </form>');
define('_clankasse_new', '<form action="" method="get" onsubmit="return(DZCP.submitButton())">
      <input type="hidden" name="action" value="admin" />
      <input type="hidden" name="do" value="new" />
      <input id="contentSubmit" type="submit" class="submit" value="Neuen Beitrag hinzuf&uuml;gen" />
    </form>');
define('_admin_reg_info', 'Hier kannst du einstellen, ob sich jemand f&uuml;r einen der Bereiche registrieren muss um dort etwas tun zu k&ouml;nnen (Beitr&auml;ge schreiben, einen Download herunterladen, etc)');
define('_config_c_floods_what', 'Hier kannst du die Zeit in Sekunden einstellen die ein User warten muss, bis er im jeweiligen Bereich was neues posten darf');
define('_confirm_del_shout', 'Soll dieser Shoutboxeintrag wirklich geloescht werden');
## ADDED FOR 1.4.9.3
define('_ergebnisticker_more', 'Weitere Ergebnisse');
define('_site_ergebnisticker', 'ESL Pro Series - Ergebnisticker');
## ADDED FOR 1.4.5
define('_admin_smiley_exists', 'Es ist bereits ein Smiley mit diesem Namen vorhanden!');
## ADDED FOR 1.4.3
define('_download_last_date', 'Zuletzt heruntergeladen');
## EDITED FOR 1.4.1
define('_ulist_normal', 'Rang &amp; Level');
## ADDED FOR 1.4.1
define('_lobby_mymessages', '<a href="../user/?action=msg">Du hast <span class="fontWichtig">[cnt]</span> neue Nachrichten!</a>');
define('_lobby_mymessage', '<a href="../user/?action=msg">Du hast <span class="fontWichtig">1</span> neue Nachricht!</a>');
## EDIT/ADDED FOR 1.4
//Added
define('_protocol_action', 'Aktion');
define('_protocol', 'Adminprotokoll');
define('_button_title_del_protocol', 'Komplettes Protokoll l&ouml;schen!');
define('_protocol_deleted', 'Das komplette Protokoll wurde erfolgreich gel&ouml;scht!');
define('_vote_no_answer', 'Du musst eine Antwort ausw&auml;hlen!');
define('_linkus_admin_edit', 'LinkUs editieren');
define('_config_linkus', 'LinkUs');
define('_glossar_specialchar', 'Es d&uuml;rfen keine Sonderzeichen in der Bezeichnung vorhanden sein!');
define('_admin_gmaps_who', 'Memberkarte');
define('_gmaps_who_all', 'Alle User anzeigen');
define('_gmaps_who_mem', 'Nur Mitglieder anzeigen');
define('_urls_linked_info', 'Textlinks werden automatisch in anklickbare Links konvertiert');
define('_config_gmaps_key', 'Google Maps Key');
define('_gmaps_key_info', 'Wird f&uuml;r die Membermap ben&ouml;tigt.<br />Der Key kann <a href="http://www.google.com/apis/maps/signup.html" target="_blank">hier</a> kostenlos erstellt werden!');
define('_gmaps_no_key', 'Die Membermap wurde noch nicht aktiviert!');
define('_membermap', 'Membermap');
define('_sponsoren', 'Sponsoren');
define('_downloads', 'Downloads');
define('_cw', 'Clanwars');
define('_awards', 'Awards');
define('_serverlist', 'Serverliste');
define('_ts', 'Teamspeak');
define('_galerie', 'Galerie');
define('_kontakt', 'Kontakt');
define('_nachrichten', 'Nachrichten');
define('_edit_profile', 'Profil editieren');
define('_clankasse', 'Clankasse');
define('_taktiken', 'Taktiken');
define('_user_new_newsc', '&nbsp;&nbsp;<a href="../news/?action=show&amp;id=[id]#lastcomment"><span class="fontWichtig">[cnt]</span> [eintrag] in <span class="fontWichtig">[news]</span></a><br />');
define('_config_c_teamrow', 'Men&uuml;: Teams');
define('_config_c_teamrow_info', '(Member pro Zeile)');
define('_config_c_lartikel', 'Men&uuml;: Last Artikel');
define('_config_hover', 'Mouseover Informationen');
define('_config_seclogin', 'Login Sicherheitsabfrage');
define('_config_hover_standard', 'Standard Informationen einblenden');
define('_config_hover_all', 'Alle Informationen einblenden');
define('_config_hover_cw', 'Nur Clanwar Informationen einblenden');
define('_shout_must_reg', 'Nur f&uuml;r registrierte User!');
define('_error_vote_show', 'Dies ist eine &ouml;ffentliche Umfrage und kann somit nicht eingesehen werden!');
define('_login_pwd_dont_match', 'Benutzername und/oder Passwort sind ung&uuml;ltig oder der Account wurde gesperrt!');
define('_sq_aktiv', 'Aktiv');
define('_sq_inaktiv', 'Inaktiv');
define('_sq_sstatus', '<center>Gibt an, ob das Team im Fightus Formular etc. aufgelistet werden soll</center>');
define('_internal', 'Intern');
define('_sticky', 'Wichtig');
define('_lobby_new_cwc_1', 'neuer Clanwarkommentar');
define('_lobby_new_cwc_2', 'neue Clanwarkommentare');
define('_admin_glossar_added', 'Der Begriff wurde erfolgreich eingetragen!');
define('_admin_glossar_edited', 'Der Begriff wurde erfolgreich editiert!');
define('_admin_glossar_deleted', 'Der Begriff wurde erfolgreich gel&ouml;scht!');
define('_admin_error_glossar_desc', 'Du musst eine Erkl&auml;rung zu dem angegbenen Begriff angeben!');
define('_admin_error_glossar_word', 'Du musst einen Begriff angeben!');
define('_admin_glossar_add', 'Neuen Begriff eintragen');
define('_config_glossar', 'Glossar');
define('_config_gallery', 'Galerie');
define('_glossar', 'Glossar');
define('_admin_glossar', 'Admin: Glossar');
define('_admin_fightus', 'FightUs empfangen?');
define('_misc', "Sonstige");
define('_all', "Alle");
define('_glossar_link', 'Klicke hier um mehr &uuml;ber <span class=fontBold>[word]</span> zu erfahren!');
define('_glossar_head', 'Glossar');
define('_glossar_bez', 'Bezeichnung');
define('_glossar_erkl', 'Erkl&auml;rung');
define('_admin_support_head', 'Support Informationen');
define('_admin_support_info', 'Nachfolgende Informationen bitte bei einer Supportanfrage z.B.im Forum von <a href="http://www.dzcp.de" target="_blank">www.dzcp.de</a> mit angeben, um schneller zu einer L&ouml;sung des Problemes zu kommen!');
define('_config_support', 'Supportinfos');
define('_search_head', 'Suchfunktion');
define('_search_word', 'Suchen nach...');
define('_search_forum_all', 'In allen Foren suchen');
define('_search_forum_hint', '(Durch dr&uuml;cken der \'Strg-Taste\' lassen<br />sich mehrere einzelne Foren ausw&auml;hlen)');
define('_search_for_area', 'Suchbereich');
define('_search_type_full', 'vollst&auml;ndige Suche');
define('_search_type_title', 'nur Topic durchsuchen');
define('_search_type', 'Suchtyp');
define('_search_type_autor', 'Autoren finden');
define('_search_type_text', 'Text und Topic durchsuchen');
define('_search_in', 'Suchen in...');
define('_user_profile_of', 'Userprofil von ');
define('_sites_not_available', 'Die angeforderte Seite existiert nicht!');
define('_wrote', 'schrieb');
define('_voted_head', 'Bereits an der Umfrage teilgenommen');
define('_show_who_voted', 'Zeige User, die bereits abgestimmt haben');
define('_no_live_status', 'Keine Liveabfrage');
define('_comment_edited', 'Der Kommentar wurde erfolgreich editiert!');
define('_comments_edit', 'Kommentar editieren');
define('_forum_post_where_preview', '<a href="javascript:void(0)">[mainkat]</a> <span class="fontBold">Forum:</span> <a href="javascript:void(0)">[wherekat]</a> <span class="fontBold">Thread:</span> <a href="javascript:void(0)">[wherepost]</a>');
define('_aktiv_icon', '<img src="../inc/images/active.gif" alt="" class="icon" />');
define('_inaktiv_icon', '<img src="../inc/images/inactive.gif" alt="" class="icon" />');
define('_pn_write_forum', '<a href="../user/?action=msg&amp;do=pn&amp;id=[id]"><img src="../inc/images/forum_pn.gif" alt="" title="[nick] eine Nachricht schreiben" class="icon" /></a>');
define('_uhr', '&nbsp;Uhr');
define('_admin_editor', 'Admin: Seitenverwaltung');
define('_kalender_admin_head', 'Kalender - Ereignisse');
define('_smileys_specialchar', 'Es d&uuml;rfen keine Sonder- oder Leerzeichen im BBCode angegeben sein!');
define('_award', 'Award');
define('_month', 'Monat');
define('_preview', 'Vorschau');
define('_error_edit_post', 'Du bist nicht dazu berechtigt diesen Eintrag zu editieren!');
define('_nletter_prev_head', 'Newslettervorschau');
define('_error_downloads_upload', 'Es gab einen Problem beim Upload (Datei zu gro&szlig;?)');
define('_news_comments_prev', '<a href="javascript:void(0)">0 Kommentare</a>');
define('_only_for_admins', ' (f&uuml;r Admins sichtbar)');
define('_content', 'Content');
define('_rootadmin', 'Seitenadmin');
define('_gb_edit_head', 'G&auml;stebucheintrag editieren');
define('_gb_edited', 'Der G&auml;stebucheintrag wurde erfolgreich editiert!');
define('_day', 'Tag');
define('_year', 'Jahr');
define('_nletter', 'Newsletter');
define('_subject', 'Betreff');
define('_server_admin_qport', 'Optional: Queryport');
define('_admin_server_nostatus', 'Keine Live-Abfrage');
define('_nletter_head', 'Newsletter verfassen');
define('_confirm_del_cw', 'Soll dieser Clanwar wirklich geloescht werden');
define('_confirm_del_vote', 'Soll diese Umfrage wirklich geloescht werden');
define('_confirm_del_dl', 'Soll dieser Download wirklich geloescht werden');
define('_confirm_del_galpic', 'Soll dieses Bild wirklich geloescht werden');
define('_confirm_del_gallery', 'Soll diese Galerie wirklich geloescht werden');
define('_confirm_del_entry', 'Soll dieser Eintrag wirklich geloescht werden');
define('_confirm_del_navi', 'Soll dieser Link wirklich geloescht werden');
define('_confirm_del_profil', 'Soll dieses Profilfeld wirklich geloescht werden? \n Alle Usereingaben f&uuml;r dieses Feld gehen dabei verloren!');
define('_confirm_del_smiley', 'Soll dieser Smiley wirklich geloescht werden');
define('_confirm_del_kat', 'Soll diese Kategorie wirklich geloescht werden');
define('_confirm_del_news', 'Soll diese News wirklich geloescht werden');
define('_confirm_del_site', 'Soll diese Seite wirklich geloescht werden');
define('_confirm_del_server', 'Soll dieser Server wirklich geloescht werden');
define('_confirm_del_artikel', 'Soll dieser Artikel wirklich geloescht werden');
define('_confirm_del_team', 'Soll dieses Team wirklich geloescht werden');
define('_confirm_del_award', 'Soll dieser Award wirklich geloescht werden');
define('_confirm_del_ranking', 'Soll dieses Ranking wirklich geloescht werden');
define('_confirm_del_link', 'Soll dieser Link wirklich geloescht werden');
define('_confirm_del_sponsor', 'Soll dieser Sponsor wirklich geloescht werden');
define('_confirm_del_kalender', 'Soll dieses Ereignis wirklich geloescht werden');
define('_confirm_del_taktik', 'Soll diese Taktik wirklich geloescht werden');
define('_link_type', 'Linktyp');
define('_sponsor', 'Sponsor');
define('_config_fileeditor_head', 'Dateieditor');
//-----------------------------------------------
define('_main_info', 'Hier kannst du allgemein Dinge einstellen wie den Seitentitel, das Standardtemplate, die Standardsprache, etc...');
define('_admin_eml_head', 'Emailvorlagen');
define('_admin_eml_info', 'Hier kannst du die Emailtemplates aus verschiedenen Bereichen editieren. Achte darauf, das du die Platzhalter in den Klammern nicht l&ouml;schst!');
define('_admin_reg_subj', 'Betreff: Registrierung');
define('_admin_pwd_subj', 'Betreff: Passwort vergessen');
define('_admin_nletter_subj', 'Betreff: Newsletter');
define('_admin_reg', 'Registrierungstemplate');
define('_admin_pwd', 'Passwort vergessen-Template');
define('_admin_nletter', 'Newslettertemplate');
define('_result', 'Endstand');
define('_opponent', 'Gegner');
define('_played_at', 'Gespielt am');
define('_error_empty_nachricht', 'Du musst eine Nachricht angeben!');
define('_ulist_usuche', 'Usersuche');
define('_links_empty_text', 'Du musst eine Banneradresse angeben!');
define('_login_secure_help', 'Gib den zweistelligen Zahlencode in das Feld ein um dich zu verifizieren!');
define('_online_head_guests', 'G&auml;ste online');
define('_back_3', '<a href="javascript: history.go(-3)" class="files">zur&uuml;ck</a>');
define('_filebrowser_head', 'DZCP Dateieditor');
define('_allowed_files', 'Editierbare Dateien');
define('_filebrowser_info_saved', 'Die Datei <b>[file]</b> wurde erfolgreich gespeichert!');
define('_filebrowser_info_restored', 'Die Datei <b>[file]</b> wurde erfolgreich wiederhergetellt!');
define('_filebrowser_info_deleted', 'Die Datei <b>[file]</b> wurde erfolgreich gel&ouml;scht!');
define('_legend_del', 'Datei l&ouml;schen');
define('_legend_ed', 'Datei editieren');
define('_legend_res', 'Datei wiederherstellen');
define('_admin_first', 'als erstes');
define('_admin_squads_nav', 'Navigation');
define('_admin_squad_show_info', '<center>Definiert, ob ein Team in der Team&uuml;bersicht standardm&auml;&szlig;ig ein- oder aufgeklappt ist</center>');
//Edited
define('_config_c_gallerypics_what', 'Maximale Anzahl der Bilder in der Usergalerie');
define('_dl_getfile', '[file] jetzt herunterladen');
define('_partners_link_add', 'Partnerbutton hinzuf&uuml;gen');
define('_config_forum_kats_add', 'Neue Kategorie hinzuf&uuml;gen');
define('_config_c_lnews', 'Men&uuml;: Last News');
define('_msg_new', 'Neue Nachricht schreiben');
define('_gallery_show_admin', 'Galerie hinzuf&uuml;gen');
define('_dl_titel', '<span class="fontBold">[name]</span> - [cnt] [file]');
define('_config_artikel', 'Artikel');
define('_config_forum', 'Forenkategorien');
define('_config_server', 'Server');
define('_config_serverliste', 'Serverliste');
define('_config_squads', 'Teams');
define('_config_backup', 'Datenbankbackup');
define('_config_news', 'News-/Artikelkategorien');
define('_config_positions', 'Rangbezeichnungen');
define('_config_allgemein', 'Konfiguration');
define('_config_impressum', 'Impressum');
define('_config_clankasse', 'Clankasse');
define('_config_downloads', 'Downloadkategorien');
define('_config_newsadmin', 'News');
define('_config_filebrowser', 'Dateieditor');
define('_config_navi', 'Navigation');
define('_config_online', 'Seitenverwaltung');
define('_config_partners', 'Partnerbuttons');
define('_config_clear', 'Datenbank&nbsp;aufr&auml;umen');
define('_config_smileys', 'Smiley-Editor');
define('_config_profile', 'Profilfelder');
define('_config_votes', 'Umfragen');
define('_config_cw', 'Clanwars');
define('_config_awards', 'Awards');
define('_config_rankings', 'Rankings');
define('_config_kalender', 'Kalender');
define('_config_einst', 'Einstellungen');
define('_profil_sig', 'Foren Signatur');
define('_akt_version', 'DZCP Version');
define('_forum_searchlink', '- <a href="../search/">Forensuche</a> -');
define('_msg_deleted', 'Die Nachricht wurde erfolgreich gel&ouml;scht!');
define('_info_reg_valid', 'Du hast dich erfolgreich registriert!<br />Dein Passwort wurde dir an die Emailadresse [email] gesendet.');
define('_server_ftp_file', '<span class="fontBold">Zieldatei:</span>');
define('_edited_by', '<br /><br /><i>zuletzt editiert von [autor] am [time]</i>');
define('_linkus_empty_text', 'Du musst eine Banner-URL angeben!');
define('_gb_titel', '<span class="fontBold">#[postid]</span> von [nick] [email] [hp] am [datum] um [zeit][uhr] [edit] [delete] [comment] [public]');
define('_gb_titel_noreg', '<span class="fontBold">#[postid]</span> von <span class="fontBold">[nick]</span> [email] [hp] am [datum] um [zeit][uhr]  [edit] [delete] [comment] [public]');
define('_empty_news_title', 'Du musst einen Newstitel angeben!');
define('_member_admin_votes', 'Interne Umfragen sehen');
define('_member_admin_votesadmin', 'Admin: Umfragen');
define('_msg_global_all', 'alle Mitglieder');
define('_smileys_info', 'Du kannst alle neuen Smileys auch per FTP in den Ordner <span class="fontItalic">./inc/images/smileys/</span> hochladen! Dabei ist der Dateiname gleich dem des BBCodes. z.B. dzcp.gif = :dzcp:');
define('_pos_empty_kat', 'Du musst eine Rangbezeichnung angeben!');
define('_forum_lastpost', '<a href="?action=showthread&amp;id=[tid]&amp;page=[page]#p[id]"><img src="../inc/images/forum_lpost.gif" alt="" title="Zum letzten Eintrag" class="icon" /></a>');
define('_forum_addpost', '<a href="?action=post&amp;do=add&amp;kid=[kid]&amp;id=[id]"><img src="../inc/images/forum_reply.gif" alt="" title="Neuer Eintrag" class="icon" /></a>');
define('_pn_write', '<a href="../user/?action=msg&amp;do=pn&amp;id=[id]"><img src="../inc/images/pn.gif" alt="" title="[nick] eine Nachricht schreiben" class="icon" /></a>');
define('_forum_new_thread', '<a href="?action=thread&amp;do=add&amp;kid=[id]"><img src="../inc/images/forum_new.gif" alt="" title="Neuen Thread erstellen" class="icon" /></a>');
define('_anm_head', 'Anmerkung');
define('_anm_info', 'Es werden nur editierbare Dateien angezeigt!');
//--------------------------------------------\\
define('_error_invalid_regcode', 'Der eingegebene Sicherheitsscode stimmt nicht mit der in der Grafik angezeigten Zeichenfolge &uuml;berein!');
define('_welcome_guest', ' <img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" /> <a class="welcome" href="../user/?action=register">Gast</a>');
define('_online_head', 'User online');
define('_online_whereami', 'Bereich');
define('_back', '<a href="javascript: history.go(-1)" class="files">zur&uuml;ck</a>');
define('_contact_text_fightus', '
Jemand hat das Fightus-Kontaktformular ausgef&uuml;llt!<br />
Jeder berechtigte Admin hat darauf hin diese Nachricht empfangen!<br /><br />
<span class="fontBold">Team:</span> [squad]<br /><br />
<span class="fontUnder"><span class="fontBold">Ansprechpartner:</span></span><br />
<span class="fontBold">Nick:</span> [nick]<br />
<span class="fontBold">Email:</span> [email]<br />
<span class="fontBold">ICQ-Nr.:</span> [icq]<br /><br />
<span class="fontBold"><span class="fontUnder">Clandaten:</span></span><br />
<span class="fontBold">Clanname:</span> [clan]<br />
<span class="fontBold">Homepage:</span> [hp]<br />
<span class="fontBold">Game:</span> [game]<br />
<span class="fontBold">XonX:</span> [us] vs. [to]<br />
<span class="fontBold">Unsere Map:</span> [map]<br />
<span class="fontBold">Datum:</span> [date]<br /><span class="fontUnder">
<span class="fontBold">Kommentar:</span></span><br />[text]');
## EDITED/ADDED FOR v 1.3.3
define('_cw_info', 'Der Admin f&uuml;r diesen Bereich erh&auml;lt auch die FightUs-Anfragen!');
define('_level_info', 'Beim vergeben des Levels "Admin" kann das Level nur noch &uuml;ber den Root Admin (derjenige, der das Clanportal installiert hat) ge&auml;ndert werden!<br />Ferner hat der Besitzer diesen Levels <span class="fontUnder">uneingeschr&auml;nkten</span> Zugriff auf alle Bereiche!');
## EDITED FOR v 1.3.1
define('_related_links','related Links:');
define('_cw_admin_lineup_info','Namen mit Komma trennen!');
define('_profil_email2', 'E-mail #2');
define('_profil_email3', 'E-mail #3');
## Allgemein ##
define('_button_title_del', 'L&ouml;schen');
define('_button_title_edit', 'Editieren');
define('_button_title_zitat', 'Diesen Beitrag zitieren');
define('_button_title_comment', 'Diesen Beitrag kommentieren');
define('_button_title_menu', 'ins Menu eintragen');
define('_button_value_add', 'Eintragen');
define('_button_value_addto', 'Hinzuf&uuml;gen');
define('_button_value_edit', 'Editieren');
define('_button_value_search', 'Suchen');
define('_button_value_search1', 'Suche starten');
define('_button_value_vote', 'Abstimmen');
define('_button_value_show', 'Anzeigen');
define('_button_value_send', 'Abschicken');
define('_button_value_reg', 'Registrieren');
define('_button_value_msg', 'Nachricht senden');
define('_button_value_nletter', 'Newsletter abschicken');
define('_button_value_config', 'Konfiguration abspeichern');
define('_button_value_clear', 'Datenbank bereinigen');
define('_button_value_save', 'Speichern');
define('_button_value_upload', 'Hochladen');
define('_editor_from', 'Von');
define('intern', '<span class="fontWichtig">Intern</span>');
define('_comments_head', 'Kommentare');
define('_click_close', 'schlie&szlig;en');
## Begr&uuml;ﬂungen ##
define('_welcome_18', 'Guten Abend,');
define('_welcome_13', 'Guten Tag,');
define('_welcome_11', 'Mahlzeit,');
define('_welcome_5', 'Guten Morgen,');
define('_welcome_0', 'Gute Nacht,');
## Monate ##
define('_jan', 'Januar');
define('_feb', 'Februar');
define('_mar', 'M&auml;rz');
define('_apr', 'April');
define('_mai', 'Mai');
define('_jun', 'Juni');
define('_jul', 'Juli');
define('_aug', 'August');
define('_sep', 'September');
define('_okt', 'Oktober');
define('_nov', 'November');
define('_dez', 'Dezember');
## Laenderliste ##
define('_country_list', '
<option value="eg"> &Auml;gypten</option>
<option value="et"> &Auml;thopien</option>
<option value="al"> Albanien</option>
<option value="dz"> Algerien</option>
<option value="ao"> Angola</option>
<option value="ar"> Argentinien</option>
<option value="am"> Armenien</option>
<option value="aw"> Aruba</option>
<option value="au"> Australien</option>
<option value="az"> Aserbaidschan</option>
<option value="bs"> Bahamas</option>
<option value="bh"> Bahrain</option>
<option value="bd"> Bangladesh</option>
<option value="bb"> Barbados</option>
<option value="be"> Belgien</option>
<option value="bz"> Belize</option>
<option value="bj"> Benin</option>
<option value="bm"> Bermuda</option>
<option value="bt"> Bhutan</option>
<option value="bo"> Bolivien</option>
<option value="ba"> Bosnien Herzegovina</option>
<option value="bw"> Botswana</option>
<option value="br"> Brasilien</option>
<option value="bn"> Brunei Darussalam</option>
<option value="bg"> Bulgarien</option>
<option value="bf"> Burkina Faso</option>
<option value="bi"> Burundi</option>
<option value="cv"> Cape Verde</option>
<option value="ky"> Cayman Islands</option>
<option value="cl"> Chile</option>
<option value="cn"> China</option>
<option value="ck"> Cook Islands</option>
<option value="cr"> Costa Rica</option>
<option value="ci"> Cote D"Ivoire</option>
<option value="dk"> D&auml;nemark</option>
<option value="de"> Deutschland</option>
<option value="ec"> Ecuador</option>
<option value="er"> Eritrea</option>
<option value="ee"> Estland</option>
<option value="fo"> Faroer Inseln</option>
<option value="fj"> Fidschi</option>
<option value="fi"> Finnland</option>
<option value="fr"> Frankreich</option>
<option value="pf"> French Polynesia</option>
<option value="ga"> Gabon</option>
<option value="ge"> Georgien</option>
<option value="gi"> Gibraltar</option>
<option value="gr"> Griechenland</option>
<option value="uk"> Grossbritannien</option>
<option value="gl"> Gr&ouml;nland</option>
<option value="gp"> Guadeloupe</option>
<option value="gu"> Guam</option>
<option value="gt"> Guatemala</option>
<option value="gy"> Guyana</option>
<option value="ht"> Haiti</option>
<option value="hk"> Hong Kong</option>
<option value="is"> Island</option>
<option value="in"> Indien</option>
<option value="id"> Indonesien</option>
<option value="ir"> Iran</option>
<option value="iq"> Irak</option>
<option value="ie"> Irland</option>
<option value="il"> Israel</option>
<option value="it"> Italien</option>
<option value="jm"> Jamaica</option>
<option value="jp"> Japan</option>
<option value="jo"> Jordan</option>
<option value="yu"> Jugoslavien</option>
<option value="kh"> Kambodscha</option>
<option value="cm"> Kamerun</option>
<option value="ca"> Kanada</option>
<option value="qa"> Katar</option>
<option value="kz"> Kazachstan</option>
<option value="ke"> Kenia</option>
<option value="ki"> Kiribati</option>
<option value="co"> Kolumbien</option>
<option value="cg"> Kongo</option>
<option value="hr"> Kroatien</option>
<option value="cu"> Kuba</option>
<option value="kg"> Kyrgyzstan</option>
<option value="lv"> Lettland</option>
<option value="lb"> Libanon</option>
<option value="ly"> Lybien</option>
<option value="li"> Liechtenstein</option>
<option value="lt"> Litauen</option>
<option value="lu"> Luxemburg</option>
<option value="mo"> Macau</option>
<option value="mk"> Mazedonien</option>
<option value="mg"> Madagaskar</option>
<option value="my"> Malaysia</option>
<option value="ma"> Marocco</option>
<option value="mx"> Mexico</option>
<option value="md"> Moldawien</option>
<option value="mc"> Monaco</option>
<option value="mn"> Mongolei</option>
<option value="ms"> Montserrat</option>
<option value="mz"> Mozambique</option>
<option value="na"> Namibia</option>
<option value="nr"> Nauru</option>
<option value="np"> Nepal</option>
<option value="nc"> Neu Kaledonien</option>
<option value="nz"> Neuseeland</option>
<option value="nl"> Niederlande</option>
<option value="an"> Niederl&auml;ndische Antillen</option>
<option value="kp"> Nord Korea</option>
<option value="nf"> Norfolk Insel</option>
<option value="mp"> N&ouml;rdliche Marianen</option>
<option value="no"> Norwegen</option>
<option value="om"> Oman</option>
<option value="at"> &Ouml;sterreich</option>
<option value="tp"> Ost Timor</option>
<option value="pk"> Pakistan</option>
<option value="pa"> Panama</option>
<option value="py"> Paraguay</option>
<option value="pe"> Peru</option>
<option value="ph"> Philippinen</option>
<option value="pl"> Polen</option>
<option value="pt"> Portugal</option>
<option value="pr"> Puerto Rico</option>
<option value="ro"> Rum&auml;nien</option>
<option value="ru"> Russland</option>
<option value="lc"> Saint Lucia</option>
<option value="pm"> Saint Pierre und Miquelon</option>
<option value="ws"> Samoa</option>
<option value="sa"> Saudi Arabien</option>
<option value="sx"> Schottland</option>
<option value="sl"> Sierra Leone</option>
<option value="sg"> Singapur</option>
<option value="sk"> Slovakei</option>
<option value="si"> Slovenien</option>
<option value="sb"> Salomonen</option>
<option value="so"> Somalia</option>
<option value="za"> S&uuml;d Afrika</option>
<option value="kr"> S&uuml;d Korea</option>
<option value="es"> Spanien</option>
<option value="lk"> Sri Lanka</option>
<option value="sd"> Sudan</option>
<option value="sr"> Suriname</option>
<option value="se"> Schweden</option>
<option value="ch"> Schweiz</option>
<option value="sy"> Syrien</option>
<option value="tw"> Taiwan</option>
<option value="tz"> Tanzania</option>
<option value="th"> Thailand</option>
<option value="tg"> Togo</option>
<option value="to"> Tonga</option>
<option value="tt"> Trinidad und Tobago</option>
<option value="cz"> Tschechien</option>
<option value="tn"> Tunesien</option>
<option value="tr"> Turkei</option>
<option value="tc"> Turks und Caicos Islands</option>
<option value="tv"> Tuvalu</option>
<option value="ug"> Uganda</option>
<option value="ua"> Ukraine</option>
<option value="hu"> Ungarn</option>
<option value="uy"> Uruguay</option>
<option value="us"> USA</option>
<option value="ve"> Venezuela</option>
<option value="va"> Vatikan</option>
<option value="ae"> Vereinigte Arabische Emirate</option>
<option value="vn"> Vietnam</option>
<option value="vg"> Virgin Islands, Britisch</option>
<option value="vi"> Virgin Islands, U.S.</option>
<option value="by"> Weiﬂrussland</option>
<option value="ye"> Yemen</option>
<option value="zm"> Zambia</option>
<option value="cf"> Zentralafrikan. Republik</option>
<option value="cy"> Zypern</option>');
## Globale Userr&Auml;nge ##
define('_status_banned', 'gesperrt');
define('_status_unregged', 'unregistriert');
define('_status_user', 'User');
define('_status_trial', 'Trial');
define('_status_member', 'Member');
define('_status_admin', 'Admin');
## Userliste ##
define('_acc_banned', 'Gesperrt');
define('_ulist_acc_banned', 'Gesperrte Accounts');
## Login ##
define('_login_login', 'LogIn!');
## Navigation: Kalender ##
define('_kal_birthday', 'Geburtstag von ');
define('_kal_cw', 'Clanwar gegen ');
define('_kal_event', 'Event: ');
## Linkus ##
//-> Allgemein
define('_linkus_head', 'LinkUs');
//-> Admin
define('_linkus_admin_head', 'Neues LinkUs definieren');
define('_linkus_link', 'Ziellink');
define('_linkus_bsp_target', 'http://www.domain.tld');
define('_linkus_bsp_bannerurl', 'http://www.domain.tld/banner.jpg');
define('_linkus_bsp_desc', 'Beispielclan - Beschreibung');
define('_linkus_beschreibung', 'Title');
define('_linkus_text', 'Bannerlink');
define('_linkus_empty_beschreibung', 'Du musst einen Title-Tag angeben!');
define('_linkus_empty_link', 'Du musst eine Link-URL angeben!');
define('_linkus_added', 'Das LinkUs wurde erfolgreich hinzugef&uuml;gt!');
define('_linkus_edited', 'Das LinkUs wurde erfolgreich editiert!');
define('_linkus_deleted', 'Das LinkUs wurde erfolgreich gel&ouml;scht!');
define('_linkus', 'LinkUs');
## News ##
define('_news_kommentar', 'Kommentar');
define('_news_kommentare', 'Kommentare');
define('_news_viewed', '[<span class="fontItalic">[viewed] Hits</span>]');
define('_news_archiv', '<a href="?action=archiv">Archiv</a>');
define('_news_comment', '<a href="?action=show&amp;id=[id]">[comments] Kommentar</a>');
define('_news_comments', '<a href="?action=show&amp;id=[id]">[comments] Kommentare</a>');
define('_news_comments_write_head', 'Neuen Newskommentar schreiben');
define('_news_archiv_sort', 'Sortieren nach');
define('_news_archiv_head', 'Newsarchiv');
define('_news_kat_choose', 'Kategorie w&auml;hlen');
## Artikel ##
define('_artikel_comments_write_head', 'Neuen Artikelkommentar schreiben');
## Forum ##
define('_forum_head', 'Forum');
define('_forum_topic', 'Topic');
define('_forum_subtopic', 'Untertitel');
define('_forum_lpost', 'Letzter Beitrag');
define('_forum_threads', 'Threads');
define('_forum_thread', 'Thread');
define('_forum_posts', 'Beitr&auml;ge');
define('_forum_cnt_threads', '<span class="fontBold">Anzahl der Threads:</span> [threads]');
define('_forum_cnt_posts', '<span class="fontBold">Anzahl der Posts:</span> [posts]');
define('_forum_admin_head', 'Admin');
define('_forum_admin_addsticky', 'als <span class="fontWichtig">wichtig</span> markieren?');
define('_forum_katname_intern', '<span class="fontWichtig">Intern:</span> [katname]');
define('_forum_sticky', '<span class="fontWichtig">Wichtig:</span>');
define('_forum_subkat_where', '<a href="../forum/">[mainkat]</a> <span class="fontBold">Forum:</span> <a href="?action=show&amp;id=[id]">[where]</a>');
define('_forum_head_skat_search', 'In dieser Kategorie suchen');
define('_forum_head_threads', 'Threads');
define('_forum_replys', 'Antworten');
define('_forum_thread_lpost', 'von [nick]<br />am [date]');
define('_forum_new_thread_head', 'Neuen Thread erstellen');
define('_empty_topic', 'Du musst ein Topic angeben!');
define('_forum_newthread_successful', 'Der Thread wurde erfolgreich ins Forum eingetragen!');
define('_forum_new_post_head', 'Neuen Forenpost eintragen');
define('_forum_newpost_successful', 'Der Post wurde erfolgreich ins Forum eingetragen!');
define('_posted_by', '<span class="fontBold">&raquo;</span> ');
define('_forum_post_where', '<a href="../forum/">[mainkat]</a> <span class="fontBold">Forum:</span> <a href="?action=show&amp;id=[kid]">[wherekat]</a> <span class="fontBold">Thread:</span> <a href="?action=showthread&amp;id=[tid]">[wherepost]</a>');
define('_forum_lpostlink', 'Letzter Post');
define('_forum_user_posts', '<span class="fontBold">Posts:</span> [posts]');
define('_sig', '<br /><br /><hr />');
define('_error_forum_closed', 'Dieser Thread ist geschlossen!');
define('_forum_search_head', 'Forensuche');
define('_forum_edit_post_head', 'Forenpost editieren');
define('_forum_edit_thread_head', 'Thread editieren');
define('_forum_editthread_successful', 'Der Thread wurde erfolgreich editiert!');
define('_forum_editpost_successful', 'Der Eintrag wurde erfolgreich editiert!');
define('_forum_delpost_successful', 'Der Eintrag wurde erfolgreich gel&ouml;scht!');
define('_forum_admin_open', 'Thread ist ge&ouml;ffnet');
define('_forum_admin_delete', 'Thread l&ouml;schen?');
define('_forum_admin_close', 'Thread ist geschlossen');
define('_forum_admin_moveto', 'Thread verschieben nach:');
define('_forum_admin_thread_deleted', 'Der Thread wurde erfolgreich gel&ouml;scht!');
define('_forum_admin_do_move', 'Der Thread wurde erfolgreich bearbeitet<br />und in die Kategorie <span class="fontWichtig">[kat]</span> verschoben!');
define('_forum_admin_modded', 'Der Thread wurde erfolgreich bearbeitet!');
define('_forum_search_what', 'Suchen nach');
define('_forum_search_kat', 'in Kategorie');
define('_forum_search_suchwort', 'Suchw&ouml;rter');
define('_forum_search_inhalt', 'Inhalt');
define('_forum_search_kat_all', 'allen Kategorien');
define('_forum_search_results', 'Suchergebnisse');
define('_forum_online_head', 'Im Forum online:');
define('_forum_nobody_is_online', 'Zur Zeit ist kein User im Forum online!');
define('_forum_nobody_is_online2', 'Zur Zeit ist kein User auﬂer dir im Forum online!');
## G&Auml;stebuch ##
define('_gb_delete_successful', 'Der Eintrag wurde erfolgreich gel&ouml;scht!');
define('_gb_head', 'G&auml;stebuch');
define('_gb_add_head', 'Neuer G&auml;stebucheintrag');
define('_gb_eintragen', '<a href="#eintragen">Eintragen</a>');
define('_gb_entry_successful', 'Dein Eintrag ins G&auml;stebuch wurde zur Freischaltung an einem zust&auml;ndigen Admin weitergeleitet!');
define('_gb_addcomment_head', 'Kommentar');
define('_gb_addcomment_headgb', 'G&auml;stebucheintrag');
define('_gb_comment_added', 'Der Kommentar wurde erfolgreich hinzugef&uuml;gt!');
## Kalender ##
//-> Allgemein
define('_kalender_head', 'Kalender');
define('_kalender_month_select', '<option value="[i]" [sel]>[month]</option>');
define('_kalender_year_select', '<option value="[i]" [sel]>[year]</option>');
define('_montag', 'Montag');
define('_dienstag', 'Dienstag');
define('_mittwoch', 'Mittwoch');
define('_donnerstag', 'Donnerstag');
define('_freitag', 'Freitag');
define('_samstag', 'Samstag');
define('_sonntag', 'Sonntag');
//-> Events
define('_kalender_events_head', 'Ereignisse am [datum]');
define('_kalender_uhrzeit', 'Uhrzeit');
//-> Admin
define('_kalender_admin_head_add', 'Ereignis hinzuf&uuml;gen');
define('_kalender_admin_head_edit', 'Ereignis editieren');
define('_kalender_event', 'Ereignis');
define('_kalender_error_no_time', 'Du musst ein Datum und eine Zeit angeben!');
define('_kalender_error_no_title', 'Du musst einen Titel angeben!');
define('_kalender_error_no_event', 'Du musst das Ereignis beschreiben!');
define('_kalender_successful_added', 'Das Ereignis wurde erfolgreich eingetragen!');
define('_kalender_successful_edited', 'Das Ereignis wurde erfolgreich editiert!');
define('_kalender_deleted', 'Das Ereignis wurde erfolgreich gel&ouml;scht!');
## Umfragen ##
define('_error_vote_closed', 'Diese Umfrage ist geschlossen!');
define('_votes_admin_closed', 'Umfrage schlie&szlig;en');
define('_votes_head', 'Umfragen');
define('_votes_stimmen', 'Stimmen');
define('_votes_intern', '<span class="fontWichtig">Intern:</span> ');
define('_votes_results_head', 'Umfrageergebnis');
define('_votes_results_head_vote', 'Antwortm&ouml;glichkeiten');
define('_vote_successful', 'Du hast erfolgreich an der Umfrage teilgenommen!');
define('_votes_admin_head', 'Neue Umfrage hinzuf&uuml;gen');
define('_votes_admin_question', 'Frage');
define('_votes_admin_answer', 'Antwortm&ouml;glichkeit');
define('_empty_votes_question', 'Du musst eine Frage definieren!');
define('_empty_votes_answer', 'Du musst mindestens 2 Antworten definieren!');
define('_votes_admin_intern', 'Interne Umfrage');
define('_vote_admin_successful', 'Die Umfrage wurde erfolgreich eingetragen!');
define('_vote_admin_delete_successful', 'Die Umfrage wurde erfolgreich gel&ouml;scht!');
define('_vote_admin_successful_menu', 'Die Umfrage ist nun im Men&uuml; eingetragen!');
define('_vote_admin_menu_isintern', 'Du kannst keine interne Umfrage ins Men&uuml; setzen!');
define('_vote_legendemenu', 'Umfrage im Men&uuml;?<br />(Icon klicken um die Umfrage ein- oder auszutragen)');
define('_votes_admin_edit_head', 'Umfrage editieren');
define('_vote_admin_successful_edited', 'Die Umfrage wurde erfolgreich editiert!');
define('_vote_admin_successful_menu1', 'Die Umfrage wurde erfolgreich aus dem Men&uuml; ausgetragen!');
define('_error_voted_again', 'Du hast bereits an dieser Umfrage teilgenommen!');
## Links/Sponsoren ##
define('_links_head', 'Links');
define('_links_admin_head', 'Neuen Link hinzuf&uuml;gen');
define('_links_admin_head_edit', 'Link editieren');
define('_links_link', 'Linkadresse');
define('_links_beschreibung', 'Linkbeschreibung');
define('_links_art', 'Linkart');
define('_links_admin_textlink', 'Textlink');
define('_links_admin_bannerlink', 'Bannerlink');
define('_links_text', 'Banneradresse');
define('_links_empty_beschreibung', 'Du musst eine Linkbeschreibung angeben!');
define('_links_empty_link', 'Du musst eine Linkadresse angeben!');
define('_link_added', 'Der Link wurde erfolgreich hinzugef&uuml;gt!');
define('_link_edited', 'Der Link wurde erfolgreich editiert!');
define('_link_deleted', 'Der Link wurde erfolgreich gel&ouml;scht!');
define('_sponsor_head', 'Sponsoren');
## Downloads ##
define('_downloads_head', 'Downloads');
define('_downloads_download', 'Download');
define('_downloads_admin_head', 'Download hinzuf&uuml;gen');
define('_downloads_nofile', '<option value="lazy">- keine Datei -</option>');
define('_downloads_admin_head_edit', 'Download editieren');
define('_downloads_lokal', 'lokale Datei');
define('_downloads_exist', 'Datei');
define('_downloads_name', 'Downloadname');
define('_downloads_url', 'Datei');
define('_downloads_kat', 'Kategorie');
define('_downloads_empty_download', 'Du musst einen Downloadnamen angeben!');
define('_downloads_empty_url', 'Du musst eine Datei angeben!');
define('_downloads_empty_beschreibung', 'Du musst eine Beschreibung angeben!');
define('_downloads_added', 'Der Download wurde erfolreich hinzugef&uuml;gt!');
define('_downloads_edited', 'Der Download wurde erfolgreich editiert!');
define('_downloads_deleted', 'Der Download wurde erfolgreich gel&ouml;scht!');
define('_dl_info', 'Download Informationen');
define('_dl_file', 'Datei');
define('_dl_besch', 'Beschreibung');
define('_dl_info2', 'Datei Informationen');
define('_dl_size', 'Dateigr&ouml;&szlig;e');
define('_dl_speed', 'Geschwindigkeit');
define('_dl_traffic', 'verursachter Traffic');
define('_dl_loaded', 'bisherige Downloads');
define('_dl_date', 'Uploaddatum');
define('_dl_wait', 'Download der Datei: ');
## Teams ##
define('_member_squad_head', 'Teams');
define('_member_squad_no_entrys', '<tr><td align="center"><span class="fontBold">Keine eingetragenen Member</span></td></tr>');
define('_member_squad_weare', 'Wir sind insgesamt <span class="fontBold">[cm] Member</span> und besitzen <span class="fontBold">[cs] Team(s)</span>');
## Clanwars ##
define('_cw_comments_head', 'Clanwarkommentare');
define('_cw_comments_add', 'Neuen Kommentar schreiben');

define('_cw_head_details', 'Clanwar Details');
define('_cw_head_results', 'Ergebnisse');
define('_cw_head_lineup', 'Lineup');
define('_cw_head_glineup', 'Gegner Lineup');
define('_cw_head_admin', 'Admin(s)');
define('_cw_head_squad', 'Team');
define('_cw_bericht', 'Bericht');
define('_cw_maps', 'Maps');
define('_cw_serverpwd', '
<tr>
  <td class="contentMainTop"><span class="fontBold">Serverpasswort:</span></td>
  <td class="contentMainFirst" colspan="2" align="center">[cw_serverpwd]</td>
</tr>');
define('_cw_players_head', 'Spielerstatus');
define('_cw_status_set', 'Dein Status wurde erfolgreich gesetzt!');
define('_cw_players_play', 'Kannst/willst/m&ouml;chtest du spielen?');
define('_cw_player_dont_want', '<span class="fontRed">m&ouml;chte nicht spielen</span>');
define('_cw_player_want', '<span class="fontGreen">m&ouml;chte spielen</span>');
define('_cw_player_dont_know', 'wei&szlig; es noch nicht');
define('_cw_admin_head', 'Neuen Clanwar hinzuf&uuml;gen');
define('_cw_admin_head_edit', 'Clanwar editieren');
define('_cw_admin_info', 'Solange kein Ergebnis eingetragen ist, wird der War als "Next War" angezeigt!');
define('_cw_admin_gegnerstuff', 'Angaben zum Gegner');
define('_cw_admin_clantag', 'Clank&uuml;rzel');
define('_cw_admin_warstuff', 'Angaben zum Clanwar');
define('_cw_admin_maps', 'Maps');
define('_cw_admin_serverip', 'ServerIP');
define('_cw_admin_empty_gegner', 'Du musst den Namen des Gegners angeben!');
define('_cw_admin_empty_clantag', 'Du musst das Clank&uuml;rzel des Gegners angeben!');
define('_cw_admin_deleted', 'Der Clanwar wurde erfolgreich gel&ouml;scht!');
define('_cw_admin_added', 'Der Clanwar wurde erfolgreich hinzugef&uuml;gt!');
define('_cw_admin_edited', 'Der Clanwar wurde erfolgreich editiert!');
define('_cw_admin_head_squads', 'Angaben zum Team');
define('_cw_admin_head_country', 'Land');
define('_cw_head_statstik', 'Statistik');
define('_cw_gespunkte', 'Gesamtpunktzahl');
define('_cw_stats_ges_wars', '<span class="fontText">Unser Clan hat insgesamt <span class="fontBold">[ge_wars]</span> Clanwar(s) gespielt.</span>');
define('_cw_stats_ges_wars_sq', '<span class="fontText">Dieses Team hat insgesamt <span class="fontBold">[ge_wars]</span> Clanwar(s) gespielt.</span>');
define('_cw_stats_ges_points', 'Die Gesamtpunktzahl betr&auml;gt: <span class="CwWon">[ges_won]</span> : <span class="CwLost">[ges_lost]</span>');
define('_cw_stats_spiele_squads', 'Wir spielen insgesamt mit <span class="fontBold">[anz_squads]</span> Team(s) verteilt auf <span class="fontBold">[anz_games]</span> Spiel(en).');
define('_cw_stats_won_head', 'Won');
define('_cw_stats_lost_head', 'Lost');
define('_cw_stats_draw_head', 'Draw');
define('_cw_head_clanwars', 'Clanwars');
define('_cw_head_game', 'Spiel');
define('_cw_head_datum', 'Datum');
define('_cw_head_gegner', 'Gegner');
define('_cw_head_liga', 'Liga');
define('_cw_head_gametype', 'Spielart');
define('_cw_head_xonx', 'XonX');
define('_cw_head_result', 'Punkte');
define('_cw_head_details_show', 'Details');
define('_cw_head_page', 'Seite: ');
define('_cw_head_legende', 'Legende');
define('_cw_nothing', '<option value="lazy" class="" class="dropdownKat">--- nichts &auml;ndern ---</option>');
define('_cw_screens', 'Screenshots');
define('_cw_new', 'Neuer');
define('_clanwars_no_show', 'Noch keine Clanwars vorhanden!');
define('_cw_show_all', '
<tr>
  <td class="contentMainFirst" colspan="8" align="center"><a href="../clanwars/?action=showall&amp;id=[id]">Alle Wars dieses Teams anzeigen</a></td>
</tr>');
## Awards ##
define('_awards_head', 'Awards');
define('_awards_head_squad', 'Team');
define('_awards_head_date', 'Datum');
define('_awards_head_place', 'Platz');
define('_awards_head_prize', 'Preis');
define('_awards_head_event', 'Event-Name');
define('_awards_head_link', 'Event-Link');
define('_awards_no_show', 'Leider noch keine Awards vorhanden!');
define('_list_all_link', '<tr><td colspan ="7" class="contentMainTop" align="center"><a href="../awards/?action=showall&amp;id=[id]">Alle Awards dieses Teams anzeigen</td></tr>');
define('_head_stats', 'Statistik');
define('_awards_stats', '<center>Wir haben insgesamt <span class="fontBold">[anz] Awards</span> geholt!</center>');
define('_awards_stats_1', '<span class="fontBold">[anz]x</span> Platz Nr.1');
define('_awards_stats_2', '<span class="fontBold">[anz]x</span> Platz Nr.2');
define('_awards_stats_3', '<span class="fontBold">[anz]x</span> Platz Nr.3');
define('_awards_empty_url', 'Du musst einen Event Link angeben!');
define('_awards_empty_event', 'Du musst ein Event Namen angeben!');
define('_awards_admin_head_add', 'Neuen Award hinzuf&uuml;gen');
define('_awards_admin_added', 'Der Award wurde erfolgreich hinzugef&uuml;gt!');
define('_awards_admin_head_edit', 'Award editieren');
define('_awards_admin_edited', 'Der Award wurde erfolgreich editiert!');
define('_awards_admin_deleted', 'Der Award wurde erfolgreich gel&ouml;scht!');
define('_awards_head_legende', 'Legende');
## Rankings ##
define('_error_empty_league', 'Du musst eine Liga angeben!');
define('_error_empty_url', 'Du musst einen Teamlink angeben!');
define('_error_empty_rank', 'Du musst einen Platz angeben!');
## Server ##
define('_banned_reason', 'Grund');
define('_banned_head', 'Bannliste');
define('_banned_gesamt', '<span class="fontText">Insgesamt sind</span> <span class="fontBold">[ges] User</span> <span class="fontText">in der Bannliste</span>');
define('_banned_edit_head', 'Bannliste editieren');
define('_error_banned_edited', 'Der gebannte User wurde erfolgreich editiert!');
define('_server_head', 'Server');
define('_server_name', 'Servername');
define('_server_pwd', '<span class="fontBold">Passwort:</span> [pwd]');
define('_server_ip', 'IP');
define('_server_players', 'Spieler');
define('_server_aktmap', 'akt. Map');
define('_server_frags', 'Frags');
define('_server_time', 'gespielte Zeit');
define('_server_noplayers', '
<tr>
  <td class="contentMainFirst" align="center" colspan="5"><span class="fontBold">Keine Spieler auf dem Server</span></td>
</tr>');
define('_server_no_connection', '
<tr>
  <td class="contentMainFirst" align="center" colspan="2">Konnte keine Verbindung zum Server herstellen</td>
</tr>');
define('_server_splayerstats', 'Spielerstatistiken');
define('_generated_time', 'Die Seite wurde in [time] Sekunden generiert');
define('_slist_head', 'Serverliste');
define('_slist_serverip', 'ServerIP');
define('_slist_slots', 'Slots');
define('_slist_add', 'Server hinzuf&uuml;gen');
define('_slist_serverport', 'Server-Port');
define('_server_password', 'Serverpasswort');
define('_error_server_saved', 'Dein Server wurde erfolgreich eingetragen!<br /> Ein Admin wird nun deine Angaben &uuml;berpr&uuml;fen.');
define('_error_empty_slots', 'Du musst die Anzahl euer Slots angeben!');
define('_error_empty_ip', 'Du musst eure Server-IP angeben!');
define('_error_empty_port', 'Du musst euren Server-Port angeben!');
define('_slist_added_msg', 'Es ist ein neuer Eintrag in der Serverliste vorhanden!');
define('_slist_title', 'Serverliste');
define('_gt_search', 'Suchen nach');
define('_gt_server', 'Server');
define('_gt_maps', 'Maps');
define('_gt_map', 'Map');
define('_gt_player', 'Spieler');
define('_gt_addip', 'Klicke auf die ServerIP um diese im HLSW aufzurufen');
define('_gt_addname', 'Klicke auf den Servernamen um diesen im HLSW aufzurufen');
define('_gt_not_found', '<tr><td class="contentMainFirst" colspan="8" align="center">- kein Ergebnis -</td></tr>');
define('_gt_psearchhead', 'Spielersuche');
define('_gt_sip', 'ServerIP');
define('_gt_found','gefundene Eintr&auml;ge:');
define('_gt_msearchhead', 'Mapsuche');
define('_gt_server_ip', 'Servername');
define('_gt_ssearchhead', 'Serversuche');
define('_gallery_head', 'Galerien');
define('_subgallery_head', 'Galerie');
define('_gallery_images', 'Bilder');
define('_gal_back', 'zur&uuml;ck');
define('_gallery_admin_head', 'Galerie hinzuf&uuml;gen');
define('_gallery_gallery', 'Galeriebezeichnung');
define('_gallery_count', 'Anzahl der Bilder');
define('_gallery_count_new', 'Anzahl neuer Bilder');
define('_gallery_added', 'Die Galerie wurde erfolgreich erstellt!');
define('_error_gallery', 'Du musst eine Galeriebezeichnung angeben!');
define('_gallery_image', 'Bild');
define('_gallery_deleted', 'Die Galerie wurde erfolgreich gel&ouml;scht!');
define('_gallery_edited', 'Die Galerie wurde erfolgreich editiert!');
define('_gallery_admin_edit', 'Galerie editieren');
define('_gallery_pic_deleted', 'Das Bild wurde erfolgreich gel&ouml;scht!');
define('_gallery_new', 'Die Bilder wurden erfolgreich zur Galerie hinzugef&uuml;gt!');
define('_button_value_newgal', 'Weitere Bilder hinzuf&uuml;gen');
define('_contact_pflichtfeld', '<span class="fontWichtig">*</span> = Pflichtfelder');
define('_contact_nachricht', 'Nachricht');
define('_contact_sended', 'Deine Nachricht wurde erfolgreich an den Seitenadmin weitergeleitet!');
define('_contact_title', 'Kontaktformular');
define('_contact_text', '
Jemand hat das Kontaktformular ausgef&uuml;llt!<br /><br />
<span class="fontBold">Nick:</span> [nick]<br />
<span class="fontBold">Email:</span> [email]<br />
<span class="fontBold">ICQ-Nr.:</span> [icq]<br /><br />
<span class="fontUnder"><span class="fontBold">Nachricht:</span></span><br />[text]');
define('_contact_joinus', 'JoinUs-Text');
define('_contact_joinus_why', 'Beschreibe kurz, warum du bei uns aufgenommen werden willst.');
define('_contact_title_joinus', 'JoinUs-Kontaktformular');
define('_contact_text_joinus', '
Jemand hat das Joinus-Kontaktformular ausgef&uuml;llt!<br /><br />
<span class="fontBold">Nick:</span> [nick]<br />
<span class="fontBold">Alter:</span> [age]<br />
<span class="fontBold">Email:</span> [email]<br />
<span class="fontBold">ICQ-Nr.:</span> [icq]<br /><br />
<span class="fontUnder"><span class="fontBold">Nachricht:</span></span><br />[text]');
define('_contact_joinus_sended', 'Dein Joinus-Anfrage wurde erfolgreich an den zust&auml;ndigen Seitenadmin weitergeleitet!');
define('_contact_fightus', 'Kommentar');
define('_contact_title_fightus', 'FightUs-Kontaktformular');
define('_contact_fightus_sended', 'Dein Clanwar-Anfrage wurde erfolgreich einen zust&auml;ndigen Admin weitergeleitet!');
define('_contact_fightus_partner', 'Ansprechpartner');
define('_contact_fightus_clandata', 'Clandaten');
define('_contact_fightus_clanname', 'Clanname');
define('_fightus_maps', 'Eure Map');
define('_empty_fightus_map', 'Du musst die Map angeben, die ihr spielen wollt!');
define('_empty_fightus_game', 'Du musst das Spiel angeben, in dem der War stattfinden soll!');
## Statistiken ##
define('_site_stats', 'Statistiken');
define('_stats', 'Statistiken');
define('_stats_nkats', 'Kategorien');
define('_stats_news', 'geschriebene News');
define('_stats_comments', 'geschriebene Kommentare');
define('_stats_cpern', '&oslash; Kommentare pro News');
define('_stats_npert', '&oslash; News pro Tag');
define('_stats_gb_all', 'Gesamteintr&auml;ge');
define('_stats_gb_poster', 'Eintr&auml;ge G&auml;ste/reg. User');
define('_stats_gb_first', 'Erster Eintrag');
define('_stats_gb_last', 'Letzter Eintrag');
define('_from', 'von');
define('_stats_forum_ppert', '&oslash; Beitr&auml;ge pro Thread');
define('_stats_forum_pperd', '&oslash; Beitr&auml;ge pro Tag');
define('_stats_forum_top', 'Top Poster');
define('_stats_users_regged', 'reg. User');
define('_stats_users_regged_member', '&nbsp;&nbsp;- davon Member');
define('_stats_users_logins', 'Gesamt Logins');
define('_stats_users_msg', 'versendete Nachrichten');
define('_stats_users_buddys', 'Buddies');
define('_stats_users_votes', 'teilgenommene Votes');
define('_stats_users_aktmsg', '&nbsp;&nbsp;- davon aktuell im Umlauf');
define('_stats_cw_played', 'gespielte Clanwars');
define('_stats_cw_won', '&nbsp;&nbsp;- davon gewonnen');
define('_stats_cw_draw', '&nbsp;&nbsp;- davon unentschieden');
define('_stats_cw_lost', '&nbsp;&nbsp;- davon verloren');
define('_stats_cw_points', 'Gesamtpunktzahl');
define('_stats_place', '&nbsp;&nbsp;- davon Platz');
define('_stats_place_misc', '&nbsp;&nbsp;- davon sonstige Pl&auml;tze');
define('_stats_awards', 'Gewonnene Awards');
define('_stats_mysql', 'MySQL-Datenbank');
define('_stats_mysql_size', 'Datenbankgr&ouml;&szlig;e');
define('_stats_mysql_entrys', 'Tabellen');
define('_stats_mysql_rows', 'Gesamteintr&auml;ge');
define('_site_stats_files', 'Dateien');
define('_stats_hosted', 'selbst gehostete Dateien');
define('_stats_dl_size', 'Gesamtgr&ouml;&szlig;e');
define('_stats_dl_traffic', 'Insgesamt verursachter Traffic');
define('_stats_dl_hits', 'Insgesamt herruntergeladen');
## User ##
define('_profil_head', '<span class="fontBold">Userprofil von [nick]</span> [[profilhits] mal angesehen]');
define('_user_noposi', '<option value="lazy" class="dropdownKat">keine Position</option>');
define('_login_head', 'Login');
define('_new_pwd', 'neues Passwort');
define('_register_head', 'Registrierung');
define('_register_confirm', 'Sicherheitsscode');
define('_register_confirm_add', 'Code eingeben');
define('_lostpwd_head', 'Passwort zuschicken');
define('_profil_edit_head', 'Profil von [nick] editieren');
define('_profil_clan', 'Clan');
define('_profil_pic', 'Picture');
define('_profil_contact', 'Kontakt');
define('_profil_hardware', 'Hardware');
define('_profil_about', '&Uuml;ber mich');
define('_profil_real', 'Name');
define('_profil_city', 'Wohnort');
define('_profil_bday', 'Geburtstag');
define('_profil_age', 'Alter');
define('_profil_hobbys', 'Hobbys');
define('_profil_motto', 'Motto');
define('_profil_hp', 'Homepage');
define('_profil_sex', 'Geschlecht');
define('_profil_board', 'Mainboard');
define('_profil_cpu', 'CPU');
define('_profil_ram', 'RAM');
define('_profil_graka', 'Grafikkarte');
define('_profil_monitor', 'Monitor');
define('_profil_maus', 'Maus');
define('_profil_mauspad', 'Mauspad');
define('_profil_hdd', 'HDD');
define('_profil_headset', 'Headset');
define('_profil_os', 'System');
define('_profil_inet', 'Internet');
define('_profil_job', 'Job');
define('_profil_position', 'Position');
define('_profil_exclans', 'Ex-Clans');
define('_profil_status', 'Status');
define('_aktiv', '<span class=fontGreen>aktiv</span>');
define('_inaktiv', '<span class=fontRed>inaktiv</span>');
define('_male', 'm&auml;nnlich');
define('_female', 'weiblich');
define('_profil_ppic', 'Profilfoto');
define('_profil_gamestuff', 'Gamestuff');
define('_profil_userstats', 'Userstats');
define('_profil_navi_profil', '<a href="?action=user&amp;id=[id]">Profil</a>');
define('_profil_navi_gb', '<a href="?action=user&amp;id=[id]&amp;show=gb">G&auml;stebuch</a>');
define('_profil_navi_gallery', '<a href="?action=user&amp;id=[id]&amp;show=gallery">Galerie</a>');
define('_profil_profilhits', 'Profilhits');
define('_profil_forenposts', 'Forenposts');
define('_profil_votes', 'teilgenommene Votes');
define('_profil_msgs', 'versendete Nachrichten');
define('_profil_logins', 'Logins');
define('_profil_registered', 'Registrierungsdatum');
define('_profil_last_visit', 'Letzter Pagebesuch');
define('_profil_pagehits', 'Pagehits');
define('_profil_cws', 'teilgenommene CW`s');
define('_profil_edit_pic', '<a href="../upload/?action=userpic">hochladen</a>');
define('_profil_delete_pic', '<a href="../upload/?action=userpic&amp;do=deletepic">l&ouml;schen</a>');
define('_profil_edit_ava', '<a href="../upload/?action=avatar">hochladen</a>');
define('_profil_delete_ava', '<a href="../upload/?action=avatar&amp;do=delete">l&ouml;schen</a>');
define('_pedit_aktiv', '<option value="1" selected="selected">aktiv</option><option value="0">inaktiv</option>');
define('_pedit_inaktiv', '<option value="1">aktiv</option><option value="0" selected="selected">inaktiv</option>');
define('_pedit_male', '<option value="0">keine Angabe</option><option value="1" selected="selected">m&auml;nnlich</option><option value="2">weiblich</option>');
define('_pedit_female', '<option value="0">keine Angabe</option><option value="1">m&auml;nnlich</option><option value="2" selected="selected">weiblich</option>');
define('_pedit_sex_ka', '<option value="0">keine Angabe</option><option value="1">m&auml;nnlich</option><option value="2">weiblich</option>');
define('_info_edit_profile_done', 'Du hast dein Profil erfolgreich editiert!');
define('_delete_pic_successful', 'Dein Bild wurde erfolgreich gel&ouml;scht!');
define('_no_pic_available', 'Es wurde kein Bild von dir gefunden!');
define('_profil_edit_profil_link', '<a href="?action=editprofile">Profil editieren</a>');
define('_profil_edit_gallery_link', '<a href="?action=editprofile&amp;show=gallery">Usergalerie editieren</a>');
define('_profil_avatar', 'Avatar');
define('_lostpwd_failed', 'Loginname und E-Mailadresse stimmen nicht &uuml;berein!');
define('_lostpwd_valid', 'Es wurde soeben ein neues Passwort generiert und an deine Emailadresse gesendet!');
define('_error_user_already_in', 'Du bist bereits eingeloggt!');
define('_user_is_banned', 'Dein Account wurde vom Admin dieser Seite gesperrt und ist ab jetzt nicht mehr nutzbar!<br />Informiere dich bei einem authorisiertem Mitglied &uuml;ber den genauen Sachverhalt.');
define('_msghead', 'Nachrichtencenter von [nick]');
define('_posteingang', 'Posteingang');
define('_postausgang', 'Postausgang');
define('_msg_title', 'Nachricht');
define('_msg_absender', 'Absender');
define('_msg_empfaenger', 'Empf&auml;nger');
define('_msg_answer_msg', 'Nachricht von [nick]');
define('_msg_sended_msg', 'Nachricht an [nick]');
define('_msg_answer_done', 'Die Nachricht wurde erfolgreich versendet!');
define('_msg_titel', 'Neue Nachricht schreiben');
define('_msg_titel_answer', 'Antworten');
define('_to', 'An');
define('_or', 'oder');
define('_msg_to_just_1', 'Du kannst nur einen Empf&auml;nger angeben!');
define('_msg_not_to_me', 'Du kannst keine Nachricht an dich selber schreiben!');
define('_legende_readed', 'Nachricht wurde vom Empf&auml;nger gelesen?');
define('_legende_msg', 'Neue Nachricht');
define('_msg_from_nick', 'Nachricht von [nick]');
define('_msg_global_reg', 'alle registrierten User');
define('_msg_global_squad', 'einzelne Teams:');
define('_msg_bot', '<span class="fontBold">MsgBot</span>');
define('_msg_global_who', 'Empf&auml;nger');
define('_msg_reg_answer_done', 'Die Nachricht wurde erfolgreich an alle registrierten User versendet!');
define('_msg_member_answer_done', 'Die Nachricht wurde erfolgreich an alle Mitglieder versendet!');
define('_msg_squad_answer_done', 'Die Nachricht wurde erfolgreich an das ausgew&auml;hlte Team versendet!');
define('_buddyhead', 'Buddyverwaltung');
define('_addbuddys', 'Buddies hinzuf&uuml;gen');
define('_buddynick', 'Buddy');
define('_add_buddy_successful', 'Der User wurde erfolgreich als Buddy geadded!');
define('_buddys_legende_addedtoo', 'Der User hat dich auch geadded');
define('_buddys_legende_dontaddedtoo', 'Der User hat dich nicht geadded');
define('_buddys_delete_successful', 'Der User wurde erfolgreich als Buddy gel&ouml;scht!');
define('_buddy_added_msg', 'Der User <span class="fontBold">[user]</span> hat dich soeben als Buddy geadded!');
define('_buddy_title', 'Buddies');
define('_buddy_del_msg', 'Der User <span class="fontBold">[user]</span> hat dich soeben als Buddy gel&ouml;scht!');
define('_ulist_lastreg', 'neuste User');
define('_ulist_online', 'Onlinestatus');
define('_ulist_age', 'Alter');
define('_ulist_sex', 'Geschlecht');
define('_ulist_country', 'Nationalit&auml;t');
define('_ulist_sort', 'Sortieren nach:');
define('_usergb_eintragen', '<a href="?action=usergb&amp;id=[id]">Eintragen</a>');
define('_usergb_entry_successful', 'Dein Eintrag ins Profilg&auml;stebuch war erfolgreich!');
define('_gallery_pic', 'Picture');
define('_gallery_beschr', 'Beschreibung');
define('_gallery_edit_new', '<a href="../upload/?action=usergallery">Neues Bild hinzuf&uuml;gen</a>');
define('_info_edit_gallery_done', 'Du hast den Datensatz erfolgreich gel&ouml;scht!');
define('_admin_user_edithead', 'Admin: User editieren');
define('_admin_user_clanhead', 'Authorisierungen');
define('_admin_user_squadhead', 'Team');
define('_admin_user_personalhead', 'Pers&ouml;nliches');
define('_admin_user_level', 'Level');
define('_admin_user_clankasse', 'Admin: Clankasse');
define('_admin_user_serverliste', 'Admin: Serverliste');
define('_admin_user_editserver', 'Admin: Server');
define('_admin_user_edittactics', 'Admin: Taktiken');
define('_admin_user_edituser', 'User editieren');
define('_admin_user_editsquads', 'Admin: Teams');
define('_admin_user_editkalender', 'Admin: Kalender');
define('_member_admin_newsletter', 'Admin: Newsletter');
define('_member_admin_downloads', 'Admin: Downloads');
define('_member_admin_links', 'Admin: Links');
define('_member_admin_gb', 'Admin: G&auml;stebuch');
define('_member_admin_forum', 'Admin: Forum');
define('_member_admin_intforum', 'Internes Forum sehen');
define('_member_admin_news', 'Admin: News');
define('_member_admin_clanwars', 'Admin: Clanwars');
define('_error_edit_myself', 'Du kannst dich nicht selber editieren!');
define('_error_edit_admin', 'Du darfst keine Admins editieren!');
define('_admin_level_banned', 'Account sperren');
define('_admin_user_identitat', 'Identit&auml;t');
define('_admin_user_get_identitat', '<a href="?action=admin&amp;do=identy&amp;id=[id]">annehmen</a>');
define('_identy_admin', 'Du kannst nicht die Identit&auml;t von einem Admin annehmen!');
define('_admin_squad_del', '<option value="delsq">- User aus dem Team l&ouml;schen -</option>');
define('_admin_squad_nosquad', '<option class="dropdownKat" value="lazy">- User ist in keinem Team -</option>');
define('_admin_user_edited', 'Der User wurde erfolgreich editiert!');
define('_userlobby', 'Userlobby');
define('_lobby_new', 'Neuerungen seit dem letzten Pagebesuch');
define('_lobby_new_erased', 'Die tempor&auml;ren Neuerungen wurden erfolgreich gel&ouml;scht!');
define('_last_forum', 'Letzten 10 Forumthreads');
define('_lobby_forum', 'Foreneintr&auml;ge');
define('_new_post_1', 'neuer Post');
define('_new_post_2', 'neue Posts');
define('_new_thread', 'im Thread ');
define('_no_new_thread', 'Neuer Thread:');
define('_lobby_gb', 'G&auml;stebucheintr&auml;ge');
define('_new_gb', '<br /><span class="fontBoldUnder">G&auml;stebuch:</span><br />');
define('_new_eintrag_1', 'neuer Eintrag');
define('_new_eintrag_2', 'neue Eintr&auml;ge');
define('_lobby_user', 'Registrierte User');
define('_new_users_1', 'neu registrierter User');
define('_new_users_2', 'neu registrierte User');
define('_lobby_membergb', 'Mein Profilg&auml;stebuch');
define('_lobby_news', 'News');
define('_lobby_new_news', 'neue News');
define('_lobby_newsc', 'Newskommentare');
define('_lobby_new_newsc_1', 'neuer Newskommentar');
define('_lobby_new_newsc_2', 'neue Newskommentare');
define('_new_msg_1', 'neue Nachricht');
define('_new_msg_2', 'neue Nachrichten');
define('_lobby_votes', 'Umfragen');
define('_new_vote_1', 'neue Umfrage');
define('_new_vote_2', 'neue Umfragen');
define('_lobby_cw', 'Clanwars');
define('_user_new_cw', '<tr><td style="width:22px;text-align:center"><img src="../inc/images/gameicons/[icon]" class="icon" alt="" /></td><td style="vertical-align:middle"><a href="../clanwars/?action=details&amp;id=[id]">Clanwar am <span class="fontWichtig">[datum]</span> gegen <span class="fontWichtig">[gegner]</span></a></td></tr>');
define('_user_delete_verify', '
<tr>
  <td class="contentHead"><span class="fontBold">User l&ouml;schen</span></td>
</tr>
<tr>
  <td class="contentMainFirst" align="center">
    Bist du dir sicher das du den User [user] l&ouml;schen willst?<br />
    <span class="fontUnder">Alle</span> Aktivit&auml;ten dieses Users auf dieser Seite werden damit gel&ouml;scht!<br /><br /> 
    <a href="?action=admin&amp;do=delete&verify=yes&amp;id=[id]">Ja, l&ouml;schen!</a>
  </td>
</tr>');
define('_user_deleted', 'Der User wurde erfolgreich gel&ouml;scht!');
define('_admin_user_shoutbox', 'Admin: Shoutbox');
define('_admin_user_awards', 'Admin: Awards');
define('_userlobby_kal_today', 'N&auml;chster Event ist <a href="../kalender/?action=show&time=[time]"><span class="fontWichtig">heute</span></a>');
define('_userlobby_kal_not_today', 'N&auml;chstes Event ist am <a href="../kalender/?action=show&time=[time]"><span class="fontUnder">[date]</span></a>');
define('_profil_country', 'Land');
define('_lobby_awards', 'Awards');
define('_new_awards_1', 'neuer Award');
define('_new_awards_2', 'neue Awards');
define('_lobby_rankings', 'Rankings');
define('_new_rankings_1', 'neue Ver&auml;nderung');
define('_new_rankings_2', 'neue Ver&auml;nderungen');
define('_profil_favos', 'Favoriten');
define('_profil_drink', 'Drink');
define('_profil_essen', 'Essen');
define('_profil_film', 'Film');
define('_profil_musik', 'Musik');
define('_profil_song', 'Song');
define('_profil_buch', 'Buch');
define('_profil_autor', 'Autor');
define('_profil_person', 'Person');
define('_profil_sport', 'Sport');
define('_profil_sportler', 'Sportler');
define('_profil_auto', 'Auto');
define('_profil_favospiel', 'Spiel');
define('_profil_game', 'Spiel');
define('_profil_favoclan', 'Clan');
define('_profil_spieler', 'Spieler');
define('_profil_map', 'Map');
define('_profil_waffe', 'Waffe');
define('_profil_rasse', 'Rasse');
define('_profil_sonst', 'Sonstiges');
define('_profil_url1', 'Page #1');
define('_profil_url2', 'Page #2');
define('_profil_url3', 'Page #3');
define('_profil_ich', 'Beschreibung');
define('_lobby_gallery', 'Galerien');
define('_new_gal_1', 'neue Galerie');
define('_new_gal_2', 'neue Galerien');
## Upload ##
define('_upload_wrong_size', 'Die ausgew&auml;hlte Datei ist gr&ouml;&szlig;er als zugelassen!');
define('_upload_no_data', 'Du musst eine Datei angeben!');
define('_info_upload_success', 'Die Datei wurde erfolgreich hochgeladen!');
define('_upload_info', 'Info');
define('_upload_file', 'Datei');
define('_upload_beschreibung', 'Beschreibung');
define('_upload_button', 'Hochladen');
define('_upload_over_limit', 'Du darfst nicht mehr Bilder hochladen! L&ouml;sche alte Bilder um neue hochladen zu d&uuml;rfen!');
define('_upload_file_exists', 'Die angegebene Datei existiert bereits! Benenne die Datei um oder w&auml;hle eine andere Datei aus!');
define('_upload_head', 'Userbild uploaden');
define('_upload_userpic_info', 'Nur jpg, gif oder png Dateien mit einer maximalen Gr&ouml;&szlig;e von [userpicsize]KB!<br />Die empfohlene Gr&ouml;&szlig;e ist 170px * 210px ');
define('_upload_head_usergallery', 'Usergalerie bearbeiten');
define('_edit_gallery_done', 'Die Usergalerie wurde erfolgreich bearbeitet!');
define('_upload_usergallery_info', 'Nur jpg, gif oder png Dateien mit einer maximalen Gr&ouml;&szlig;e von [userpicsize]KB!');
define('_upload_icons_head', 'GameIcons');
define('_upload_taktiken_head', 'Taktikscreens');
define('_upload_ava_head', 'Useravatar');
define('_upload_userava_info', 'Nur jpg, gif oder png Dateien mit einer maximalen Gr&ouml;&szlig;e von [userpicsize]KB!<br />Die empfohlene Gr&ouml;&szlig;e ist 100px * 100px ');
define('_upload_newskats_head', 'Kategoriebilder');
## Unzugeordnet ##
define('_forum_no_last_post', 'Der letzte Post kann leider nicht angezeigt werden!');
define('_config_maxwidth', 'Bilder autom. verkleinern');
define('_config_maxwidth_info', 'Hier kannst du einstellen, ab wann ein zu breites Bild verkleinert werden soll!');
define('_forum_top_posts', 'Top 5 Poster');
define('_error_no_teamspeak', 'Der Teamspeakserver ist zur Zeit nicht erreichbar!');
define('_user_cant_delete_admin', 'Du darfst keine Member oder Admins l&ouml;schen!');
define('_no_entrys_yet', '
<tr>
  <td class="contentMainFirst" colspan="[colspan]" align="center">Bisher noch kein Eintrag vorhanden!</td>
</tr>');
define('_nav_no_nextwars', 'Es stehen keine Wars an!');
define('_nav_no_lastwars', 'Bisher noch keine Wars!');
define('_nav_no_ftopics', 'Noch kein Eintrag!');
define('_gallery_folder_exists', 'Der angegebene Ordner existiert bereits!');
define('_server_isnt_live', 'Der Server ist auf keinen Live-Status eingestellt!');
define('_target', 'Neues Fenster');
define('_rankings_edit_head', 'Ranking editieren');
define('_fopen', 'Der Webhoster dieser Seite erlaubt die ben&ouml;tigte Funktion fopen() nicht!');
define('_and', 'und');
define('_lobby_artikelc', 'Artikelkommentare');
define('_lobby_new_art_1', 'neuer Artikel');
define('_lobby_new_art_2', 'neue Artikel');
define('_user_new_art', '&nbsp;&nbsp;<a href="../artikel/"><span class="fontWichtig">[cnt]</span> [eintrag]</span><br />');
define('_lobby_new_artc_1', 'neuer Artikelkommentar');
define('_lobby_new_artc_2', 'neue Artikelkommentare');
define('_page', '<span class="fontBold">[num]</span>  ');
define('_profil_nletter', 'Newsletter empfangen?');
define('_forum_admin_addglobal', '<span class="fontWichtig">Globaler</span> Eintrag? (In allen Foren und Subforen)');
define('_forum_admin_global', '<span class="fontWichtig">Globaler</span> Eintrag?');
define('_forum_global', '<span class="fontWichtig">Global:</span>');
define('_admin_config_badword', 'Badword-Filter');
define('_admin_config_badword_info', 'Hier kannst du W&ouml;rter angeben, die bei Eingabe mit **** versehen werden. Die W&ouml;rter m&uuml;ssen mit Komma getrennt werden!');
define('_iplog_info', '<span class="fontBold">Hinweis:</span> Aus Sicherheitsgr&uuml;nden wird deine IP geloggt!');
define('_logged', 'IP gespeichert');
define('_info_ip', 'IP-Adresse');
define('_info_browser', 'Browser');
define('_info_res', 'Aufl&ouml;sung');
define('_unknown_browser', 'unbekannter Browser');
define('_unknown_system', 'unbekanntes System');
define('_info_sys', 'System');
define('_nav_montag', 'Mo');
define('_nav_dienstag', 'Di');
define('_nav_mittwoch', 'Mi');
define('_nav_donnerstag', 'Do');
define('_nav_freitag', 'Fr');
define('_nav_samstag', 'Sa');
define('_nav_sonntag', 'So');
define('_age', 'Alter');
define('_years', 'Jahre');
define('_error_empty_age', 'Du musst dein aktuelles Alter angeben!');
define('_member_admin_intforums', 'interne Forumauthorisierungen');
define('_access', 'Authorisierung');
define('_error_no_access', 'Du hast nicht die n&ouml;tigen Rechte um diesen Bereich betreten zu d&uuml;rfen!');
define('_artikel_show_link', '<a href="../artikel/?action=show&amp;id=[id]">[titel]</a>');
define('_ulist_bday', 'Geburtstag');
define('_ulist_last_login', 'Letzter Login');
## Taktiken ##
define('_taktik_head', 'Intern: Taktiken');
define('_taktik_standard_t', '<a href="?action=standard&amp;what=t&amp;id=[id]">Standard</a>');
define('_taktik_standard_ct', '<a href="?action=standard&amp;what=ct&amp;id=[id]">Standard</a>');
define('_taktik_spar_t', '<a href="?action=spar&amp;what=t&amp;id=[id]">Spar</a>');
define('_taktik_spar_ct', '<a href="?action=spar&amp;what=ct&amp;id=[id]">Spar</a>');
define('_taktik_upload', 'Tatkikscreen uploaden');
define('_taktik_t', 'Terrorists');
define('_taktik_ct', 'Anti-Terrorists');
define('_taktik_posted', 'posted by <span class="fontBold">[autor]</span> - [datum]');
define('_taktik_headline', '<span class="fontBold">Map:</span> [map] - <span class="fontBold">Taktik:</span> [what]');
define('_taktik_tstandard_t', 'Terrorists -> Standard');
define('_taktik_tstandard_ct', 'Anti-Terrorists -> Standard');
define('_taktik_tspar_t', 'Terrorists -> Spar');
define('_taktik_tspar_ct', 'Anti-Terrorists -> Spar');
define('_error_taktik_empty_map', 'Du musst eine Map angeben!');
define('_taktik_new', 'Neue Taktik hinzuf&uuml;gen');
define('_taktik_added', 'Die Taktik wurde erfolgreich eingetragen!');
define('_taktik_deleted', 'Die Taktik wurde erfolgreich gel&ouml;scht!');
define('_taktik_edit_head', 'Taktik editieren');
define('_taktik_new_head', 'Neue Taktik');
define('_error_taktik_edited', 'Die Taktik wurde erfolgreich editiert!');
## Impressum ##
define('_impressum_head', 'Impressum');
define('_impressum_autor', 'Autor der Seite');
define('_impressum_domain', 'Domain:');
define('_impressum_disclaimer', 'Haftungsausschluss');
define('_impressum_txt', '<blockquote>
<h2><span class="fontBold">1. Inhalt des Onlineangebotes</span></h2>
<br />
Der Autor &uuml;bernimmt keinerlei Gew&auml;hr f&uuml;r die Aktualit&auml;t, Korrektheit, Vollst&auml;ndigkeit oder Qualit&auml;t der bereitgestellten Informationen. Haftungsanspr&uuml;che
gegen den Autor, welche sich auf Sch&auml;den materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollst&auml;ndiger Informationen verursacht wurden, sind grunds&auml;tzlich ausgeschlossen, sofern seitens
des Autors kein nachweislich vors&auml;tzliches oder grob fahrl&auml;ssiges Verschulden vorliegt.
<br />
<br />Alle Angebote sind freibleibend und unverbindlich. Der Autor beh&auml;lt es sich ausdr&uuml;cklich vor,
Teile der Seiten oder das gesamte Angebot ohne gesonderte Ank&uuml;ndigung zu ver&auml;ndern, zu erg&auml;nzen, zu l&ouml;schen oder die Ver&ouml;ffentlichung zeitweise oder endg&uuml;ltig einzustellen.
<br />
<br /><h2><span class="fontBold">2. Verweise und Links</span></h2>
<br />
Bei direkten oder indirekten Verweisen auf fremde Webseiten (\'Hyperlinks\'), die au&szlig;erhalb des Verantwortungsbereiches
des Autors liegen, w&uuml;rde eine Haftungsverpflichtung ausschlie&szlig;lich in dem Fall
in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch m&ouml;glich und zumutbar w&auml;re, die Nutzung im Falle rechtswidriger Inhalte zu verhindern.
<br /><br />
Der Autor erkl&auml;rt hiermit ausdr&uuml;cklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zuk&uuml;nftige
Gestaltung, die Inhalte oder die Urheberschaft der verlinkten/verkn&uuml;pften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdr&uuml;cklich von allen Inhalten aller verlinkten
/verkn&uuml;pften Seiten, die nach der Linksetzung ver&auml;ndert wurden. Diese Feststellung gilt f&uuml;r alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie f&uuml;r Fremdeintr&auml;ge in vom Autor eingerichteten G&auml;steb&uuml;chern, Diskussionsforen, Linkverzeichnissen, Mailinglisten und in allen anderen Formen von Datenbanken, auf deren Inhalt externe Schreibzugriffe m&ouml;glich sind. F&uuml;r illegale, fehlerhafte oder unvollst&auml;ndige Inhalte und insbesondere f&uuml;r Sch&auml;den, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der &uuml;ber Links auf die jeweilige Ver&ouml;ffentlichung lediglich verweist.
<br />
<br /><h2><span class="fontBold">3. Urheber- und Kennzeichenrecht</span></h2>
<br />
Der Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Bilder, Grafiken, Tondokumente, Videosequenzen und Texte
zu beachten, von ihm selbst erstellte Bilder, Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zur&uuml;ckzugreifen. 
<br />
Alle innerhalb des Internetangebotes genannten und ggf. durch Dritte gesch&uuml;tzten Marken- und Warenzeichen unterliegen uneingeschr&auml;nkt den Bestimmungen des jeweils g&uuml;ltigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigent&uuml;mer. Allein aufgrund der blo&szlig;en Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter gesch&uuml;tzt sind!
<br />
Das Copyright f&uuml;r ver&ouml;ffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten. 
Eine Vervielf&auml;ltigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdr&uuml;ckliche Zustimmung des Autors nicht gestattet.
<br />
<br /><h2><span class="fontBold">4. Datenschutz</span></h2>
<br />
Sofern innerhalb des Internetangebotes die M&ouml;glichkeit zur Eingabe pers&ouml;nlicher oder gesch&auml;ftlicher Daten (Emailadressen, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdr&uuml;cklich freiwilliger Basis. Die Inanspruchnahme und Bezahlung aller angebotenen Dienste ist - soweit technisch m&ouml;glich und zumutbar - auch ohne Angabe solcher Daten bzw. unter Angabe anonymisierter Daten oder eines Pseudonyms gestattet.
Die Nutzung der im Rahmen des Impressums oder vergleichbarer Angaben ver&ouml;ffentlichten Kontaktdaten wie Postanschriften, Telefon- und Faxnummern sowie Emailadressen durch Dritte zur &Uuml;bersendung von nicht ausdr&uuml;cklich angeforderten Informationen ist nicht gestattet. Rechtliche Schritte gegen die Versender von sogenannten Spam-Mails bei Verst&ouml;ssen gegen dieses Verbot sind ausdr&uuml;cklich vorbehalten.
<br />
<br /><h2><span class="fontBold">5. Rechtswirksamkeit dieses Haftungsausschlusses</span></h2>
<br />
Dieser Haftungsausschluss ist als Teil des Internetangebotes zu betrachten, von dem aus auf diese Seite verwiesen wurde. Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollst&auml;ndig entsprechen sollten, bleiben die &uuml;brigen Teile des Dokumentes in ihrem Inhalt und ihrer G&uuml;ltigkeit davon unber&uuml;hrt.
</blockquote>');
## Admin ##
define('_config_head', 'Adminbereich');
define('_config_empty_katname', 'Du musst eine Kategoriebezeichnung angeben!');
define('_config_katname', 'Kategoriebezeichnung');
define('_config_set', 'Die Einstellungen wurden erfolgreich &uuml;bernommen!');
define('_config_forum_status', 'Status');
define('_config_forum_head', 'Forenkategorien');
define('_config_forum_mainkat', 'Hauptkategorie');
define('_config_forum_subkathead', 'Unterkategorien von <span class="fontUnder">[kat]</span>');
define('_config_forum_subkat', 'Unterkategorie');
define('_config_forum_subkats', '<span class="fontBold">[topic]</span><br /><span class="fontItalic">[subtopic]</span>');
define('_config_forum_kat_head', 'neue Kategorie hinzuf&uuml;gen');
define('_config_forum_public', '&ouml;ffentlich');
define('_config_forum_intern', 'Intern');
define('_config_forum_kat_added', 'Die Kategorie wurde erfolgreich hinzugef&uuml;gt!');
define('_config_forum_kat_deleted', 'Die Kategorie wurde erfolgreich gel&ouml;scht!');
define('_config_forum_kat_head_edit', 'Kategorie editieren');
define('_config_forum_kat_edited', 'Die Kategorie wurde erfolgreich editiert!');
define('_config_forum_add_skat', 'Neue Unterkategorie hinzuf&uuml;gen');
define('_config_forum_skatname', 'Unterkategoriebezeichnung');
define('_config_forum_empty_skat', 'Du musst eine Unterkategoriebezeichnung angeben!');
define('_config_forum_skat_added', 'Die Unterkategorie wurde erfolgreich hinzugef&uuml;gt!');
define('_config_forum_stopic', 'Untertitel');
define('_config_forum_skat_edited', 'Die Unterkategorie wurde erfolreich editiert!');
define('_config_forum_edit_skat', 'Unterkategorie editieren');
define('_config_forum_skat_deleted', 'Die Unterkategorie wurde erfolgreich gel&ouml;scht!');
define('_config_newskats_kat', 'Kategorie');
define('_config_newskats_head', 'News-/Artikelkategorien');
define('_config_newskats_katbild', 'Katbild');
define('_config_newskats_add', '<a href="?admin=news&amp;do=add">Neue Kategorie hinzuf&uuml;gen</a>');
define('_config_newskat_deleted', 'Die Kategorie wurde erfolgreich gel&ouml;scht!');
define('_config_newskats_add_head', 'Neue Kategorie hinzuf&uuml;gen');
define('_config_newskats_added', 'Die Kategorie wurde erfolgreich hinzugef&uuml;gt!');
define('_config_newskats_edit_head', 'Kategorie editieren');
define('_config_newskats_edited', 'Die Kategorie wurde erfolgreich editiert!');
define('_config_impressum_head', 'Impressum');
define('_config_impressum_domains', 'Domains');
define('_config_impressum_autor', 'Autor der Seite');
define('_config_konto_head', 'Kontodaten');
define('_config_clankasse_head', 'Ein-/Auszahlungsbezeichnungen');
define('_backup_head', 'Datenbankbackup');
define('_backup_info_head', 'Anmerkung');
define('_backup_info', 'Der Backupvorgang kann je nach Gr&ouml;&szlig;e der Datenbank mehrere Minuten in Anspruch nehmen.');
define('_backup_link', 'neues Backup anlegen!');
define('_backup_successful', 'Das Datenbankbackup wurde erfolgreich angelegt!');
define('_backup_last_head', 'Letztes Backup');
define('_backup_last_not_exist', 'Du hast bisher noch kein MySQL-Datenbankbackup angelegt!');
define('_news_admin_head', 'Newsbereich');
define('_admin_news_add', '<a href="?admin=newsadmin&amp;do=add">News hinzuf&uuml;gen</a>');
define('_admin_news_head', 'News hinzuf&uuml;gen');
define('_news_admin_kat', 'Kategorie');
define('_news_admin_klapptitel', 'Klapptexttitel');
define('_news_admin_more', 'More');
define('_empty_news', 'Du musst eine News eintragen!');
define('_news_sended', 'Die News wurde erfolgreich eingetragen!');
define('_admin_news_edit_head', 'News editieren');
define('_news_edited', 'Die News wurde erfolgreich editiert!');
define('_news_deleted', 'Die News wurde erfolgreich gel&ouml;scht!');
define('_member_admin_header', 'Teambereich');
define('_member_admin_squad', 'Team');
define('_member_admin_game', 'Game');
define('_member_admin_icon', 'Icon');
define('_member_admin_add', '<a href="?admin=squads&amp;do=add">Team hinzuf&uuml;gen</a>');
define('_admin_squad_deleted', 'Das Team wurde erfolgreich gel&ouml;scht!');
define('_member_admin_add_header', 'Team hinzuf&uuml;gen');
define('_admin_squad_no_squad', 'Du musst einen Teamnamen angeben!');
define('_admin_squad_no_game', 'Du musst ein Game angeben, welches dieses Team spielt!');
define('_admin_squad_add_successful', 'Das Team wurde erfolgreich hinzugef&uuml;gt!');
define('_admin_squad_edit_successful', 'Das Team wurde erfolgreich editiert!');
define('_member_admin_edit_header', 'Team editieren');
define('_error_server_edit', 'Der Server wurde erfolgreich editiert!');
define('_error_empty_clanname', 'Du musst euren Clannamen angeben!');
define('_error_server_accept', 'Die ausgew&auml;hlten Server wurden erfolgreich freigeschaltet!');
define('_error_server_dont_accept', 'Die ausgew&auml;hlten Server wurden erfolgreich aus der Liste genommen!');
define('_slist_head_admin', 'Serverliste');
define('_slist_server_deleted', 'Der Server wurde erfolgreich gel&ouml;scht!');
define('_server_admin_head', 'Server');
define('_server_add_new', '<a href="?admin=server&amp;do=new">Neuen Server hinzuf&uuml;gen</a>');
define('_admin_server_edit', 'Server editieren');
define('_empty_ip', 'Du musst eine IP angeben!');
define('_server_admin_edited', 'Der Server wurde erfolgreich editiert!');
define('_server_admin_deleted', 'Der Server wurde erfolgreich gel&ouml;scht!');
define('_admin_server_new', 'Neuen Server hinzuf&uuml;gen');
define('_server_admin_added', 'Der Server wurde erfolgreich hinzugef&uuml;gt!');
define('_empty_game', 'Du musst ein Icon ausw&auml;hlen!');
define('_empty_servername', 'Du musst einen Servernamen angeben!');
define('_server_admin_ftp_head', 'Gameserver-FTP-Daten f&uuml;r die Bannliste (Steam only)');
define('_server_admin_ftp_url', 'Host');
define('_server_admin_ftp_login', 'Login-Name');
define('_config_server_ftp_updated', 'Die FTP-Daten wurden erfolgreich gesetzt!');
define('_server_admin_servermaps_head', 'Mapscreens (f&uuml;r den Live-Serverstatus)');
define('_config_server_mapname', 'Mapname');
define('_config_server_maps_head', 'Servermaps');
define('_config_server_map_deleted', 'Der Mapscreen wurde erfolgreich gel&ouml;scht!');
define('_admin_dlkat', 'Downloadkategorien');
define('_admin_download_kat', 'Bezeichnung');
define('_dl_add_new', '<a href="?admin=dl&amp;do=new">Neue Kategorie hinzuf&uuml;gen</a>');
define('_dl_new_head', 'Neue Downloadkategorie hinzuf&uuml;gen');
define('_dl_dlkat', 'Kategorie');
define('_dl_empty_kat', 'Du musst eine Kategoriebezeichnung angeben!');
define('_dl_admin_added', 'Die Downloadkategorie wurde erfolgreich hinzugef&uuml;gt!');
define('_dl_admin_deleted', 'Die Downloadkategorie wurde erfolgreich gel&ouml;scht!');
define('_dl_edit_head', 'Downloadkategorie editieren');
define('_dl_admin_edited', 'Die Downloadkategorie wurde erfolgreich editiert!');
define('_config_global_head', 'Konfiguration');
define('_config_c_limits', 'Seitenaufteilungen (LIMITS)');
define('_config_c_limits_what', 'Hier kannst du die Eintr&auml;ge einstellen, die pro Bereich maximal angezeigt werden');
define('_config_c_usergb', 'User-G&auml;stebuch');
define('_config_c_clankasse', 'Clankasse');
define('_config_c_gb', 'G&auml;stebuch');
define('_config_c_archivnews', 'News-Archiv');
define('_config_c_news', 'News');
define('_config_c_banned', 'Bannliste');
define('_config_c_adminnews', 'News-Admin');
define('_config_c_clanwars', 'Clanwars');
define('_config_c_shout', 'Shoutbox');
define('_config_c_userlist', 'Userliste');
define('_config_c_comments', 'Newskommentare');
define('_config_c_fthreads', 'Forumthreads');
define('_config_c_fposts', 'Forumposts');
define('_config_c_floods', 'Anti-Flooding');
define('_config_c_forum', 'Forum');
define('_config_c_length', 'L&auml;ngenangaben');
define('_config_c_length_what', 'Hier kannst du die L&auml;nge in Anzahl der Zeichen angeben, bei der nach &Uuml;berschreitung die Ausgabe gek&uuml;rzt wird.');
define('_config_c_newsadmin', 'Newsadmin: Titel');
define('_config_c_shouttext', 'Shoutbox: Text');
define('_config_c_newsarchiv', 'Newsarchiv: Titel');
define('_config_c_forumtopic', 'Forum: Topic');
define('_config_c_forumsubtopic', 'Forum: Subtopic');
define('_config_c_topdl', 'Men&uuml;: Top Downloads');
define('_config_c_ftopics', 'Men&uuml;: Last Forumtopics');
define('_config_c_lcws', 'Clanwars: Gegnername');
define('_config_c_lwars', 'Men&uuml;: Last Wars');
define('_config_c_nwars', 'Men&uuml;: Next Wars');
define('_config_c_main', 'Allgemeine Einstellungen');
define('_config_c_clanname', 'Clanname');
define('_config_c_pagetitel', 'Seitentitel');
define('_config_c_pfad', 'Script-Pfad');
define('_config_c_language', 'Default-Sprache');
define('_config_c_gametiger', 'Gametiger Suchoption');
define('_config_c_pfadlink', '<a href="../pfad.php" target="_blank">aktueller Pfad</a>');
define('_config_c_upicsize', 'Global: Uploadgr&ouml;sse Bilder');
define('_config_c_gallerypics', 'User: Usergalerie');
define('_config_c_upicsize_what', 'erlaubte Gr&ouml;&szlig;e der Bilder in KB (Newsbilder, Userprofilbilder usw.)');
define('_config_c_regcode', 'Reg: Sicherheitscode');
define('_config_c_regcode_what', 'Fragt bei der Registrierung einen Sicherheitscode ab');
define('_pos_add_new', '<a href="?admin=positions&amp;do=new">Neuen Rang hinzuf&uuml;gen</a>');
define('_pos_new_head', 'Neuen Rang hinzuf&uuml;gen');
define('_pos_edit_head', 'Rang editieren');
define('_pos_admin_edited', 'Der Rang wurde erfolgreich editiert!');
define('_pos_admin_deleted', 'Der Rang wurde erfolgreich gel&ouml;scht!');
define('_pos_admin_added', 'Der Rang wurde erfolgreich hinzugef&uuml;gt!');
define('_admin_clankasse_add', '<a href="?admin=konto&amp;do=new">Neue Bezeichnung hinzuf&uuml;gen</a>');
define('_clankasse_new_head', 'Neue Ein-/Auszahlungsbezeichnung hinzuf&uuml;gen');
define('_clankasse_edit_head', 'Ein-/Auszahlungsbezeichnung editieren');
define('_clankasse_empty_kat', 'Du musst eine Ein-/Auszahlungsbezeichnung angeben!');
define('_clankasse_kat_added', 'Die Ein-/Auszahlungsbezeichnung wurde erfolgreich hinzugef&uuml;gt!');
define('_clankasse_kat_edited', 'Die Ein-/Auszahlungsbezeichnung wurde erfolgreich editiert!');
define('_clankasse_kat_deleted', 'Die Ein-/Auszahlungsbezeichnung wurde erfolgreich gel&ouml;scht!');
define('_config_c_gallery', 'Galerie');
define('_config_info_gallery', 'Anzahl der Bilder die maximal in einer Reihe gezeigt werden');
define('_config_server_ts_updated', 'Die TeamspeakIP wurde erfogreich gesetzt!');
define('_ts_sport', 'Server Queryport');
define('_ts_width', 'Breite der Anzeige');
define('_server_admin_ftp_path', 'Pfad zur banned.cfg');
define('_config_c_awards', 'Awards');
define('_counter_start', 'Counter');
define('_counter_start_info', 'Hier kannst du eine Zahl eintragen, die zum Counter dazuaddiert wird.');
define('_config_head_balken', 'Balkenl&auml;ngen');
define('_config_balken_info', 'Hier kannst du den Faktor der L&auml;nge der Statistikbalken einstellen');
define('_config_balken_cw', 'Clanwar Statistik');
define('_config_balken_vote_head', 'Vote Statistik');
define('_config_balken_vote_menu_head', 'Men&uuml;: Vote Statistik');
define('_admin_nc', 'Newskommentare');
define('_admin_reg_head', 'Registrierungspflicht');
define('_config_shoutarchiv', 'Shoutbox: Archiv');
define('_config_zeichen', 'Shoutbox: Zeichen');
define('_config_zeichen_info', 'Hier kannst du einstellen, nach wieviel Zeichen das Eingabefeld der Shoutbox gesperrt wird.');
define('_wartungsmodus_info', 'wenn eingeschaltet kann keiner, ausser der Admin die Seite betreten.');
define('_navi_kat', 'Bereich');
define('_navi_name', 'Linkname');
define('_navi_url', 'Weiterleitung');
define('_navi_shown', 'Sichtbar');
define('_navi_type', 'Art');
define('_navi_wichtig', 'Markieren');
define('_navi_space', '<b>Leerzeile</b>');
define('_navi_head', 'Navigationsverwaltung');
define('_navi_add', '<a href="?admin=navi&amp;do=add">Neuen Link hinzuf&uuml;gen</a>');
define('_navi_add_head', 'Neuen Link hinzuf&uuml;gen');
define('_navi_edit_head', 'Link editieren');
define('_navi_url_to', 'Weiterleiten nach');
define('_posi', 'Position');
define('_nach', 'nach');
define('_navi_no_name', 'Du musst einen Linknamen angeben!');
define('_navi_no_url', 'Du musst ein Weiterleitungsziel angeben!');
define('_navi_no_pos', 'Du musst die Position f&uuml;r den Link festlegen!');
define('_navi_added', 'Der Link wurde erfolgreich angelegt!');
define('_navi_deleted', 'Der Link wurde erfolgreich gel&ouml;scht!');
define('_navi_edited', 'Der Link wurde erfolgreich editiert!');
define('_editor_head', 'Seiten erstellen/verwalten');
define('_editor_name', 'Seitenbezeichnung');
define('_editor_add', '<a href="?admin=editor&amp;do=add">Neue Seite erstellen</a>');
define('_editor_add_head', 'Neue Seite hinzuf&uuml;gen');
define('_inhalt', 'Inhalt');
define('_editor_allow_html', 'HTML erlauben?');
define('_empty_editor_inhalt', 'Du musst einen Text schreiben!');
define('_site_added', 'Die Seite wurde erfolgreich eingetragen!');
define('_editor_linkname', 'Link-Name');
define('_editor_deleted', 'Die Seite wurde erfolgreich gel&ouml;scht!');
define('_editor_edit_head', 'Seite editieren');
define('_site_edited', 'Die Seite wurde erfolgreich editiert!');
define('_navi_standard', 'Der Standard wurde erfolgreich wieder hergestellt!');
define('_standard_sicher', 'Bist du dir sicher das du den Standard wiederherstellen willst?<br />Alle bisher erstellten Links und neue Seiten werden gel&ouml;scht!');
define('_partners_head', 'Partnerbuttons');
define('_partners_button', 'Button');
define('_partners_add_head', 'Neuen Partnerbutton hinzuf&uuml;gen');
define('_partners_edit_head', 'Partnerbutton editieren');
define('_partners_select_icons', '<option value="[icon]" [sel]>[icon]</option>');
define('_partners_added', 'Der Partnerbutton wurde erfolgreich hinzugef&uuml;gt!');
define('_partners_edited', 'Der Partnerbutton wurde erfolgreich editiert!');
define('_partners_deleted', 'Der Partnerbutton wurde erfolgreich gel&ouml;scht!');
define('_clear_head', 'Datenbank aufr&auml;umen');
define('_clear_news', 'Newseintr&auml;ge mit einbeziehen?');
define('_clear_forum', 'Forumeintr&auml;ge mit einbeziehen?');
define('_clear_forum_info', 'Forumeintr&auml;ge, die als <span class="fontWichtig">wichtig</span> markiert sind werden nicht gel&ouml;scht!');
define('_clear_misc', 'Sonstiges mit einbeziehen (empfohlen)?');
define('_clear_days', 'Eintr&auml;ge l&ouml;schen, die &auml;lter sind als');
define('_clear_what', 'Tage');
define('_clear_deleted', 'Die Datenbank wurde erfolgreich aufger&auml;umt!');
define('_clear_error_days', 'Du musst die Tage angeben, ab wann etwas gel&ouml;scht werden soll!');
define('_admin_status', 'Live-Status');
define('_error_unregistered', 'Du musst registriert sein um diese Funktion Nutzen zu k&ouml;nnen!');
define('_seiten', 'Seite:');
define('_admin_user_gallery', 'Admin: Galerie');
define('_user_admin_joinus', 'JoinUs empfangen?');
define('_user_admin_contact', 'Kontakt empfangen?');
define('_user_admin_formulare', 'Formulare');
define('_smileys_error_file', 'Du musst ein Smiley angeben!');
define('_smileys_error_bbcode', 'Du musst ein BB-Code angeben!');
define('_smileys_error_type', 'Es sind nur GIF-Dateien erlaubt!');
define('_smileys_added', 'Das Smiley wurde erfolgreich hinzugef&uuml;gt!');
define('_smileys_edited', 'Das Smiley wurde erfolgreich editiert!');
define('_smileys_deleted', 'Das Smiley wurde erfolgreich gel&ouml;scht!');
define('_smileys_normals', 'Standardsmileys (k&ouml;nnen nicht gel&ouml;scht werden!)');
define('_smileys_customs', 'Neue Smileys');
define('_smileys_head', 'Smiley-Editor');
define('_smileys_smiley', 'Smiley');
define('_smileys_bbcode', 'BBCode');
define('_smileys_head_add', 'Neuen Smiley hinzuf&uuml;gen');
define('_smileys_head_edit', 'Smiley editieren');
define('_head_waehrung', 'W&auml;hrung');
define('_dl_version', 'downloadbare Version');
define('_admin_artikel_add', '<a href="?admin=artikel&amp;do=add">Artikel hinzuf&uuml;gen</a>');
define('_artikel_add', 'Artikel hinzuf&uuml;gen');
define('_artikel_added', 'Der Artikel wurde erfolgreich hinzugef&uuml;gt');
define('_artikel_edit', 'Artikel editieren');
define('_artikel_edited', 'Der Artikel wurde erfolgreich editiert!');
define('_artikel_deleted', 'Der Artikel wurde erfolgreich gel&ouml;scht!');
define('_empty_artikel_title', 'Du musst einen Titel angeben!');
define('_empty_artikel', 'Du musst einen Artikel angeben!');
define('_admin_artikel', 'Admin: Artikel');
define('_c_l_shoutnick', 'Men&uuml;: Shoutbox: Nick');
define('_config_c_martikel', 'Artikel');
define('_config_c_madminartikel', 'Artikel-Admin');
define('_admin_squadtemplate', 'Teamlayouts');
define('_admin_squadtemplate_info', 'Hier kannst du versch. Teamlayouts einstellen!');
define('_reg_artikel', 'Artikelkommentare');
define('_cw_comments', 'Clanwarkommentare');
define('_on', 'eingeschaltet');
define('_off', 'ausgeschaltet');
define('_pers_info_info', 'Zeigt eine Infobox im Header mit pers&ouml;nlichen Informationen wie IP, Browser, Aufl&ouml;sung etc');
define('_pers_info', 'Infobox');
define('_config_lreg', 'Men&uuml;: Last reg. User');
define('_config_mailfrom', 'E-Mail Absender');
define('_config_mailfrom_info', 'Diese Emailadresse wird bei versendeten Emails wie Newsletter, Registrierung, etc als Absender angezeigt!');
define('_profile_del_confirm', 'Achtung, es gehen alle Usereingaben f&uuml;r dieses Feld verloren. Willst du es wirklich l&ouml;schen?');
define('_profile_del_confirm_link', '<a href="?admin=profile&amp;do=delete&amp;id=[id]&confirm=yes">Feld l&ouml;schen</a> - <a href="javascript:history.go(-1)">Zur&uuml;ck</a>');
define('_profile_about', '&Uuml;ber mich');
define('_profile_clan', 'Clan');
define('_profile_contact', 'Kontakt');
define('_profile_favos', 'Favoriten');
define('_profile_hardware', 'Hardware');
define('_profile_name', 'Feldname');
define('_profile_type', 'Feldtyp');
define('_profile_kat', 'Kategorie');
define('_profile_head', 'Profilfelderverwaltung');
define('_profile_edit_head', 'Profilfeld editieren');
define('_profile_shown', 'Sichtbar');
define('_profile_type_1', 'Textfeld');
define('_profile_type_2', 'URL');
define('_profile_type_3', 'Email-Adresse');
define('_profile_shown_dropdown','
<option value="1">Zeigen</option>
<option value="2">Verstecken</option>');
define('_profile_kat_dropdown', '
<option value="1">&Uuml;ber mich</option>
<option value="2">Clan</option>
<option value="3">Kontakt</option>
<option value="4">Favoriten</option>
<option value="5">Hardware</option>');
define('_profile_type_dropdown', '
<option value="1">Textfeld</option>
<option value="2">URL</option>
<option value="3">Email-Adresse</option>');
define('_profile_add_head', 'Profilfeld hinzuf&uuml;gen');
define('_profile_added', 'Das Profilfeld wurde erfolgreich hinzugef&uuml;gt!');
define('_profil_no_name', 'Du musst einen Feldnamen angeben!');
define('_profil_deleted', 'Das Profilfeld wurde erfolgreich gel&ouml;scht!');
define('_profile_edited', 'Das Profilfeld wurde erfolgreich editiert!');
## Clankasse ##
define('_clankasse_saved', 'Der Beitrag wurde erfolgreich zur Clankasse hinzugef&uuml;gt!');
define('_clankasse_deleted', 'Der Beitrag wurde erfolgreich aus der Clankasse gel&ouml;scht!');
define('_error_clankasse_empty_datum', 'Du musst ein Datum angeben!');
define('_clankasse_edited', 'Der Betrag wurde erfolgreich editiert!');
define('_error_clankasse_empty_transaktion', 'Du musst eine Beschreibung der Transaktion angeben!');
define('_error_clankasse_empty_betrag', 'Du musst einen Betrag angeben!');
define('_clankasse_ctransaktion', 'Was');
define('_clankasse_cbetrag', 'Betrag');
define('_clankasse_server_head', 'Clankonto');
define('_clankasse_nr', 'Kontonr.');
define('_clankasse_blz', 'Bankleitzahl');
define('_clankasse_inhaber', 'Inhaber');
define('_clankasse_bank', 'Bank');
define('_clankasse_head', 'Clankasse');
define('_clankasse_cakt', 'aktueller Kontostand');
define('_clankasse_admin_minus', 'Minus');
define('_clankasse_plus', '<span class="fontGreen">[betrag] [w]</span>');
define('_clankasse_minus', '<span class="fontRed">- [betrag] [w]</span>');
define('_clankasse_summe_plus', '<span class="fontGreen">[summe] [w]</span>');
define('_clankasse_summe_minus', '<span class="fontRed">[summe] [w]</span>');
define('_clankasse_trans', '[transaktion] von/an [member]');
define('_clankasse_head_edit', 'Beitrag editieren');
define('_clankasse_head_new', 'Neuen Beitrag hinzuf&uuml;gen');
define('_clankasse_sonstiges', 'sonstiges');
define('_clankasse_for', 'von/an');
define('_clankasse_einzahlung', '-> Einzahlung');
define('_clankasse_auszahlung', '-> Auszahlung');
define('_clankasse_didpayed', 'Zahlstatus');
define('_clankasse_status_status', 'Status');
define('_clankasse_status_bis', 'bis');
define('_clankasse_status_payed', '<span class="fontGreen">bezahlt</span> bis zum <span>[payed]</span>');
define('_clankasse_status_today', '<span class="fontGreen">bezahlt</span> bis <span class="fontBold">heute</span>');
define('_clankasse_status_notpayed', '<span class="fontRed">&uuml;berf&auml;llig</span> seit dem <span class="fontBold">[payed]</span>');
define('_clankasse_status_noentry', 'noch kein Eintrag');
define('_clankasse_edit_paycheck', 'Zahlstatus editieren');
define('_clankasse_payed_till', 'bezahlt bis');
define('_info_clankass_status_edited', 'Der Zahlstatus wurde erfolgreich gesetzt!');
## Shoutbox ##
define('_shoutbox_head', 'Shoutbox');
define('_error_empty_shout', 'Du musst einen Text in die Shoutbox eingeben!');
define('_error_shout_saved', 'Dein Beitrag wurde erfolgreich in die Shoutbox eingetragen!');
define('_shoutbox_archiv', 'Archiv');
define('_shout_archiv_head', 'Shoutbox Archiv');
define('_noch', 'noch');
define('_zeichen', 'Zeichen');
## Misc ##
define('_error_have_to_be_logged', 'Du musst eingeloggt sein um diese Funktion Nutzen zu k&ouml;nnen!');
define('_error_invalid_email', 'Du hast eine ung&uuml;ltige Emailadresse angegeben!');
define('_error_invalid_url', 'Die angegebene Homepage ist nicht erreichbar!');
define('_error_nick_exists', 'Der Nickname ist leider schon vergeben!');
define('_error_user_exists', 'Der Loginname ist leider schon vergeben!');
define('_error_email_exists', 'Die von dir angegebene EMailadresse wird schon von jemandem verwendet!');
define('_info_edit_profile_done_pwd', 'Du hast dein Profil erfolgreich editiert!');
define('_error_select_buddy', 'Du hast keinen User angegeben!');
define('_error_buddy_self', 'Du kannst dich nicht selbst als Buddy eintragen!');
define('_error_buddy_already_in', 'Der User steht schon in deiner Buddyliste!');
define('_error_msg_self', 'Du kannst dir nicht selber eine Nachricht schreiben!');
define('_error_back', 'zur&uuml;ck');
define('_user_dont_exist', 'Der von dir angegebene User existiert nicht!');
define('_error_fwd', 'weiter');
define('_error_wrong_permissions', 'Du hast nicht die erforderlichen Rechte um diese Aktion durchzuf&uuml;hren!');
define('_error_flood_post', 'Du kannst nur alle [sek] Sekunden einen neuen Eintrag schreiben!');
define('_empty_titel', 'Du musst einen Titel angeben!');
define('_empty_eintrag', 'Du musst einen Beitrag schreiben!');
define('_empty_nick', 'Du musst deinen Nick angeben!');
define('_empty_email', 'Du musst eine E-Mailadresse angeben!');
define('_empty_user', 'Du musst einen Loginnamen angeben!');
define('_empty_to', 'Du musst einen Empf&auml;nger  angeben!');
define('_empty_url', 'Du musst eine URL angeben!');
define('_empty_datum', 'Du musst ein Datum angeben!');
define('_site_loading', 'preloading site');
define('_site_wait', 'please wait');
define('_index_headtitle', '[clanname]');
define('_site_sponsor', 'Sponsoren');
define('_site_user', 'User');
define('_site_online', 'Besucher online');
define('_site_gametiger', 'Gametiger');
define('_site_member', 'Member');
define('_site_serverlist', 'Serverliste');
define('_site_rankings', 'Rankings');
define('_site_server', 'GameServer');
define('_site_forum', 'Forum');
define('_site_backup', 'Datenbankbackup');
define('_site_links', 'Links');
define('_site_dl', 'Downloads');
define('_site_news', 'News');
define('_site_messerjocke', 'Messerjocke');
define('_site_banned', 'Bannliste');
define('_site_gb', 'G&auml;stebuch');
define('_site_clankasse', 'Clankasse');
define('_site_clanwars', 'Clanwars');
define('_site_upload', 'Upload');
define('_site_taktiken', 'Taktiken');
define('_site_ulist', 'Userliste');
define('_site_msg', 'Nachrichten');
define('_site_reg', 'Registrierung');
define('_site_shoutbox', 'Shoutbox');
define('_site_user_login', 'Login');
define('_site_user_lostpwd', 'Lostpwd');
define('_site_user_logout', 'Logout');
define('_site_artikel', 'Artikel');
define('_site_user_lobby', 'Userlobby');
define('_site_user_profil', 'Userprofil');
define('_site_user_editprofil', 'Profil editieren');
define('_site_user_buddys', 'Buddies');
define('_site_impressum', 'Impressum');
define('_site_votes', 'Umfragen');
define('_site_gallery', 'Galerie');
define('_site_config', 'Adminbereich');
define('_login', 'Login');
define('_register', 'registrieren');
define('_userlist', 'Userliste');
define('_rankings', 'Rankings');
define('_gallery', 'Galerie');
define('_news', 'News');
define('_newsarchiv', 'Newsarchiv');
define('_gametiger', 'Gametiger');
define('_serverliste', 'Serverliste');
define('_banned', 'Bannliste');
define('_links', 'Links');
define('_impressum', 'Impressum');
define('_contact', 'Kontakt');
define('_clanwars', 'Clanwars');
define('_artikel', 'Artikel');
define('_dl', 'Downloads');
define('_votes', 'Umfragen');
define('_forum', 'Forum');
define('_gb', 'G&auml;stebuch');
define('_squads', 'Teams');
define('_server', 'Server');
define('_editprofil', 'Profil editieren');
define('_logout', 'Logout');
define('_msg', 'Nachrichten');
define('_lobby', 'Lobby');
define('_buddys', 'Buddies');
define('_mem_clankasse', 'Clankasse');
define('_mem_taktiken', 'Taktiken');
define('_admin_config', 'Admin');
define('_head_online', 'Online');
define('_head_visits', 'Besucher');
define('_head_max', 'Max.');
define('_cnt_user', 'User');
define('_cnt_guests', 'G&auml;ste');
define('_cnt_today', 'Heute');
define('_cnt_yesterday', 'Gestern');
define('_cnt_online', 'Online');
define('_cnt_all', 'Gesamt');
define('_cnt_pperday', '&oslash; Tag');
define('_cnt_perday', 'pro Tag');
define('_show', 'Anzeigen');
define('_dont_show', 'Nicht anzeigen');
define('_status', 'Status');
define('_position', 'Position');
define('_kind', 'Art');
define('_cnt', '#');
define('_membergb', 'Profilg&auml;stebuch');
define('_pwd', 'Passwort');
define('_loginname', 'Login-Name');
define('_email', 'E-Mail');
define('_hp', 'Homepage');
define('_icq', 'ICQ-Nr.');
define('_member', 'Member');
define('_user', 'User');
define('_gast', 'unregistriert');
define('_nothing', '<option value="lazy" class="dropdownKat">- nichts &auml;ndern -</option>');
define('_pn', 'Nachricht');
define('_nick', 'Nick');
define('_info', 'Info');
define('_error', 'Fehler');
define('_datum', 'Datum');
define('_legende', 'Legende');
define('_xfire_name', 'Xfire Name');
define('_xfire', 'Xfire');
define('_steamid', 'Steam-ID');
define('_link', 'Link');
define('_linkname', 'Linkname');
define('_url', 'URL');
define('_admin', 'Admin');
define('_hits', 'Hits');
define('_map', 'Map');
define('_game', 'Game');
define('_autor', 'Autor');
define('_yes', 'Ja');
define('_no', 'Nein');
define('_maybe', 'Vielleicht');
define('_beschreibung', 'Beschreibung');
define('_admin_user_get_identy', 'Du hast erfolgreich die Identit&auml;t von [nick] angenommen!');
define('_comment_added', 'Dein Kommentar wurde erfolgreich hinzugef&uuml;gt!');
define('_comment_deleted', 'Der Kommentar wurde erfolgreich gel&ouml;scht!');
define('_stichwort', 'Stichwort');
define('_language_set', 'Du hast soeben eine andere &Uuml;bersetzung aktiviert!');
define('_language_set_uk', 'Unbekannte &Uuml;bersetzung');
define('_eintragen_titel', 'Eintragen');
define('_titel', 'Titel');
define('_bbcode', 'BBCode');
define('_answer', 'Antwort');
define('_eintrag', 'Eintrag');
define('_ftp_server_not_reachable', 'Der FTP-Server ist zur Zeit nicht erreichbar! Versuche es sp&auml;ter nochmal!');
define('_define_path', 'Der Pfad den du eintragen musst lautet:');
define('_weiter', 'weiter');
define('_site_teamspeak', 'Teamspeak');
define('_teamspeak', 'Teamspeak');
define('_site_contact', 'Kontaktformular');
define('_site_joinus', 'JoinUs - Kontaktformular');
define('_site_fightus', 'FightUs - Kontaktformular');
define('_joinus', 'JoinUs');
define('_fightus', 'FightUs');
define('_site_msg_new', 'Du hast neue Nachrichten!<br />
                         Klicke <a href="../user/?action=msg">hier</a> um ins Nachrichtenmenu zu gelangen!');
define('_site_kalender', 'Kalender');
define('_login_permanent', ' Autologin');
define('_msg_del', 'markierte l&ouml;schen');
define('_wartungsmodus', 'Die Webseite ist momentan wegen Wartungsarbeiten geschlossen!<br />
Bitte versuche es in ein paar Minuten erneut!');
define('_wartungsmodus_head', 'Wartungsmodus');
define('_kalender', 'Kalender');
define('_ts_head', 'Teamspeak');
define('_ts_name', 'Servername');
define('_ts_os', 'Betriebsystem');
define('_ts_uptime', 'Uptime');
define('_ts_channels', 'Channels');
define('_ts_user', 'User');
define('_ts_users_head', 'User Informationen');
define('_ts_player', 'User');
define('_ts_channel', 'Channel');
define('_ts_logintime', 'Eingeloggt seit');
define('_ts_idletime', 'AFK seit');
define('_ts_channel_head', 'Channel Informationen');
define('_taktik_choose', ' - Bitte w&auml;hlen - ');
define('_config_tmpdir', 'Standardtemplate');
define('_template_set', 'Du hast ein neues Design ausgew&auml;hlt!');
define('_rankings_head', 'Rankings');
define('_rankings_league', 'Liga');
define('_rankings_place', 'Platz alt/neu');
define('_rankings_admin_place', 'Platz');
define('_rankings_squad', 'Team');
define('_rankings_teamlink', 'Teamlink');
define('_ranking_added', 'Das Ranking wurde erfolgreich hinzugef&uuml;gt!');
define('_ranking_edited', 'Das Ranking wurde erfolgreich editiert!');
define('_ranking_deleted', 'Das Ranking wurde erfolgreich gel&ouml;scht!');
define('_ranking_empty_league', 'Du musst eine Liga angeben!');
define('_ranking_empty_url', 'Du musst eine URL zu der Liga angeben!');
define('_ranking_empty_rank', 'Du musst einen Rank angeben!');
define('_rankings_add_head', 'Neues Ranking hinzuf&uuml;gen');
define('_admin_rankings', 'Admin: Rankings');
define('_navi_info', 'Alle in "_" eingebetteten Linknamen (wie _admin_) sind Platzhalter, die f&uuml;r die jeweiligen &Uuml;bersetzungen ben&ouml;tigt werden!');
define('_shout_delete_successful', 'Der Eintrag wurde erfolgreich aus der Shoutbox gel&ouml;scht!');
define('_member_admin_intnews', 'Interne News sehen');
define('_news_admin_intern', 'interne News?');
define('_news_sticky', '<span class="fontWichtig">Angeheftet:</span>');
define('_news_get_sticky', 'News anheften?');
define('_news_sticky_till', 'bis zum:');
define('_cw_xonx', 'XonX');
define('_forum_lp_head', 'Letzter Forenpost');
define('_forum_previews', 'Vorschau');
define('_download_size', 'Dateigr&ouml;ﬂe:');
define('_site_awards', 'Awards');
define('_error_unregistered_nc', '
<tr>
  <td class="contentMainFirst" align="center" colspan="2">
    <span class="fontBold">Du musst registriert sein um einen Kommentar schreiben zu k&ouml;nnen!</span>
  </td>
</tr>');
define('_server_menu_austragen', 'Der Server wurde erfolgreich aus dem Menu ausgetragen!');
define('_server_menu_eintragen', 'Der Server wurde erfolgreich ins Menu eingetragen!');
define('_server_legendemenu', 'Server ist im Menu eingetragen? (aufs Icon klicken um Status zu &auml;ndern)<br />(Mehrfache Eintragungen sind m&ouml;glich!)');
define('_config_c_servernavi', 'Menu: Serverstatus');
define('_upload_partners_head', 'Partnerbuttons');
define('_upload_partners_info', 'Nur jpg, gif oder png Dateien. Empfohlene Gr&ouml;ﬂe: 88px * 31px');
define('_user_banned', 'Account gesperrt');
define('_select_field_ranking_add', '<option value="[value]" [sel]>[what]</option>');
define('_member_squad_wars', '<a href="../clanwars/?action=showall&amp;id=[id]">Wars</a>');
define('_member_squad_awards', '<a href="../awards/?action=showall&amp;id=[id]">Awards</a>');
define('_user_list_ck', 'In der Clankasse auflisten?');
define('_fightus_squad', 'gew&uuml;nschtes Team');
?>