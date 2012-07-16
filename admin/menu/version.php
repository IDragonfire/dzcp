<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       rootmenu
// Rechte:    $chkMe == 4
///////////////////////////////

//####################################################################################
//############################ READ ME FOR DEVELOPER #################################
//####################################################################################
// Um dieses Feature f�r dein AddOn/Mod nutzen zu k�nnen, sollte zum einen, bei der
// Installation deines Mod/AddOns ein Eintrag in der Tabelle versions gemacht werden
// damit dein Mod/AddOn in dieser �bersicht auftaucht, folgeden Felder sind vorhanden:
//
// -id				= nicht relevant (wird automatisch vergeben)
// -id_server 		= deine eigene ID f�r die Versionspr�fung
// -name 			= der Name deines Mod/AddOns
// -server 			= der Server(txt Datei) f�r die Versionspr�fung (siehe weiter unten)
// -download_link	= der Link zu deinem Mod/AddOn
// -own_version 	= die Version die gerade installiert wird
// -own_date		= von wann ist diese Version
//
// Versionspr�fung:
// Damit die Version von diesem Mod/AddOn automatisch gepr�ft werden kann muss eine 
// Datei auf einem Webspace hinterlegt werden, der Link zu dieser Datei ist unter
// "server" einzutragen. Die Datei muss folgenden Aufbau haben:
// id;version;datum
// Die ID ist die bei id_server angegebene ID, damit mehrere Mod/AddOns in deine Datei
// passen. Die Version ist die aktuelle Versionsnummer und das Datum ist das Datum der
// aktuellen Version als Timestamp. 
// Beispiel .txt Datei:
//   1;4;1441716824
//   2;2,3;1341746824
//   3;1;1451746824
//  Ich habe also ein AddOn mit der ID 1, aktuelle Version 4 vom 8.Septemer 2015.
//  Au�erdem eins mit der ID 2 in der Version 2,3 (Punkt oder Komma ist hierbei egal) 
//  von 8. Juli 2012. Und ein Mod/AddOn mit ID 3 ...
//
// Bei Fragen wendet euch an BlueTeck aus dem DZCP Forum.
//####################################################################################
//####################################################################################
//####################################################################################
	
if(_adminMenu != 'true') exit;

    $where = $where.': '._config_version;
    if($chkMe != 4)
    {
		$show = error(_error_wrong_permissions, 1);
    } else {
		
//####################################################################################	
		if($_GET['do'] == 'add') {
			
			        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
  			      	    	                             	 	"month" => dropdown("month",date("m",time())),
                                            	     			"year" => dropdown("year",date("Y",time()))));

			        $show = show($dir."/form_version", array("head" => _config_version,
                                                "what" => _button_value_add,
                                                "server_id" => _v_server_id,
												"server_name" => _v_server_hover,
												"name" => _v_name,
												"vown" => _v_own,
												"download_link" => _v_download_link,
												"odate" => _datum,
												"dropdown_date" => $dropdown_date,	
												"e_server_id" => "",
												"e_server_name" => "http://",
												"e_name" => "",
												"e_vown" => "",
												"e_download_link" => "",
												"e_odate" => "",												
                                                "error" => "",
												"do" => "add_sql"));
		}	
//####################################################################################		
		elseif($_GET['do'] == 'add_sql'){
			if(empty($_POST['name']) || empty($_POST['vown']))
        	{
           	$error = _empty_req_fields;
           	$error = show("errors/errortable", array("error" => $error));

		 	$dropdown_date = show(_dropdown_date, array("day" => dropdown("day",$_POST['t']),
  			      	    	                              "month" => dropdown("month",$_POST['m']),
                                            	      "year" => dropdown("year",$_POST['j'])));
													  
		    $show = show($dir."/form_version", array("head" => _config_version,
                                                "what" => _button_value_add,
                                                "server_id" => _v_server_id,
												"server_name" => _v_server_hover,
												"name" => _v_name,
												"vown" => _v_own,
												"download_link" => _v_download_link,
												"odate" => _datum,
												"dropdown_date" => $dropdown_date,	
												"e_server_id" => re($_POST['server_id']),
												"e_server_name" => re($_POST['server_name']),
												"e_name" => re($_POST['name']),
												"e_vown" => re($_POST['vown']),
												"e_download_link" => re($_POST['download_link']),
												"e_odate" => $dropdown_date,												
                                                "error" => $error,
												"do" => "add_sql"));
		 
		 
        	} else {
			
		  	$datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
			
          	$qry = db("INSERT INTO ".$db['versions']."
                     SET `id_server` = '".up($_POST['server_id'])."',
					 	`name` = '".up($_POST['name'])."',
						`download_link` = '".up($_POST['download_link'])."',
						`own_date` = '".up($datum)."',
						`own_version` = '".up($_POST['vown'])."',
                         `server`  = '".links($_POST['server_name'])."'");
	
          	$show = info(_v_added, "?admin=version");
        	}
		}
//####################################################################################	
		elseif($_GET['do'] == 'delete'){
			$qry = db("DELETE FROM ".$db['versions']." 
                   WHERE id = '".intval($_GET['id'])."'");

        	$show = info(_v_deleted, "?admin=version");
		
		}
//####################################################################################	
		elseif($_GET['do'] == 'edit'){
			
			$qrys = db("SELECT * FROM ".$db['versions']."
           		         WHERE id = '".intval($_GET['id'])."'");
        	$gets = _fetch($qrys);
			
  			$dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$gets['own_date'])),
  			      	    	                              "month" => dropdown("month",date("m",$gets['own_date'])),
                                            	      "year" => dropdown("year",date("Y",$gets['own_date']))));
													  
		   	$show = show($dir."/form_version", array("head" => _config_version,
                                                "what" => _button_value_edit,
                                                "server_id" => _v_server_id,
												"server_name" => _v_server_hover,
												"name" => _v_name,
												"vown" => _v_own,
												"download_link" => _v_download_link,
												"odate" => _datum,
												"dropdown_date" => $dropdown_date,	
												"e_server_id" => re($gets['id_server']),
												"e_server_name" => re($gets['server']),
												"e_name" => re($gets['name']),
												"e_vown" => re($gets['own_version']),
												"e_download_link" => re($gets['download_link']),
												"e_odate" => $dropdown_date,												
                                                "error" => $error,
												"do" => "edit_sql&amp;id=".$_GET['id'].""));
		}
//####################################################################################	
		elseif($_GET['do'] == 'edit_sql'){
			
			if(empty($_POST['name']) || empty($_POST['vown']))
        	{
          		$error = _empty_req_fields;
           		$error = show("errors/errortable", array("error" => $error));

		  		$dropdown_date = show(_dropdown_date, array("day" => dropdown("day",$_POST['t']),
  			      	    	                              "month" => dropdown("month",$_POST['m']),
                                            	      "year" => dropdown("year",$_POST['j'])));
													  
		    	$show = show($dir."/form_version", array("head" => _config_version,
                                                "what" => _button_value_edit,
                                                "server_id" => _v_server_id,
												"server_name" => _v_server_hover,
												"name" => _v_name,
												"vown" => _v_own,
												"download_link" => _v_download_link,
												"odate" => _datum,
												"dropdown_date" => $dropdown_date,	
												"e_server_id" => re($_POST['server_id']),
												"e_server_name" => re($_POST['server_name']),
												"e_name" => re($_POST['name']),
												"e_vown" => re($_POST['vown']),
												"e_download_link" => re($_POST['download_link']),
												"e_odate" => $dropdown_date,												
                                                "error" => $error,
												"do" => "edit_sql&amp;id=".$_GET['id'].""));
		 
		 
        	} 
			else 
			{
				$datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
			    $qry = db("UPDATE ".$db['versions']."
                    	 SET `id_server` = '".up($_POST['server_id'])."',
					 	`name` = '".up($_POST['name'])."',
						`download_link` = '".up($_POST['download_link'])."',
						`own_date` = '".up($datum)."',
						`own_version` = '".up($_POST['vown'])."',
                         `server`  = '".links($_POST['server_name'])."'
						 WHERE id = '".intval($_GET['id'])."'");
						 
				$show = info(_v_edited, "?admin=version");
				 
			}
		}
//####################################################################################	
		else {
		
		
			$qry = db("SELECT * FROM ".$db['versions']." ORDER BY own_date ASC");
			while($get = _fetch($qry))
			{	
		
			//Datei(n) einlesen
				if($_GET['do'] == 'get') {
					if(function_exists('fsockopen'))
					{
						if($get['server'] != '') {
							$zeilen = file ($get['server']);
							foreach ($zeilen as $zeile) {
   								$server_part = explode(";", $zeile);	//Zeile in Bl�cke teilen
								if($server_part[0] == $get['id_server'])		//ID-Block = DB-ID ??
								{
									break;								
								}
							}
						}
					} //if
				} //if
			//Anzeige		
				$edit = show("page/button_edit_single", array("id" => $get['id'],
															"action" => "admin=version&amp;do=edit",
															"title" => _button_title_edit));
				$delete = show("page/button_delete_single", array("id" => $get['id'],
																"action" => "admin=version&amp;do=delete",
																"title" => _button_title_del,
																"del" => convSpace(_confirm_del_version)));
														  
														  
				$class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
		
				$more = '<a target="_blank" href="'.$get['download_link'].'"><img alt="'.$get['server'].'" src="../inc/images/details.gif"></a>'; 
		
				if($get['own_version'] < $server_part[1])
				{
					$oversion =  '<span style="color:#f00;font-weight:bold">'.$get['own_version'].'</span>';
					$sversion =  '<span style="color:#f00;font-weight:bold">'.$server_part[1].'</span>';
				} 
				else
				{
					$oversion =  '<span style="color:#00FF00;font-weight:bold">'.$get['own_version'].'</span>';
					$sversion =  '<span style="color:#00FF00;font-weight:bold">'.$server_part[1].'</span>';
				}
				if($get['server'] == '')
				{
					$sdate = _v_no_server;
				}
				else
				{
					if($_GET['do'] == 'get') 
					{		
						if($server_part[2] != '') 
						{
							$sdate = date("d.m.Y",$server_part[2]);
						} elseif(!function_exists('fsockopen')) {
							$sdate = '<span style="color:#f00;font-weight:bold">'._v_fsockopen_not_allowed.'</span>';
						} else {
							$sdate = '<span style="color:#f00;font-weight:bold">'._v_unknown.'</span>';
						}				
					} 
					else 
					{
						$sdate = '<span style="color:#f00;font-weight:bold">'._v_no_data.'</span>';
					}
				}
				$kats .= show($dir."/versions_show", array("class" => $class,
															"img" => $img,
															"name" => $get['name'],
															"oversion" => $oversion,
															"sversion" => $sversion,
															"odate" => date("d.m.Y",$get['own_date']),
															"sdate" => $sdate,
															"delete" => $delete,
															"more" => $more,
															"edit" => $edit));
			}
	  
			$show = show($dir."/versions", array("head" => _config_version,
												"name" => _v_name,
												"own" => _v_own,
												"get_data" => _v_get_data,
												"server" => _v_server,
												"kats" => $kats,
												"add" => _config_v_add));
   		 } //do else ende	
	} //permission else ende
    
?>