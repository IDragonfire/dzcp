<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "away";
$where = _site_away;
## SECTIONS ##
if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];

switch ($action):
default:
$where = $where.' - '._away_list;
  if($chkMe == "unlogged" || $chkMe < 2)
  {
    $index = error(_error_wrong_permissions, 1);
  } else {

 	if(isset($_GET['page'])) $page = $_GET['page'];
	else $page = 1;
	$entrys = cnt($db['away']);
    if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("userid","start","end"))) {
	$tmp_orderby = $_GET['orderby'];
	if($_GET['orderby'] == "start") $_GET['orderby'] = "start ".$_GET['order'].", end";
	$qry = db("SELECT * FROM ".$db['away']." 
               ORDER BY ".mysql_real_escape_string($_GET['orderby']." ".$_GET['order'])."
               LIMIT ".($page - 1)*$maxaway.",".$maxaway."");
	$_GET['orderby'] = $tmp_orderby; 
	}
	else {$qry = db("SELECT * FROM ".$db['away']." 
			   ORDER BY id DESC 
			   LIMIT ".($page - 1)*$maxaway.",".$maxaway."");
	}
    while($get = _fetch($qry)) 
	{
      if($get['start'] > time()) $status = _away_status_new;
      if($get['start'] <= time() && $get['end'] >= time()) $status = _away_status_now;
      if($get['end'] < time()) $status = _away_status_done;

   	  $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

      if($userid == $get['userid'] || $chkMe == "4")
	  {
	    $value = show("page/button_edit_single", array("id" => $get['id'],
	  												   "action" => "action=edit",
													   "title" => _button_title_edit));
	  } else {
	    $value = "&nbsp;";
	  }
	  
      if($get['end'] < time()) $value = "&nbsp;";

      $chkMe == 4 ? $delete = show("page/button_delete_single", array("id" => $get['id'],
	  																  "action" => "action=del",
																	  "title" => _button_title_del,
																	  "del" => convSpace(_confirm_del_entry))) : $delete = "&nbsp;";

     $info = show($dir."/button_info", array("id" => $get['id'],
	 										 "action" => "action=info",
	 										 "title" =>"Info"));

     $show .= show($dir."/away_show", array("class"=>$class,
             				         	    "id"=>$get["id"],
             					  		    "status"=>$status,
										    "von"=>date("d.m.y",$get['start']),
										    "bis"=>date("d.m.y",$get['end']),
										    "grund"=>$get["titel"],
										    "value"=>$value,
										    "del"=>$delete,
										    "nick"=>autor($get['userid']),
										    "details"=>$info));
      }
     
	  if(!$show) $show = _away_no_entry;
      $orderby = empty($_GET['orderby']) ? "" : "?orderby".$_GET['orderby'];
      $orderby .= empty($_GET['order']) ? "" : "&order=".$_GET['order'];
      $nav= nav($entrys,$maxaway,"?".$_GET['show']."".$orderby);

      $index = show($dir."/away", array("head" => _away_head,
	  								    "show" => $show,
                                        "user" => _user,
										"titel" => _banned_reason,
										"from" => _from,
										"to" => _away_to,
										"status" => _status,
										"order_user" => orderby('userid'),
										"order_status" => orderby('end'),	
										"order_from" => orderby('start'),
										"order_to" => orderby('end'),
										"submit" => _button_value_addto,									
										"nav" => $nav));
  }
break;
case 'new';
$where = $where.' - '._away_new;
  if($chkMe == "unlogged" || $chkMe < "2")
  {
    $index = error(_error_wrong_permissions, 1);
  } else {

     $date1 = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                         "month" => dropdown("month",date("m",time())),
                                         "year" => dropdown("year",date("Y",time()))));

     $date2 = show(_dropdown_date2, array("tag" => dropdown("day",date("d",time())),
                                          "monat" => dropdown("month",date("m",time())),
                                          "jahr" => dropdown("year",date("Y",time()))));

	$index = show($dir."/form_away", array("head" => _away_new_head,
	 									   "action" => "new&amp;do=set",
										   "error" => "",
										   "reason" => _away_reason,
										   "from" => _from,
										   "to" => _away_to,
										   "date1" => $date1,
                                       	   "date2" => $date2,
										   "comment" => _news_kommentar,
										   "titel" => "",
										   "text" => "",
										   "submit" => _button_value_add));

     if($_GET['do'] == "set") 
	 {
	   $abdata = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
       $bisdata = mktime(0,0,0,$_POST['monat'],$_POST['tag'],$_POST['jahr']);

	 if(empty($_POST['titel']) || empty($_POST['reason']) || $bisdata == $abdata || $abdata > $bisdata)
	 {
        if(empty($_POST['titel'])) $error = show("errors/errortable", array("error" => _away_empty_titel));
        if(empty($_POST['reason'])) $error = show("errors/errortable", array("error" => _away_empty_reason));
        if($bisdata == $abdata) $error = show("errors/errortable", array("error" => _away_error_1));
        if($abdata > $bisdata) $error = show("errors/errortable", array("error" => _away_error_2));

		$date1 = show(_dropdown_date, array("day" => dropdown("day",$_POST['t']),
										   	"month" => dropdown("month",$_POST['m']),
										   	"year" => dropdown("year",$_POST['j'])));
	
		$date2 = show(_dropdown_date2, array("tag" => dropdown("day",$_POST['tag']),
										  	 "monat" => dropdown("month",$_POST['monat']),
										   	 "jahr" => dropdown("year",$_POST['jahr'])));     
	 
		$index = show($dir."/form_away", array("head" => _away_new_head,
	 										   "action" => "new&amp;do=set",
											   "error" => $error,
											   "reason" => _away_reason,
											   "from" => _from,
											   "to" => _away_to,
											   "date1" => $date1,
                                          	   "date2" => $date2,
											   "comment" => _news_kommentar,
											   "titel" => $_POST['titel'],
											   "text" => $_POST['reason'],
											   "submit" => _button_value_add));
	 
	 } else {
      
	  $time = mktime(23,59,59,$_POST['monat'],$_POST['tag'],$_POST['jahr']);
	  
             $qry = db("INSERT INTO ".$db['away']."
                        SET `userid`= '".((int)$userid)."',
              	       		`start`= '".((int)$abdata)."',
              	       		`end`= '".((int)$time)."',
                        	`titel`= '".up($_POST['titel'])."',
                        	`reason`= '".up($_POST['reason'],1)."',
                        	`date`= '".time()."'");


               $index = info(_away_successful_added, "../away/");
              }
             }
  }
break;
case 'info';
$where = $where.' - '._info;
  if($chkMe == "unlogged" || $chkMe < "2")
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
	$qry = db("SELECT * FROM ".$db['away']." 
			   WHERE id = '".intval($_GET['id'])."'");
 	$get = _fetch($qry);

    if($get['start'] > time()) $status = _away_status_new;
    if($get['start'] <= time() && $get['end'] >= time()) $status = _away_status_now;
    if($get['end'] < time()) $status = _away_status_done;

    if(empty($get['lastedit'])) $edit = "&nbsp;";
    else $edit = bbcode($get['lastedit']);

 	$index = show($dir."/info", array("head" => _away_info_head,
									  "i_reason" => _away_reason,
									  "i_addeton" => _away_addon,
									  "i_from_to" => _away_formto,
									  "i_status" => _status,
									  "i_info" => _info,
									  "back" => _away_back,
									  "nick" => autor($get['userid']),
									  "von" => date("d.m.Y",$get['start']),
									  "bis" => date("d.m.Y",$get['end']),
									  "text" => bbcode($get['reason']),
									  "titel" => re($get['titel']),
									  "edit" => $edit,
									  "status" => $status,
									  "addnew" => date("d.m.Y",$get['date'])." "._away_on." ".date("H:i",$get['date'])._uhr));
  }
break;
case 'del';
  if($chkMe == "unlogged" || $chkMe < "2")
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
	$qry = db("DELETE FROM ".$db['away']." WHERE id = '".intval($_GET['id'])."'");

	$index = info(_away_successful_del, "../away/");
  }
break;
case 'edit';
  if($chkMe == "unlogged" || $chkMe < "2")
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
	$qry = db("SELECT * FROM ".$db['away']." WHERE id = '".intval($_GET['id'])."'");
 	$get = _fetch($qry);

	$date1 = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['start'])),
									   	"month" => dropdown("month",date("m",$get['start'])),
									   	"year" => dropdown("year",date("Y",$get['start']))));
	
	$date2 = show(_dropdown_date2, array("tag" => dropdown("day",date("d",$get['end'])),
									  	 "monat" => dropdown("month",date("m",$get['end'])),
									   	 "jahr" => dropdown("year",date("Y",$get['end'])))); 

    $index = show($dir."/form_away", array("head" => _away_edit_head,
	 									   "action" => "edit&amp;do=set&amp;id=".$get['id'],
										   "error" => "",
										   "reason" => _away_reason,
										   "from" => _from,
										   "to" => _away_to,
										   "date1" => $date1,
                                       	   "date2" => $date2,
										   "comment" => _news_kommentar,
										   "titel" => $get['titel'],
										   "text" => $get['reason'],
										   "submit" => _button_value_edit));

	$abdata = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
    $bisdata = mktime(0,0,0,$_POST['monat'],$_POST['tag'],$_POST['jahr']);
    if($_GET['do'] == "set") 
	{
	   if(empty($_POST['titel']) || empty($_POST['reason']) || $bisdata == $abdata || $abdata > $bisdata)
	   {
        if(empty($_POST['titel'])) $error = show("errors/errortable", array("error" => _away_empty_titel));
        if(empty($_POST['reason'])) $error = show("errors/errortable", array("error" => _away_empty_reason));
        if($bisdata == $abdata) $error = show("errors/errortable", array("error" => _away_error_1));
        if($abdata > $bisdata) $error = show("errors/errortable", array("error" => _away_error_2));

		$date1 = show(_dropdown_date, array("day" => dropdown("day",$_POST['t']),
										   	"month" => dropdown("month",$_POST['m']),
										   	"year" => dropdown("year",$_POST['j'])));
	
		$date2 = show(_dropdown_date2, array("tag" => dropdown("day",$_POST['tag']),
										  	 "monat" => dropdown("month",$_POST['monat']),
										   	 "jahr" => dropdown("year",$_POST['jahr'])));     
	 
		$index = show($dir."/form_away", array("head" => _away_new_head,
	 										   "action" => "edit&amp;do=set&amp;id=".$get['id'],
											   "error" => $error,
											   "reason" => _away_reason,
											   "from" => _from,
											   "to" => _away_to,
											   "date1" => $date1,
                                          	   "date2" => $date2,
											   "comment" => _news_kommentar,
											   "titel" => $_POST['titel'],
											   "text" => $_POST['reason'],
											   "submit" => _button_value_add));
	 
	   } else {
         $time = mktime(23,59,59,$_POST['monat'],$_POST['tag'],$_POST['jahr']);
         $editedby = show(_edited_by, array("autor" => autor($userid),
                                            "time" => date("d.m.Y H:i", time())._uhr));

             $qry = db("UPDATE ".$db['away']."
                    SET `start`= '".((int)$abdata)."',
              	        `end`= '".((int)$time)."',
                        `titel`= '".up($_POST['titel'])."',
                        `reason`= '".up($_POST['reason'], 1)."',
                        `lastedit`= '".addslashes($editedby)."'
                        WHERE id = '".intval($_GET['id'])."'");

            $index = info(_away_successful_edit, "../away/");
       }
     }
  }
break;
endswitch;
## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>