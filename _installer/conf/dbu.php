<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */


/*
 * UPDATES
 */
$lines_dbu = array(); $data_dbu = array();
/************** Artikel Kommentare **************/
$lines_dbu['acomments'] = array('nick','email','hp','comment','editby');
$data_dbu['acomments']  = array('nick'    => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                'email'   => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                                'hp'      => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                'comment' => array('TEXT','NOT NULL',true),
                                'ip'      => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                                'editby'  => array('TEXT','NULL DEFAULT NULL',false));

/************** Artikel **************/
$lines_dbu['artikel'] = array('titel','text','link1','url1','link2','url2','link3','url3');
$data_dbu['artikel']  = array('titel' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                              'text'  => array('TEXT','NOT NULL',true),
                              'link1' => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                              'url1'  => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                              'link2' => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                              'url2'  => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                              'link3' => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                              'url3'  => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false));

/************** Awards **************/
$lines_dbu['awards'] = array('event','place','prize','url');
$data_dbu['awards']  = array('event' => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                             'place' => array('VARCHAR(5)','NOT NULL DEFAULT \'\'',false),
                             'prize' => array('TEXT','NULL DEFAULT NULL',false),
                             'url'   => array('TEXT','NULL DEFAULT NULL',false));

/************** Abwesenheitsliste **************/
$lines_dbu['away'] = array('titel','reason','lastedit');
$data_dbu['away']  = array('titel'    => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                           'reason'   => array('TEXT','NULL DEFAULT NULL',true),
                           'lastedit' => array('TEXT','NULL DEFAULT NULL',false));

/************** User Buddys **************/
$data_dbu['buddys'] = ($lines_dbu['buddys'] = array());

/************** Counter IPs **************/
$lines_dbu['c_ips'] = array('ip');
$data_dbu['c_ips']  = array('ip' => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false));

/************** Clan Kasse Kats **************/
$lines_dbu['c_kats'] = array('kat');
$data_dbu['c_kats']  = array('kat' => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false));

/************** Clan Kasse Payed **************/
$lines_dbu['c_payed'] = array('payed');
$data_dbu['c_payed']  = array('payed' => array('VARCHAR(20)','NOT NULL DEFAULT \'0\'',false));

/************** Counter Whoison **************/
$lines_dbu['c_who'] = array('ip','whereami');
$data_dbu['c_who']  = array('ip'       => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                            'whereami' => array('TEXT','NULL DEFAULT NULL',false));

/************** Clan Kasse **************/
$lines_dbu['clankasse'] = array('member','transaktion');
$data_dbu['clankasse']  = array('member'      => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                'transaktion' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false));

/************** Global Config **************/
$data_dbu['config']  = ($lines_dbu['config'] = array());

/************** Counter **************/
$lines_dbu['counter'] = array('today');
$data_dbu['counter']  = array('today' => array('VARCHAR(10)','NOT NULL DEFAULT \'0\'',false));

/************** Clanwars **************/
$lines_dbu['cw'] = array('gametype','gcountry','matchadmins','lineup','glineup','clantag','gegner','url','xonx','liga','maps','serverip','servername','serverpwd','bericht');
$data_dbu['cw']  = array('gametype'    => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                         'gcountry'    => array('VARCHAR(20)','NOT NULL DEFAULT \'de\'',false),
                         'matchadmins' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                         'lineup'      => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                         'glineup'     => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                         'clantag'     => array('VARCHAR(20)','NOT NULL DEFAULT \'\'',false),
                         'gegner'      => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                         'url'         => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                         'xonx'        => array('VARCHAR(10)','NOT NULL DEFAULT \'\'',false),
                         'liga'        => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false),
                         'maps'        => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false),
                         'serverip'    => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                         'servername'  => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                         'serverpwd'   => array('VARCHAR(20)','NOT NULL DEFAULT \'\'',false),
                         'bericht'     => array('TEXT','NULL DEFAULT NULL',false));

/************** Clanwars Kommentare **************/
$lines_dbu['cw_comments'] = array('nick','email','hp','comment','editby');
$data_dbu['cw_comments']  = array('nick'    => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                  'email'   => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                                  'hp'      => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                  'comment' => array('TEXT','NOT NULL',true),
                                  'ip'      => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                                  'editby'  => array('TEXT','NULL DEFAULT NULL',false));

/************** Clanwars Player **************/
$data_dbu['cw_player']  = ($lines_dbu['cw_player'] = array());

/************** Download Kats **************/
$lines_dbu['dl_kat'] = array('name');
$data_dbu['dl_kat']  = array('name' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false));

/************** Downloads **************/
$lines_dbu['downloads'] = array('download','url','beschreibung');
$data_dbu['downloads']  = array('download'     => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                                'url'          => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                                'beschreibung' => array('TEXT','NULL DEFAULT NULL',false));

/************** Events **************/
$lines_dbu['events'] = array('title','event');
$data_dbu['events']  = array('title' => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false),
                             'event' => array('TEXT','NULL DEFAULT NULL',false));

/************** Forum Abo **************/
$data_dbu['f_abo'] = ($lines_dbu['f_abo'] = array());

/************** Forum Access **************/
$data_dbu['f_access'] = ($lines_dbu['f_access'] = array());

/************** Forum Kats **************/
$lines_dbu['f_kats'] = array('name');
$data_dbu['f_kats']  = array('name' => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false));

/************** Forum Posts **************/
$lines_dbu['f_posts'] = array('nick','email','text','edited','ip','hp');
$data_dbu['f_posts']  = array('nick'   => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                              'email'  => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                              'text'   => array('TEXT','NULL DEFAULT NULL',false),
                              'edited' => array('TEXT','NULL DEFAULT NULL',false),
                              'ip'     => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                              'hp'     => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false));

/************** Forum SubKats **************/
$lines_dbu['f_skats'] = array('kattopic','subtopic');
$data_dbu['f_skats']  = array('kattopic' => array('VARCHAR(150)','NOT NULL DEFAULT \'\'',false),
                              'subtopic' => array('VARCHAR(150)','NOT NULL DEFAULT \'\'',false));

/************** Forum Threads **************/
$lines_dbu['f_threads'] = array('topic','subtopic','t_nick','t_email','t_text','edited','ip','t_hp','vote');
$data_dbu['f_threads']  = array('topic'    => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                                'subtopic' => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                                't_nick'   => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                't_email'  => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                                't_text'   => array('TEXT','NULL DEFAULT NULL',false),
                                'edited'   => array('TEXT','NULL DEFAULT NULL',false),
                                'ip'       => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                                't_hp'     => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                                'vote'     => array('VARCHAR(10)','NOT NULL DEFAULT \'\'',false));

/************** Gallery **************/
$lines_dbu['gallery'] = array('kat','beschreibung');
$data_dbu['gallery']  = array('kat'          => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                              'beschreibung' => array('TEXT','NULL DEFAULT NULL',true));

/************** Gastebuch **************/
$lines_dbu['gb'] = array('nick','email','hp','nachricht','ip','editby');
$data_dbu['gb']  = array('nick'      => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                         'email'     => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                         'hp'        => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                         'nachricht' => array('TEXT','NULL DEFAULT NULL',true),
                         'ip'        => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                         'editby'    => array('TEXT','NULL DEFAULT NULL',true));

/************** Glossar **************/
$lines_dbu['glossar'] = array('word','glossar');
$data_dbu['glossar']  = array('word'      => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                              'glossar'   => array('TEXT','NULL DEFAULT NULL',true));

/************** IPCheck **************/
$lines_dbu['ipcheck'] = array('ip','what');
$data_dbu['ipcheck']  = array('ip'   => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                              'what' => array('VARCHAR(40)','NOT NULL DEFAULT \'\'',false));

/************** Links **************/
$lines_dbu['links'] = array('url','text','beschreibung');
$data_dbu['links']  = array('url'          => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                            'text'         => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                            'beschreibung' => array('TEXT','NULL DEFAULT NULL',true));

/************** LinkUS **************/
$lines_dbu['linkus'] = array('url','text','beschreibung');
$data_dbu['linkus']  = array('url'          => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                             'text'         => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                             'beschreibung' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false));

/************** ( PN ) - Messages **************/
$lines_dbu['msg'] = array('titel','nachricht');
$data_dbu['msg']  = array('titel'     => array('VARCHAR(80)','NOT NULL DEFAULT \'\'',false),
                          'nachricht' => array('TEXT','NULL DEFAULT NULL',false));

/************** Navigation **************/
$lines_dbu['navi'] = array('kat','name','url');
$data_dbu['navi']  = array('kat'  => array('VARCHAR(20)','NOT NULL DEFAULT \'\'',false),
                           'name' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                           'url'  => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false));

/************** Navigation Kats **************/
$lines_dbu['navi_kats'] = array('name','placeholder');
$data_dbu['navi_kats']  = array('name'        => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                                'placeholder' => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false));

/************** News **************/
$lines_dbu['news'] = array('titel','text','klapplink','klapptext','link1','url1','link2','url2','link3','url3');
$data_dbu['news']  = array('titel'     => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                           'text'      => array('TEXT','NULL DEFAULT NULL',false),
                           'klapplink' => array('VARCHAR(20)','NOT NULL DEFAULT \'\'',false),
                           'klapptext' => array('TEXT','NULL DEFAULT NULL',false),
                           'link1'     => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                           'link2'     => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                           'link3'     => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                           'url1'      => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                           'url2'      => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                           'url3'      => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false));

/************** News Kommentare **************/
$lines_dbu['newscomments'] = array('nick','email','hp','comment','ip','editby');
$data_dbu['newscomments']  = array('nick'    => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                   'email'   => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                                   'hp'      => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                                   'comment' => array('TEXT','NULL DEFAULT NULL',false),
                                   'ip'      => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                                   'editby'  => array('TEXT','NULL DEFAULT NULL',false));

/************** News Kats **************/
$lines_dbu['newskat'] = array('katimg','kategorie');
$data_dbu['newskat']  = array('katimg'    => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                              'kategorie' => array('VARCHAR(60)','NOT NULL DEFAULT \'\'',false));

/************** Partners **************/
$lines_dbu['partners'] = array('link','banner');
$data_dbu['partners']  = array('link'   => array('VARCHAR(150)','NOT NULL DEFAULT \'\'',false),
                               'banner' => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false));

/************** Permissions **************/
$data_dbu['permissions'] = ($lines_dbu['permissions'] = array());

/************** User Positions **************/
$lines_dbu['pos'] = array('position');
$data_dbu['pos']  = array('position' => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false));

/************** Profile Felder **************/
$lines_dbu['profile'] = array('name','feldname');
$data_dbu['profile']  = array('name'     => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                              'feldname' => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false));

/************** Rankings **************/
$lines_dbu['rankings'] = array('league','url');
$data_dbu['rankings']  = array('league' => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                               'url'    => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false));

/************** Gameserver **************/
$lines_dbu['server'] = array('status','name','ip','pwd','game','qport');
$data_dbu['server']  = array('status' => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false),
                             'name'   => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                             'ip'     => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                             'pwd'    => array('VARCHAR(40)','NOT NULL DEFAULT \'\'',false),
                             'game'   => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false),
                             'qport'  => array('VARCHAR(10)','NOT NULL DEFAULT \'\'',false));

/************** Server Liste **************/
$lines_dbu['serverliste'] = array('clanname','clanurl','ip','pwd',);
$data_dbu['serverliste']  = array('clanname' => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                                  'clanurl'  => array('VARCHAR(255)','NOT NULL DEFAULT \'\'',false),
                                  'ip'       => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                                  'pwd'      => array('VARCHAR(40)','NOT NULL DEFAULT \'\'',false));

/************** Settings **************/
$lines_dbu['settings'] = array('clanname','tmpdir','iban','bic','badwords','pagetitel','i_domain','i_autor',
    'k_nr','k_inhaber','k_blz','k_bank','k_waehrung','language','domain','ts_ip','mailfrom','eml_reg_subj',
    'eml_pwd_subj','eml_nletter_subj','eml_reg','eml_pwd','eml_nletter','eml_fabo_npost_subj','eml_fabo_tedit_subj',
    'eml_fabo_pedit_subj','eml_pn_subj','eml_fabo_npost','eml_fabo_tedit','eml_fabo_pedit','eml_pn','k_vwz','steam_api_key');

$data_dbu['settings']  = array('clanname'            => array('TEXT','NULL DEFAULT NULL',false),
                               'tmpdir'              => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                               'iban'                => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                               'bic'                 => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                               'badwords'            => array('TEXT','NULL DEFAULT NULL',false),
                               'pagetitel'           => array('TEXT','NULL DEFAULT NULL',false),
                               'i_domain'            => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),                               
                               'i_autor'             => array('TEXT','NULL DEFAULT NULL',false),
                               'k_nr'                => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                               'k_inhaber'           => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                               'k_blz'               => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                               'k_bank'              => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'k_waehrung'          => array('VARCHAR(15)','NOT NULL DEFAULT \'\'',false),
                               'language'            => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false),
                               'domain'              => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'mailfrom'            => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_reg_subj'        => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_pwd_subj'        => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_nletter_subj'    => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_reg'             => array('TEXT','NULL DEFAULT NULL',false),
                               'eml_pwd'             => array('TEXT','NULL DEFAULT NULL',false),
                               'eml_nletter'         => array('TEXT','NULL DEFAULT NULL',false),
                               'eml_fabo_npost_subj' => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_fabo_tedit_subj' => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_fabo_pedit_subj' => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_pn_subj'         => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'eml_fabo_npost'      => array('TEXT','NULL DEFAULT NULL',false),
                               'eml_fabo_tedit'      => array('TEXT','NULL DEFAULT NULL',false),
                               'eml_fabo_pedit'      => array('TEXT','NULL DEFAULT NULL',false),
                               'eml_pn'              => array('TEXT','NULL DEFAULT NULL',false),
                               'k_vwz'               => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                               'steam_api_key'       => array('VARCHAR(50)','NOT NULL DEFAULT \'\'',false));

/************** Shoutbox **************/
$lines_dbu['shout'] = array('nick','email','text','ip');
$data_dbu['shout']  = array('nick'  => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false),
                            'email' => array('VARCHAR(130)','NOT NULL DEFAULT \'\'',false),
                            'text'  => array('TEXT','NULL DEFAULT NULL',false),
                            'ip'    => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false));

/************** Seiten **************/
$lines_dbu['sites'] = array('titel','text');
$data_dbu['sites']  = array('titel' => array('TEXT','NULL DEFAULT NULL',false),
                            'text'  => array('TEXT','NULL DEFAULT NULL',true));

/************** Slideshow **************/
$lines_dbu['slideshow'] = array('bez','desc','url');
$data_dbu['slideshow']  = array('bez'  => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false),
                                'desc' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',true),
                                'url'  => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',true));

/************** Sponsoren **************/
$lines_dbu['sponsoren'] = array('name','link','beschreibung','slink','bend','blink','xend','xlink');
$data_dbu['sponsoren']  = array('name'         => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false),
                                'link'         => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',true),
                                'beschreibung' => array('TEXT','NULL DEFAULT NULL' ,true),
                                'slink'        => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',true),
                                'bend'         => array('VARCHAR(5)','NOT NULL DEFAULT \'\'',true),
                                'blink'        => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',true),
                                'xend'         => array('VARCHAR(5)','NOT NULL DEFAULT \'\'',true),
                                'xlink'        => array('VARCHAR(255)','NOT NULL DEFAULT \'\'',true));

/************** Squads **************/
$lines_dbu['squads'] = array('name','game','icon','beschreibung');
$data_dbu['squads']  = array('name' => array('VARCHAR(40)','NOT NULL DEFAULT \'\'',false),
                             'game' => array('VARCHAR(40)','NOT NULL DEFAULT \'\'',false),
                             'icon' => array('VARCHAR(20)','NOT NULL DEFAULT \'\'',false),
                             'beschreibung' => array('TEXT','NULL DEFAULT NULL',true));

/************** Squad User **************/
$data_dbu['squaduser'] = ($lines_dbu['squaduser'] = array());

/************** Taktiken **************/
$lines_dbu['taktik'] = array('map','spart','standardt','sparct','standardct');
$data_dbu['taktik']  = array('map'        => array('VARCHAR(20)','NOT NULL DEFAULT \'\'',false),
                             'spart'      => array('TEXT','NULL DEFAULT NULL',false),
                             'standardt'  => array('TEXT','NULL DEFAULT NULL',false),
                             'sparct'     => array('TEXT','NULL DEFAULT NULL',false),
                             'standardct' => array('TEXT','NULL DEFAULT NULL',false));

/************** User Gallery **************/
$lines_dbu['usergallery'] = array('beschreibung','pic');
$data_dbu['usergallery']  = array('beschreibung' => array('TEXT','NULL DEFAULT NULL',false),
                                  'pic'          => array('VARCHAR(200)','NOT NULL DEFAULT \'\'',false));

/************** User GB **************/
$lines_dbu['usergb'] = array('nick','email','hp','nachricht','ip','editby');
$data_dbu['usergb']  = array('nick'      => array('VARCHAR(30)','NOT NULL DEFAULT \'\'',false),
                             'email'     => array('VARCHAR(130)','NOT NULL DEFAULT \'\'',false),
                             'hp'        => array('VARCHAR(100)','NOT NULL DEFAULT \'\'',false),
                             'nachricht' => array('TEXT','NULL DEFAULT NULL',false),
                             'ip'        => array('VARCHAR(15)','NOT NULL DEFAULT \'0.0.0.0\'',false),
                             'editby'    => array('TEXT','NULL DEFAULT NULL',false));

/************** User Positions **************/
$data_dbu['userpos'] = ($lines_dbu['userpos'] = array());

/************** Users **************/
$lines_dbu['users'] = array('user','nick','pwd','sessid','country','ip','email','icq','hlswid','steamid','battlenetid','originid','skypename',
'psnid','xboxid','rlname','city','hobbys','motto','hp','cpu','ram','monitor','maus','mauspad','headset','board',
'os','graka','hdd','inet','signatur','ex','job','whereami','drink','essen','film','musik','song','buch','autor',
'person','sport','sportler','auto','game','favoclan','spieler','map','waffe','rasse','url2','url3','beschreibung','gmaps_koord');
$data_dbu['users']  = array('user'         => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'nick'         => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'pwd'          => array('varchar(255)','NOT NULL DEFAULT \'\'',false),
                            'sessid'       => array('varchar(32)','NOT NULL DEFAULT \'\'',false),
                            'country'      => array('varchar(20)','NOT NULL DEFAULT \'de\'',false),
                            'ip'           => array('varchar(50)','NOT NULL DEFAULT \'\'',false),
                            'email'        => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'icq'          => array('varchar(20)','NOT NULL DEFAULT \'\'',false),
                            'hlswid'       => array('varchar(100)','NOT NULL DEFAULT \'\'',false),
                            'steamid'      => array('varchar(20)','NOT NULL DEFAULT \'\'',false),
                            'battlenetid'  => array('varchar(100)','NOT NULL DEFAULT \'\'',false),
                            'originid'     => array('varchar(100)','NOT NULL DEFAULT \'\'',false),
                            'skypename'    => array('varchar(100)','NOT NULL DEFAULT \'\'',false),
                            'psnid'        => array('varchar(100)','NOT NULL DEFAULT \'\'',false),
                            'xboxid'       => array('varchar(100)','NOT NULL DEFAULT \'\'',false),
                            'rlname'       => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'city'         => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'hobbys'       => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'motto'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'hp'           => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'cpu'          => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'ram'          => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'monitor'      => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'maus'         => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'mauspad'      => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'headset'      => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'board'        => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'os'           => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'graka'        => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'hdd'          => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'inet'         => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'signatur'     => array('TEXT','NULL DEFAULT NULL',false),
                            'ex'           => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'job'          => array('varchar(200)','NOT NULL DEFAULT \'\'',false),
                            'whereami'     => array('TEXT','NULL DEFAULT NULL',false),
                            'drink'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'essen'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'film'         => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'musik'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'song'         => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'buch'         => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'autor'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'person'       => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'sport'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'sportler'     => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'auto'         => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'game'         => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'favoclan'     => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'spieler'      => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'map'          => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'waffe'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'rasse'        => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'url2'         => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'url3'         => array('varchar(249)','NOT NULL DEFAULT \'\'',false),
                            'beschreibung' => array('TEXT','NULL DEFAULT NULL',false),
                            'gmaps_koord'  => array('varchar(249)','NOT NULL DEFAULT \'\'',false));

/************** User Stats **************/
$data_dbu['userstats'] = ($lines_dbu['userstats'] = array());

/************** Votes Results **************/
$lines_dbu['vote_results'] = array('what','sel');
$data_dbu['vote_results']  = array('what' => array('VARCHAR(5)','NOT NULL DEFAULT \'\'',false),
                                   'sel'  => array('VARCHAR(80)','NOT NULL DEFAULT \'\'',false));

/************** Votes **************/
$lines_dbu['votes'] = array('titel');
$data_dbu['votes']  = array('titel' => array('VARCHAR(249)','NOT NULL DEFAULT \'\'',false));

foreach ($lines_dbu as $key => $v) {
    databaseUpdater::set_update($key,$lines_dbu[$key],$data_dbu[$key]);
} 
unset($lines_dbu,$data_dbu);
        
class databaseUpdater {
    private static $txt = "";
    private static $bbcode = false;
    private static $toUpdate = array();
    private static $toUpdateTable = array();
    private static $cache = array();

    public static final function run() {
        global $db;
        foreach ($db as $key_i => $tb) { //DB
            self::$cache = array();
            if(array_key_exists($key_i, self::$toUpdate)) {
                //Get Text
                $update = self::$toUpdate[$key_i];
                if(count($update) >= 1) {
                    $sql_select = '`id`,';
                    foreach($update as $update_str) {
                        $sql_select .= '`'.$update_str.'`,';
                    }
                    $sql_select = substr($sql_select, 0, -1);
                    $query = db("SELECT ".$sql_select." FROM `".$tb."`;");
                    while ($get = _fetch($query)) {
                        $id = $get['id']; unset($get['id']);
                        self::$cache[$id] = $get;
                    }

                    //Update Text
                    $table_data = self::$toUpdateTable[$key_i];
                    foreach(self::$cache as $id => $data) {
                        foreach($update as $update_key) {
                            $bbcode = $table_data[$update_key];
                            self::$txt = $data[$update_key];
                            self::$bbcode = $bbcode[2];
                            self::recode(); //Decode to Orginal
                            self::encode(); //Encode to new Format
                            $data[$update_key] = self::$txt;
                        }

                        self::$cache[$id] = $data; //Save
                    }

                    //Update Table
                    foreach($update as $update_key) {
                        $table_data_input = $table_data[$update_key];
                        db("ALTER TABLE `".$tb."` CHANGE `".$update_key."` `".$update_key."` ".$table_data_input[0]." "
                        . "CHARACTER SET utf8 COLLATE utf8_general_ci ".$table_data_input[1].";");
                    }

                    //Update Text to DB
                    foreach(self::$cache as $id => $data) {
                        $sql = '';
                        foreach($data as $key => $var) {
                            $sql .= "`".$key."` = '".$var."',";
                        }

                        $sql = substr($sql, 0, -1);
                        db("UPDATE `".$tb."` SET ".$sql." WHERE `id` = '".$id."';");
                    }
                }
                
                db("ALTER TABLE `".$tb."` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;"); //Update Table
            }
        }
    }
    
    public static function set_update($table,$toUpdate,$toUpdateTable) {
        self::$toUpdate[$table] = $toUpdate;
        self::$toUpdateTable[$table] = $toUpdateTable;
    }
    
    //Private
    private static function recode() { //Old Recode * DZCP 1.5.x / 1.6.0.x
        self::spCharsRe();
        self::$txt = str_replace(array("&amp; ","&#34;"),array("& ","\""),self::$txt);

        if(!self::$bbcode) {
            self::$txt = html_entity_decode(self::$txt, ENT_QUOTES, 'UTF-8');
            self::$txt = preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, self::$txt);
        }

        self::$txt = str_replace("&#39;","'",self::$txt);
    }
    
    private static function spCharsRe() {
        self::$txt = str_replace("&Auml;","Ä",self::$txt);
        self::$txt = str_replace("&auml;","ä",self::$txt);
        self::$txt = str_replace("&Uuml;","Ü",self::$txt);
        self::$txt = str_replace("&uuml;","ü",self::$txt);
        self::$txt = str_replace("&Ouml;","Ö",self::$txt);
        self::$txt = str_replace("&ouml;","ö",self::$txt);
        self::$txt = str_replace("&szlig;","ß",self::$txt);
        self::$txt = str_replace("&euro;","?",self::$txt);
    }
    
    private static function encode() { //New Encode * DZCP 1.6.1 *
        self::$txt = utf8_encode(stripcslashes(self::spChars(htmlentities(self::$txt, ENT_COMPAT, 'iso-8859-1')))); 
    }
    
    private static function spChars($txt) {
        $search  = array("Ä","ä","Ü","ü","Ö","ö","ß","'");
        $replace = array("&Auml;","&auml;","&Uuml;","&uuml;","&Ouml;","&ouml;","&szlig;","&#39;");
        return str_replace($search,$replace,$txt);
    }
}