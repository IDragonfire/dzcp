<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('clanwars')
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._clanwars;
    if(!permission("clanwars"))
    {
      $show = error(_error_wrong_permissions, 1);
    } else {
      if($_GET['do'] == "new")
      {
        $qry = db("SELECT * FROM ".$db['squads']."
                   WHERE status = '1'
                   ORDER BY game ASC");
        while($get = _fetch($qry))
        {
          $squads .= show(_cw_add_select_field_squads, array("name" => re($get['name']),
                                                           	 "game" => re($get['game']),
  		              				                                 "id" => $get['id'],
  							                                             "icon" => $get['icon']));
        }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
  			      	    	                              "month" => dropdown("month",date("m",time())),
                                            	      "year" => dropdown("year",date("Y",time()))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                              	    "minute" => dropdown("minute",date("i",time())),
                                                    "uhr" => _uhr));

        $show = show($dir."/form_cw", array("head" => _cw_admin_head,
                                           "datum" => _datum,
                                           "gegner" => _cw_head_gegner,
                                           "xonx" => _cw_head_xonx,
                                           "liga" => _cw_head_liga,
                                           "screen_info" => _cw_screens_info,
                                           "nothing" => "",
                                           "preview" => _preview,
                                           "screenshot1" => _cw_screenshot." 1",
                                           "screenshot2" => _cw_screenshot." 2",
                                           "screenshot3" => _cw_screenshot." 3",
                                           "screenshot4" => _cw_screenshot." 4",
                                           "screens" => _cw_screens,
  									                       "gametype" => _cw_head_gametype,
                                           "url" => _url,
                                           "clantag" => _cw_admin_clantag,
                                           "lineup_info" => _cw_admin_lineup_info,
                                           "bericht" => _cw_bericht,
                                           "result" => _cw_head_result,
                                           "info" => _cw_admin_info,
                                           "gegnerstuff" => _cw_admin_gegnerstuff,
                                           "warstuff" => _cw_admin_warstuff,
                                           "maps" => _cw_admin_maps,
                                           "serverip" => _cw_admin_serverip,
                                           "servername" => _server_name,
                                           "serverpwd" => _server_password,
  							  		                     "match_admins" => _cw_head_admin,
  								  	                     "lineup" => _cw_head_lineup,
  									                       "glineup" => _cw_head_glineup,
                                           "do" => "add",
                                           "what" => _button_value_add,
                                           "cw_clantag" => "",
                                           "cw_gegner" => "",
                                           "cw_url" => "",
                                           "logo" => _cw_logo,
                                           "cw_xonx1" => "",
                                           "cw_xonx2" => "",
                                           "cw_maps" => "",
                                           "cw_servername" => "",
                                           "cw_serverip" => "",
                                           "cw_serverpwd" => "",
                                           "cw_punkte" => "",
                                           "cw_gpunkte" => "",
  			  						                     "cw_matchadmins" => "",
  				  					                     "cw_lineup" => "",
  					  				                     "cw_glineup" => "",
                                           "cw_bericht" => "",
  				                                 "dropdown_date" => $dropdown_date,
  				                                 "dropdown_time" => $dropdown_time,
                                           "hour" => "",
                                           "minute" => "",
                                           "name" => _member_admin_squad,
  		    						                     "squad_info" => _cw_admin_head_squads,
                                           "game" => _member_admin_game,
                                           "squads" => $squads,
                                           "cw_liga" => "",
  									                       "country" => _cw_admin_head_country,
  									                       "countrys" => show_countrys(),
  									                       "cw_gametype" => ""));
      } elseif($_GET['do'] == "edit") {

        $qry = db("SELECT * FROM ".$db['cw']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        list($xonx1,$xonx2) = explode('on', $get['xonx']);
  	    $qrym = db("SELECT * FROM ".$db['squads']."
                    WHERE status = '1'
                    ORDER BY game");
        while($gets = _fetch($qrym))
        {
  	      if($get['squad_id'] == $gets['id']) $sel = "selected=\"selected\"";
          else $sel = "";

          $squads .= show(_cw_edit_select_field_squads, array("id" => $gets['id'],
  				    		                                      	    "name" => re($gets['name']),
                                                         	    "game" => re($gets['game']),
  		                  				                              "sel" => $sel,
  							                                              "icon" => $gets['icon']));
  	    }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
  			      	    	                              "month" => dropdown("month",date("m",$get['datum'])),
                                            	      "year" => dropdown("year",date("Y",$get['datum']))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['datum'])),
                                            	      "minute" => dropdown("minute",date("i",$get['datum'])),
                                                    "uhr" => _uhr));

        $show = show($dir."/form_cw", array("head" => _cw_admin_head_edit,
                                           "datum" => _datum,
                                           "gegner" => _cw_head_gegner,
                                           "xonx" => _cw_head_xonx,
                                           "preview" => _preview,
                                           "nothing" => _cw_nothing,
                                           "screenshot1" => _cw_new." "._cw_screenshot." 1",
                                           "screenshot2" => _cw_new." "._cw_screenshot." 2",
                                           "screenshot3" => _cw_new." "._cw_screenshot." 3",
                                           "screenshot4" => _cw_new." "._cw_screenshot." 4",
                                           "screens" => _cw_screens,
                                           "liga" => _cw_head_liga,
                                           "screen_info" => _cw_screens_info,
  									                       "gametype" => _cw_head_gametype,
                                           "url" => _url,
                                           "clantag" => _cw_admin_clantag,
                                           "bericht" => _cw_bericht,
                                           "result" => _cw_head_result,
                                           "info" => _cw_admin_info,
                                           "gegnerstuff" => _cw_admin_gegnerstuff,
                                           "warstuff" => _cw_admin_warstuff,
                                           "maps" => _cw_admin_maps,
  		  							                     "match_admins" => _cw_head_admin,
  			  						                     "lineup" => _cw_head_lineup,
  				  					                     "glineup" => _cw_head_glineup,
                                           "serverip" => _cw_admin_serverip,
                                           "lineup_info" => _cw_admin_lineup_info,
                                           "servername" => _server_name,
                                           "serverpwd" => _server_password,
                                           "do" => "editcw&amp;id=".$_GET['id']."",
                                           "what" => _button_value_edit,
                                           "cw_clantag" => re($get['clantag']),
                                           "cw_gegner" => re($get['gegner']),
                                           "cw_url" => $get['url'],
                                           "cw_xonx1" => $xonx1,
                                           "logo" => _cw_logo,
                                           "cw_xonx2" => $xonx2,
                                           "cw_maps" => re($get['maps']),
  									                       "cw_matchadmins" => re($get['matchadmins']),
    									                     "cw_lineup" => re($get['lineup']),
  	  								                     "cw_glineup" => re($get['glineup']),
                                           "cw_servername" => re($get['servername']),
                                           "cw_serverip" => $get['serverip'],
                                           "cw_serverpwd" => re($get['serverpwd']),
                                           "cw_punkte" => $get['punkte'],
                                           "cw_gpunkte" => $get['gpunkte'],
                                           "cw_bericht" => re_bbcode($get['bericht']),
                                           "day" => date("d", $get['datum']),
  				                                 "dropdown_date" => $dropdown_date,
  				                                 "dropdown_time" => $dropdown_time,
                                           "month" => date("m", $get['datum']),
                                           "year" => date("Y", $get['datum']),
                                           "hour" => date("H", $get['datum']),
                                           "minute" => date("i", $get['datum']),
                                           "name" => _member_admin_squad,
  									                       "countrys" => show_countrys($get['gcountry']),
  		    						                     "squad_info" => _cw_admin_head_squads,
                                           "game" => _member_admin_game,
                                           "squads" => $squads,
                                           "cw_liga" => re($get['liga']),
  									                       "country" => _cw_admin_head_country,
  									                       "cw_gametype" => re($get['gametype'])));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['gegner']) || empty($_POST['clantag']) || empty($_POST['t']))
        {
          if(empty($_POST['gegner'])) $show = error(_cw_admin_empty_gegner, 1);
          elseif(empty($_POST['clantag'])) $show = error(_cw_admin_empty_clantag, 1);
          elseif(empty($_POST['t'])) $show = error(_empty_datum, 1);
        } else {
          if(empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
          else $xonx = "`xonx` = '".$_POST['xonx1']."on".$_POST['xonx2']."',";

          $datum = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

          if($_POST['land'] == "lazy") $kid = "";
  		    else $kid = "`gcountry` = '".$_POST['land']."',";

          $qry = db("INSERT INTO ".$db['cw']."
                     SET ".$kid."
                         ".$xonx."
                         `datum`        = '".((int)$datum)."',
  	 				             `squad_id`     = '".((int)$_POST['squad'])."',
                         `clantag`      = '".up($_POST['clantag'])."',
                         `gegner`       = '".up($_POST['gegner'])."',
                         `url`          = '".links($_POST['url'])."',
                         `liga`         = '".up($_POST['liga'])."',
  				 	             `gametype`     = '".up($_POST['gametype'])."',
                         `punkte`       = '".((int)$_POST['punkte'])."',
                         `gpunkte`      = '".((int)$_POST['gpunkte'])."',
                         `maps`         = '".up($_POST['maps'])."',
                         `serverip`     = '".up($_POST['serverip'])."',
                         `servername`   = '".up($_POST['servername'])."',
                         `serverpwd`    = '".up($_POST['serverpwd'])."',
  					             `lineup`       = '".up($_POST['lineup'])."',
  					             `glineup`      = '".up($_POST['glineup'])."',
  					             `matchadmins`  = '".up($_POST['match_admins'])."',
                         `bericht`      = '".up($_POST['bericht'],1)."'");

          $cwid = mysql_insert_id();

          $tmp = $_FILES['logo']['tmp_name'];
          $type = $_FILES['logo']['type'];
          $end = explode(".", $_FILES['logo']['name']);
          $end = strtolower($end[count($end)-1]);
          
          if(!empty($tmp))
          {
            $img = @getimagesize($tmp);
						if($img1[0])
            {
              @copy($tmp, basePath."/inc/images/clanwars/".mysql_insert_id()."_logo.".strtolower($end));
              @unlink($tmp);
            }
          }

          $tmp1 = $_FILES['screen1']['tmp_name'];
          $type1 = $_FILES['screen1']['type'];
          $end1 = explode(".", $_FILES['screen1']['name']);
          $end1 = strtolower($end1[count($end1)-1]);
          
          if(!empty($tmp1))
          {
            $img1 = @getimagesize($tmp1);
						if($img1[0])
            {
              @copy($tmp1, basePath."/inc/images/clanwars/".mysql_insert_id()."_1.".strtolower($end1));
              @unlink($tmp1);
            }
          }					

          $tmp2 = $_FILES['screen2']['tmp_name'];
          $type2 = $_FILES['screen2']['type'];
          $end2 = explode(".", $_FILES['screen2']['name']);
          $end2 = strtolower($end2[count($end2)-1]);
          
          if(!empty($tmp2))
          {
            $img2 = @getimagesize($tmp2);
						if($img2[0])
            {
              @copy($tmp2, basePath."/inc/images/clanwars/".mysql_insert_id()."_2.".strtolower($end2));
              @unlink($tmp2);
            }
          }

          $tmp3 = $_FILES['screen3']['tmp_name'];
          $type3 = $_FILES['screen3']['type'];
          $end3 = explode(".", $_FILES['screen3']['name']);
          $end3 = strtolower($end3[count($end3)-1]);
          
          if(!empty($tmp3))
          {
            $img3 = @getimagesize($tmp3);
						if($img3[0])
            {
              @copy($tmp3, basePath."/inc/images/clanwars/".mysql_insert_id()."_3.".strtolower($end3));
              @unlink($tmp3);
            }
          }
					
          $tmp4 = $_FILES['screen4']['tmp_name'];
          $type4 = $_FILES['screen4']['type'];
          $end4 = explode(".", $_FILES['screen4']['name']);
          $end4 = strtolower($end4[count($end4)-1]);
          
          if(!empty($tmp4))
          {
            $img4 = @getimagesize($tmp4);
						if($img4[0])
            {
              @copy($tmp4, basePath."/inc/images/clanwars/".mysql_insert_id()."_4.".strtolower($end4));
              @unlink($tmp4);
            }
          }					

          $show = info(_cw_admin_added, "?admin=cw");
        }
      } elseif($_GET['do'] == "editcw") {
			
        if(empty($_POST['gegner']) || empty($_POST['clantag']) || empty($_POST['t']))
        {
          if(empty($_POST['gegner'])) $show = error(_cw_admin_empty_gegner, 1);
          elseif(empty($_POST['clantag'])) $show = error(_cw_admin_empty_clantag, 1);
          elseif(empty($_POST['t'])) $show = error(_empty_datum, 1);
        } else {
          if(empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
          else $xonx = "`xonx` = '".$_POST['xonx1']."on".$_POST['xonx2']."',";

          $datum = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

          if($_POST['land'] == "lazy") $kid = "";
  		    else $kid = "`gcountry` = '".$_POST['land']."',";

          $qry = db("UPDATE ".$db['cw']."
                     SET ".$xonx."
                         ".$kid."
                         `datum`        = '".((int)$datum)."',
  				 	             `squad_id`     = '".((int)$_POST['squad'])."',
                         `clantag`      = '".up($_POST['clantag'])."',
                         `gegner`       = '".up($_POST['gegner'])."',
                         `url`          = '".links($_POST['url'])."',
                         `liga`         = '".up($_POST['liga'])."',
  	  				           `gametype`     = '".up($_POST['gametype'])."',
                         `punkte`       = '".((int)$_POST['punkte'])."',
                         `gpunkte`      = '".((int)$_POST['gpunkte'])."',
                         `maps`         = '".up($_POST['maps'])."',
                         `serverip`     = '".up($_POST['serverip'])."',
                         `servername`   = '".up($_POST['servername'])."',
                         `serverpwd`    = '".up($_POST['serverpwd'])."',
  					             `lineup`       = '".up($_POST['lineup'])."',
  					             `glineup`      = '".up($_POST['glineup'])."',
  					             `matchadmins`  = '".up($_POST['match_admins'])."',
                         `bericht`      = '".up($_POST['bericht'],1)."'
                     WHERE id = '".intval($_GET['id'])."'");

          $cwid = $_GET['id'];

          $tmp = $_FILES['logo']['tmp_name'];
          $type = $_FILES['logo']['type'];
          $end = explode(".", $_FILES['logo']['name']);
          $end = strtolower($end[count($end)-1]);
          
          if(!empty($tmp))
          {
            $img = @getimagesize($tmp);
						foreach($picformat AS $end1)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_logo.'.$end1))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_logo.'.$end1);
                break;
              }
            }
            if($img[0])
            {
              copy($tmp, basePath."/inc/images/clanwars/".intval($_GET['id'])."_logo.".strtolower($end));
              @unlink($tmp);
            }
          }

          $tmp1 = $_FILES['screen1']['tmp_name'];
          $type1 = $_FILES['screen1']['type'];
          $end1 = explode(".", $_FILES['screen1']['name']);
          $end1 = strtolower($end1[count($end1)-1]);
          
          if(!empty($tmp1))
          {
            $img1 = @getimagesize($tmp1);
						foreach($picformat AS $endun1)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_1.'.$endun1))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_1.'.$endun1);
                break;
              }
            }
						
            if($img1[0])
            {
              copy($tmp1, basePath."/inc/images/clanwars/".intval($_GET['id'])."_1.".strtolower($end1));
              @unlink($tmp1);
            }
          }
					
					$tmp2 = $_FILES['screen2']['tmp_name'];
          $type2 = $_FILES['screen2']['type'];
          $end2 = explode(".", $_FILES['screen2']['name']);
          $end2 = strtolower($end2[count($end2)-1]);
          
          if(!empty($tmp2))
          {
            $img2 = @getimagesize($tmp2);
						foreach($picformat AS $endun2)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_2.'.$endun2))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_2.'.$endun2);
                break;
              }
            }
            if($img2[0])
            {
              copy($tmp2, basePath."/inc/images/clanwars/".intval($_GET['id'])."_2.".strtolower($end2));
              @unlink($tmp2);
            }
          }
					
					$tmp3 = $_FILES['screen3']['tmp_name'];
          $type3 = $_FILES['screen3']['type'];
          $end3 = explode(".", $_FILES['screen3']['name']);
          $end3 = strtolower($end3[count($end3)-1]);
          
          if(!empty($tmp3))
          {
            $img3 = @getimagesize($tmp3);
						foreach($picformat AS $endun3)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_3.'.$endun3))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_3.'.$endun3);
                break;
              }
            }
            if($img3[0])
            {
              copy($tmp3, basePath."/inc/images/clanwars/".intval($_GET['id'])."_3.".strtolower($end3));
              @unlink($tmp3);
            }
          }
					
					$tmp4 = $_FILES['screen4']['tmp_name'];
          $type4 = $_FILES['screen4']['type'];
          $end4 = explode(".", $_FILES['screen4']['name']);
          $end4 = strtolower($end4[count($end4)-1]);
          
          if(!empty($tmp4))
          {
            $img4 = @getimagesize($tmp4);
						foreach($picformat AS $endun4)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_4.'.$endun4))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_4.'.$endun4);
                break;
              }
            }
            if($img4[0])
            {
              copy($tmp4, basePath."/inc/images/clanwars/".intval($_GET['id'])."_4.".strtolower($end4));
              @unlink($tmp4);
            }
          }

          $show = info(_cw_admin_edited, "?admin=cw");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".$db['cw']."
                   WHERE id = '".intval($_GET['id'])."'");

        $qry = db("DELETE FROM ".$db['cw_comments']."
                   WHERE cw = '".intval($_GET['id'])."'");

        $show = info(_cw_admin_deleted, "?admin=cw");
      } elseif($_GET['do'] == "top") {
        $qry = db("UPDATE ".$db['cw']."
                   SET `top` = '".intval($_GET['set'])."'
                   WHERE id = '".intval($_GET['id'])."'");
                   
        $show = info((empty($_GET['set']) ? _cw_admin_top_unsetted : _cw_admin_top_setted), "?admin=cw");
      } else {
        if(isset($_GET['page'])) $page = $_GET['page'];
        else $page = 1;

        $qry = db("SELECT * FROM ".$db['cw']."
                   ORDER BY datum DESC
                   LIMIT ".($page - 1)*$maxadmincw.",".$maxadmincw."");
        $entrys = cnt($db['cw']);
        while($get = _fetch($qry))
        {
          $top = empty($get['top']) 
               ? '<a href="?admin=cw&amp;do=top&amp;set=1&amp;id='.$get['id'].'"><img src="../inc/images/no.gif" alt="" title="'._cw_admin_top_set.'" /></a>'
               : '<a href="?admin=cw&amp;do=top&amp;set=0&amp;id='.$get['id'].'"><img src="../inc/images/yes.gif" alt="" title="'._cw_admin_top_unset.'" /></a>';

          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=cw&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=cw&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_cw)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $show_ .= show($dir."/clanwars_show", array("class" => $class,
                                                      "cw" => re($get['clantag'])." - ".re($get['gegner']),
                                                      "datum" => date("d.m.Y H:i",$get['datum'])._uhr,
                                                      "top" => $top,
                                                      "id" => $get['id'],
                                                      "edit" => $edit,
                                                      "delete" => $delete
                                                      ));
        }

        $show = show($dir."/clanwars", array("head" => _clanwars,
                                             "add" => _cw_admin_head,
                                             "date" => _datum,
                                             "titel" => _opponent,
                                             "show" => $show_,
                                             "navi" => nav($entrys,$maxadmincw,"?admin=cw")
                                             ));
      }
    }
?>