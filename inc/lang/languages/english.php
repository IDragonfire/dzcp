<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

$charset = 'iso-8859-1';
header("Content-type: text/html; charset=".$charset);

## ADDED / REDEFINED FOR 1.6 Final
define('_txt_navi_main', 'Main Navigation');
define('_txt_navi_clan', 'Clan Navigation');
define('_txt_navi_server', 'Server Navigation');
define('_txt_navi_misc', 'Misc Navigation');
define('_txt_userarea', 'Userarea');
define('_txt_vote', 'Vote');
define('_txt_partners', 'Partners');
define('_txt_sponsors', 'Sponsors');
define('_txt_counter', 'Counter');
define('_txt_l_news', 'News');
define('_txt_ftopics', 'Topics');
define('_txt_l_wars', 'Last Wars');
define('_txt_n_wars', 'Next Wars');
define('_txt_teams', 'Teams');
define('_txt_gallerie', 'Our Gallerys');
define('_txt_top_match', 'Top Match');
define('_txt_shout', 'Shoutbox');
define('_txt_template_switch', 'Switch Template');
define('_txt_events', 'Events');
define('_txt_kalender', 'Calendar');
define('_txt_l_artikel', 'Articles');
define('_txt_l_reg', 'new Users');
define('_txt_motm', 'Member of the Moment');
define('_txt_random_gallery', 'random Gallerypic');
define('_txt_server', 'Server');
define('_txt_teamspeak', 'Teamspeak');
define('_txt_top_dl', 'Top Downloads');
define('_txt_uotm', 'User of the Moment');

define('_gal_pics', 'Pics in Gallery');
define('_config_slideshow', 'Slideshow');
define('_perm_slideshow', 'Manage Slideshow Pics');
define('_slider', 'Slideshow');
define('_slider_admin_add', 'Add new Slideshowpicture');
define('_slider_admin_add_done', 'Slideshowpicture successfully added');
define('_slider_admin_del', 'Realy Delete this Slideshowpicture?');
define('_slider_admin_del_done', 'Slideshowpicture successfully deleted');
define('_slider_admin_edit', 'edit Slideshowpicture');
define('_slider_admin_edit_done', 'Slideshowpicture successfully edited!');
define('_slider_admin_error_empty_bezeichnung', 'you have to enter a title');
define('_slider_admin_error_empty_url', 'you have to enter a link');
define('_slider_admin_error_nopic', 'You have to upload a picture');
define('_slider_bezeichnung', 'Title');
define('_slider_new_window', 'new Window?');
define('_slider_pic', 'Picture');
define('_slider_desc', 'Description');
define('_slider_position', 'Position');
define('_slider_position_first', 'first');
define('_slider_position_lazy', '<option value="lazy">- no change -</option>');
define('_slider_url', 'URL');
define('_slider_show_title', 'Show title');
define('_forum_kat', 'Categorie');

define('_artikel_userimage', 'Own Articlepicture');
define('_artikelpic_del', 'delete Articlepicture?');
define('_artikelpic_deleted', 'Articlepicture deleted successfully');

define('_news_userimage', 'Own Newspicture');
define('_newspic_del', 'delete Newspicture?');
define('_newspic_deleted', 'Newspicture deleted successfully');
define('_max', 'max.');

define('_perm_galleryintern','View internal Gallery');
define('_perm_dlintern','View internal Downloads');

define('_config_url_linked_head', "URLs linking");
define('_config_c_m_membermap', 'Membermap');
define('_ts_settings_customicon', 'custom icons downloading');
define('_ts_settings_showchannels', 'Only show channels with user');
define('_ts_settings_showchannels_desc', 'If this is on, they will only show channels there are users.');

define('_upload_error', 'Failed to upload the file!');
define('_login_banned', 'Your account has been banned by administrator!');
define('_lobby_no_mymessages', '<a href="../user/?action=msg">You have no new messages!</a>');

define('_perm_smileys', 'manage smileys');
define('_perm_protocol', 'can see admin protocol');
define('_perm_support', 'can see support page');
define('_perm_backup', 'manage SQL-Backups');
define('_perm_clear', 'clean database');
define('_perm_forumkats', 'manage forums categories');
define('_perm_impressum', 'manage impressum');
define('_perm_config', 'manage configuration page');
define('_perm_positions', 'manage user ranks');
define('_perm_partners', 'manage partner');
define('_perm_profile', 'manage profile fields');

define('_dzcp_vcheck', 'The DZCP Version Checker will inform you about new DZCP updates and shows you whether your version is up to date.<br><br><span class=fontBold>Description:</span><br><font color=#17D427>Green:</font>Up to Date!<br><font color=#FFFF00>Yellow:</font> Could not connect to Server</br><font color=#FF0000>Red:</font>A new update available!');
define('_cw_dont_exist', 'The specified clanwar ID does not exist!');

//Steam
define('_steam', 'Steam');
define('_steam_online', 'Online');
define('_steam_offline', 'Last online: [time]');
define('_steam_offline_simple', 'Offline.');
define('_steam_in_game', 'In Game');
define('_config_steam_apikey', 'Steam API-Key');
define('_steam_apikey_info', 'Registering a Steam API Key: <a href="http://steamcommunity.com/dev/apikey/" target="_blank">steamcommunity.com</a>');

define('_years', 'Years');
define('_year', 'Year');
define('_months', 'Months');
define('_month', 'Month');
define('_weeks', 'Weeks');
define('_week', 'Week');
define('_days', 'Days');
define('_day', 'Day');
define('_hours', 'Hours');
define('_hour', 'Hour');
define('_minutes', 'Minutes');
define('_minute', 'Minute');
define('_seconds', 'Seconds');
define('_second', 'Second');

## ADDED / REDEFINED FOR 1.5 Final
define('_side_membermap', 'Membermap');
define('_id_dont_exist', 'The requested ID does not exist!');
define('_perm_editts', 'manage teamspeak server');
define('_perm_receivecws', 'receive fight us form');

## ADDED / REDEFINED FOR 1.5.2
define('_button_title_del_account' , 'User-Account delete');
define('_confirm_del_account' , 'You really want to delete your Account on dzcp.de');
define('_profil_del_account' , 'Account delete');
define('_info_account_deletet' , 'Your Account has been successfully deleted!');
define('_profil_del_admin' , '<b>Deleting not possible!</b>');
define('_news_get_timeshift' , "Timeshift News?");
define('_news_timeshift_from', 'Show news from:');
define('_config_gb_activ' , 'Guestbook');
define('_config_gb_activ_info' , '<center>Here you can specify whether an item will only be released by an admin must.</center>');
define('_legend_map_download' , 'Download map screenshots from dzcp.de');
define('_map_download_success' , 'The screenshots has been successfully downloaded and saved!');
define('_mapdl_download' , 'Should I search and download map screenshots for this server?');
define('_error_mapdl_nomap' , 'For this game, no map screenshot is available right now.<br />If you want to share maps for this game with the community, please mail them to webmaster@dzcp.de.');
define('_error_mapdl_connection' , 'No connection to the gameserver could be established.');
define('_error_mapdl_server' , 'This gameserver does not exist!');
define('_mapdl_loading' , 'Loading maps from dzcp.de');
define('_placeholder' , 'Template Placeholder');
define('_menu_kats_head' , 'Menu Categories');
define('_menu_add_kat' , 'Add new menu category');
define('_confirm_del_menu' , 'Dyo you really want to delet the menu category?');
define('_menu_edit_kat' , 'Edit menu category');
define('_menukat_updated' , 'The menu category has been successfully edited!');
define('_menukat_inserted' , 'The menu category has been successfully added!');
define('_menukat_deleted' , 'The menu category has been successfully deleted!');
define('_menu_visible' , 'visible for status');
define('_menu_kat_info' , 'The css classes for the link will be constructed from the template placeholder, automatically.<br />e.g. for the placeholder <i>[nav_main]</i>, the css class will be <i>a.navMain</i>');
define('_admin_sqauds_roster' , 'Team-Roster');
define('_admin_squads_nav_info' , 'This will put a direct link in the navigation, which target to full size of the Team.');
define('_admin_squads_teams' , 'Team-Show');
define('_admin_squads_no_navi' , 'Don\'t show');
define('_config_cache_info' , 'here you can set intervals, when teamspeak and gamserver will be reloaded. Outherwise the informations will be read from the cache.');
define('_config_direct_refresh' , 'Direct Forward');
define('_config_direct_refresh_info' , 'If activated, the site will be forwarded directly, instead of showing the status information.');
define('_cw_reset_button' , 'Admin: Reset player status');
define('_cw_players_reset' , 'The player status has been successfully reseted!');
define('_eintrag_titel_forum' , '<a href="[url]" title="Show this post"><span class="fontBold">#[postid]</span></a> at [datum] on [zeit]  [edit] [delete]');
define('_eintrag_titel' , '<span class="fontBold">#[postid]</span> at [datum] on [zeit]  [edit] [delete]');
## ADDED / REDEFINED FOR 1.5.1
define('_config_double_post' , 'Forum double post');
define('_config_fotum_vote' , 'Forum-Vote');
define('_config_fotum_vote_info' , '<center>Here you can specify whether a Forum-Vote also Vote to be displayed.</center>');
## ADDED / REDEFINED FOR 1.5
define('_installdir' , "<tr><td colspan=\"15\" class=\"contentMainFirst\"><br /><center>In case of security reasons, please remove the folder \"<b>/_installer</b>\" from your webserver! Only then the admin menu available!</center><br /></td></tr>");
define('_no_ts' , 'no Teamspeak');
define('_search_sites' , 'Sites');
define('_search_results' , 'Search Results');
define('_config_useradd_head' , 'Add User');
define('_config_adduser' , 'Add User');
define('_uderadd_info' , 'The User has been successfully added');
define('_useradd_head' , 'Add new User');
define('_useradd_about' , 'Userdetails');
define('_login_signup' , 'Register');
define('_login_lostpwd' , 'Password?');
define('_config_links' , 'Links');
define('_no_server_navi' , 'no server registered');
define('_vote_menu_no_vote' , 'no vote registered');
define('_no_top_match' , 'no top match registered!');
define('_team_logo' , 'Team Logo');
define('_cw_logo' , 'Opponent Logo');
define('_cw_screenshot' , 'Screenshot');
define('_cw_admin_top_setted' , 'This Clanwar has been successfully added as top match!');
define('_cw_admin_top_unsetted' , 'This Clanwar has been successfully removed as top match!');
define('_cw_admin_top_set' , 'Add es top match');
define('_cw_admin_top_unset' , 'Remove as top match');
define('_sq_banner' , 'Teambanner');
define('_forum_abo_title' , 'Suscribe Thread');
define('_forum_vote' , 'Vote');
define('_admin_user_clanhead_info' , 'These permissions can be set <u>additional</u> to the permissions in the user ranks.');
define('_user_noposi' , '<option value="lazy" class="dropdownKat">no user rank</option>');
define('_config_positions_boardrights' , 'internal board permissions');
define('_perm_awards' , 'manage awards');
define('_perm_clankasse' , 'manage Clancash');
define('_perm_contact' , 'receive contact form');
define('_perm_editkalender' , 'manage calendar entries');
define('_perm_editserver' , 'manage server');
define('_perm_edittactics' , 'manage tactics');
define('_perm_forum' , 'board admin');
define('_perm_gb' , 'guestbook admin');
define('_perm_links' , 'manage links');
define('_perm_newsletter' , 'manage newsletter');
define('_perm_rankings' , 'manage rankings');
define('_perm_serverliste' , 'manage serverlist');
define('_perm_votesadmin' , 'manage votes');
define('_perm_artikel' , 'manage articles');
define('_perm_clanwars' , 'manage clanwars');
define('_perm_downloads' , 'manage dowloads');
define('_perm_editor' , 'manage sites');
define('_perm_editsquads' , 'manage teams');
define('_perm_editusers' , 'can edit users');
define('_perm_gallery' , 'manage galleries');
define('_perm_glossar' , 'manage glossar');
define('_perm_intnews' , 'can read internal news');
define('_perm_joinus' , 'receive joinus form');
define('_perm_news' , 'manage news');
define('_perm_shoutbox' , 'Shoutbox admin');
define('_perm_votes' , 'can see internal votes');
define('_perm_gs_showpw' , 'can see gameserver password');
define('_config_positions_rights' , 'Permissions');
define('_config_positions' , 'User Ranks');
define('_admin_pos' , 'User Ranks');
define('_awaycal' , 'Away Calendar');
define('_clear_away' , 'Away Calendar entrys?');
define('_config_sponsors' , 'Sponsors');
define('_sponsors_admin_head' , 'Sponsors');
define('_sponsors_admin_add' , 'Add Sponsor');
define('_sponsor_added' , 'The sponsor has been successfully registered!');
define('_sponsor_edited' , 'The sponsor has been successfully edited!');
define('_sponsor_deleted' , 'The sponsor has been successfully deleted!');
define('_sponsor_name' , 'Sponsorname');
define('_sponsors_admin_name' , 'Name');
define('_sponsors_admin_site' , 'Sponsor site');
define('_sponsors_admin_addsite' , 'To sponsorsite');
define('_sponsors_admin_add_site' , 'This banner will be displayed at the sponsor page');
define('_sponsors_admin_upload' , 'Pic-Upload');
define('_sponsors_admin_url' , 'or: Pic-URL');
define('_sponsors_admin_banner' , 'Rotation Banner');
define('_sponsors_admin_addbanner' , 'To Rotation-Banner');
define('_sponsors_admin_add_banner' , 'This banner will be displayed at the top of the rotationbanners');
define('_sponsors_admin_box' , 'Sponsor-Box');
define('_sponsors_admin_addbox' , 'To Sponsor-Box');
define('_sponsors_admin_add_box' , 'This banner will be displayed in the sponsors box');
define('_sponsors_empty_name' , 'Insert the name of the sponsor!');
define('_sponsors_empty_beschreibung' , 'You have to indicate a title tag!');
define('_sponsors_empty_link' , 'You have to indivate a link url!');
define('_site_away' , 'Away Calendar');
define('_away_list' , 'Away List');
define('_config_c_away' , 'Away List');
define('_away_status_new' , '<b><font color=orange>Added</font></b>');
define('_away_status_now' , '<b><font color=green>Current</font></b>');
define('_away_status_done' , '<b><font color=red>Expired</font></b>');
define('_away_new' , 'Report');
define('_away_empty_titel' , 'Please specify a reason');
define('_away_empty_reason' , 'Please specify a comment');
define('_away_error_1' , 'The end date may not be the same as the start date!');
define('_away_error_2' , 'The start date is greater than the end date!');
define('_away_to' , 'To');
define('_away_to2' , '');
define('_away_head' , 'Away List');
define('_away_new_head' , 'Add Absence');
define('_away_reason' , 'Reason');
define('_away_successful_added' , 'The Absence has been successfully added!');
define('_away_on' , 'on');
define('_away_info_head' , 'Absence Info by');
define('_away_addon' , 'Added on');
define('_away_formto' , 'From - To:');
define('_away_back' , 'back to list');
define('_away_edit_head' , 'Edit Absence');
define('_away_successful_del' , 'The Absence has been successfully deleted!');
define('_away_successful_edit' , 'The Absence has been successfully edited!');
define('_away_no_entry' , '<tr><td align="center" class="contentMainFirst" colspan="10"><span class="smallfont">no absence available !</span></td></tr>');
define('_lobby_away' , 'currently Absence');
define('_lobby_away_new' , 'Absence');
define('_user_away' , '<tr><td class="contentMainTop" width="25%" valign="top"><span class="fontBold">[naway]:</span></td><td class="contentMainFirst" width="75%">[away]</td>
</tr>');
define('_user_away_currently' , '<tr><td class="contentMainTop" width="25%" valign="top"><span class="fontBold">[ncaway]:</span></td><td class="contentMainFirst" width="75%">[caway]</td></tr>
');
define('_user_away_new' , '[user] - <b>Reason:</b> <a href="../away/?action=info&id=[id]">[what]</a><br />&nbsp;&nbsp;away from [ab] until [wieder]<br />');
define('_user_away_now' , '[user] - <b>Reason:</b> <a href="../away/?action=info&id=[id]">[what]</a><br />&nbsp;&nbsp;away until [wieder]<br />');
define('_away_today' , 'including <b>today</ b>');
define('_public' , 'Public');
define('_non_public' , 'non Public');
define('_no_public' , '<b>unpublished</b>');
define('_no_events' , '<center>no events available</center>');
define('_config_c_events' , 'Menu: Events');
define('_news_send' , 'Send News');
define('_news_send_source' , 'Source');
define('_news_send_titel' , 'News proposal of [nick]');
define('_news_send_note' , 'Communication or notice to the editor');
define('_news_send_done' , 'Thank you very much! The news has been successfully forwarded to the editor');
define('_news_send_description' , 'Dear visitor,<br /><br />with the following form, it is possible to send us. The completed form from you will be forwarded to our editors. Please remember that we prepare every submission, and possible more precise details must investigate to ensure the quality of our usual news maintain. This is obviously easier if you are already sending a lot of details and that even includes texts formulated. 1:1 messages only from other pages are copied, make our work and often prevent a publication of returning to our main page.<br /><br />Naturally, we are informed of any news submitted by you grateful and happy about the commitment of our visitors. Thanks in advance.<br /><br />Your Editorial Team');
define('_contact_text_sendnews' , '
[nick] has given us a proposal submitted News!<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontBold">Nick:</span> [nick]<p>&nbsp;</p>
<span class="fontBold">Email:</span> [email]<p>&nbsp;</p>
<span class="fontBold">Source:</span> [hp]<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontBold">Title:</span> [titel]<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontUnder"><span class="fontBold">News:</span></span><p>&nbsp;</p>[text]<p>&nbsp;</p><p>&nbsp;</p>
<span class="fontUnder"><span class="fontBold">Communication or notic:</span></span><p>&nbsp;</p>[info]');

define('_msg_sendnews_user' , '
<tr>
  <td align="center" class="contentMainTop"><span class="fontBold">In order for the other editors know that you will publish this news, <br /> please click on the following button. Thanks</span></td>
</tr>
<tr>
  <td align="center" class="contentMainTop">
    <form action="" method="get" onsubmit="sendMe()">
      <input type="hidden" name="action" value="msg" />
      <input type="hidden" name="do" value="sendnewsdone" />
      <input type="hidden" name="id" value="[id]" />
      <input type="hidden" name="datum" value="[datum]" />
      <input id="contentSubmit" type="submit" class="submit" value="Submit" />
    </form>
  </td>
</tr>');
define('_msg_sendnews_done' , '
<tr>
  <td align="center" class="contentMainTop"><span class="fontRed">
This news is / was made from the [user] edited!!!</span></td>
</tr>');
define('_send_news_done' , 'Thank you for your news!');
define('_msg_all_leader' , "all Leader & Co-Leader");
define('_msg_leader' , "Squad-Leader");
define('_pos_nletter' , 'Include this position in newsletter to Leader and Co-Leader');
define('_clankasse_vwz' , 'Purpose');
define('_pwd2' , 'repeat password');
define('_wrong_pwd' , 'The password entered does not match');
define('_info_reg_valid_pwd' , 'You has been successfully registered and can login now with your access data!<br /><br />Your access data has been send to your email address [email], too.');
define('_profil_pnmail' , 'Email on new message');
define('_admin_pn_subj' , 'Subject: PN-Email');
define('_admin_pn' , 'PN-Email Template');
define('_admin_fabo_npost_subj' , 'Subject: Board Subscription: New Post');
define('_admin_fabo_pedit_subj' , 'Subject: Board Subscription: Post edit');
define('_admin_fabo_tedit_subj' , 'Subject: Board Subscription: Thread edit');
define('_admin_fabo_npost' , 'Board Subscription: New Post Template');
define('_admin_fabo_pedit' , 'Board Subscription: Post edit Template');
define('_admin_fabo_tedit' , 'Board Subscription: Thread edit Template');
define('_foum_fabo_checkbox' , 'Subscribe to this thread and for notification via e-mail about new posts?');
define('_forum_fabo_do' , 'E-Mail notification has been successfully edited!');
define('_user_link_fabo' , '[nick]');
define('_forum_vote_del' , 'Delete Vote');
define('_forum_vote_preview' , 'Here the vote appears.');
define('_forum_spam_text' , '[ltext]<p>&nbsp;</p><p>&nbsp;</p><span class="fontBold">Addendum by </span>[autor]:<p>&nbsp;</p>[ntext]');
####################################################################################
define('_config_config' , 'Global Settings');
define('_config_dladmin' , 'Downloads');
define('_config_editor' , 'Sites');
define('_config_konto' , 'Clancash');
define('_config_dl' , 'Download Categories');
define('_config_nletter' , 'Newsletter');
define('_config_protocol' , 'Adminprotocoll');
define('_config_serverlist' , 'Serverlist');
define('_partnerbuttons_textlink' , 'Textlink');
define('_config_forum_subkats_add' , '
    <form action="" method="get" onsubmit="DZCP.submitButton()">
      <input type="hidden" name="admin" value="forum" />
      <input type="hidden" name="do" value="newskat" />
      <input type="hidden" name="id" value="[id]" />
      <input id="contentSubmit" type="submit" class="submit" value="Insert sub-category" />
    </form>
');
define('_msg_answer' , '
    <form action="" method="get" onsubmit="DZCP.submitButton()">
      <input type="hidden" name="action" value="msg" />
      <input type="hidden" name="do" value="answer" />
      <input type="hidden" name="id" value="[id]" />
      <input id="contentSubmit" type="submit" class="submit" value="Answer" />
    </form>');
define('_user_new_erase' , '<form method="get" action="" onsubmit="DZCP.submitButton()"><input type="hidden" name="action" value="erase" /><input id="contentSubmit" type="submit" name="submit" class="submit" value="Mark all as readed" /></form>');
define('_klapptext_server_link' , '<a href="javascript:DZCP.toggle(\'[id]\')"><img src="../inc/images/[moreicon].gif" alt="" id="img[id]">[link]</a>');
define('_target' , 'New window');
define('_profile_add' , '<form action="" method="get" onsubmit="return(DZCP.submitButton())">
      <input type="hidden" name="admin" value="profile" />
      <input type="hidden" name="do" value="add" />
      <input id="contentSubmit" type="submit" class="submit" value="Insert profile field" />
    </form>');
define('_clankasse_new' , '<form action="" method="get" onsubmit="return(DZCP.submitButton())">
      <input type="hidden" name="action" value="admin" />
      <input type="hidden" name="do" value="new" />
      <input id="contentSubmit" type="submit" class="submit" value="Insert new contribution" />
    </form>');
define('_config_c_floods_what' , 'Here you can adjust the time in secontds which a user have to wait<br />to write something new in this area');;
define('_confirm_del_shout' , 'You really want to delete this shoutbox entry');
## ADDED FOR 1.4.9.3
define('_ergebnisticker_more' , 'More Results');
define('_site_ergebnisticker' , 'ESL Pro Series - Resultticker');
## ADDED FOR 1.4.5
define('_admin_smiley_exists' , 'There is already a smiley with this name!');
## ADDED FOR 1.4.3
define('_download_last_date' , 'Last downloaded');
## EDITED FOR 1.4.1
define('_ulist_normal' , 'Rank &amp; Level');
## ADDED FOR 1.4.1
define('_lobby_mymessages' , '<a href="../user/?action=msg">You have <span class="fontWichtig">[cnt]</span> new messages!</a>');
define('_lobby_mymessage' , '<a href="../user/?action=msg">You have <span class="fontWichtig">1</span> new message!</a>');
## EDIT/ADDED FOR 1.4
//Added
define('_protocol_action' , 'Action');
define('_protocol' , 'Admin protocol');
define('_button_title_del_protocol' , 'Completely delete the protocol!');
define('_protocol_deleted' , 'The admin protocol was successfull deleted!');

define('_vote_no_answer' , 'You have to choose an answer!');
define('_linkus_admin_edit' , 'Edit linkus');
define('_config_linkus' , 'Linkus');
define('_glossar_specialchar' , 'No special characters may be present in the designation!');
define('_admin_gmaps_who' , 'Membermap');
define('_gmaps_who_all' , 'Show all user');
define('_gmaps_who_mem' , 'Show members only');
define('_urls_linked_info', 'Convert text links into clickable hyperlinks');
define('_membermap', 'Membermap');
define('_membermap_user', 'Membermap User');
define('_membermap_pic', 'Userpic');
define('_membermap_nick', 'Nick');
define('_membermap_rank', 'Position');
define('_membermap_city', 'City');
define('_sponsoren' , 'Sponsors');
define('_downloads' , 'Downloads');
define('_cw' , 'Clanwars');
define('_awards' , 'Awards');
define('_serverlist' , 'Serverlist');
define('_ts' , 'Teamspeak');
define('_galerie' , 'Gallery');
define('_kontakt' , 'Contact');
define('_nachrichten' , 'Messages');
define('_edit_profile' , 'Edit profile');
define('_clankasse' , 'Clan cash');
define('_taktiken' , 'Tactics');
define('_user_new_newsc' , '&nbsp;&nbsp;<a href="../news/?action=show&amp;id=[id]#lastcomment"><span class="fontWichtig">[cnt]</span> [eintrag] in <span class="fontWichtig">[news]</span></a><br />');
define('_config_c_teamrow' , 'Menu: Teams');
define('_config_c_teamrow_info' , '(Members per row)');
define('_config_c_lartikel' , 'Menu: Last article');
define('_config_hover' , 'Mouseover informations');
define('_config_seclogin' , 'Login securitycode');
define('_config_hover_standard' , 'Show standard informations');
define('_config_hover_all' , 'Show all informations');
define('_config_hover_cw' , 'Show clanwar informations only');
define('_shout_must_reg' , 'Only for registered Users!');
define('_error_vote_show' , 'This is a public vote! Just internal votes can be shown detailed.');
define('_login_pwd_dont_match' , 'Loginname and/or password are invalid or account has been banned!');
define('_sq_aktiv' , 'Active');
define('_sq_inaktiv' , 'Inactive');
define('_sq_sstatus' , '<center>If checked, the team will be also shown in figtus form, etc</center>');
define('_internal' , 'Internal');
define('_sticky' , 'Important');
define('_lobby_new_cwc_1' , 'new clanwar comment');
define('_lobby_new_cwc_2' , 'new clanwar comments');
define('_admin_glossar_added' , 'The term was successfully registered!');
define('_admin_glossar_edited' , 'The term was successfully edited!');
define('_admin_glossar_deleted' , 'The term was successfully deleted!');
define('_admin_error_glossar_desc' , 'You have to write an explanation to the indicated term !');
define('_admin_error_glossar_word' , 'You have to indicate a term!');
define('_admin_glossar_add' , 'Insert term');
define('_config_glossar' , 'Glossary');
define('_config_gallery' , 'Gallery');
define('_glossar' , 'Glossary');
define('_admin_glossar' , 'Admin: Glossary');
define('_admin_fightus' , 'receive fightus form?');
define('_misc' , "Misc");
define('_all' , "All");
define('_glossar_link' , 'Click right here to read more informations about <span class=fontBold>[word]</span>!');
define('_glossar_head' , 'Glossary');
define('_glossar_bez' , 'Designation');
define('_glossar_erkl' , 'Explanation');
define('_admin_support_head' , 'Support informations');
define('_admin_support_info' , 'The following informations are very helpful if you ask a support-question in the board of <a href="http://www.dzcp.de" target="_blank">www.dzcp.de</a>.');
define('_config_support' , 'Supportinformations');
define('_search_con_or' , 'OR-Operation');
define('_search_con_and' , 'AND-Operation');
define('_search_head' , 'Search');
define('_search_word' , 'Search in...');
define('_search_forum_all' , 'Search in all boards');
define('_search_forum_hint' , '(Through press the \'Strg key\', more<br />boards can be selected seperately)');
define('_search_for_area' , 'Searcharea');
define('_search_type_full' , 'Complete search');
define('_search_type_title' , 'Search in topics only');
define('_search_type' , 'Search type');
define('_search_type_autor' , 'Find authors');
define('_search_type_text' , 'Search in text and topics');
define('_search_in' , 'Search in...');
define('_user_profile_of' , 'Userprofile from ');
define('_sites_not_available' , 'The requested site does not exist!');
define('_wrote' , 'wrote');
define('_voted_head' , 'Already participated in the vote');
define('_show_who_voted' , 'Show user, who voted already');
define('_no_live_status' , 'No live status');
define('_comment_edited' , 'The comment was successfully edited!');
define('_comments_edit' , 'Edit comment');
define('_forum_post_where_preview' , '<a href="javascript:void(0)">[mainkat]</a> <span class="fontBold">Board:</span> <a href="javascript:void(0)">[wherekat]</a> <span class="fontBold">Thread:</span> <a href="javascript:void(0)">[wherepost]</a>');
define('_aktiv_icon' , '<img src="../inc/images/active.gif" alt="" class="icon" />');
define('_inaktiv_icon' , '<img src="../inc/images/inactive.gif" alt="" class="icon" />');
define('_pn_write_forum' , '<a href="../user/?action=msg&amp;do=pn&amp;id=[id]"><img src="../inc/images/forum_pn.gif" alt="" title="write [nick] a message" class="icon" /></a>');
define('_uhr' , 'h');
define('_admin_editor' , 'Admin: Site administration');
define('_kalender_admin_head' , 'Calendar - Events');
define('_smileys_specialchar' , 'No special chars or empty space can be indicated in the bbcode!');
define('_award' , 'Award');
define('_preview' , 'Preview');
define('_error_edit_post' , 'You are not allowed to edit this entry!');
define('_nletter_prev_head' , 'Newsletter preview');
define('_error_downloads_upload' , 'There was an errow during the upload (filesize to big?)');
define('_news_comments_prev' , '<a href="javascript:void(0)">0 comments</a>');
define('_only_for_admins' , ' (visible for admins)');
define('_content' , 'Content');
define('_rootadmin' , 'Siteadmin');
define('_gb_edit_head' , 'Edit guestbook entry');
define('_gb_edited' , 'The guestbook entry was successfully edited!');
define('_nletter' , 'Newsletter');
define('_subject' , 'Subject');
define('_server_admin_qport' , 'Optionally: queryport');
define('_admin_server_nostatus' , 'No live status');
define('_nletter_head' , 'Write newsletter');
define('_squad', 'Team');
define('_confirm_del_cw' , 'You really want to delete this clanwar');
define('_confirm_del_vote' , 'You really want to delete this vote');
define('_confirm_del_dl' , 'You really want to deletethis download');
define('_confirm_del_galpic' , 'You really want to delete this picture');
define('_confirm_del_gallery' , 'You really want to delete this gallery');
define('_confirm_del_entry' , 'You really want to delete this entry');
define('_confirm_del_navi' , 'You really want to delete this link');
define('_confirm_del_profil' , 'You really want to delete this profile field?');
define('_confirm_del_smiley' , 'You really want to delete this smiley');
define('_confirm_del_kat' , 'You really want to delete this category');
define('_confirm_del_artikel' , 'You really want to delete this article');
define('_confirm_del_news' , 'You really want to delete this news');
define('_confirm_del_site' , 'You really want to delete this site');
define('_confirm_del_server' , 'You really want to delete this server');
define('_confirm_del_team' , 'You really want to delete this team');
define('_confirm_del_award' , 'You really want to delete this award');
define('_confirm_del_ranking' , 'You really want to delete this ranking');
define('_confirm_del_link' , 'You really want to delete this link');
define('_confirm_del_sponsor' , 'You really want to delete this sponsor');
define('_confirm_del_kalender' , 'You really want to delete this event');
define('_confirm_del_taktik' , 'SYou really want to delete this tactic');
define('_link_type' , 'Link type');
define('_sponsor' , 'Sponsor');
define('_config_fileeditor_head' , 'File editor');
//-----------------------------------------------
define('_main_info' , 'Here you can set global settings like default language, default template, title of the site, etc...');
define('_admin_eml_head' , 'Emailtemplates');
define('_admin_eml_info' , 'Here you can edit the emailtemplates from different areas.<br />Make sure that you do not delete the placeholders in the triggers [...]!');
define('_admin_reg_subj' , 'Subject: Registration');
define('_admin_pwd_subj' , 'Subject: Lost password');
define('_admin_nletter_subj' , 'Subject: Newsletter');
define('_admin_reg' , 'Template for registration');
define('_admin_pwd' , 'Template for lost password');
define('_admin_nletter' , 'Template for newsletter');
define('_result' , 'Result');
define('_opponent' , 'Opponent');
define('_played_at' , 'Played at');
define('_ulist_usuche' , 'Search user');
define('_login_secure_help' , 'To verify, put the two-digit number code in the inputfield.');
define('_online_head_guests' , 'Guests online');
define('_back_3' , '<a href="javascript: history.go(-3)" class="files">back</a>');
define('_filebrowser_head' , 'DZCP filebrowser');
define('_allowed_files' , 'Editable files');
define('_filebrowser_info_saved' , 'The file <b>[file]</b> was successfully stored!');
define('_filebrowser_info_restored' , 'The file <b>[file]</b> was successfully restored!');
define('_filebrowser_info_deleted' , 'The file <b>[file]</b> was successfully deleted!');
define('_legend_del' , 'Delete file');
define('_legend_ed' , 'Edit file');
define('_legend_res' , 'Restore file');
define('_admin_first' , 'at first');
define('_admin_squads_nav' , 'Navigation');
define('_admin_squad_show_info' , 'Defined weather a team in the team overview is shown or not shown by default.');
//Edited
define('_config_c_gallerypics_what' , 'Max. amount of pictures allowed in the users gallery');
define('_dl_getfile' , 'download [file] now');
define('_partners_link_add' , 'Insert partner button');
define('_config_forum_kats_add' , 'Insert category');
define('_config_c_lnews' , 'Menu: Last news');
define('_msg_new' , 'Write new message');
define('_gallery_show_admin' , 'Insert gallery');
define('_dl_titel' , '<span class="fontBold">[name]</span> - [cnt] [file]');
define('_config_artikel' , 'Article');
define('_config_forum' , 'Board categories');
define('_config_server' , 'Server');
define('_config_serverliste' , 'Serverlist');
define('_config_squads' , 'Teams');
define('_config_backup' , 'Backup database');
define('_config_news' , 'News-/Article categories');
define('_config_allgemein' , 'Configuration');
define('_config_impressum' , 'Imprint');
define('_config_clankasse' , 'Clan Cash');
define('_config_downloads' , 'Download categories');
define('_config_newsadmin' , 'News');
define('_config_filebrowser' , 'Filebrowser');
define('_config_navi' , 'Navigation');
define('_config_online' , 'Site administration');
define('_config_partners' , 'Partner buttons');
define('_config_clear' , 'Clear database');
define('_config_smileys' , 'Smiley editor');
define('_config_profile' , 'Profile fields');
define('_config_votes' , 'Votes');
define('_config_cw' , 'Clanwars');
define('_config_awards' , 'Awards');
define('_config_rankings' , 'Rankings');
define('_config_kalender' , 'Calendar');
define('_config_einst' , 'Attitudes');
define('_profil_sig' , 'Board signature');
define('_akt_version' , 'DZCP Version');
define('_forum_searchlink' , '- <a href="../search/">Board search</a> -');
define('_msg_deleted' , 'The message was successfully deleted!');
define('_info_reg_valid' , 'You successfully registered on this page!<br />
Your password were send to your e-mail adress [email]');
define('_edited_by' , '<br /><br /><i>last edited by [autor] at [time]</i>');
define('_linkus_empty_text' , 'You have to indicate an url of the banner!');
define('_gb_titel' , '<span class="fontBold">#[postid]</span> from [nick] [email] [hp] at [datum] on [zeit][uhr] [edit] [delete] [comment] [public]');
define('_gb_titel_noreg' , '<span class="fontBold">#[postid]</span> from <span class="fontBold">[nick]</span> [email] [hp] at [datum] on [zeit][uhr]  [edit] [delete] [comment] [public]');
define('_empty_news_title' , 'You have to indicate a news headline!');
define('_member_admin_votes' , 'View internal votes');
define('_member_admin_votesadmin' , 'Admin: Votes');
define('_msg_global_all' , 'all members');
define('_smileys_info' , 'You can also upload the smilies via FTP into the folder <span class="fontItalic">inc/images/smileys/</span>! The file name will be used for the bbcode, for example dzcp.gif = :dzcp:');
define('_pos_empty_kat' , 'You have to indicate a position!');
define('_forum_lastpost' , '<a href="?action=showthread&amp;id=[tid]&amp;page=[page]#p[id]"><img src="../inc/images/forum_lpost.gif" alt="" title="Go to the last entry" class="icon" /></a>');
define('_forum_addpost' , '<a href="?action=post&amp;do=add&amp;kid=[kid]&amp;id=[id]"><img src="../inc/images/forum_reply.gif" alt="" title="New entry" class="icon" /></a>');
define('_pn_write' , '<a href="../user/?action=msg&amp;do=pn&amp;id=[id]"><img src="../inc/images/pn.gif" alt="" title="Write [nick] a new message" class="icon" /></a>');
define('_forum_new_thread' , '<a href="?action=thread&amp;do=add&amp;kid=[id]"><img src="../inc/images/forum_new.gif" alt="" title="Insert thread" class="icon" /></a>');
define('_anm_head' , 'Note');
define('_anm_info' , 'Just editable files will be shown!');
//--------------------------------------------\\
define('_error_invalid_regcode' , 'The entered safety code does not agree with the character sequence indicated in the diagram!');
define('_welcome_guest' , ' <img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" /> <a class="welcome" href="../user/?action=register">Guest</a>');
define('_online_head' , 'User online');
define('_online_whereami' , 'Area');
define('_back' , '<a href="javascript: history.go(-1)" class="files">back</a>');
define('_contact_text_fightus' , '
Someone filled out the fightus contactform!<br />
Each clanwar admin received this message!<br /><br />
<span class="fontBold">Team:</span> [squad]<br /><br />
<span class="fontUnder"><span class="fontBold">Contact:</span></span><br />
<span class="fontBold">Nick:</span> [nick]<br />
<span class="fontBold">Email:</span> [email]<br />
<span class="fontBold">ICQ-Nr.:</span> [icq]<br /><br />
<span class="fontBold"><span class="fontUnder">Clandata:</span></span><br />
<span class="fontBold">Clan name:</span> [clan]<br />
<span class="fontBold">Homepage:</span> [hp]<br />
<span class="fontBold">Game:</span> [game]<br />
<span class="fontBold">XonX:</span> [us] vs. [to]<br />
<span class="fontBold">Our Map:</span> [map]<br />
<span class="fontBold">Date:</span> [date]<br /><span class="fontUnder">
<span class="fontBold">Comment:</span></span><br />[text]');
## EDITED/ADDED FOR v 1.3.3
define('_cw_info' , 'The admin for this area will receive the fightus contactform, too!');
define('_level_info' , 'By set the level "admin", the level can be unset by root admin only! (the one who installed the clanportal)!<br />Furthermore the owner this level has <span class="fontUnder">unrestricted</span> access to all administrative areas!');
## EDITED FOR v 1.3.1
define('_related_links' , 'related links:');
define('_cw_admin_lineup_info' , 'Seperate names with a comma!');
define('_profil_email2' , 'E-mail #2');
define('_profil_email3' , 'E-mail #3');
## Allgemein ##
define('_button_title_del' , 'Delete');
define('_button_title_edit' , 'Edit');
define('_button_title_zitat' , 'Quote this entry');
define('_button_title_comment' , 'Commentate this entry');
define('_button_title_menu' , 'Set to menu');
define('_button_value_add' , 'Insert');
define('_button_value_addto' , 'Insert');
define('_button_value_edit' , 'Edit');
define('_button_value_search' , 'Search');
define('_button_value_search1' , 'Start search');
define('_button_value_upload' , 'Upload');
define('_button_value_vote' , 'Vote');
define('_button_value_show' , 'Show');
define('_button_value_send' , 'Send');
define('_button_value_reg' , 'Register');
define('_button_value_msg' , 'Send message');
define('_button_value_nletter' , 'Send newsletter');
define('_button_value_config' , 'Store configuration');
define('_button_value_clear' , 'Clear database');
define('_button_value_save' , 'Save');
define('_editor_from' , 'From');
define('intern' , '<span class="fontWichtig">Internal</span>');
define('_comments_head' , 'Comments');
define('_click_close' , 'close');
## Begruessungen ##
define('_welcome_18' , 'Good evening,');
define('_welcome_13' , 'Good day,');
define('_welcome_11' , 'Good lunch,');
define('_welcome_5' , 'Good morning,');
define('_welcome_0' , 'Good night,');
## Monate ##
define('_jan' , 'January');
define('_feb' , 'February');
define('_mar' , 'March');
define('_apr' , 'April');
define('_mai' , 'May');
define('_jun' , 'June');
define('_jul' , 'July');
define('_aug' , 'August');
define('_sep' , 'September');
define('_okt' , 'October');
define('_nov' , 'November');
define('_dez' , 'Dezember');
## Laenderliste ##
define('_country_list' , '
<option value="al"> Albania</option>
<option value="dz"> Algeria</option>
<option value="ao"> Angola</option>
<option value="ar"> Argentinia</option>
<option value="am"> Armenia</option>
<option value="aw"> Aruba</option>
<option value="au"> Australia</option>
<option value="at"> Austria</option>
<option value="az"> Azerbaijan</option>
<option value="bs"> Bahamas</option>
<option value="bh"> Bahrain</option>
<option value="bd"> Bangladesh</option>
<option value="bb"> Barbados</option>
<option value="be"> Belgium</option>
<option value="bz"> Belize</option>
<option value="bj"> Benin</option>
<option value="bm"> Bermuda</option>
<option value="bt"> Bhutan</option>
<option value="bo"> Bolivia</option>
<option value="ba"> Bosnia Herzegovina</option>
<option value="bw"> Botswana</option>
<option value="br"> Brazil</option>
<option value="bn"> Brunei Darussalam</option>
<option value="bg"> Bulgaria</option>
<option value="bf"> Burkina Faso</option>
<option value="bi"> Burundi</option>
<option value="ca"> Canada</option>
<option value="cv"> Cape Verde</option>
<option value="ky"> Cayman Islands</option>
<option value="cf"> Central African republic</option>
<option value="cl"> Chile</option>
<option value="cn"> China</option>
<option value="co"> Colombia</option>
<option value="cg"> Congo</option>
<option value="ck"> Cook Islands</option>
<option value="cr"> Costa Rica</option>
<option value="ci"> Cote D"Ivoire</option>
<option value="hr"> Croatia</option>
<option value="cu"> Cuba</option>
<option value="cy"> Cyprus</option>
<option value="cz"> Czech Republic</option>
<option value="dk"> Denmark</option>
<option value="tp"> East Timor</option>
<option value="ec"> Ecuador</option>
<option value="eg"> Egypt</option>
<option value="er"> Eritrea</option>
<option value="ee"> Estonia</option>
<option value="et"> Ethiopia</option>
<option value="fo"> Faroer islands</option>
<option value="fj"> Fiji</option>
<option value="fi"> Finland</option>
<option value="fr"> France</option>
<option value="pf"> French Polynesia</option>
<option value="ga"> Gabon</option>
<option value="ge"> Georgia</option>
<option value="de"> Germany</option>
<option value="gi"> Gibraltar</option>
<option value="gr"> Greece</option>
<option value="uk"> Great Britain</option>
<option value="gl"> Greenland</option>
<option value="gp"> Guadeloupe</option>
<option value="gu"> Guam</option>
<option value="gt"> Guatemala</option>
<option value="gy"> Guyana</option>
<option value="ht"> Haiti</option>
<option value="hk"> Hong Kong</option>
<option value="hu"> Hungary</option>
<option value="is"> Iceland</option>
<option value="in"> India</option>
<option value="id"> Indonesia</option>
<option value="ir"> Iran</option>
<option value="iq"> Iraq</option>
<option value="ie"> Ireland</option>
<option value="il"> Israel</option>
<option value="it"> Italia</option>
<option value="jm"> Jamaica</option>
<option value="jp"> Japan</option>
<option value="jo"> Jordan</option>
<option value="kh"> Kambodscha</option>
<option value="cm"> Kamerun</option>
<option value="qa"> Katar</option>
<option value="kz"> Kazachstan</option>
<option value="ke"> Kenya</option>
<option value="ki"> Kiribati</option>
<option value="kg"> Kyrgyzstan</option>
<option value="lv"> Latvia</option>
<option value="lb"> Lebanon</option>
<option value="ly"> Lybia</option>
<option value="li"> Liechtenstein</option>
<option value="lt"> Lithuania</option>
<option value="lu"> Luxembourg</option>
<option value="mo"> Macau</option>
<option value="mk"> Macedonia</option>
<option value="mg"> Madagascar</option>
<option value="my"> Malaysia</option>
<option value="mx"> Mexico</option>
<option value="md"> Moldova</option>
<option value="mc"> Monaco</option>
<option value="mn"> Mongolia</option>
<option value="ms"> Montserrat</option>
<option value="ma"> Marocco</option>
<option value="mz"> Mozambique</option>
<option value="na"> Namibia</option>
<option value="nr"> Nauru</option>
<option value="np"> Nepal</option>
<option value="nl"> Netherlands</option>
<option value="an"> Netherlands Antilles</option>
<option value="nc"> New Caledonia</option>
<option value="nz"> New Zealand</option>
<option value="kp"> North Korea</option>
<option value="nf"> Norfolk Island</option>
<option value="mp"> Northern Marianen</option>
<option value="no"> Norway</option>
<option value="om"> Oman</option>
<option value="pk"> Pakistan</option>
<option value="pa"> Panama</option>
<option value="py"> Paraguay</option>
<option value="pe"> Peru</option>
<option value="ph"> Philippines</option>
<option value="pl"> Poland</option>
<option value="pt"> Portugal</option>
<option value="pr"> Puerto Rico</option>
<option value="ro"> Romania</option>
<option value="ru"> Russia</option>
<option value="lc"> Saint Lucia</option>
<option value="pm"> Saint Pierre and Miquelon</option>
<option value="ws"> Samoa</option>
<option value="sa"> Saudi Arabien</option>
<option value="sx"> Scottland</option>
<option value="sl"> Sierra Leone</option>
<option value="sg"> Singapur</option>
<option value="sk"> Slovakia</option>
<option value="si"> Slovenia</option>
<option value="sb"> Solomon Islands</option>
<option value="so"> Somalia</option>
<option value="za"> South Afrika</option>
<option value="kr"> South Korea</option>
<option value="es"> Spain</option>
<option value="lk"> Sri Lanka</option>
<option value="sd"> Sudan</option>
<option value="sr"> Suriname</option>
<option value="se"> Sweden</option>
<option value="ch"> Switzerland</option>
<option value="sy"> Syria</option>
<option value="tw"> Taiwan</option>
<option value="tz"> Tanzania</option>
<option value="th"> Thailand</option>
<option value="tg"> Togo</option>
<option value="to"> Tonga</option>
<option value="tt"> Trinidad and Tobago</option>
<option value="tn"> Tunisia</option>
<option value="tr"> Turkey</option>
<option value="tc"> Turks and Caicos Islands</option>
<option value="tv"> Tuvalu</option>
<option value="ug"> Uganda</option>
<option value="ua"> Ukraine</option>
<option value="uy"> Uruguay</option>
<option value="us"> USA</option>
<option value="ve"> Venezuela</option>
<option value="va"> Vatikan</option>
<option value="ae"> United Arab Emirates</option>
<option value="vn"> Vietnam</option>
<option value="vg"> Virgin Islands, Britisch</option>
<option value="vi"> Virgin Islands, U.S.</option>
<option value="by"> White Russia</option>
<option value="yu"> Yugoslavia</option>
<option value="ye"> Yemen</option>
<option value="zm"> Zambia</option>');
## Globale Userraenge ##
define('_status_banned' , 'banned');
define('_status_unregged' , 'unregistered');
define('_status_user' , 'User');
define('_status_trial' , 'Trial');
define('_status_member' , 'Member');
define('_status_admin' , 'Admin');
## Userliste ##
define('_acc_banned' , 'Banned');
define('_ulist_acc_banned' , 'Banned accounts');
## Login ##
define('_login_login' , 'LogIn!');
## Navigation: Kalender ##
define('_kal_birthday' , 'Birthday from ');
define('_kal_cw' , 'Clanwar against ');
define('_kal_event' , 'Event: ');
## LinkUs ##
//-> Allgemein
define('_linkus_head' , 'Linkus');
//-> Admin
define('_linkus_admin_head' , 'Insert linkus');
define('_linkus_link' , 'Target link');
define('_linkus_bsp_target' , 'http://www.domain.tld');
define('_linkus_bsp_bannerurl' , 'http://www.domain.tld/banner.jpg');
define('_linkus_bsp_desc' , 'Description');
define('_linkus_beschreibung' , 'Title');
define('_linkus_text' , 'Banner link');
define('_linkus_empty_beschreibung' , 'You have to indicate a title tag!');
define('_linkus_empty_link' , 'You have to indivate an link url!');
define('_linkus_added' , 'The linkus was successfully registered!');
define('_linkus_edited' , 'The linkus was successfully edited!');
define('_linkus_deleted' , 'The linkus was successfully deleted!');
define('_linkus' , 'Linkus');
## News ##
define('_news_kommentar' , 'Comment');
define('_news_kommentare' , 'Comments');
define('_news_viewed' , '[<span class="fontItalic">[viewed] Hits</span>]');
define('_news_archiv' , '<a href="?action=archiv">Archive</a>');
define('_news_comment' , '<a href="?action=show&amp;id=[id]">[comments] Comment</a>');
define('_news_comments' , '<a href="?action=show&amp;id=[id]">[comments] Comments</a>');
define('_news_comments_write_head' , 'Write new comment');
define('_news_archiv_sort' , 'Sort by');
define('_news_archiv_head' , 'News archive');
define('_news_kat_choose' , 'Choose category');
## Artikel ##
define('_artikel_comments_write_head' , 'Write new comment');
## Forum ##
define('_forum_head' , 'Board');
define('_forum_topic' , 'Topic');
define('_forum_subtopic' , 'Subtitle');
define('_forum_lpost' , 'Last entry');
define('_forum_threads' , 'Threads');
define('_forum_thread' , 'Thread');
define('_forum_posts' , 'Posts');
define('_forum_cnt_threads' , '<span class="fontBold">Amount of threads:</span> [threads]');
define('_forum_cnt_posts' , '<span class="fontBold">Amount of Posts:</span> [posts]');
define('_forum_admin_head' , 'Admin');
define('_forum_admin_addsticky' , 'mark as <span class="fontWichtig">important</span>?');
define('_forum_katname_intern' , '<span class="fontWichtig">Internal:</span> [katname]');
define('_forum_sticky' , '<span class="fontWichtig">Important:</span>');
define('_forum_subkat_where' , '<a href="../forum/">[mainkat]</a> <span class="fontBold">Board:</span> <a href="?action=show&amp;id=[id]">[where]</a>');
define('_forum_head_skat_search' , 'Search in this category');
define('_forum_head_threads' , 'Threads');
define('_forum_replys' , 'Answers');
define('_forum_thread_lpost' , 'from [nick]<br />at [date]');
define('_forum_new_thread_head' , 'Insert thread');
define('_empty_topic' , 'You have to indicate a topic!');
define('_forum_newthread_successful' , 'The thread was successfully registered to the board!');
define('_forum_new_post_head' , 'Add new post');
define('_forum_newpost_successful' , 'The post was successfully registered to the board!');
define('_posted_by' , '<span class="fontBold">&raquo;</span> ');
define('_forum_post_where' , '<a href="../forum/">[mainkat]</a> <span class="fontBold">Board:</span> <a href="?action=show&amp;id=[kid]">[wherekat]</a> <span class="fontBold">Thread:</span> <a href="?action=showthread&amp;id=[tid]">[wherepost]</a>');
define('_forum_lpostlink' , 'Last post');
define('_forum_user_posts' , '<span class="fontBold">posts:</span> [posts]');
define('_sig' , '<br /><br /><hr />');
define('_error_forum_closed' , 'This thread is closed!');
define('_forum_search_head' , 'Board search');
define('_forum_edit_post_head' , 'Edit post');
define('_forum_edit_thread_head' , 'Edit thread');
define('_forum_editthread_successful' , 'The thread was successfully edited!');
define('_forum_editpost_successful' , 'The entry was successfully edited!');
define('_forum_delpost_successful' , 'The entry was successfully deleted!');
define('_forum_admin_open' , 'Thread is opened');
define('_forum_admin_delete' , 'Delete thread?');
define('_forum_admin_close' , 'Thread is closed');
define('_forum_admin_moveto' , 'Move thread to:');
define('_forum_admin_thread_deleted' , 'The thread was successfully deleted!');
define('_forum_admin_do_move' , 'The thread was successfully edited<br />and moved into the category <span class="fontWichtig">[kat]</span>!');
define('_forum_admin_modded' , 'The thread was successfully edited!');
define('_forum_search_what' , 'Search for');
define('_forum_search_kat' , 'in category');
define('_forum_search_suchwort' , 'Keywords');
define('_forum_search_inhalt' , 'Content');
define('_forum_search_kat_all' , 'all Categories');
define('_forum_search_results' , 'Search results');
define('_forum_online_head' , 'Browsing the board');
define('_forum_nobody_is_online' , 'Right now no user is browsing the board!');
define('_forum_nobody_is_online2' , 'Right now no user except you is browsing the board!');
## Gaestebuch ##
define('_gb_delete_successful' , 'The entry was successfully deleted!');
define('_gb_head' , 'Guestbook');
define('_gb_add_head' , 'Insert entry');
define('_gb_eintragen' , '<a href="#eintragen">Insert</a>');
define('_gb_entry_successful' , 'Your entry in the guestbook was required for the release of a competent admin redirected!');
define('_gb_addcomment_head' , 'Comment');
define('_gb_addcomment_headgb' , 'New entry');
define('_gb_comment_added' , 'The comment was successfully registered!');
## Kalender ##
//-> Allgemein
define('_kalender_head' , 'Calendar');
define('_kalender_month_select' , '<option value="[i]" [sel]>[month]</option>');
define('_kalender_year_select' , '<option value="[i]" [sel]>[year]</option>');
define('_montag' , 'Monday');
define('_dienstag' , 'Tuesday');
define('_mittwoch' , 'Wednesday');
define('_donnerstag' , 'Thursday');
define('_freitag' , 'Friday');
define('_samstag' , 'Saturday');
define('_sonntag' , 'Sunday');
//-> Events
define('_kalender_events_head' , 'Events at [datum]');
define('_kalender_uhrzeit' , 'Time');
//-> Admin
define('_kalender_admin_head_add' , 'Insert event');
define('_kalender_admin_head_edit' , 'Edit event');
define('_kalender_event' , 'Event');
define('_kalender_error_no_time' , 'You have to indcate the date and time for this event!');
define('_kalender_error_no_title' , 'You have to indicate a title!');
define('_kalender_error_no_event' , 'You have to describe this event!');
define('_kalender_successful_added' , 'The event was successfully registered!');
define('_kalender_successful_edited' , 'The event was successfully edited!');
define('_kalender_deleted' , 'The event was successfully deleted!');
## Umfragen ##
define('_error_vote_closed' , 'This vote is closed!');
define('_votes_admin_closed' , 'Close vote');
define('_votes_head' , 'Votes');
define('_votes_stimmen' , 'Voted');
define('_votes_intern' , '<span class="fontWichtig">Internal:</span> ');
define('_votes_results_head' , 'Vote results');
define('_votes_results_head_vote' , 'Answers');
define('_vote_successful' , 'You successfully participated in this vote!');
define('_votes_admin_head' , 'Insert Vote');
define('_votes_admin_question' , 'Question');
define('_votes_admin_answer' , 'Possible answers');
define('_empty_votes_question' , 'You have to indicate a question!');
define('_empty_votes_answer' , 'You have to indicate at least 2 answers!');
define('_votes_admin_intern' , 'internal vote');
define('_vote_admin_successful' , 'The vote was successfully registered!');
define('_vote_admin_delete_successful' , 'The vote was successfully deleted!');
define('_vote_admin_successful_menu' , 'The vote is successfully registered into the menu!');
define('_vote_admin_menu_isintern' , 'It`s impossible to set an internal vote into the menu!');
define('_vote_legendemenu' , 'Vote set into menu?<br />(Press icon to set/unset vote)');
define('_votes_admin_edit_head' , 'Edit vote');
define('_vote_admin_successful_edited' , 'The vote was successfully edited!');
define('_vote_admin_successful_menu1' , 'The menu was successfully unset from the menu!');
define('_error_voted_again' , 'You already participated in this vote!');
## Links/Sponsoren ##
define('_links_head' , 'Links');
define('_links_admin_head' , 'Insert link');
define('_links_admin_head_edit' , 'Edit link');
define('_links_link' , 'URL');
define('_links_beschreibung' , 'Description');
define('_links_art' , 'Type');
define('_links_admin_textlink' , 'Textlink');
define('_links_admin_bannerlink' , 'Bannerlink');
define('_links_text' , 'Banner URL');
define('_links_empty_beschreibung' , 'You have to indicate a description!');
define('_links_empty_link' , 'You have to indicate an url!');
define('_link_added' , 'The link was successfully registered!');
define('_link_edited' , 'The link was successfully edited!');
define('_link_deleted' , 'The link was successfully deleted!');
define('_sponsor_head' , 'Sponsors');
## Downloads ##
define('_downloads_head' , 'Downloads');
define('_downloads_download' , 'Download');
define('_downloads_admin_head' , 'Insert Download');
define('_downloads_nofile' , '<option value="lazy">- no file -</option>');
define('_downloads_admin_head_edit' , 'Edit download');
define('_downloads_lokal' , 'lokal file');
define('_downloads_exist' , 'File');
define('_downloads_name' , 'Download name');
define('_downloads_url' , 'File');
define('_downloads_kat' , 'Categorie');
define('_downloads_empty_download' , 'You have to indicate a download name!');
define('_downloads_empty_url' , 'You have to indicate a file!');
define('_downloads_empty_beschreibung' , 'You have to indicate a description!');
define('_downloads_added' , 'The download was successfully registered!');
define('_downloads_edited' , 'The download was successfully edited!');
define('_downloads_deleted' , 'The download was successfully deleted!');
define('_dl_info' , 'Download informations');
define('_dl_file' , 'File');
define('_dl_besch' , 'Descriptopm');
define('_dl_info2' , 'File informations');
define('_dl_size' , 'Filesize');
define('_dl_speed' , 'Download speed');
define('_dl_traffic' , 'Caused traffic');
define('_dl_loaded' , 'Downloaded');
define('_dl_date' , 'Upload date');
define('_dl_wait' , 'Download of file: ');
## Teams ##
define('_member_squad_head' , 'Teams');
define('_member_squad_no_entrys' , '<tr><td align="center"><span class="fontBold">No registered members</span></td></tr>');
define('_member_squad_weare' , 'Alltogether we are <span class="fontBold">[cm] members</span>, seperated in <span class="fontBold">[cs] team(s)</span>');
## Clanwars ##
define('_cw_comments_head' , 'Comments');
define('_cw_comments_add' , 'Write new comment');

define('_cw_head_details' , 'Clanwar details');
define('_cw_head_results' , 'Results');
define('_cw_head_lineup' , 'Lineup');
define('_cw_head_glineup' , 'Opponent Lineup');
define('_cw_head_admin' , 'Admin(s)');
define('_cw_head_squad' , 'Team');
define('_cw_bericht' , 'Report');
define('_cw_maps' , 'Maps');
define('_cw_serverpwd' , '
<tr>
  <td class="contentMainTop"><span class="fontBold">Serverpassword:</span></td>
  <td class="contentMainFirst" colspan="2" align="center">[cw_serverpwd]</td>
</tr>');
define('_cw_players_head' , 'Player status');
define('_cw_status_set' , 'Your status was successfully set!');
define('_cw_players_play' , 'Do you want to play?');
define('_cw_player_dont_want' , '<span class="fontRed">won`t play</span>');
define('_cw_player_want' , '<span class="fontGreen">want to play</span>');
define('_cw_player_dont_know' , 'won`t now it yet');
define('_cw_admin_head' , 'Insert clanwar');
define('_cw_admin_head_edit' , 'Edit clanwar');
define('_cw_admin_info' , 'As no result is indicated, the clanwar will be shown as "next war"!');
define('_cw_admin_gegnerstuff' , 'Opponent informations');
define('_cw_admin_clantag' , 'Clantag');
define('_cw_admin_warstuff' , 'Clanwar informations');
define('_cw_admin_maps' , 'Maps');
define('_cw_admin_serverip' , 'ServerIP');
define('_cw_admin_empty_gegner' , 'You have to indicate the opponent`s name!');
define('_cw_admin_empty_clantag' , 'You have to indicate the opponent`s clantag!');
define('_cw_admin_deleted' , 'The clanwar was successfully deleted!');
define('_cw_admin_added' , 'The clanwar was successfully registered!');
define('_cw_admin_edited' , 'The clanwar was successfully edited!');
define('_cw_admin_head_squads' , 'Team informations');
define('_cw_admin_head_country' , 'Country');
define('_cw_head_statstik' , 'Statistic');
define('_cw_gespunkte' , 'Total score');
define('_cw_stats_ges_wars' , '<span class="fontText">Our clan totally played <span class="fontBold">[ge_wars]</span> clanwar(s).</span>');
define('_cw_stats_ges_wars_sq' , '<span class="fontText">This team totally played <span class="fontBold">[ge_wars]</span> clanwar(s).</span>');
define('_cw_stats_ges_points' , 'Total score: <span class="CwWon">[ges_won]</span> : <span class="CwLost">[ges_lost]</span>');
define('_cw_stats_spiele_squads' , 'We alltogether play with <span class="fontBold">[anz_squads]</span> team(s), seperated into <span class="fontBold">[anz_games]</span> Game(s).');
define('_cw_stats_won_head' , 'Won');
define('_cw_stats_lost_head' , 'Lost');
define('_cw_stats_draw_head' , 'Draw');
define('_cw_head_clanwars' , 'Clanwars');
define('_cw_head_game' , 'Game');
define('_cw_head_datum' , 'Date');
define('_cw_head_gegner' , 'Opponent');
define('_cw_head_liga' , 'League');
define('_cw_head_gametype' , 'Type');
define('_cw_head_xonx' , 'XonX');
define('_cw_head_result' , 'Points');
define('_cw_head_details_show' , 'Details');
define('_cw_head_page' , 'Site: ');
define('_cw_head_legende' , 'Legend');
define('_cw_nothing' , '<option value="lazy" class="" class="dropdownKat">--- change nothing ---</option>');
define('_cw_screens' , 'Screenshots');
define('_cw_new' , 'New');
define('_cw_screens_info' , 'jpg, gif and png files only!');
define('_clanwars_no_show' , 'No registered clanwar yet!');
define('_cw_show_all' , '
<tr>
  <td class="contentMainFirst" colspan="8" align="center"><a href="../clanwars/?action=showall&amp;id=[id]">Show all clanwars of this team</a></td>
</tr>');
## Awards ##
define('_awards_head' , 'Awards');
define('_awards_head_squad' , 'Team');
define('_awards_head_date' , 'Date');
define('_awards_head_place' , 'Place');
define('_awards_head_prize' , 'Prize');
define('_awards_head_event' , 'Event');
define('_awards_head_link' , 'Link');
define('_awards_no_show' , 'No award registered yet!');
define('_list_all_link' , '<tr><td colspan ="7" class="contentMainTop" align="center"><a href="../awards/?action=showall&amp;id=[id]">Show all awards of this team</td></tr>');
define('_head_stats' , 'Statistic');
define('_awards_stats' , '<center>We alltogether won <span class="fontBold">[anz] awards</span>!</center>');
define('_awards_stats_1' , '<span class="fontBold">[anz]x</span> Place no.1');
define('_awards_stats_2' , '<span class="fontBold">[anz]x</span> Place no.2');
define('_awards_stats_3' , '<span class="fontBold">[anz]x</span> Place no.3');
define('_awards_empty_url' , 'You have to indicate a link!');
define('_awards_empty_event' , 'You have to indicate an event!');
define('_awards_admin_head_add' , 'Insert award');
define('_awards_admin_added' , 'The award was successfully registered!');
define('_awards_admin_head_edit' , 'Edit award');
define('_awards_admin_edited' , 'The award was successfully edited!');
define('_awards_admin_deleted' , 'The award was successfully deleted!');
define('_awards_head_legende' , 'Legend');
## Rankings ##
define('_error_empty_league' , 'You have to indicate a league!');
define('_error_empty_url' , 'You have to indicate a team link!');
define('_error_empty_rank' , 'You have to indicate a rank!');
## Server ##
define('_banned_reason' , 'Reason');
define('_banned_head' , 'Bannlist');
define('_banned_gesamt' , '<span class="fontText">Totally</span> <span class="fontBold">[ges] users</span> <span class="fontText">are inthe banlist</span>');
define('_banned_edit_head' , 'Edit banlist');
define('_error_banned_edited' , 'The banned user was successfully edited!');
define('_server_head' , 'Server');
define('_server_name' , 'Server name');
define('_server_pwd' , '<span class="fontBold">Password:</span> [pwd]<br />');
define('_server_ip' , 'IP');
define('_server_players' , 'Player');
define('_server_aktmap' , 'curr. map');
define('_server_frags' , 'Frags');
define('_server_time' , 'Time played');
define('_server_noplayers' , '
<tr>
  <td class="contentMainFirst" align="center" colspan="2"><span class="fontBold">No players on the server</span></td>
</tr>');
define('_server_no_connection' , '
<tr>
  <td class="contentMainFirst" align="center" colspan="2">Could not connect to the server</td>
</tr>');
define('_server_splayerstats' , 'Player statistics');
define('_generated_time' , 'This site was generated in [time] seconds');
define('_slist_head' , 'Serverlist');
define('_slist_serverip' , 'Serverip');
define('_slist_slots' , 'Slots');
define('_slist_add' , 'Insert server');
define('_slist_serverport' , 'Serverport');
define('_server_password' , 'Serverpassword');
define('_error_server_saved' , 'Your server was successfully registered!<br /> An admin will proof this entry soon.');
define('_error_empty_slots' , 'You have to indicate the amount of your slots!');
define('_error_empty_ip' , 'You have to indicate your serverip!');
define('_error_empty_port' , 'You have to indicate your serverport!');
define('_slist_added_msg' , 'New entry in the serverlist!');
define('_slist_title' , 'Serverlist');
define('_gt_search' , 'Search for');
define('_gt_server' , 'Server');
define('_gt_maps' , 'Maps');
define('_gt_map' , 'Map');
define('_gt_player' , 'Player');
define('_gt_addip' , 'Click in the serverip to add it to hlsw.');
define('_gt_addname' , 'Click on the server name to add it to hlsw');
define('_gt_not_found' , '<tr><td class="contentMainFirst" colspan="8" align="center">- no result -</td></tr>');
define('_gt_psearchhead' , 'Player searc');
define('_gt_sip' , 'Serverip');
define('_gt_found' , 'Results:');
define('_gt_msearchhead' , 'Map search');
define('_gt_server_ip' , 'Servername');
define('_gt_ssearchhead' , 'Server search');
define('_gallery_head' , 'Galleries');
define('_subgallery_head' , 'Gallery');
define('_gallery_images' , 'Pictures');
define('_gal_back' , 'back');
define('_gallery_admin_head' , 'Insert gallery');
define('_gallery_gallery' , 'Gallery description');
define('_gallery_count' , 'Amount of pictures');
define('_gallery_count_new' , 'Amount of new pictures');
define('_gallery_added' , 'The gallery was successfully registered!');
define('_error_gallery' , 'You have to indicate a gallery description!');
define('_gallery_image' , 'Picture');
define('_gallery_deleted' , 'The gallery was successfully deleted!');
define('_gallery_edited' , 'The gallery was successfully edited!');
define('_gallery_admin_edit' , 'Edit gallery');
define('_gallery_pic_deleted' , 'The picture was successfully deleted!');
define('_gallery_new' , 'The picture was successfully added to this gallery!');
define('_button_value_newgal' , 'Insert more pictures');
define('_contact_pflichtfeld' , '<span class="fontWichtig">*</span> = Required field');
define('_contact_nachricht' , 'Message');
define('_contact_sended' , 'Your message was successfully sent to the site`s admin!');
define('_contact_title' , 'Contact form');
define('_contact_text' , '
Somebody filled out the contact form!<br /><br />
<span class="fontBold">Nick:</span> [nick]<br />
<span class="fontBold">Email:</span> [email]<br />
<span class="fontBold">ICQ-Nr.:</span> [icq]<br />
<span class="fontBold">Skype:</span> [skype]<br />
<span class="fontBold">Steam:</span> [steam]<br /><br />
<span class="fontUnder"><span class="fontBold">Message:</span></span><br />[text]');
define('_contact_joinus' , 'Joinus-Text');
define('_contact_joinus_why' , 'Describe shortly, why you want to join our clan.');
define('_contact_title_joinus' , 'Joinus form');
define('_contact_text_joinus' , '
Somebody filled out the joinus form!<br /><br />
<span class="fontBold">Nick:</span> [nick]<br />
<span class="fontBold">Age:</span> [age]<br />
<span class="fontBold">Email:</span> [email]<br />
<span class="fontBold">ICQ-Nr.:</span> [icq]<br />
<span class="fontBold">Skype:</span> [skype]<br />
<span class="fontBold">Steam:</span> [steam]<br /><br />
<span class="fontBold">Team:</span> [squad]<br /><br />
<span class="fontUnder"><span class="fontBold">Message:</span></span><br />[text]');
define('_contact_joinus_no_squad_aviable', 'No team aviable');
define('_contact_joinus_sended' , 'Your inquiry was successfully sent to the site`s admin!');
define('_contact_fightus' , 'Comment');
define('_contact_title_fightus' , 'Fightus form');
define('_contact_fightus_sended' , 'Your inquiry was successfully sent to the team`s admin!');
define('_contact_fightus_partner' , 'Point of contact');
define('_contact_fightus_clandata' , 'Clan data');
define('_contact_fightus_clanname' , 'Clan name');
define('_fightus_maps' , 'Your map');
define('_empty_fightus_map' , 'You have to indicate the map, which you want to play!');
define('_empty_fightus_game' , 'You have to indicate the game which you want to play!!');
## Statistiken ##
define('_site_stats' , 'Statistics');
define('_stats' , 'Statistics');
define('_stats_nkats' , 'Categories');
define('_stats_news' , 'written news');
define('_stats_comments' , 'written comments');
define('_stats_cpern' , '&oslash; comments per news');
define('_stats_npert' , '&oslash; news per day');
define('_stats_gb_all' , 'Total entrys');
define('_stats_gb_poster' , 'Entrys by guests/reg. user');
define('_stats_gb_first' , 'First entry');
define('_stats_gb_last' , 'Last entry');
define('_from' , 'from');
define('_stats_forum_ppert' , '&oslash; Entrys per thread');
define('_stats_forum_pperd' , '&oslash; Entrys per day');
define('_stats_forum_top' , 'Top poster');
define('_stats_users_regged' , 'reg. user');
define('_stats_users_regged_member' , '&nbsp;&nbsp;- therefrom member');
define('_stats_users_logins' , 'Total logins');
define('_stats_users_msg' , 'Messages sended');
define('_stats_users_buddys' , 'Buddies');
define('_stats_users_votes' , 'participated votes');
define('_stats_users_aktmsg' , '&nbsp;&nbsp;- therefrom in circulation');
define('_stats_cw_played' , 'played clanwars');
define('_stats_cw_won' , '&nbsp;&nbsp;- therefrom won');
define('_stats_cw_draw' , '&nbsp;&nbsp;- therefrom draw');
define('_stats_cw_lost' , '&nbsp;&nbsp;- therefrom lost');
define('_stats_cw_points' , 'Total points');
define('_stats_place' , '&nbsp;&nbsp;- therefrom place');
define('_stats_place_misc' , '&nbsp;&nbsp;- therefrom misc places');
define('_stats_awards' , 'Won awards');
define('_stats_mysql' , 'MySQL-Database');
define('_stats_mysql_size' , 'Database size');
define('_stats_mysql_entrys' , 'Tables');
define('_stats_mysql_rows' , 'Total entrys');
define('_site_stats_files' , 'Files');
define('_stats_hosted' , 'own hosted files');
define('_stats_dl_size' , 'Total size');
define('_stats_dl_traffic' , 'Totally caused traffic');
define('_stats_dl_hits' , 'Totally downloaded');
## User ##
define('_profil_head' , '<span class="fontBold">Userprofile from [nick]</span> [[profilhits] times viewed]');
define('_login_head' , 'Login');
define('_new_pwd' , 'new password');
define('_register_head' , 'Registration');
define('_register_confirm' , 'Securitycode');
define('_register_confirm_add' , 'Enter code');
define('_lostpwd_head' , 'Send password');
define('_profil_edit_head' , 'Edit profile from [nick]');
define('_profil_clan' , 'Clan');
define('_profil_pic' , 'Picture');
define('_profil_contact' , 'Contact');
define('_profil_hardware' , 'Hardware');
define('_profil_about' , 'About me');
define('_profil_real' , 'Name');
define('_profil_city' , 'City');
define('_profil_bday' , 'Birthday');
define('_profil_age' , 'Age');
define('_profil_hobbys' , 'Hobbys');
define('_profil_motto' , 'Slogan');
define('_profil_hp' , 'Homepage');
define('_profil_sex' , 'Sex');
define('_profil_board' , 'Mainboard');
define('_profil_cpu' , 'CPU');
define('_profil_ram' , 'RAM');
define('_profil_graka' , 'Videocard');
define('_profil_monitor' , 'Monitor');
define('_profil_maus' , 'Mouse');
define('_profil_mauspad' , 'Mousepad');
define('_profil_hdd' , 'HDD');
define('_profil_headset' , 'Headset');
define('_profil_os' , 'System');
define('_profil_inet' , 'Internet');
define('_profil_job' , 'Job');
define('_profil_position' , 'Position');
define('_profil_exclans' , 'Ex-Clans');
define('_profil_status' , 'Status');
define('_aktiv' , '<span class=fontGreen>active</span>');
define('_inaktiv' , '<span class=fontRed>inactive</span>');
define('_male' , 'male');
define('_female' , 'female');
define('_profil_ppic' , 'Profile picture');
define('_profil_gamestuff' , 'Gamestuff');
define('_profil_userstats' , 'Userstats');
define('_profil_navi_profil' , '<a href="?action=user&amp;id=[id]">Profile</a>');
define('_profil_navi_gb' , '<a href="?action=user&amp;id=[id]&amp;show=gb">Guestbook</a>');
define('_profil_navi_gallery' , '<a href="?action=user&amp;id=[id]&amp;show=gallery">Gallery</a>');
define('_profil_profilhits' , 'Profile hits');
define('_profil_forenposts' , 'Posts in board');
define('_profil_votes' , 'participated votes');
define('_profil_msgs' , 'messages sent');
define('_profil_logins' , 'Logins');
define('_profil_registered' , 'Date of registration');
define('_profil_last_visit' , 'Last page visit');
define('_profil_pagehits' , 'Pagehits');
define('_pedit_visibility', 'Visibility/Permissions');
define('_pedit_visibility_gb', 'Guestbook Posts');
define('_pedit_visibility_gallery', 'Gallery');
define('_pedit_perm_public', '<option value="0" selected="selected">Public</option><option value="1">User only</option><option value="2">Member only</option>');
define('_pedit_perm_user', '<option value="0">Public</option><option value="1" selected="selected">User only</option><option value="2">Member only</option>');
define('_pedit_perm_member', '<option value="0">Public</option><option value="1">User only</option><option value="2" selected="selected">Member only</option>');
define('_pedit_perm_allow', '<option value="1" selected="selected">Allow</option><option value="0">Deny</option>');
define('_pedit_perm_deny', '<option value="1">Allow</option><option value="0" selected="selected">Deny</option>');
define('_gallery_no_perm', '<div align="center">'+"<br/>You don't have permissions for this gallery</div>");
define('_profil_cws' , 'participated cw`s');
define('_profil_edit_pic' , '<a href="../upload/?action=userpic">upload</a>');
define('_profil_delete_pic' , '<a href="../upload/?action=userpic&amp;do=deletepic">delete</a>');
define('_profil_edit_ava' , '<a href="../upload/?action=avatar">upload</a>');
define('_profil_delete_ava' , '<a href="../upload/?action=avatar&amp;do=delete">delete</a>');
define('_pedit_aktiv' , '<option value="1" selected="selected">active</option><option value="0">inactive</option>');
define('_pedit_inaktiv' , '<option value="1">active</option><option value="0" selected="selected">inactive</option>');
define('_pedit_male' , '<option value="0">no indication</option><option value="1" selected="selected">male</option><option value="2">female</option>');
define('_pedit_female' , '<option value="0">no indication</option><option value="1">male</option><option value="2" selected="selected">female</option>');
define('_pedit_sex_ka' , '<option value="0">no indication</option><option value="1">male</option><option value="2">female</option>');
define('_info_edit_profile_done' , 'Your profile was successfully edited!');
define('_delete_pic_successful' , 'Your picture was successfully deleted!');
define('_no_pic_available' , 'No picture from you are available!');
define('_profil_edit_profil_link' , '<a href="?action=editprofile">Edit profile</a>');
define('_profil_edit_gallery_link' , '<a href="?action=editprofile&amp;show=gallery">Edit Usergallery</a>');
define('_profil_avatar' , 'Avatar');
define('_lostpwd_failed' , 'Loginname and email address does not match!');
define('_lostpwd_valid' , 'A new password was generated and sent to you by e-mail!');
define('_error_user_already_in' , 'You are logged in already!');
define('_user_is_banned' , 'Your account is banned by a site admin.');
define('_msghead' , 'Messagecenter from [nick]');
define('_posteingang' , 'Inbox');
define('_postausgang' , 'Outbox');
define('_msg_title' , 'Message');
define('_msg_absender' , 'From');
define('_msg_empfaenger' , 'To');
define('_msg_answer_msg' , 'Message from [nick]');
define('_msg_sended_msg' , 'Message to [nick]');
define('_msg_answer_done' , 'The message was successfuly sent!');
define('_msg_titel' , 'Write new message');
define('_msg_titel_answer' , 'Answer');
define('_to' , 'To');
define('_or' , 'or');
define('_msg_to_just_1' , 'You can indicate just one receiver!');
define('_msg_not_to_me' , 'You can`t write yourself!');
define('_legende_readed' , 'Message was read by receiver?');
define('_legende_msg' , 'New message');
define('_msg_from_nick' , 'Message from [nick]');
define('_msg_global_reg' , 'all registered user');
define('_msg_global_squad' , 'following team:');
define('_msg_bot' , '<span class="fontBold">MsgBot</span>');
define('_msg_global_who' , 'Receiver');
define('_msg_reg_answer_done' , 'The message was successfully sent to all registered users!');
define('_msg_member_answer_done' , 'The message was successfully sent to all members!');
define('_msg_squad_answer_done' , 'The message was successfully sent to all members of the selected team!');
define('_buddyhead' , 'Buddies');
define('_addbuddys' , 'Add buddies');
define('_buddynick' , 'Buddy');
define('_add_buddy_successful' , 'The user was successfully added as buddy!');
define('_buddys_legende_addedtoo' , 'The user aded you as buddy, too');
define('_buddys_legende_dontaddedtoo' , 'The user didn`t added you as buddy, too');
define('_buddys_delete_successful' , 'The user was successfully deleted as buddy!');
define('_buddy_added_msg' , 'The user <span class="fontBold">[user]</span> added you to his buddies!');
define('_buddy_title' , 'Buddies');
define('_buddy_del_msg' , 'The user <span class="fontBold">[user]</span> deleted you from his buddies!');
define('_ulist_lastreg' , 'newest user');
define('_ulist_online' , 'Onlinestatus');
define('_ulist_age' , 'Age');
define('_ulist_sex' , 'Sex');
define('_ulist_country' , 'Nationality');
define('_ulist_sort' , 'Sort by:');
define('_usergb_eintragen' , '<a href="?action=usergb&amp;id=[id]">Insert</a>');
define('_usergb_entry_successful' , 'Your entry in the profile guestbook was successfully registered!');
define('_gallery_pic' , 'Picture');
define('_gallery_beschr' , 'Description');
define('_gallery_edit_new' , '<a href="../upload/?action=usergallery">Add new picture</a>');
define('_info_edit_gallery_done' , 'You successfully deleted the gallery!');
define('_admin_user_edithead' , 'Admin: Edit users');
define('_admin_user_clanhead' , 'Authorisation');
define('_admin_user_squadhead' , 'Team');
define('_admin_user_personalhead' , 'Personal');
define('_admin_user_level' , 'Level');
define('_admin_user_clankasse' , 'Admin: Clan Cash');
define('_admin_user_serverliste' , 'Admin: Serverlist');
define('_admin_user_editserver' , 'Admin: Server');
define('_admin_user_edittactics' , 'Admin: Tactics');
define('_admin_user_edituser' , 'Edit users');
define('_admin_user_editsquads' , 'Admin: Teams');
define('_admin_user_editkalender' , 'Admin: Calendar');
define('_member_admin_newsletter' , 'Admin: Newsletter');
define('_member_admin_downloads' , 'Admin: Downloads');
define('_member_admin_links' , 'Admin: Links');
define('_member_admin_gb' , 'Admin: Guestbook');
define('_member_admin_forum' , 'Admin: Forum');
define('_member_admin_intforum' , 'View internal boards');
define('_member_admin_news' , 'Admin: News');
define('_member_admin_clanwars' , 'Admin: Clanwars');
define('_error_edit_myself' , 'You can`t edit yourself!');
define('_error_edit_admin' , 'You are not allowed to edit admins!');
define('_admin_level_banned' , 'Ban account');
define('_admin_user_identitat' , 'Identity');
define('_admin_user_get_identitat' , '<a href="?action=admin&amp;do=identy&amp;id=[id]">take identity</a>');
define('_identy_admin' , 'You can`t take the identity from an admin!');
define('_admin_squad_del' , '<option value="delsq">- delete user out of this team -</option>');
define('_admin_squad_nosquad' , '<option class="dropdownKat" value="lazy">- user isn``t in a team -</option>');
define('_admin_user_edited' , 'The user successfully was edited!');
define('_userlobby' , 'Userlobby');
define('_lobby_new' , 'New stuff since your last page visit');
define('_lobby_new_erased' , 'You successfully marked everything as readed!');
define('_last_forum' , 'last 10 board posts');
define('_lobby_forum' , 'Board posts');
define('_new_post_1' , 'new post');
define('_new_post_2' , 'new posts');
define('_new_thread' , 'in thread ');
define('_no_new_thread' , 'New thread:');
define('_lobby_gb' , 'Guestbook entrys');
define('_new_gb' , '<br /><span class="fontBoldUnder">Guestbook:</span><br />');
define('_new_eintrag_1' , 'new entry');
define('_new_eintrag_2' , 'new entries');
define('_lobby_user' , 'Registered user');
define('_new_users_1' , 'new registered User');
define('_new_users_2' , 'new registered Users');
define('_lobby_membergb' , 'My profile guestbook');
define('_lobby_news' , 'News');
define('_lobby_new_news' , 'new news');
define('_lobby_newsc' , 'News comments');
define('_lobby_new_newsc_1' , 'new comment');
define('_lobby_new_newsc_2' , 'new comments');
define('_new_msg_1' , 'new message');
define('_new_msg_2' , 'new messages');
define('_lobby_votes' , 'Votes');
define('_new_vote_1' , 'new Vote');
define('_new_vote_2' , 'new Votes');
define('_lobby_cw' , 'Clanwars');
define('_user_new_cw' , '<tr><td style="width:22px;text-align:center"><img src="../inc/images/gameicons/[icon]" class="icon" alt="" /></td><td style="vertical-align:middle"><a href="../clanwars/?action=details&amp;id=[id]">Clanwar at <span class="fontWichtig">[datum]</span> againstgen <span class="fontWichtig">[gegner]</span></a></td></tr>');
define('_user_delete_verify' , '
<tr>
  <td class="contentHead"><span class="fontBold">Delete user</span></td>
</tr>
<tr>
  <td class="contentMainFirst" align="center">
    Are you sure to delete the user [user]?<br />
    <span class="fontUnder">Every</span> activities from this user will be deleted, too!<br /><br />
    <a href="?action=admin&amp;do=delete&verify=yes&amp;id=[id]">Yes, delete [user]!</a>
  </td>
</tr>');
define('_user_deleted' , 'The user successfully was deleted!');
define('_admin_user_shoutbox' , 'Admin: Shoutbox');
define('_admin_user_awards' , 'Admin: Awards');
define('_userlobby_kal_today' , 'Next event is <a href="../kalender/?action=show&time=[time]"><span class="fontWichtig">today - [event]</span></a>');
define('_userlobby_kal_not_today' , 'Next event is at <a href="../kalender/?action=show&time=[time]"><span class="fontUnder">[date] - [event]</span></a>');
define('_profil_country' , 'Country');
define('_lobby_awards' , 'Awards');
define('_new_awards_1' , 'new award');
define('_new_awards_2' , 'new awards');
define('_lobby_rankings' , 'Rankings');
define('_new_rankings_1' , 'new change');
define('_new_rankings_2' , 'new changes');
define('_profil_favos' , 'Favorites');
define('_profil_drink' , 'Drink');
define('_profil_essen' , 'Meal');
define('_profil_film' , 'Film');
define('_profil_musik' , 'Music');
define('_profil_song' , 'Song');
define('_profil_buch' , 'Book');
define('_profil_autor' , 'Author');
define('_profil_person' , 'Person');
define('_profil_sport' , 'Sport');
define('_profil_sportler' , 'Sportsman');
define('_profil_auto' , 'Car');
define('_profil_favospiel' , 'Game');
define('_profil_game' , 'Game');
define('_profil_favoclan' , 'Clan');
define('_profil_spieler' , 'Player');
define('_profil_map' , 'Map');
define('_profil_waffe' , 'Weapon');
define('_profil_rasse' , 'Race');
define('_profil_sonst' , 'Misc');
define('_profil_url1' , 'Page #1');
define('_profil_url2' , 'Page #2');
define('_profil_url3' , 'Page #3');
define('_profil_ich' , 'Description');
define('_lobby_gallery' , 'Galleries');
define('_new_gal_1' , 'new Gallery');
define('_new_gal_2' , 'new Galelries');
## Upload ##
define('_upload_wrong_size' , 'The uploaded file is bigger than allowed!!');
define('_upload_no_data' , 'You have to indicate a file!');
define('_info_upload_success' , 'The file was successfully uploaded!');
define('_upload_info' , 'Info');
define('_upload_file' , 'File');
define('_upload_beschreibung' , 'Description');
define('_upload_button' , 'upLoad');
define('_upload_over_limit' , 'You are not allowed to upload more pictuires! Delete present pictures to upload a new one!');
define('_upload_file_exists' , 'The uploaded file already exists! Rename the file and upload again or upload another file!');
define('_upload_head' , 'Upload userpic');
define('_upload_userpic_info' , ' Only jpg, gif or png files with a maximum filesize of [userpicsize]KB!<br />The recommended dimension is 170px * 210px ');
define('_upload_head_usergallery' , 'Edit Usergallery');
define('_edit_gallery_done' , 'The usergallery was successfully edited!');
define('_upload_usergallery_info' , 'Only jpg, gif or png files with a maximum filesize of [userpicsize]KB!');
define('_upload_icons_head' , 'Gameicons');
define('_upload_taktiken_head' , 'Takcticscreens');
define('_upload_ava_head' , 'Useravatar');
define('_upload_userava_info' , 'Only jpg, gif or png files with a maximum filesize of [userpicsize]KB!<br />The recommended dimension is 100px * 100px ');
define('_upload_newskats_head' , 'Category pictures');
## Unzugeordnet ##
define('_config_maxwidth' , 'Resize pictures automatically');
define('_config_maxwidth_info' , 'Here you can adjust at which width a picture will be resized!');
define('_forum_top_posts' , 'Top 5 poster');
define('_error_no_teamspeak' , 'The teamspeak server is not reachable at the moment!');
define('_user_cant_delete_admin' , 'You can`t delete members or admins!');
define('_no_entrys_yet' , '
<tr>
  <td class="contentMainFirst" colspan="[colspan]" align="center">No entry yetn!</td>
</tr>');
define('_nav_no_nextwars' , 'No next wars yet!');
define('_nav_no_lastwars' , 'No last wars yet!');
define('_nav_no_ftopics' , 'No entry yet!');
define('_gallery_folder_exists' , 'The indicated folder already exist!');
define('_server_isnt_live' , 'The server isn`t set to a live status!');
define('_rankings_edit_head' , 'Edit ranking');
define('_fopen' , 'The webhoster of this site does not allow the function fopen() which is needed!');
define('_and' , 'and');
define('_lobby_artikelc' , 'Article comments');
define('_lobby_new_art_1' , 'new article');
define('_lobby_new_art_2' , 'new article');
define('_user_new_art' , '&nbsp;&nbsp;<a href="../artikel/"><span class="fontWichtig">[cnt]</span> [eintrag]</span><br />');
define('_lobby_new_artc_1' , 'new comment');
define('_lobby_new_artc_2' , 'new comments');
define('_page' , '<span class="fontBold">[num]</span>  ');
define('_profil_nletter' , 'Receive newsletter?');
define('_forum_admin_addglobal' , '<span class="fontWichtig">Global</span> entry? (In all boards and subboards)');
define('_forum_admin_global' , '<span class="fontWichtig">Global</span> entry?');
define('_forum_global' , '<span class="fontWichtig">global:</span>');
define('_admin_config_badword' , 'Badwordfilter');
define('_admin_config_badword_info' , 'Here you can indicate the words, which will be filter and replaced with ***. The words have to be seperated with a comma!');
define('_iplog_info' , '<span class="fontBold">Note:</span> In case of security reasons your ip will be logged!');
define('_logged' , 'IP saved');
define('_info_ip' , 'IP Address');
define('_info_browser' , 'Browser');
define('_info_res' , 'Solution');
define('_unknown_browser' , 'unknown browser');
define('_unknown_system' , 'unknown system');
define('_info_sys' , 'System');
define('_nav_montag' , 'Mo');
define('_nav_dienstag' , 'Tu');
define('_nav_mittwoch' , 'We');
define('_nav_donnerstag' , 'Th');
define('_nav_freitag' , 'Fr');
define('_nav_samstag' , 'Sa');
define('_nav_sonntag' , 'Su');
define('_age' , 'Age');
define('_error_empty_age' , 'You have to indicate your actual age!');
define('_member_admin_intforums' , 'internal board authorisation');
define('_access' , 'Authorisation');
define('_error_no_access' , 'You don`t have the rights to enter this area!');
define('_artikel_show_link' , '<a href="../artikel/?action=show&amp;id=[id]">[titel]</a>');
define('_ulist_bday' , 'Birthday');
define('_ulist_last_login' , 'Last login');
## Taktiken ##
define('_taktik_head' , 'Internal: Tactics');
define('_taktik_standard_t' , '<a href="?action=standard&amp;what=t&amp;id=[id]">Defence</a>');
define('_taktik_standard_ct' , '<a href="?action=standard&amp;what=ct&amp;id=[id]">Defence</a>');
define('_taktik_spar_t' , '<a href="?action=spar&amp;what=t&amp;id=[id]">Attack</a>');
define('_taktik_spar_ct' , '<a href="?action=spar&amp;what=ct&amp;id=[id]">Attack</a>');
define('_taktik_upload' , 'Upload tactic screen');
define('_taktik_t' , 'Team 2');
define('_taktik_ct' , 'Team 1');
define('_taktik_posted' , 'posted by <span class="fontBold">[autor]</span> - [datum]');
define('_taktik_headline' , '<span class="fontBold">Map:</span> [map] - <span class="fontBold">Tactic:</span> [what]');
define('_taktik_tstandard_t' , 'Team 2 -> Defence');
define('_taktik_tstandard_ct' , 'Team 1 -> Defence');
define('_taktik_tspar_t' , 'Team 2 -> Attack');
define('_taktik_tspar_ct' , 'Team 1 -> Attack');
define('_error_taktik_empty_map' , 'You have to indicate a map!');
define('_taktik_new' , 'Insert new tactic');
define('_taktik_added' , 'The tactic was successfully registered!');
define('_taktik_deleted' , 'The tactic was successfully deleted!');
define('_taktik_edit_head' , 'Edit tactic');
define('_taktik_new_head' , 'New tactic');
define('_error_taktik_edited' , 'The tactic was successfully edited!');
## Impressum ##
define('_impressum_head' , 'Imprint');
define('_impressum_autor' , 'Author of the site');
define('_impressum_domain' , 'Domain:');
define('_impressum_disclaimer' , 'Disclaimer');
define('_impressum_txt' , '<blockquote>
<h2><span class="fontBold">1. Content</span></h2>
<br />
The author reserves the right not to be responsible for the topicality, correctness,
completeness or quality of the information provided. Liability claims regarding
damage caused by the use of any information provided, including any kind
of information which is incomplete or incorrect,will therefore be rejected.
<br />All offers are not-binding and without obligation. Parts of the pages or the complete
publication including all offers and information might be extended, changed
or partly or completely deleted by the author without separate announcement.
<br /><br />
<h2><span class="fontBold">2. Referrals and links</span></h2>
<br />
The author is not responsible for any contents linked or referred to from his pages - unless he has full knowledge of illegal contents and would be able to prevent the visitors of his site fromviewing those pages.
If any damage occurs by the use of information presented there, only the author of the respective pages might be liable, not the one who has linked to these pages. Furthermore the author is not liable for any postings or messages published by users of discussion boards, guestbooks or mailinglists provided on his page.
<br /><br />
<h2><span class="fontBold">3. Copyright</span></h2>
<br />
The author intended not to use any copyrighted material for the publication or, if not possible, to indicate the copyright of the respective object.
<br />
The copyright for any material created by the author is reserved. Any duplication or use of objects such as images, diagrams, sounds or texts in other
electronic or printed publications is not permitted without the author\'s agreement.
<br /><br />
<h2><span class="fontBold">4. Privacy policy<</span></h2>
<br />
If the opportunity for the input of personal or business data (email addresses, name, addresses) is given, the input of these data takes place voluntarily. The use and payment of all offered services are permitted - if and so far technically possible and reasonable - without specification of any personal data or under specification of anonymized data or an alias.
The use of published postal addresses, telephone or fax numbers and email addresses for marketing purposes is prohibited, offenders sending unwanted spam messages will be punished.
<br /><br />
<h2><span class="fontBold">5. Legal validity of this disclaimer</span></h2>
<br />
This disclaimer is to be regarded as part of the internet publication which you were referred from. If sections or individual terms of this statement are not legal or correct, the content or validity of the other parts remain uninfluenced by this fact.
</blockquote>');
## Admin ##
define('_config_head' , 'Administrative area');
define('_config_empty_katname' , 'You have to indicate a category description!');
define('_config_katname' , 'Category description');
define('_config_set' , 'The attitudes were successfully saved!');
define('_config_forum_status' , 'Status');
define('_config_forum_head' , 'Board categories');
define('_config_forum_mainkat' , 'Main category');
define('_config_forum_subkathead' , 'Sub categories from <span class="fontUnder">[kat]</span>');
define('_config_forum_subkat' , 'Sub  category');
define('_config_forum_subkats' , '<span class="fontBold">[topic]</span><br /><span class="fontItalic">[subtopic]</span>');
define('_config_forum_kat_head' , 'Insert category');
define('_config_forum_public' , 'public');
define('_config_forum_intern' , 'internal');
define('_config_forum_kat_added' , 'The category was successfully registered!');
define('_config_forum_kat_deleted' , 'The category was successfully deleted!');
define('_config_forum_kat_head_edit' , 'Edit category');
define('_config_forum_kat_edited' , 'The category was successfully edited!');
define('_config_forum_add_skat' , 'Insert sub category');
define('_config_forum_skatname' , 'Sub category description');
define('_config_forum_empty_skat' , 'You have to indicate a sub category description!');
define('_config_forum_skat_added' , 'The sub category was successfully registered!');
define('_config_forum_stopic' , 'Subtitle');
define('_config_forum_skat_edited' , 'The sub category was successfully edited!');
define('_config_forum_edit_skat' , 'Edit sub category');
define('_config_forum_skat_deleted' , 'The sub category was successfully deleted!');
define('_config_newskats_kat' , 'Category');
define('_config_newskats_head' , 'News-/Article categories');
define('_config_newskats_katbild' , 'Category pic');
define('_config_newskats_add' , '<a href="?admin=news&amp;do=add">Insert category picture</a>');
define('_config_newskat_deleted' , 'The category was successfully deleted!');
define('_config_newskats_add_head' , 'Insert category');
define('_config_newskats_added' , 'The category was sucessfully registeredD!');
define('_config_newskats_edit_head' , 'Edit category');
define('_config_newskats_edited' , 'The category was successfully edited!');
define('_config_impressum_head' , 'Imprint');
define('_config_impressum_domains' , 'Domains');
define('_config_impressum_autor' , 'Author of this site');
define('_config_konto_head' , 'Bank accont');
define('_config_clankasse_head' , 'In-/outpayment labels');
define('_backup_head' , 'DDatabase backup');
define('_backup_info_head' , 'Note');
define('_backup_info' , 'The backup process can take some minutes.');
define('_backup_link' , 'Make new backup!');
define('_backup_successful' , 'The database backup was successfully made!');
define('_backup_last_head' , 'Last backup');
define('_backup_last_not_exist' , 'You didn`t backed up your database yet!');
define('_news_admin_head' , 'Newsarea');
define('_admin_news_add' , '<a href="?admin=newsadmin&amp;do=add">Insert news</a>');
define('_admin_news_head' , 'Insert news');
define('_news_admin_kat' , 'Category');
define('_news_admin_klapptitel' , 'Cliptitle');
define('_news_admin_more' , 'More');
define('_empty_news' , 'You have to indicate a news!');
define('_news_sended' , 'The news was successfully registered!');
define('_admin_news_edit_head' , 'Edit news');
define('_news_edited' , 'The news was successfully edited!');
define('_news_deleted' , 'The news was successfully deleted!');
define('_member_admin_header' , 'Teamarea');
define('_member_admin_squad' , 'Team');
define('_member_admin_game' , 'Game');
define('_member_admin_icon' , 'Icon');
define('_member_admin_add' , '<a href="?admin=squads&amp;do=add">Insert team</a>');
define('_admin_squad_deleted' , 'The team was successfully deleted!');
define('_member_admin_add_header' , 'Insert team');
define('_admin_squad_no_squad' , 'You have to indicate a team`s name!');
define('_admin_squad_no_game' , 'You have to indicate the game which the team plays!');
define('_admin_squad_add_successful' , 'The team was successfully registered!');
define('_admin_squad_edit_successful' , 'The team was successfully edited!');
define('_member_admin_edit_header' , 'Edit team');
define('_error_server_edit' , 'The server was successfully edited!');
define('_error_empty_clanname' , 'You have to indicate your clan`s name!');
define('_error_server_accept' , 'The selected server were successfully set!');
define('_error_server_dont_accept' , 'The selected server were successfully unset!');
define('_slist_head_admin' , 'Serverlist');
define('_slist_server_deleted' , 'The server was successfully deleted!');
define('_server_admin_head' , 'Server');
define('_server_add_new' , '<a href="?admin=server&amp;do=new">Insert server</a>');
define('_admin_server_edit' , 'Edit server');
define('_empty_ip' , 'You have to indicate an ip address!');
define('_server_admin_edited' , 'The server was successfully edited!');
define('_server_admin_deleted' , 'The server was successfully deleted!');
define('_admin_server_new' , 'Insert server');
define('_server_admin_added' , 'The server was successfully registered!');
define('_empty_game' , 'You have to choose an icon!');
define('_empty_servername' , 'You have to choose a server`s name!');
define('_admin_dlkat' , 'Download categories');
define('_admin_download_kat' , 'Description');
define('_dl_add_new' , '<a href="?admin=dl&amp;do=new">Insert new category</a>');
define('_dl_new_head' , 'Insert new download categorie');
define('_dl_dlkat' , 'Category');
define('_dl_empty_kat' , 'You have to indicate a category description!');
define('_dl_admin_added' , 'The download category was successfully registered!');
define('_dl_admin_deleted' , 'The download category was successfully deleted!');
define('_dl_edit_head' , 'Edit download category');
define('_dl_admin_edited' , 'The download category was successfully edited!');
define('_config_global_head' , 'Configuration');
define('_config_c_limits' , 'Limits');
define('_config_c_limits_what' , 'Here you can adjust the amount of entrys which will be maximum shown');
define('_config_c_usergb' , 'User guestbook');
define('_config_c_clankasse' , 'Clan Cash');
define('_config_c_gb' , 'Guestbook');
define('_config_c_archivnews' , 'News archive');
define('_config_c_news' , 'News');
define('_config_c_banned' , 'Banlist');
define('_config_c_adminnews' , 'Newsadmin');
define('_config_c_clanwars' , 'Clanwars');
define('_config_c_shout' , 'Shoutbox');
define('_config_c_userlist' , 'Userlist');
define('_config_c_comments' , 'Newscomments');
define('_config_c_fthreads' , 'Board threads');
define('_config_c_fposts' , 'Board posts');
define('_config_c_floods' , 'Anti-Flooding');
define('_config_c_forum' , 'Board');
define('_config_c_length' , 'Length specifications');
define('_config_c_length_what' , 'Here you can adjust the maximum length of an entry (longer entries will be cutted automatically).');
define('_config_c_newsadmin' , 'Newsadmin: Title');
define('_config_c_shouttext' , 'Shoutbox: Text');
define('_config_c_newsarchiv' , 'Newsarchiv: Title');
define('_config_c_forumtopic' , 'Board: Topic');
define('_config_c_forumsubtopic' , 'Board: Subtopic');
define('_config_c_topdl' , 'Menu: Top Downloads');
define('_config_c_ftopics' , 'Menu: Last Forumtopics');
define('_config_c_lcws' , 'Clanwars: Opponent`s name');
define('_config_c_lwars' , 'Menu: Last Wars');
define('_config_c_nwars' , 'Menu: Next Wars');
define('_config_c_main' , 'General configuration');
define('_config_c_clanname' , 'Clan`s name');
define('_config_c_pagetitel' , 'Site title');
define('_config_c_language' , 'Default language');
define('_config_c_gametiger' , 'Gametiger Search option');
define('_config_c_upicsize' , 'Global: Pictureupload Size');
define('_config_c_gallerypics' , 'User: Usergallery');
define('_config_c_upicsize_what' , 'allowed filesize of the pictures in KB (Newspicture, Userpicture etc.)');
define('_config_c_regcode' , 'Reg: Securitycodee');
define('_config_c_regcode_what' , 'User have to enter a securitycode during the registration');
define('_pos_add_new' , '<a href="?admin=positions&amp;do=new">Insert position</a>');
define('_pos_new_head' , 'Insert position');
define('_pos_edit_head' , 'Edit position');
define('_pos_admin_edited' , 'The position was successfully edited!');
define('_pos_admin_deleted' , 'The position was successfully deleted!');
define('_pos_admin_added' , 'The position was successfully registered!');
define('_clankasse_new_head' , 'Insert new in-/outpayment label');
define('_clankasse_edit_head' , 'Edit in-/outpayment label');
define('_clankasse_empty_kat' , 'You have to indicate an in-/outpayment label!');
define('_clankasse_kat_added' , 'The in-/outpayment label was successfully registered!');
define('_clankasse_kat_edited' , 'The in-/outpayment label was successfully edited!');
define('_clankasse_kat_deleted' , 'The in-/outpayment label was successfully deleted!');
define('_config_c_gallery' , 'Gallery ');
define('_config_info_gallery' , 'Amount of pictures which will be maximum shown in a row');
define('_config_server_ts_updated' , 'The teamspeak`s ip was successfully updated!');
define('_ts_sport' , 'Server queryport');
define('_config_c_awards' , 'Awards');
define('_counter_start' , 'Counter');
define('_counter_start_info' , 'Here you can enter a number which will be added to the counter.');
define('_admin_nc' , 'Newscomments');
define('_admin_reg_info' , 'Here you can djust, if users have to be registered to do stuff (write comments, download things, etc)');
define('_admin_reg_head' , 'Registration required');
define('_config_shoutarchiv' , 'Shoutbox: archive');
define('_config_zeichen' , 'Shoutbox: chars');
define('_config_zeichen_info' , 'Here you can adjust, how many chars an entry can have.');
define('_wartungsmodus_info' , 'if set to \'on\' admins only can enter the site.');
define('_navi_kat' , 'Area');
define('_navi_name' , 'Link`s name');
define('_navi_url' , 'Forwarding');
define('_navi_shown' , 'Viewable');
define('_navi_type' , 'Type');
define('_navi_wichtig' , 'Mark');
define('_navi_space' , '<b>Blank line</b>');
define('_navi_head' , 'Navigation administration');
define('_navi_add_head' , 'Insert link');
define('_navi_edit_head' , 'Edit link');
define('_navi_url_to' , 'Weiterleiten nach');
define('_posi' , 'Position');
define('_nach' , 'after');
define('_navi_no_name' , 'You have to indicate a link`s name!');
define('_navi_no_url' , 'You have to indicate a target!');
define('_navi_no_pos' , 'You have to indicate a position!');
define('_navi_added' , 'The link was successfully registered!');
define('_navi_deleted' , 'The link was successfully deleted!');
define('_navi_edited' , 'The link was successfully edited!');
define('_editor_head' , 'Insert site');
define('_editor_name' , 'Site description');
define('_editor_add_head' , 'Insert site');
define('_inhalt' , 'Content');
define('_allow', 'Allow');
define('_deny', 'Deny');
define('_editor_allow_html' , 'allow HTML?');
define('_empty_editor_inhalt' , 'You have to indicate a text!');
define('_site_added' , 'The site was successfully registered!');
define('_editor_linkname' , 'Linkname');
define('_editor_deleted' , 'The site was successfully deleted!');
define('_editor_edit_head' , 'Edit site');
define('_site_edited' , 'The site was successfully edited!');
define('_partners_head' , 'Partnerbuttons');
define('_partners_button' , 'Button');
define('_partners_add_head' , 'Insert partnerbutton');
define('_partners_edit_head' , 'Edit partnerbutton');
define('_partners_select_icons' , '<option value="[icon]" [sel]>[icon]</option>');
define('_partners_added' , 'The partnerbutton was successfully registered!');
define('_partners_edited' , 'The partnerbutton was successfully edited!');
define('_partners_deleted' , 'The partnerbutton was successfully deleted!');
define('_clear_head' , 'Clear database');
define('_clear_news' , 'Newsentrys?');
define('_clear_forum' , 'Boardentrys?');
define('_clear_forum_info' , 'Boardentrys which are marked as <span class="fontWichtig">important</span> won`t be deleted!');
define('_clear_days' , 'Delete entrys which are older than');
define('_clear_what' , 'days');
define('_clear_deleted' , 'The database was successfully cleared up!');
define('_clear_error_days' , 'You have to indicate the days when something is supposed to be deleted!');
define('_admin_status' , 'Livestatus');
define('_error_unregistered' , 'You have to be registered to use this function!');
define('_seiten' , 'Site:');
define('_admin_user_gallery' , 'Admin: Gallery');
define('_user_admin_joinus' , 'Receive joinus?');
define('_user_admin_contact' , 'Receive contact?');
define('_user_admin_formulare' , 'Forms');
define('_smileys_error_file' , 'You have to indicate a smiley!');
define('_smileys_error_bbcode' , 'You have to indicate a bbcode!');
define('_smileys_error_type' , 'Only gif files are allowed!');
define('_smileys_added' , 'The smiley was successfully registered!');
define('_smileys_edited' , 'The smiley was successfully edited!');
define('_smileys_deleted' , 'The smiley was successfully deleted!');
define('_smileys_head' , 'Smiley editor');
define('_smileys_smiley' , 'Smiley');
define('_smileys_bbcode' , 'BBCode');
define('_smileys_head_add' , 'Insert smiley');
define('_smileys_head_edit' , 'Edit smiley');
define('_head_waehrung' , 'Currency');
define('_dl_version' , 'downloadable Version');
define('_admin_artikel_add' , '<a href="?admin=artikel&amp;do=add">insert article</a>');
define('_artikel_add' , 'Insert article');
define('_artikel_added' , 'The article was successfully registered');
define('_artikel_edit' , 'Edit article');
define('_artikel_edited' , 'The article was successfully edited!');
define('_artikel_deleted' , 'The article was successfully deleted!');
define('_empty_artikel_title' , 'You have to indicate a title!');
define('_empty_artikel' , 'You have to indicate an article!');
define('_admin_artikel' , 'Admin: Article');
define('_c_l_shoutnick' , 'Menu: Shoutbox: Nick');
define('_config_c_martikel' , 'Article');
define('_config_c_madminartikel' , 'Articleadmin');
define('_reg_artikel' , 'Articlecomments');
define('_cw_comments' , 'Clanwarcomments');
define('_on' , 'on');
define('_off' , 'off');
define('_pers_info_info' , 'Shows an infobox in the header with personal Informations like ip, browser, solution, etc');
define('_pers_info' , 'Infobox');
define('_config_lreg' , 'Menu: Last reg. user');
define('_config_mailfrom' , 'Email mailfrom');
define('_config_mailfrom_info' , 'This email address will be used for sent emails like newsletter, registration, etc!');
define('_profile_del_confirm' , 'Caution! All user`s entrys for this field will be lost. Do you really want to delete this field?');
define('_profile_del_confirm_link' , '<a href="?admin=profile&amp;do=delete&amp;id=[id]&confirm=yes">Delete field</a> - <a href="javascript:history.go(-1)">back</a>');
define('_profile_about' , 'About me');
define('_profile_clan' , 'Clan');
define('_profile_contact' , 'Contact');
define('_profile_favos' , 'Favorites');
define('_profile_hardware' , 'Hardware');
define('_profile_name' , 'Field`s name');
define('_profile_type' , 'Field`s type');
define('_profile_kat' , 'Category');
define('_profile_head' , 'Profile field administration');
define('_profile_edit_head' , 'Edit profile field');
define('_profile_shown' , 'Visible');
define('_profile_type_1' , 'Textfield');
define('_profile_type_2' , 'URL');
define('_profile_type_3' , 'Email address');
define('_profile_shown_dropdown' , '
<option value="1">Show</option>
<option value="2">Hide</option>');
define('_profile_kat_dropdown' , '
<option value="1">About me</option>
<option value="2">Clan</option>
<option value="3">Contact</option>
<option value="4">Favorites</option>
<option value="5">Hardware</option>');
define('_profile_type_dropdown' , '
<option value="1">Textfield</option>
<option value="2">URL</option>
<option value="3">Email-Adresse</option>');
define('_profile_add_head' , 'Insert profile field');
define('_profile_added' , 'The profile field was successfully registered!');
define('_profil_no_name' , 'You have to indicate the field`s name!');
define('_profil_deleted' , 'The profile field was successfully deleted!');
define('_profile_edited' , 'The profile field was successfully edited!');
## Clankasse ##
define('_clankasse_saved' , 'The contribution was successfully added to the clan cash!');
define('_clankasse_deleted' , 'The contribution was successfully deleted from the clan cash!');
define('_error_clankasse_empty_datum' , 'You have to indicate a date!');
define('_clankasse_edited' , 'The amount was successfully edited!');
define('_error_clankasse_empty_transaktion' , 'You have to indicate a description for this transaction!');
define('_error_clankasse_empty_betrag' , 'You have to indicate an amount!');
define('_clankasse_ctransaktion' , 'What');
define('_clankasse_cbetrag' , 'Amount');
define('_clankasse_server_head' , 'Clan account');
define('_clankasse_nr' , 'Kontono.');
define('_clankasse_blz' , 'Bank code');
define('_clankasse_inhaber' , 'Account owner');
define('_clankasse_bank' , 'Bank');
define('_clankasse_head' , 'Clan cash');
define('_clankasse_cakt' , 'actual balance');
define('_clankasse_admin_minus' , 'Minus');
define('_clankasse_plus' , '<span class="fontGreen">[betrag] [w]</span>');
define('_clankasse_minus' , '<span class="fontRed">- [betrag] [w]</span>');
define('_clankasse_summe_plus' , '<span class="fontGreen">[summe] [w]</span>');
define('_clankasse_summe_minus' , '<span class="fontRed">[summe] [w]</span>');
define('_clankasse_trans' , '[transaktion] from/to [member]');
define('_clankasse_head_edit' , 'Edit contribution');
define('_clankasse_head_new' , 'Insert contribution');
define('_clankasse_sonstiges' , 'Misc');
define('_clankasse_for' , 'from/to');
define('_clankasse_einzahlung' , '-> Ingoing');
define('_clankasse_auszahlung' , '-> Outgoing');
define('_clankasse_didpayed' , 'Paystatus');
define('_clankasse_status_status' , 'Status');
define('_clankasse_status_bis' , 'till');
define('_clankasse_status_payed' , '<span class="fontGreen">payed</span> until <span>[payed]</span>');
define('_clankasse_status_today' , '<span class="fontGreen">payed</span> untill <span class="fontBold">today</span>');
define('_clankasse_status_notpayed' , '<span class="fontRed">overdue</span> since <span class="fontBold">[payed]</span>');
define('_clankasse_status_noentry' , 'No entry yet');
define('_clankasse_edit_paycheck' , 'Edit paystatus');
define('_clankasse_payed_till' , 'payed until');
define('_info_clankass_status_edited' , 'The paystatus was successfully updated!');
## Shoutbox ##
define('_shoutbox_head' , 'Shoutbox');
define('_error_empty_shout' , 'You have to indicate a text for the shoutbox!');
define('_error_shout_saved' , 'Your entry was successfully registered into the shoutbox!');
define('_shoutbox_archiv' , 'Archive');
define('_shout_archiv_head' , 'Shoutbox archive');
define('_noch' , 'till');
define('_zeichen' , 'chars');
## Misc ##
define('_error_have_to_be_logged' , 'You havet to be logged in to use this feature!');
define('_error_invalid_email' , 'The indicated email address is invalid!');
define('_error_invalid_url' , 'The indicated homepage isn`t reachable!');
define('_error_nick_exists' , 'The indicated nickname is already in use!');
define('_error_user_exists' , 'The indicated loginname is already in use!');
define('_error_passwords_dont_match', "Passwords don't match!");
define('_error_email_exists' , 'The indicated email address is already in use!');
define('_info_edit_profile_done_pwd' , 'Your profile was successfully edited!');
define('_error_select_buddy' , 'You didn`t selected an user!');
define('_error_buddy_self' , 'You can`t add yourself as a buddy!');
define('_error_buddy_already_in' , 'You already added this user to your buddies list!');
define('_error_msg_self' , 'You can`t write yourself a message!');
define('_error_back' , 'back');
define('_user_dont_exist' , 'The requested user does not exist!');
define('_error_fwd' , 'forward');
define('_error_wrong_permissions' , 'You don`t have the right permissions to do this!');
define('_error_flood_post' , 'You just can write a new entry every [sek] seconds!');
define('_empty_titel' , 'You have to indicate a title!');
define('_empty_eintrag' , 'You have to indicate an entry!');
define('_empty_nick' , 'You have to indicate a nick!');
define('_empty_email' , 'You have to indicate an email address!');
define('_empty_user' , 'You have to indicate a loginname!');
define('_empty_to' , 'You have to indicate a receiver!');
define('_empty_url' , 'You have to indicate an url!');
define('_empty_datum' , 'You have to indicate a date!');
define('_site_loading' , 'preloading site');
define('_site_wait' , 'please wait');
define('_index_headtitle' , '[clanname]');
define('_site_sponsor' , 'Sponsors');
define('_site_user' , 'User');
define('_site_online' , 'Visitors online');
define('_site_gametiger' , 'Gametiger');
define('_site_member' , 'Member');
define('_site_serverlist' , 'Serverlist');
define('_site_rankings' , 'Rankings');
define('_site_server' , 'Gameserver');
define('_site_forum' , 'Board');
define('_site_backup' , 'Database backup');
define('_site_links' , 'Links');
define('_site_dl' , 'Downloads');
define('_site_news' , 'News');
define('_site_messerjocke' , 'Messerjocke');
define('_site_banned' , 'Banlist');
define('_site_gb' , 'Guestbook');
define('_site_clankasse' , 'Clan cash');
define('_site_clanwars' , 'Clanwars');
define('_site_upload' , 'Upload');
define('_site_taktiken' , 'Tactics');
define('_site_ulist' , 'Userlist');
define('_site_msg' , 'Messages');
define('_site_reg' , 'Registration');
define('_site_shoutbox' , 'Shoutbox');
define('_site_user_login' , 'Login');
define('_site_user_lostpwd' , 'Lost pwd');
define('_site_user_logout' , 'Logout');
define('_site_artikel' , 'Article');
define('_site_user_lobby' , 'Userlobby');
define('_site_user_profil' , 'Userprofile');
define('_site_user_editprofil' , 'Edit profile');
define('_site_user_buddys' , 'Buddies');
define('_site_impressum' , 'Imprint');
define('_site_votes' , 'Votes');
define('_site_gallery' , 'Gallery');
define('_site_config' , 'Administrative area');
define('_login' , 'Login');
define('_register' , 'Registration');
define('_userlist' , 'Userlist');
define('_rankings' , 'Rankings');
define('_gallery' , 'Gallery');
define('_news' , 'News');
define('_newsarchiv' , 'Newsarchive');
define('_gametiger' , 'Gametiger');
define('_serverliste' , 'Serverlist');
define('_banned' , 'Banlist');
define('_links' , 'Links');
define('_impressum' , 'Imprint');
define('_contact' , 'Contact');
define('_clanwars' , 'Clanwars');
define('_artikel' , 'Article');
define('_dl' , 'Downloads');
define('_votes' , 'Votes');
define('_forum' , 'Board');
define('_gb' , 'Guestbook');
define('_squads' , 'Teams');
define('_squads_joinus', 'Team-JoinUs');
define('_squads_fightus', 'Team-FightUs');
define('_server' , 'Server');
define('_editprofil' , 'Edit profile');
define('_logout' , 'Logout');
define('_msg' , 'Messages');
define('_lobby' , 'Lobby');
define('_buddys' , 'Buddies');
define('_mem_clankasse' , 'Clan cash');
define('_mem_taktiken' , 'Tacticsken');
define('_admin_config' , 'Admin');
define('_head_online' , 'Online');
define('_head_visits' , 'Visitors');
define('_head_max' , 'Max.');
define('_cnt_user' , 'User');
define('_cnt_guests' , 'Guests');
define('_cnt_today' , 'Today');
define('_cnt_yesterday' , 'Yesterday');
define('_cnt_online' , 'Online');
define('_cnt_all' , 'Total');
define('_cnt_pperday' , '&oslash; day');
define('_cnt_perday' , 'per day');
define('_show' , 'Show');
define('_dont_show' , 'Don`t show');
define('_status' , 'Status');
define('_position' , 'Position');
define('_kind' , 'Type');
define('_cnt' , '#');
define('_membergb' , 'Profile guestbook');
define('_pwd' , 'Password');
define('_loginname' , 'Loginname');
define('_email' , 'Email');
define('_hp' , 'Homepage');
define('_icq' , 'ICQ-No.');
define('_member' , 'Member');
define('_user' , 'User');
define('_gast' , 'unregistered');
define('_nothing' , '<option value="lazy" class="dropdownKat">- change nothing -</option>');
define('_pn' , 'Message');
define('_nick' , 'Nick');
define('_info' , 'Info');
define('_error' , 'Error');
define('_datum' , 'Date');
define('_legende' , 'Legend');
define('_hlswid' , 'XFire Name');
define('_hlswstatus' , 'XFire');
define('_steamid', 'Steam Community-ID');
define('_xboxid', 'Xbox Live');
define('_xboxstatus', 'Xbox Live');
define('_xboxuserpic', 'Xbox Live Avatar:');
define('_psnid', 'Playstation Network');
define('_psnstatus', 'Playstation Network');
define('_skypeid', 'Skype Name');
define('_skypestatus', 'Skype');
define('_originid', 'Origin');
define('_originstatus', 'Origin');
define('_battlenetid', 'Battlenet');
define('_battlenetstatus', 'Battlenet');
define('_link' , 'Link');
define('_linkname' , 'Linkname');
define('_url' , 'URL');
define('_admin' , 'Admin');
define('_hits' , 'Hits');
define('_map' , 'Map');
define('_game' , 'Game');
define('_autor' , 'Author');
define('_yes' , 'Yes');
define('_no' , 'No');
define('_maybe' , 'Maybe');
define('_beschreibung' , 'Description');
define('_admin_user_get_identy' , 'You was successfully took the identity of [nick]!');
define('_comment_added' , 'The comment was successfully registered!');
define('_comment_deleted' , 'The comment was successfullydeleted!');
define('_stichwort' , 'Keyword');
define('_language_set' , 'You just set another language!');
define('_language_set_uk' , 'Unknown language');
define('_eintragen_titel' , 'insert');
define('_titel' , 'Title');
define('_bbcode' , 'BBCode');
define('_answer' , 'Answer');
define('_eintrag' , 'Entry');
define('_weiter' , 'forward');
define('_site_teamspeak' , 'Teamspeak');
define('_teamspeak' , 'Teamspeak');
define('_site_contact' , 'Contact form');
define('_site_joinus' , 'Joinus - Contact form');
define('_site_fightus' , 'Fightus - Contact form');
define('_joinus' , 'Joinus');
define('_fightus' , 'Fightus');
define('_site_msg_new' , 'You have new messages!<br />
                         Click <a href="../user/?action=msg">here</a> to go to the message center!');
define('_site_kalender' , 'Calendar');
define('_login_permanent' , ' Autologin');
define('_msg_del' , 'Delete marked');
define('_wartungsmodus' , 'This website is closed in case of maintenance work!<br />
Please try it again later!');
define('_wartungsmodus_head' , 'Maintenance modes');
define('_kalender' , 'Calender');
define('_ts_head' , 'Teamspeak');
define('_ts_name' , 'Server`s name');
define('_ts_os' , 'OS');
define('_ts_uptime' , 'Uptime');
define('_ts_channels' , 'Channels');
define('_ts_user' , 'User');
define('_ts_users_head' , 'User Informations');
define('_ts_player' , 'User');
define('_ts_channel' , 'Channel');
define('_ts_logintime' , 'Logged in since');
define('_ts_idletime' , 'AFK since');
define('_ts_channel_head' , 'Channel Informations');
define('_taktik_choose' , ' - Please choose - ');
define('_config_tmpdir' , 'Standardtemplate');
define('_template_set' , 'You choose a new template!');
define('_rankings_head' , 'Rankings');
define('_rankings_league' , 'League');
define('_rankings_place' , 'Place old/new');
define('_rankings_admin_place' , 'Place');
define('_rankings_squad' , 'Team');
define('_rankings_teamlink' , 'Teamlink');
define('_ranking_added' , 'The ranking was successfully registered!');
define('_ranking_edited' , 'The ranking was successfully edited!');
define('_ranking_deleted' , 'The ranking was successfully deleted!');
define('_ranking_empty_league' , 'You have to indicate a league!');
define('_ranking_empty_url' , 'You have to indicate the website of the league!');
define('_ranking_empty_rank' , 'You have to indicate a rank!');
define('_rankings_add_head' , 'Insert ranking');
define('_admin_rankings' , 'Admin: Rankings');
define('_navi_info' , 'Every link names shown in "_" (like _admin_) are placeholders, which will be used for the several languages!');
define('_shout_delete_successful' , 'The entry was successfully deleted from the shoutbox!');
define('_member_admin_intnews' , 'View internal news');
define('_news_admin_intern' , 'Internal News?');
define('_news_sticky' , '<span class="fontWichtig">Announcment:</span>');
define('_news_get_sticky' , 'Announce news?');
define('_news_sticky_till' , 'until:');
define('_cw_xonx' , 'XonX');
define('_forum_lp_head' , 'Last post');
define('_forum_previews' , 'Preview');
define('_download_size' , 'Filesize:');
define('_site_awards' , 'Awards');
define('_error_unregistered_nc' , '
<tr>
  <td class="contentMainFirst" colspan="2" align="center">
    <span class="fontBold">You have to be registered to write a comment!</span>
  </td>
</tr>');
define('_server_menu_austragen' , 'The server was successfully unset from the menu!');
define('_server_menu_eintragen' , 'The server was successfully set into the menu!');
define('_server_legendemenu' , 'Server is set into menu? (Click icon to change the status)<br />(Multiple entrys are possible!)');
define('_config_c_servernavi' , 'Menu: Serverstatus');
define('_upload_partners_head' , 'Partnerbuttons');
define('_upload_partners_info' , 'Only jpg, gif or png files. Recommended dimensions: 88px * 31px');
define('_user_banned' , 'Account banned');
define('_select_field_ranking_add' , '<option value="[value]" [sel]>[what]</option>');
define('_member_squad_wars' , '<a href="../clanwars/?action=showall&amp;id=[id]">Clanwars</a>');
define('_member_squad_awards' , '<a href="../awards/?action=showall&amp;id=[id]">Awards</a>');
define('_user_list_ck' , 'List in clan cash?');
define('_fightus_squad' , 'Wanted team');
?>
