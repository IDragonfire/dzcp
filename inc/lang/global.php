<?php
## ADDED FOR 1.5.5.3
define('_klapptext_link','<a href="javascript:DZCP.toggle(\'[id]\')"><img src="../inc/images/[moreicon].gif" alt="" id="img[id]">[link]</a>');
## ADDED / REDEFINED FOR 1.5.2
define('_dropdown_date_ts', '<select id="t_[nr]" name="t_[nr]" class="dropdown">[day]</select> <select id="m_[nr]" name="m_[nr]" class="dropdown">[month]</select> <select id="j_[nr]" name="j_[nr]" class="dropdown">[year]</select>');
define('_dropdown_time_ts', '<select id="h_[nr]" name="h_[nr]" class="dropdown">[hour]</select> <select id="min_[nr]" name="min_[nr]" class="dropdown">[minute]</select>[uhr]');
define('_cw_details_gegner', '<a href="[url]" title="[gegner]">[gegner]</a>');
define('_cw_details_gegner_blank', '<a href="[url]" title="[gegner]" target="_blank">[gegner]</a>');
## ADDED / REDEFINED FOR 1.5.1
define('_elevel_admin_select', '      
<option value="banned">[banned]</option>
<option value="1" [selu]>[ruser]</option>
<option value="2" [selt]>[trial]</option>
<option value="3" [selm]>[member]</option>
<option value="4" [sela]>[admin]</option>');
define('_elevel_perm_select', '
<option value="banned">[banned]</option>
<option value="1" [selu]>[ruser]</option>
<option value="2" [selt]>[trial]</option>
<option value="3" [selm]>[member]</option>');
## ADDED / REDEFINED FOR 1.5
define('_profil_edit_custom', '
<tr>
  <td class="contentMainTop" width="30%"><span class="fontBold">[name]</span></td>
  <td class="contentMainFirst" align="center">
    <input type="text" name="[feldname]" value="[value]" class="inputField_dis_profil"
    onfocus="this.className=\'inputField_en_profil\';"
    onblur="this.className=\'inputField_dis_profil\';" />
  </td>
</tr>');
define('_profil_custom_url', '
<tr>
  <td class="contentMainTop" width="20%"><span class="fontBold">[name]:</span></td>
  <td class="contentMainFirst" align="center"><a href="[value]" target="_blank" class="icon" />[value]</a></td>
</tr>');
define('_awards_event', '<a href="[url]" target="_blank">[event]</a>');
define('_sponsors_textlink', '<a href="../sponsors/?action=link&amp;id=[id]" target="_blank">[name]</a>');
define('_sponsors_bannerlink', '<a href="../sponsors/?action=link&amp;id=[id]" target="_blank" title="[title]"><img src="[banner]" alt="" /></a>');
define('_next_event_link', '[datum] - <a class="navLastReg" href="../kalender/?action=show&amp;time=[timestamp]">[event]</a>');
define('_user_link_blank', '[nick]');
define('_dropdown_date2', '<select id="tag" name="tag" class="dropdown">[tag]</select> <select id="monat" name="monat" class="dropdown">[monat]</select> <select id="jahr" name="jahr" class="dropdown">[jahr]</select>');
//Added for DZCP 1.4
define('_buddys_yesicon', '<img src="../inc/images/buddys_yes.gif" alt="" class="icon" />');
define('_buddys_noicon', '<img src="../inc/images/buddys_no.gif" alt="" class="icon" />');
define('_closedicon_votes', '<img src="../inc/images/closed_votes.gif" alt="" class="icon" />');
define('_icqstatus_forum', '<img src="../inc/images/forum_icq.gif" alt="" title="[uin]" class="icon" />');
define('_hpicon_forum', '<a href="[hp]" target="_blank"><img src="../inc/images/forum_hp.gif" alt="" title="[hp]" class="icon" /></a>');
define('_emailicon_forum', '<a href="mailto:[email]"><img src="../inc/images/forum_email.gif" alt="" title="[email]" class="icon" /></a>');
define('_server_menu_icon_yesno', '<a href="?admin=server&amp;do=menu&amp;id=[id]"><img src="../inc/images/server_yesno.gif" alt="" class="icon" /></a>');
define('_forum_pn_preview', '<img src="../inc/images/forum_pn.gif" alt="" class="icon" style="cursor:pointer" />');
define('_forum_zitat_preview', '<img src="../inc/images/zitat.gif" alt="" class="icon" style="cursor:pointer" />');
define('_user_link_preview', '[country] <a class="[class]" href="javascript:void(0)">[nick]</a>');
define('_userpic_link_raw', '<img src=../inc/images/uploads/userpics/[id].[endung] width=[width] height=[height] alt= />');
define('_no_userpic_raw', '<img src=../inc/images/nopic.gif width=[width] height=[height] alt= />');
define('_hlswicon', '<img src="../inc/images/xfire.gif" alt="" class="icon" onmouseover="DZCP.showInfo(\'<tr><td><img src=http://de.miniprofile.xfire.com/bg/bg/type/0/[id].png /></td></tr>\')" onmouseout="DZCP.hideInfo()" />');
//Edited for DZCP 1.4
define('_gal_newicon', '<a href="?admin=gallery&amp;do=new&amp;id=[id]"><img src="../inc/images/new.gif" alt="" title="[titel]" class="icon" /></a>');
define('_downloads_link', '<a href="?action=download&amp;id=[id]" style="display:block" title="[titel]"><img src="../inc/images/download.gif" alt="" class="icon" /> [download]</a>');
define('_forum_thread_search_link', '[sticky] <a href="../forum/?action=showthread&amp;id=[id]&amp;hl=[hl]">[topic]</a> [closed]');
define('_dropdown_time', '<select id="h" name="h" class="dropdown">[hour]</select> <select id="min" name="min" class="dropdown">[minute]</select>[uhr]');
define('_server_menu_icon_yes', '<a href="?admin=server&amp;do=menu&amp;id=[id]"><img src="../inc/images/server_no.gif" alt="" class="icon" /></a>');
define('_server_menu_icon_no', '<a href="?admin=server&amp;do=menu&amp;id=[id]"><img src="../inc/images/server_yes.gif" alt="" class="icon" /></a>');
define('_no_userpic_small_link', '<a href="../user/?action=user&amp;id=[id]"><img src="../inc/images/nopic.gif" width="60" height="80" alt="" /></a>');
////////////////////
## Allgemein ##
define('_user_link', '[country] <a class="[class]" href="../user/?action=user&amp;id=[id][get]">[nick]</a>');
define('_user_link_noreg', '<a class="[class]" href="mailto:[email]">[nick]</a>');
define('_link_mailto', '<a href="mailto:[email]">[nick]</a>');
define('_link_hp', '<a href="[hp]"><img src="../inc/images/hp.gif" alt="" title="[hp]" class="icon" /></a>');
define('_userava_link', '<img src="../inc/images/uploads/useravatare/[id].[endung]" width="[width]" height="[height]" alt="" />');
define('_no_userava', '<img src="../inc/images/noavatar.gif" width="[width]" height="[height]" alt="" />');
define('_userpic_link', '<img src="../inc/images/uploads/userpics/[id].[endung]" width="[width]" height="[height]" alt="" />');
define('_no_userpic', '<img src="../inc/images/nopic.gif" width="[width]" height="[height]" alt="" />');
## Icons ##
define('_closedicon', '<img src="../inc/images/closed.gif" alt="" class="icon" />');
define('_icqstatus', '<img src="http://web.icq.com/whitepages/online?icq=[uin]&amp;img=5" alt="" title="[uin]" class="icon" />');
define('_hpicon', '<a href="[hp]" target="_blank"><img src="../inc/images/hp.gif" alt="" title="[hp]" class="icon" /></a>');
define('_email_mailto', '<a href="mailto:[email]">[email]</a>');
define('_emailicon', '<a href="mailto:[email]"><img src="../inc/images/email.gif" alt="" title="[email]" class="icon" /></a>');
define('_steamicon_blank', '<img src="../inc/images/steam.gif" alt="" class="icon" />');
define('_emailicon_blank', '<img src="../inc/images/email.gif" alt="" class="icon" />');
define('_hlswicon_blank', '<img src="../inc/images/xfire.gif" alt="" class="icon" />');
define('_zitaticon', '<img src="../inc/images/zitat.gif" alt="" class="icon" />');
define('_hpicon_blank', '<img src="../inc/images/hp.gif" alt="" class="icon" />');
define('_topicon', '<img src="../inc/images/top.gif" alt="" class="icon" />');
define('_icqicon_blank', '<img src="../inc/images/icq.gif" alt="" class="icon" />');
define('_mficon_blank', '<img src="../inc/images/mf.gif" alt="" class="icon" />');
define('_maleicon', '<img src="../inc/images/male.gif" alt="" class="icon" />');
define('_femaleicon', '<img src="../inc/images/female.gif" alt="" class="icon" />');
define('_pnicon_blank', '<img src="../inc/images/pn.gif" alt="" class="icon" />');
define('_yesno', '<img src="../inc/images/yesno.gif" alt="" class="icon" />');
define('_cw_stats_won_icon', '<img src="../inc/images/won.gif" alt="" class="icon" />');
define('_cw_stats_lost_icon', '<img src="../inc/images/lost.gif" alt="" class="icon" />');
define('_cw_stats_draw_icon', '<img src="../inc/images/draw.gif" alt="" class="icon" />');
define('_yesicon', '<img src="../inc/images/yes.gif" alt="" class="icon" />');
define('_noicon', '<img src="../inc/images/no.gif" alt="" class="icon" />');
define('_newicon', '<img src="../inc/images/forum_newpost.gif" alt="" class="icon" />');
define('_notnewicon', '<img src="../inc/images/notnew.gif" alt="" class="icon" />');
define('_deleteicon_blank', '<img alt="" src="../inc/images/delete.gif" class="icon" />');
define('_buddys_delete', '<a href="?action=buddys&amp;do=delete&amp;id=[id]"><img src="../inc/images/delete.gif" alt="" class="icon" /></a>');
define('_addbuddyicon_blank', '<img alt="" src="../inc/images/add.gif" class="icon" />');
define('_editicon_blank', '<img alt="" src="../inc/images/edit.gif" class="icon" />');
define('_addbuddyicon', '<a href="../user/?action=buddys&amp;do=addbuddy&amp;id=[id]"><img alt="" src="../inc/images/add.gif" class="icon" /></a>');
define('_gameicon', '<img alt="" src="../inc/images/gameicons/[icon]" class="icon" />');
define('_gallery_editicon', '<a href="../upload/?action=usergallery&amp;do=edit&gid=[id]"><img alt="" src="../inc/images/edit.gif" title="Edit" class="icon" /></a>');
define('_admin_default_edit', '<a href="?action=admin&amp;edit=[id]"><img src="../inc/images/edit.gif" alt="" title="Edit" class="icon" /></a>');
define('_admin_ck_edit', '<a href="?action=admin&amp;do=paycheck&amp;id=[id]"><img src="../inc/images/edit.gif" alt="" title="Edit" class="icon" /></a>');
define('_msg_delete_sended', '<a href="?action=msg&amp;do=deletesended&amp;id=[id]"><img alt="" src="../inc/images/delete.gif" title="Delete" class="icon" /></a>');
define('_gallery_deleteicon', '<a href="?action=editprofile&gallery=delete&gid=[id]"><img alt="" src="../inc/images/delete.gif" title="Delete" class="icon" /></a>');
define('_delete', '<a href="?action=msg&amp;do=deletethis&amp;id=[id]"><img alt="" src="../inc/images/delete.gif" title="Delete" class="icon" /></a>');
define('_forum_delete', '<a href="?action=post&amp;do=delete&amp;id=[id]"><img alt="" src="../inc/images/delete.gif" title="Delete" class="icon" /></a>');
define('_newsc_delete', '<a href="?action=show&amp;id=[id]&amp;do=delete&cid=[cid]"><img alt="" src="../inc/images/delete.gif" title="Delete" class="icon" /></a>');
define('_cwc_delete', '<a href="?action=details&amp;id=[id]&amp;do=delete&cid=[cid]"><img alt="" src="../inc/images/delete.gif" title="Delete" class="icon" /></a>');
## News ##
define('_news_kat', '<img src="../inc/images/newskat/[img]" alt="" />');
define('_news_klapplink', '<br /><a href="javascript:DZCP.toggle(\'[id]\')"><img src="../inc/images/[which].gif" id="img[id]" alt="" class="icon" /> [klapplink]</a>');
define('_news_links', '<span class="fontItalic">[rel]</span><br />[link1] [link2] [link3]');
define('_artikel_links', '<span class="fontItalic">[rel]</span><br />[link1] [link2] [link3]');
define('_news_link', '<span class="fontBold">&raquo;</span> <a href="[url]" target="_blank">[link]</a> ');
define('_news_show_link', '<a href="../news/?action=show&amp;id=[id]">[titel]</a>');
define('_news_stats', 'Insgesamt <span class="fontBold">[news] News</span> mit <span class="fontBold">[comments] [com]</span>');
define('_news_com', '#');
## Artikel ##
define('_artikel_link', '<span class="fontBold">&raquo;</span> <a href="[url]" target="_blank">[link]</a> ');
## Forum ##
define('_forum_thread_link', '[global] [sticky] <a href="?action=showthread&amp;kid=[kid]&amp;id=[id]">[topic]</a> [closed] <a href="?action=showthread&amp;id=[id]&amp;page=[page]#p[lpid]">&raquo;</a>');
define('_forum_dowhat_add_thread', 'addthread&amp;kid=[kid]');
define('_forum_add_lastpost', '?action=showthread&amp;id=[tid]&amp;page=[page]#p[id]');
define('_forum_dowhat_add_post', 'addpost&amp;kid=[kid]&amp;id=[id]');
define('_forum_avatar', '<img src="../inc/images/uploads/useravatare/[id].[endung]" border="1" width="100" height="100" alt="" />');
define('_forum_dowhat_edit_thread', 'editthread&amp;id=[id]');
define('_forum_dowhat_edit_post', 'editpost&amp;id=[id]');
define('_forum_select_field_kat', '<option value="[value]" class="dropdownKat">[what]</option> [skat]');
define('_forum_select_field_skat', '<option value="[value]">-> [what]</option>');
define('_forum_select_field_search', '<option value="[value]" [sel]>-> [what]</option>');
## Gästebuch ##
define('_gb_commenticon', '<a href="?action=admin&amp;do=addcomment&amp;id=[id]"><img src="../inc/images/comment.gif" alt="" title="[title]" class="icon" /></a>');
## DropDownmenu-Datum/Zeit ##
define('_dropdown_date', '<select id="t" name="t" class="dropdown">[day]</select> <select id="m" name="m" class="dropdown">[month]</select> <select id="j" name="j" class="dropdown">[year]</select>');
## Umfragen ##
define('_votes_titel', '<a href="javascript:DZCP.toggle(\'[vid]\')"><img src="../inc/images/[icon].gif" alt="" id="img[vid]" class="icon" />[intern][titel]</a>');
define('_votes_balken', '<img src="../inc/images/vote.gif" width="[width]%" height="4" alt="[width]%" />');
## Downloads ##
define('_downloads_files_exists', '<option value="[dl]" [sel]>[dl]</option>');
## Links ##
define('_links_textlink', '<center><a href="?action=link&amp;id=[id]" target="_blank">[text]</a></center>');
define('_links_bannerlink', '<center><a href="?action=link&amp;id=[id]" target="_blank"><img src="[banner]" alt="" /></a></center>');
## Squads ##
define('_member_squad_squadlink', '<a href="javascript:DZCP.toggle(\'[id]\')">[squad]</a>');
define('_userpic_small_link', '<a href="../user/?action=user&amp;id=[id]"><img src="../inc/images/uploads/userpics/[id].[endung]" width="60" height="80" alt="" /></a>');
define('_no_userpic_small', '<img src="../inc/images/nopic.gif" width="60" height="80" alt="" />');
## Clanwars ##
define('_cw_details_squad', '[img] [game] - <a href="../squads/?showsquad=[id]">[name]</a>');
define('_cw_details_server', '<a href="hlsw://[serverip]">[servername] - [serverip]</a>');
define('_cw_no_results', '
<td class="contentMainFirst"></td>
<td class="contentMainFirst" colspan="2"></td>');
define('_cw_add_select_field_squads', '<option value="[id]"> [name]</option>');
define('_cw_edit_select_field_squads', '<option value="[id]" [sel]> [name]</option>');
define('_cw_show_details', '<a href="?action=details&amp;id=[id]"><img src="../inc/images/details.gif" alt="" class="icon" /></a>');
define('_cw_legende', '<tr><td class="[class]" align="center" width="1%">[img]</td><td class="[class]" colspan="10">[game]</td></tr>');
## Awards ##
define('_awards_erster_img', '<img src="../inc/images/1st.gif" alt="" class="icon" /> (1)');
define('_awards_zweiter_img', '<img src="../inc/images/2nd.gif" alt="" class="icon" /> (2)');
define('_awards_dritter_img', '<img src="../inc/images/3rd.gif" alt="" class="icon" /> (3)');
define('_awards_admin_add_select_field_squads', '<option value="[id]"> [name]</option>');
define('_awards_admin_edit_select_field_squads', '<option value="[id]" [sel]> [name]</option>');
define('_awards_legende', '<tr><td class="[class]" align="center" width="1%">[img]</td><td class="[class]" colspan="6">[game]</td></tr>');
## Serverliste ##
define('_slist_clanname_with_url', '<a href="[url]" target="blank">[name]</a>');
define('_slist_clanname_without_url', '<span class="fontBold">[name]</span>');
## Gametiger ##
define('_gametiger_player_results', '<tr><td class="[class]" colspan="4">[players]</td><td class="[class]" colspan="4"><a href="hlsw://[servers]">[servers]</a></td></tr>');
define('_gametiger_server_results', '<tr><td class="[class]"><a href="hlsw://[serverip]">[servername]</a></td><td class="[class]" width="2%" align="center">[map]</td><td width="2%"class="[class]" align="center">[players]</td></tr>');
define('_gametiger_map_results', '<tr><td class="[class]"><a href="hlsw://[ip]">[name]</a></td><td class="[class]" width="2%" align="center">[aktmap]</td><td width="2%" class="[class]" align="center">[player]</td></tr>');
## Kontaktformulare ##
define('_contact_hp', '<a href="[hp]" target="_blank">[hp]</a>');
## Linkus ##
define('_linkus_bannerlink', '<a href="[url]" target="_blank"><img src="[banner]" alt="[besch]" title="[besch]"></a><p><textarea class="inputField_dis" onfocus="this.className=\'inputField_en\';this.select();" onblur="this.className=\'inputField_dis\';" readonly="yes" name="textarea" style="height: 40px; width: 80%;"><a href="[url]" target="blank"><img src="[banner]" alt="[besch]" title="[besch]"></a></textarea>');
define('_linkus_admin', '<a href="?action=admin&amp;do=new">[new]</a>');
## Admin ##
define('_news_edit_link', "editnews&amp;id=[id]");
define('_artikel_edit_link', 'editartikel&amp;id=[id]');
define('_config_delete', '<a href="?admin=[what]&amp;do=delete&amp;id=[id]"><img src="../inc/images/delete.gif" alt="" class="icon" /></a>');
define('_config_edit', '<a href="?admin=[what]&amp;do=edit&amp;id=[id]"><img src="../inc/images/edit.gif" alt="" class="icon" /></a>');
define('_config_forum_kats_titel', '<a href="?admin=forum&amp;show=subkats&amp;id=[id]" style="display:block">[kat]</a>');
define('_config_newskats_img', '<img src="../inc/images/newskat/[img]" alt="" />');
define('_config_neskats_katbild_upload', '<a href="../upload/?action=newskats">upload</a>');
define('_config_neskats_katbild_upload_edit', '<a href="../upload/?action=newskats&amp;edit=[id]">upload</a>');
define('_config_newskats_editid', 'editnewskat&amp;id=[id]');
define('_icon_edit_news', '<a href="?admin=newsadmin&amp;do=edit&amp;id=[id]"><img alt="" src="../inc/images/edit.gif" class="icon" /></a>');
define('_icon_delete_news', '<a href="?admin=newsadmin&amp;do=delete&amp;id=[id]"><img alt="" src="../inc/images/delete.gif" class="icon" /></a>');
define('_icon_edit_squads', '<a href="?admin=squads&amp;do=edit&amp;id=[id]"><img alt="" src="../inc/images/edit.gif" class="icon" /></a>');
define('_icon_delete_squads', '<a href="?admin=squads&amp;do=delete&amp;id=[id]"><img alt="" src="../inc/images/delete.gif" class="icon" /></a>');
define('_member_admin_icon_upload', '<a href="../upload/">upload</a>');
define('_icon_delete_slist', '<a href="?admin=serverlist&amp;do=delete&amp;id=[id]"><img alt="" src="../inc/images/delete.gif" class="icon" /></a>');
define('_server_show_bannlist', '<a href="?action=banned&amp;id=[id]"><img src="../inc/images/rows.gif" alt="" class="icon" /> Bannliste</a>');
define('_checkfield_squads', '
<tr>
  <td><input class="checkbox" type="checkbox" id="squad_[id]" name="squad[id]" value="[id]" [check] /><label for="squad_[id]"> [squad]</label></td>
  <td align="center">
    <select name="sqpos[id]" class="dropdown">
      [noposi]
      [eposi]
    </select>
  </td>
</tr>');
define('_select_field_posis', '<option value="[value]" [sel]>[what]</option>');
define('_select_field_waehrung', '
<option value="ADF">(ADF) Andorran Franc</option>
<option value="ADP">(ADP) Andorran Peseta</option>
<option value="AED">(AED) Utd. Arab Emir. Dirham</option>
<option value="AFA">(AFA) Afghanistan Afghani</option>
<option value="ALL">(ALL) Albanian Lek</option>
<option value="ANG">(ANG) NL Antillian Guilder</option>
<option value="AON">(AON) Angolan New Kwanza</option>
<option value="ARS">(ARS) Argentine Peso</option>
<option value="AUD">(AUD) Australian Dollar</option>
<option value="AWG">(AWG) Aruban Florin</option>
<option value="BBD">(BBD) Barbados Dollar</option>
<option value="BDT">(BDT) Bangladeshi Taka</option>
<option value="BHD">(BHD) Bahraini Dinar</option>
<option value="BIF">(BIF) Burundi Franc</option>
<option value="BMD">(BMD) Bermudian Dollar</option>
<option value="BND">(BND) Brunei Dollar</option>
<option value="BOB">(BOB) Bolivian Boliviano</option>
<option value="BRL">(BRL) Brazilian Real</option>
<option value="BSD">(BSD) Bahamian Dollar</option>
<option value="BTN">(BTN) Bhutan Ngultrum</option>
<option value="BWP">(BWP) Botswana Pula</option>
<option value="BZD">(BZD) Belize Dollar</option>
<option value="CAD">(CAD) Canadian Dollar</option>
<option value="CHF">(CHF) Swiss Franc</option>
<option value="CLP">(CLP) Chilean Peso</option>
<option value="CNY">(CNY) Chinese Yuan Renminbi</option>
<option value="COP">(COP) Colombian Peso</option>
<option value="CRC">(CRC) Costa Rican Colon</option>
<option value="CUP">(CUP) Cuban Peso</option>
<option value="CVE">(CVE) Cape Verde Escudo</option>
<option value="CYP">(CYP) Cyprus Pound</option>
<option value="CZK">(CZK) Czech Koruna</option>
<option value="DJF">(DJF) Djibouti Franc</option>
<option value="DOP">(DOP) Dominican R. Peso</option>
<option value="DZD">(DZD) Algerian Dinar</option>
<option value="ECS">(ECS) Ecuador Sucre</option>
<option value="EEK">(EEK) Estonian Kroon</option>
<option value="EGP">(EGP) Egyptian Pound</option>
<option value="ETB">(ETB) Ethiopian Birr</option>
<option value="&euro;">(EUR) Euro</option>
<option value="FJD">(FJD) Fiji Dollar</option>
<option value="FKP">(FKP) Falkland Islands Pound</option>
<option value="GBP">(GBP) British Pound</option>
<option value="GHC">(GHC) Ghanaian Cedi</option>
<option value="GIP">(GIP) Gibraltar Pound</option>
<option value="GMD">(GMD) Gambian Dalasi</option>
<option value="GNF">(GNF) Guinea Franc</option>
<option value="GTQ">(GTQ) Guatemalan Quetzal</option>
<option value="GYD">(GYD) Guyanese Dollar</option>
<option value="HKD">(HKD) Hong Kong Dollar</option>
<option value="HNL">(HNL) Honduran Lempira</option>
<option value="HRK">(HRK) Croatian Kuna</option>
<option value="HTG">(HTG) Haitian Gourde</option>
<option value="HUF">(HUF) Hungarian Forint</option>
<option value="IDR">(IDR) Indonesian Rupiah</option>
<option value="ILS">(ILS) Israeli New Shekel</option>
<option value="INR">(INR) Indian Rupee</option>
<option value="IQD">(IQD) Iraqi Dinar</option>
<option value="IRR">(IRR) Iranian Rial</option>
<option value="ISK">(ISK) Iceland Krona</option>
<option value="JMD">(JMD) Jamaican Dollar</option>
<option value="JOD">(JOD) Jordanian Dinar</option>
<option value="JPY">(JPY) Japanese Yen</option>
<option value="KES">(KES) Kenyan Shilling</option>
<option value="KHR">(KHR) Cambodian Riel</option>
<option value="KMF">(KMF) Comoros Franc</option>
<option value="KPW">(KPW) North Korean Won</option>
<option value="KRW">(KRW) South-Korean Won</option>
<option value="KWD">(KWD) Kuwaiti Dinar</option>
<option value="KYD">(KYD) Cayman Islands Dollar</option>
<option value="KZT">(KZT) Kazakhstan Tenge</option>
<option value="LAK">(LAK) Lao Kip</option>
<option value="LBP">(LBP) Lebanese Pound</option>
<option value="LKR">(LKR) Sri Lanka Rupee</option>
<option value="LRD">(LRD) Liberian Dollar</option>
<option value="LSL">(LSL) Lesotho Loti</option>
<option value="LTL">(LTL) Lithuanian Litas</option>
<option value="LVL">(LVL) Latvian Lats</option>
<option value="LYD">(LYD) Libyan Dinar</option>
<option value="MAD">(MAD) Moroccan Dirham</option>
<option value="MGF">(MGF) Malagasy Ariary</option>
<option value="MMK">(MMK) Myanmar Kyat</option>
<option value="MNT">(MNT) Mongolian Tugrik</option>
<option value="MOP">(MOP) Macau Pataca</option>
<option value="MRO">(MRO) Mauritanian Ouguiya</option>
<option value="MTL">(MTL) Maltese Lira</option>
<option value="MUR">(MUR) Mauritius Rupee</option>
<option value="MVR">(MVR) Maldive Rufiyaa</option>
<option value="MWK">(MWK) Malawi Kwacha</option>
<option value="MXN">(MXN) Mexican Peso</option>
<option value="MYR">(MYR) Malaysian Ringgit</option>
<option value="MZM">(MZM) Mozambique Metical</option>
<option value="NAD">(NAD) Namibia Dollar</option>
<option value="NGN">(NGN) Nigerian Naira</option>
<option value="NIO">(NIO) Nicaraguan Cordoba Oro</option>
<option value="NLG">(NLG) Dutch Guilder</option>
<option value="NOK">(NOK) Norwegian Kroner</option>
<option value="NPR">(NPR) Nepalese Rupee</option>
<option value="NZD">(NZD) New Zealand Dollar</option>
<option value="OMR">(OMR) Omani Rial</option>
<option value="PAB">(PAB) Panamanian Balboa</option>
<option value="PEN">(PEN) Peruvian Nuevo Sol</option>
<option value="PGK">(PGK) Papua New Guinea Kina</option>
<option value="PHP">(PHP) Philippine Peso</option>
<option value="PKR">(PKR) Pakistan Rupee</option>
<option value="PLN">(PLN) Polish Zloty</option>
<option value="PTE">(PTE) Portuguese Escudo</option>
<option value="PYG">(PYG) Paraguay Guarani</option>
<option value="QAR">(QAR) Qatari Rial</option>
<option value="ROL">(ROL) Romanian Lei</option>
<option value="RUB">(RUB) Russian Rouble</option>
<option value="SAR">(SAR) Saudi Riyal</option>
<option value="SBD">(SBD) Solomon Islands Dollar</option>
<option value="SCR">(SCR) Seychelles Rupee</option>
<option value="SDD">(SDD) Sudanese Dinar</option>
<option value="SDP">(SDP) Sudanese Pound</option>
<option value="SEK">(SEK) Swedish Krona</option>
<option value="SGD">(SGD) Singapore Dollar</option>
<option value="SHP">(SHP) St. Helena Pound</option>
<option value="SIT">(SIT) Slovenian Tolar</option>
<option value="SKK">(SKK) Slovak Koruna</option>
<option value="SLL">(SLL) Sierra Leone Leone</option>
<option value="SOS">(SOS) Somali Shilling</option>
<option value="STD">(STD) Sao Tome/Principe Dobra</option>
<option value="SVC">(SVC) El Salvador Colon</option>
<option value="SYP">(SYP) Syrian Pound</option>
<option value="SZL">(SZL) Swaziland Lilangeni</option>
<option value="THB">(THB) Thai Baht</option>
<option value="TND">(TND) Tunisian Dinar</option>
<option value="TOP">(TOP) Tonga Pa"anga</option>
<option value="TRL">(TRL) Turkish Lira</option>
<option value="TTD">(TTD) Trinidad/Tobago Dollar</option>
<option value="TWD">(TWD) Taiwan Dollar</option>
<option value="TZS">(TZS) Tanzanian Shilling</option>
<option value="UAH">(UAH) Ukraine Hryvnia</option>
<option value="UGS">(UGS) Uganda Shilling</option>
<option value="$">(USD) US Dollar</option>
<option value="UYP">(UYP) Uruguayan Peso</option>
<option value="VEB">(VEB) Venezuelan Bolivar</option>
<option value="VND">(VND) Vietnamese Dong</option>
<option value="VUV">(VUV) Vanuatu Vatu</option>
<option value="WST">(WST) Samoan Tala</option>
<option value="XAF">(XAF) CFA Franc BEAC</option>
<option value="XAG">(XAG) Silver (oz.)</option>
<option value="XAU">(XAU) Gold (oz.)</option>
<option value="XCD">(XCD) East Caribbean Dollar</option>
<option value="XEU">(XEU) ECU</option>
<option value="XOF">(XOF) CFA Franc BCEAO</option>
<option value="XPD">(XPD) Palladium (oz.)</option>
<option value="XPF">(XPF) CFP Franc</option>
<option value="XPT">(XPT) Platinum (oz.)</option>
<option value="YER">(YER) Yemeni Rial</option>
<option value="YUN">(YUN) Yugoslav Dinar</option>
<option value="ZAR">(ZAR) South African Rand</option>
<option value="ZMK">(ZMK) Zambian Kwacha</option>
<option value="ZWD">(ZWD) Zimbabwe Dollar</option>');
## Userprofile ##
define('_profil_custom', '
<tr>
  <td class="contentMainTop" width="20%"><span class="fontBold">[name]:</span></td>
  <td class="contentMainFirst" align="center">[value]</td>
</tr>');
define('_profil_custom_mail', '
<tr>
  <td class="contentMainTop" width="20%"><span class="fontBold">[name]:</span></td>
  <td class="contentMainFirst" align="center"><img src="../inc/images/mailto.gif" alt="" class="icon" /> <a href="mailto:[value]">[value]</a></td>
</tr>');
## Userprofil editieren ##
define('_profil_head_cont', '
<tr>
  <td class="contentMainTop" colspan="4" align="center"><span class="fontBold">[what]</span></td>
</tr>');
## User ##
define('_msg_in_title', '<a href="?action=msg&amp;do=show&amp;id=[id]">[titel]</a>');
define('_msg_out_title', '<a href="?action=msg&amp;do=showsended&amp;id=[id]">[titel]</a>');
define('_to_buddys', '<option value=[id] [selected="selected"]>[nick]</option>');
define('_to_users', '<option value=[id] [selected="selected"]>[nick]</option>');
define('_to_squads', '<option value="[id]" [sel]>-> [name]</option>');
define('_gallery_pic_link', '<img src="../inc/images/uploads/usergallery/[user]_[img]" alt="" />');
define('_gallery_edit_unlink', '../inc/images/uploads/usergallery/[user]_[img]');
define('_user_new_forum', '&nbsp;&nbsp;<a href="../forum/?action=showthread&amp;id=[tid]&amp;page=[page]#p[lp]">[intern][wichtig]<span class="fontWichtig">[cnt]</span> [post] [nthread] <span class="fontWichtig">[thread]</span></a><br />');
define('_user_new_gb', '&nbsp;&nbsp;<a href="../gb/"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_users', '&nbsp;&nbsp;<a href="?action=userlist&amp;show=newreg"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_membergb', '&nbsp;&nbsp;<a href="?action=user&amp;id=[id]&amp;show=gb"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_news', '&nbsp;&nbsp;<a href="../news/"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_clanwar', '&nbsp;&nbsp;<a href="../clanwars/?action=details&amp;id=[id]#lastcomment"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_msg', '&nbsp;&nbsp;<a href="?action=msg"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_votes', '&nbsp;&nbsp;<a href="../votes/"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_awards', '&nbsp;&nbsp;<a href="../awards/"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_user_new_rankings', '&nbsp;&nbsp;<a href="../rankings/"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_select_field', '<option value="[value]" [sel]> [what]</option>');
define('"_shout_nick"', "<a href='mailto:[email]'>[nick]</a>");
define('_user_new_gallery', '&nbsp;&nbsp;<a href="../gallery/"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
## Unzugeordnet ##
define('_user_new_artc', '&nbsp;&nbsp;<a href="../artikel/?action=show&amp;id=[id]#lastcomment"><span class="fontWichtig">[cnt]</span> [eintrag]</a><br />');
define('_artike_sites', '<a href="?action=show&amp;id=[id]&part=[part]">[num]</a> ');
## Taktiken ##
define('_taktik_select', '<option value="[iconimg]">[iconimg]</option>');
define('_upload_image_taktiken', '<img src="../inc/images/uploads/taktiken/[file]" alt="" />');
## Sonstiges ##
define('_klapptext_show', '<a href="javascript:DZCP.toggle(\'[id]\')"><img id="img[id]" src="../inc/images/collapse.gif" alt="" class="icon" /></a>');
define('_klapptext_dont_show', '<a href="javascript:DZCP.toggle(\'[id]\')"><img id="img[id]" src="../inc/images/expand.gif" alt="" class="icon" /></a>');
define('_klapptext_show_link', '<a href="javascript:DZCP.toggle(\'[id]\')"><img id="img[id]" src="../inc/images/expand.gif" alt="" class="icon" />[link]</a>');
define('_select_field_fightus', '<option value="[id]">[squad] ([game])</option>');
?>