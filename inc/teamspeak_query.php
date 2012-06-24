<?php
/*
  TS2 Class © by iklas Håkansson <niklas.hk@telia.com>
  TS3 Class © by Sebastien Gerard <sebeuu@gmail.com>
  
  modified by CodeKing for DZCP 08-01-2010 (mm-dd-yyyy)
*/

function teamspeakViewer($s) {
  return ($s['ts_version'] == 3) ? teamspeak3($s) : teamspeak2($s);
}

######################################
### TS 2 Viewer###
######################################
function teamspeak2($s) {
      @set_time_limit(10);
    	$fp = @fsockopen($s['ts_ip'], $s['ts_sport'], $errno, $errstr, 2);
      @stream_set_timeout($fp, 2, 0); @stream_set_blocking($fp, true);
  	  if($fp)
      {
  		  fputs($fp, "sel ".$s['ts_port']."\n");
  		  fputs($fp, "si\n");
  		  fputs($fp, "quit\n");
  		  while(!feof($fp))
        {
  		   $out .= fgets($fp, 1024);
  		  }
  		  $out = str_replace("[TS]", "", $out);
  		  $out = str_replace("OK", "", $out);
  		  $out = trim($out);

     		$name=substr($out,indexOf($out,"server_name="),strlen($out));
  	  	$name=substr($name,0,indexOf($name,"server_platform=")-strlen("server_platform="));
  		  $cAmount=substr($out,indexOf($out,"server_currentchannels="),strlen($out));
  		  $cAmount=substr($cAmount,0,indexOf($cAmount,"server_bwinlastsec=")-strlen("server_bwinlastsec="));
  		  $user=substr($out,indexOf($out,"server_currentusers="),strlen($out));
  		  $user=substr($user,0,indexOf($user,"server_currentchannels=")-strlen("server_currentchannels="));
  		  $max=substr($out,indexOf($out,"server_maxusers="),strlen($out));
  		  $max=substr($max,0,indexOf($max,"server_allow_codec_celp51=")-strlen("server_allow_codec_celp51="));
        fclose($fp);
    	}

    	$uArr = getTSChannelUsers($s['ts_ip'],$s['ts_port'],$s['ts_sport']);
  	  $pcArr = Array();
  	  $ccArr = Array();
  	  $thisArr = Array();
  	  $listArr = Array();
  	  $usedArr = Array();
  	  $cArr	= getChannels($s['ts_ip'],$s['ts_port'],$s['ts_sport']);
  	  $z = 0;
  	  $x = 0;

    	for($i=0;$i<count($cArr);$i++)
  	  {
  		  $innerArr=$cArr[$i];
  		  $listArr[$i]=$innerArr[3];
  	  }
  	  sort($listArr);
  	  for($i=0;$i<count($listArr);$i++)
  	  {
  		  for($j=0;$j<count($cArr);$j++)
  		  {
  			  $innArr=$cArr[$j];

  			  if($innArr[3]==$listArr[$i] && usedID($usedArr,$innArr[0]))
  			  {
  				  if($innArr[2]==-1)
  				  {
  					  $thisArr[0] = $innArr[0];
  					  $thisArr[1] = $innArr[5];
  					  $thisArr[2] = $innArr[2];
  					  $pcArr[$z] = $thisArr;
  					  $usedArr[count($usedArr)] = $innArr[0];
  					  $z++;
  				  } else {
  					  $thisArr[0] = $innArr[0];
  					  $thisArr[1] = $innArr[5];
  					  $thisArr[2] = $innArr[2];
  					  $ccArr[$x] = $thisArr;
  					  $usedArr[count($usedArr)] = $innArr[0];
  					  $x++;
  				  }
  			  }
  		  }
  	  }

  	  for($i=0;$i<count($pcArr);$i++)
      {
  	    $innerArr=$pcArr[$i];

        $subchan = "";
  	    for($j=0;$j<count($ccArr);$j++)
        {
  	      $innerCCArray=$ccArr[$j];
  	      if($innerArr[0]==$innerCCArray[2])
          {
     	      for($p=1;$p<count($uArr);$p++)
            {
              $subusers = "";
              for($p=1;$p<count($uArr);$p++)
              {
  			        $innerUArray=$uArr[$p];
  			        if($innerCCArray[0]==$innerUArray[1])
  			        {
                  $subusers .= "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"../inc/images/tsicons/trenner.gif\" alt=\"\" class=\"tsicon\" />".setUserStatus($innerUArray[12])."&nbsp;".removeChar($innerUArray[14])."&nbsp;(".setPPriv($innerUArray[11])."".setCPriv($innerUArray[10]).")<br />";
  	            }
  		        }
  		      }
            $subchannels = "<img src=\"../inc/images/tsicons/trenner.gif\" alt=\"\" class=\"tsicon\" /><img src=\"../inc/images/tsicons/channel.gif\" alt=\"\" class=\"tsicon\" />&nbsp;<a href=\"javascript:DZCP.popup('../teamspeak/login.php?cName=".removeChar($innerCCArray[1])."', '420', '100')\" class=\"navTeamspeak\" style=\"font-weight:bold\" title=\"".removeChar($innerCCArray[1])."\">".removeChar($innerCCArray[1])."</a><br /> ".$subusers."";
            $subchan .= show("menu/teamspeak_subchan", array("subchannels" => $subchannels));
  	      }
        }
        $users = "";
        for($k=1;$k<count($uArr);$k++)
        {
  		    $innerUArray=$uArr[$k];
  		    if($innerArr[0]==$innerUArray[1])
          {
            $users .= "<img src=\"../inc/images/tsicons/trenner.gif\" alt=\"\" class=\"tsicon\" />".setUserStatus($innerUArray[12])."&nbsp;".removeChar($innerUArray[14])."&nbsp;(".setPPriv($innerUArray[11])."".setCPriv($innerUArray[10]).")&nbsp;</span> <br />";
  		    }
  	    }

        $channels = "<img src=\"../inc/images/tsicons/channel.gif\"  alt=\"\" class=\"tsicon\" />&nbsp;<a href=\"javascript:DZCP.popup('../teamspeak/login.php?cName=".removeChar($innerArr[1])."', '420', '100')\" class=\"navTeamspeak\" style=\"font-weight:bold\" title=\"".removeChar($innerArr[1])."\">".removeChar($innerArr[1])."</a><br /> ".$users."";
        $chan .= show("menu/teamspeak_chan", array("channel" => $channels,
                                                   "subchannels" => $subchan));

        $hostname = '
        <tr>
          <td nowrap="nowrap"><img src="../inc/images/tsicons/ts.gif" alt="" class="tsicon" /> <span class="fontBold">'.$name.'</span></td>
        </tr>
        <tr>
          <td style="height:4px"></td>
        </tr>';
                                                   
        $teamspeak = show("menu/teamspeak", array("hostname" => $hostname,
                                                  "channels" => $chan
                                                ));
      }
      
      if(empty($teamspeak)) $teamspeak = '<br /><center>'._error_no_teamspeak.'</center><br />';
      
   return $teamspeak;
}

function setUserStatus($img)
{
	switch ($img) {
		case "1" : 
		$img = "<img src=\"../inc/images/tsicons/ccommander.gif\" class=\"tsicon\" alt=\"\">"; 
   		break;
		
		case "3" : 
		$img = "<img src=\"../inc/images/tsicons/ccommander.gif\" class=\"tsicon\" alt=\"\">"; 
		break;	
		
		case "5" : 
		$img = "<img src=\"../inc/images/tsicons/ccommander.gif\" class=\"tsicon\" alt=\"\">"; 
		break;
		
		case "7" : 
		$img = "<img src=\"../inc/images/tsicons/ccommander.gif\" class=\"tsicon\" alt=\"\">"; 
		break;		
		
		case "8" :
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "9" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "10" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "11" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "12" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "13" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "14" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "15" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;			
		
		case "16" :
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "17" : 
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "18" : 
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "19" : 
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "20" : 
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "21" : 
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "22" : 
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "23" : 
		$img = "<img src=\"../inc/images/tsicons/muted.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
		case "24" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "25" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "26" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "27" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "28" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "29" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "30" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "31" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
		case "32" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "33" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "34" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "35" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "36" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "37" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "38" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "39" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
		case "40" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "41" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "42" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "43" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "44" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "45" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "46" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "47" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
		case "48" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "49" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "50" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "51" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "52" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "53" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "54" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "55" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "56" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "57" : 
		$img = "<img src=\"../inc/images/tsicons/smuted.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
		case "58" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "59" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
		case "60" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;
		
		case "61" : 
		$img = "<img src=\"../inc/images/tsicons/away.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
		default :
   		$img = "<img src=\"../inc/images/tsicons/user.gif\" class=\"tsicon\" alt=\"\">";
		break;		
		
	}			 
	return $img;		
}

function setCPriv($str)
{
	switch ($str) {		 	
		case "1" : //Channel Admin
		$str = "&nbsp;CA";  
	   	break;
		
		case "2" : //Channel Ops
		$str = "&nbsp;O";  
	   	break;
		
		case "3" : //Channel Admin & Ops 
		$str = "&nbsp;CA&nbsp;O";  
	   	break;
		
		case "4" : //Voice
		$str = "&nbsp;V";  
	   	break;
		
		case "5" : //Channel Admin & Voice
		$str = "&nbsp;CA&nbsp;V";  
	   	break;
		
		case "6" : //Ops & Voice
		$str = "&nbsp;O&nbsp;V";  
	   	break;
		
		case "7" : //Channel Admin & Ops & Voiced 
		$str = "&nbsp;CA&nbsp;O&nbsp;V";  
	   	break;
		
		case "8" : //Auto Ops 
		$str = "&nbsp;AO";  
	   	break;
		
		case "9" : //Channel Admin & Auto Ops 
		$str = "&nbsp;CA&nbsp;AO";  
	   	break;
		
		case "10" : //Channel Admin & Auto Ops 
		$str = "&nbsp;AO&nbsp;O";  
	   	break;
		
		case "11" : //Channel Admin & Auto Ops & Ops
		$str = "&nbsp;CA&nbsp;AO&nbsp;O";  
	   	break;
		
		case "12" : //Auto Ops & Voiced
		$str = "&nbsp;AO&nbsp;V";  
	   	break;
		
		case "13" : //Channel Admin & Auto Ops & Voiced
		$str = "&nbsp;CA&nbsp;AO&nbsp;V";  
	   	break;	
		
		case "14" : //Auto Ops & Ops & Voiced
		$str = "&nbsp;AO&nbsp;O&nbsp;V";  
	   	break;
		
		case "15" : //Channel Admin & Auto Ops & Ops & Voiced
		$str = "&nbsp;CA&nbsp;AO&nbsp;O&nbsp;V";  
	   	break;
		
		case "16" : //Auto Voice
		$str = "&nbsp;AV";  
	   	break;
		
		case "17" : //Channel Admin & Auto Voice
		$str = "&nbsp;CA&nbsp;AV";  
	   	break;
		
		case "18" : //Auto Voice & Ops
		$str = "&nbsp;AV&nbsp;O";  
	   	break;
		
		case "19" : //Channel Admin & Auto Voice & Ops
		$str = "&nbsp;CA&nbsp;AV&nbsp;O";  
	   	break;
		
		case "20" : //Auto Voice & Voice 
		$str = "&nbsp;AV&nbsp;V";  
	   	break;
		
		case "21" : //Channel Admin & Auto Voice & Voice 
		$str = "&nbsp;CA&nbsp;AV&nbsp;V";  
	   	break;
		
		case "22" : //Auto Voice & Ops & Voice 
		$str = "&nbsp;AV&nbsp;O&nbsp;V";  
	   	break;
		
		case "23" : //Channel Admin & Auto Voice & Ops & Voice 
		$str = "&nbsp;CA&nbsp;AV&nbsp;O&nbsp;V";  
	   	break;
		
		case "24" : //Auto Ops & Auto Voice
		$str = "&nbsp;AO&nbsp;AV";  
	   	break;
		
		case "25" : //Channel Admin & Auto Ops & Auto Voice 
		$str = "&nbsp;CA&nbsp;AO&nbsp;AV";  
	   	break;
		
		case "26" : //Auto Ops & Auto Voice & Ops 
		$str = "&nbsp;AO&nbsp;AV&nbsp;O";  
	   	break;
		
		case "27" : //Channel Admin & Auto Ops & Auto Voice & Ops 
		$str = "&nbsp;CA&nbsp;AO&nbsp;AV&nbsp;O";  
	   	break;
		
		case "28" : //Auto Ops & Auto Voice & Voice 
		$str = "&nbsp;AO&nbsp;AV&nbsp;V";  
	   	break;
		
		case "29" : //Channel Admin & Auto Ops & Auto Voice & Voice 
		$str = "&nbsp;CA&nbsp;AO&nbsp;AV&nbsp;V";  
	   	break;
		
		case "30" : //Auto Ops & Auto Voice & Ops & Voiced
		$str = "&nbsp;AO&nbsp;AV&nbsp;O&nbsp;V";  
	   	break;
		
		case "31" : //Channel Admin & Auto Ops & Auto Voice & Ops & Voiced
		$str = "&nbsp;CA&nbsp;AO&nbsp;AV&nbsp;O&nbsp;V";  
	   	break;
		
		default :
	   	$str = "";
	   	break;	
	}
	
	return $str;
}

function removeChar($str)
{
	$str = str_replace('"', '', $str);
	return $str;
}

function time_convert($time, $ms = false)
{ 
  if($ms) $time = $time / 1000;

	$day = floor($time/86400);
	$hours = floor(($time%86400)/3600);
	$minutes = floor(($time%3600)/60);
	$seconds = floor($time%60);
	
	if($day>0) $time = $day."d ".$hours."h ".$minutes."m ".$seconds."s";
	elseif($hours>0) $time = $hours."h ".$minutes."m ".$seconds."s";
	elseif($minutes>0) $time = $minutes."m ".$seconds."s";
	else $time = $seconds."s";
	 
  return $time;
} 

function getCodec($codec)
{
	switch ($codec) {		 	
		case "0" : 
		$codec = "CELP 5.2 Kbit";  
	   	break;
		
		case "1" : 
		$codec = "CELP 6.3 Kbit";  
	   	break;
		
		case "2" : 
		$codec = "GSM 14.8 Kbit";  
	   	break;
		
		case "3" : 
		$codec = "GSM 16.4 Kbit";  
	   	break;
		
		case "4" : 
		$codec = "Windows CELP 5.2 Kbit";  
	   	break;			
		
		case "5" : 
		$codec = "Speex 3.4 Kbit";  
	   	break;
		
		case "6" : 
		$codec = "Speex 5.2 Kbit";  
	   	break;
		
		case "7" : 
		$codec = "Speex 7.2 Kbit";  
	   	break;		
		
		case "8" : 
		$codec = "Speex 9.3 Kbit";  
	   	break;		
		
		case "9" : 
		$codec = "Speex 12.3 Kbit";  
	   	break;
		
		case "10" : 
		$codec = "Speex 16.3 Kbit";  
	   	break;
		
		case "11" : 
		$codec = "Speex 19.5 Kbit";  
	   	break;	
		
		case "12" : 
		$codec = "Speex 25.9 Kbit";  
	   	break;			
		
		default :
	    $codec = "";
	   	break;
	}	
		
	return $codec;
}

function setPPriv($str)
{
	switch ($str) {	
	   	case "5" : //Server Admin
		$str = "R&nbsp;SA";  
	   	break;
	
	   	case "4" : //Registered
	    $str = "R"; 
	   	break;
		
	   	default :
	   	$str = "U";
	   	break;   
  	}
   
	return $str;
}

function setPPrivText($str)
{
	switch ($str) {	
	   	case "5" : //Server Admin
		$str = "Server Administrator<br />Registered";  
	   	break;
	
	   	case "4" : //Registered
	    $str = "Registered"; 
	   	break;
		
	   	default :
	   	$str = "None";
	   	break;   
  	}
   
	return $str;
}

function setCPrivText($str)
{
	switch ($str) {		 	
		case "1" : //Channel Admin
		$str = "Channel Admin";  
	   	break;
		
		case "2" : //Channel Ops
		$str = "Channel Ops";  
	   	break;
		
		case "3" : //Channel Admin & Ops 
		$str = "Channel Admin<br />Ops";  
	   	break;
		
		case "4" : //Voice
		$str = "Voice";  
	   	break;
		
		case "5" : //Channel Admin & Voice
		$str = "Channel Admin<br />Voice";  
	   	break;
		
		case "6" : //Ops & Voice
		$str = "Ops<br />Voice";  
	   	break;
		
		case "7" : //Channel Admin & Ops & Voiced 
		$str = "Channel Admin<br />Ops<br />Voiced";  
	   	break;
		
		case "8" : //Auto Ops 
		$str = "Auto Ops";  
	   	break;
		
		case "9" : //Channel Admin & Auto Ops 
		$str = "Channel Admin<br />Auto Ops";  
	   	break;
		
		case "10" : //Auto Ops & Auto Ops 
		$str = "Auto Ops<br />Ops";  
	   	break;
		
		case "11" : //Channel Admin & Auto Ops & Operator
		$str = "Channel Admin<br />Auto Ops<br />Ops";  
	   	break;
		
		case "12" : //Auto Ops & Voiced
		$str = "Auto Ops<br />Voiced";  
	   	break;
		
		case "13" : //Channel Admin & Auto Ops & Voiced
		$str = "Channel Admin<br />Auto Ops<br />Voiced";  
	   	break;	
		
		case "14" : //Auto Ops & Ops & Voiced
		$str = "Auto Ops<br />Ops<br />Voiced";  
	   	break;
		
		case "15" : //Channel Admin & Auto Ops & Ops & Voiced
		$str = "Channel Admin<br />Auto Ops<br />Ops<br />Voiced";  
	   	break;
		
		case "16" : //Auto Voice
		$str = "Auto Voice";  
	   	break;
		
		case "17" : //Channel Admin & Auto Voice
		$str = "Channel Admin<br />Auto Voice";  
	   	break;
		
		case "18" : //Auto Voice & Ops
		$str = "Auto Voice<br />Ops";  
	   	break;
		
		case "19" : //Channel Admin & Auto Voice & Ops
		$str = "Channel Admin<br />Auto Voice<br />Ops";  
	   	break;
		
		case "20" : //Auto Voice & Voice 
		$str = "Auto Voice<br />Voice";  
	   	break;
		
		case "21" : //Channel Admin & Auto Voice & Voice 
		$str = "Channel Admin<br />Auto Voice<br />Voice";  
	   	break;
		
		case "22" : //Auto Voice & Ops & Voice 
		$str = "Auto Voice<br />Ops<br />Voice";  
	   	break;
		
		case "23" : //Channel Admin & Auto Voice & Ops & Voice 
		$str = "Channel Admin<br />Auto Voice<br />Ops<br />Voice";  
	   	break;
		
		case "24" : //Auto Ops & Auto Voice
		$str = "Auto Ops<br />Auto Voice";  
	   	break;
		
		case "25" : //Channel Admin & Auto Ops & Auto Voice 
		$str = "Channel Admin<br />Auto Ops<br />Auto Voice";  
	   	break;
		
		case "26" : //Auto Ops & Auto Voice & Ops 
		$str = "Auto Ops<br />Auto Voice<br />Ops";  
	   	break;
		
		case "27" : //Channel Admin & Auto Ops & Auto Voice & Ops 
		$str = "Channel Admin<br />Auto Ops<br />Auto Voice<br />Ops";  
	   	break;
		
		case "28" : //Auto Ops & Auto Voice & Voice 
		$str = "Auto Ops<br />Auto Voice<br />Voice";  
	   	break;
		
		case "29" : //Channel Admin & Auto Ops & Auto Voice & Voice 
		$str = "Channel Admin<br />Auto Ops<br />Auto Voice<br />Voice";  
	   	break;
		
		case "30" : //Auto Ops & Auto Voice & Ops & Voiced
		$str = "Auto Ops<br />Auto Voice<br />Ops<br />Voiced";  
	   	break;
		
		case "31" : //Channel Admin & Auto Ops & Auto Voice & Ops & Voiced
		$str = "Channel Admin<br />Auto Ops<br />Auto Voice<br />Ops<br />Voiced";  
	   	break;
		
		default :
	   	$str = "None";
	   	break;	
	}
	
	return $str;
}

function indexOf($str,$strChar)
{
	if(strlen(strchr($str,$strChar))>0) {
		$position_num = strpos($str,$strChar) + strlen($strChar);		
		return $position_num;
	} else {
		return -1;
	}
}
function getChannelName($cid,$ip,$port,$tPort)
{		
	$name = "Uknown";
	$cArray = getChannels($ip,$port,$tPort);
	
	for($i=0;$i<count($cArray);$i++)
	{
		$innerArray=$cArray[$i];		
		if($innerArray[0]==$cid)
			$name = removeChar($innerArray[5]);	
	}		
	return $name;
}

function getChannels($ip,$port,$tPort)
{
	$cArray 	= array();
	$out		= "";
	$j			= 0; 
	$k			= 0;
	$fp = fsockopen($ip, $tPort, $errno, $errstr, 2);
  stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);
	if($fp) {
		fputs($fp, "cl ".$port."\n");		
		fputs($fp, "quit\n");
		while(!feof($fp)) {
			$out .= fgets($fp, 1024);
		}
		$out   = str_replace("[TS]", "", $out);
		$out   = str_replace("\n", "\t", $out);			
		$data 	= explode("\t", $out);
		$num 	= count($data);				
		
		for($i=0;$i<count($data);$i++) {
			if($i>=10) {
				$innerArray[$j] = $data[$i];
				if($j>=8)
				{
					$cArray[$k]=$innerArray;
					$j = 0;
					$k = $k+1;
				} else {
					$j++;
				}
			}			
		}			
		fclose($fp);	
	} 	

	return $cArray;
}
function getTSChannelUsers($ip,$port,$tPort)
{
	$uArray 	= array();
	$innerArray = array();
	$out		= "";
	$j			= 0; 
	$k			= 0;
	
	$fp = fsockopen($ip, $tPort, $errno, $errstr, 2);
  stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);
	if($fp) {
		fputs($fp, "pl ".$port."\n");		
		fputs($fp, "quit\n");
		while(!feof($fp)) {
			$out .= fgets($fp, 1024);
		}
		$out   = str_replace("[TS]", "", $out);
		$out   = str_replace("loginname", "loginname\t", $out);		
		$data 	= explode("\t", $out);
		$num 	= count($data);				
		
		for($i=0;$i<count($data);$i++) {
			$innerArray[$j] = $data[$i];
			if($j>=15)
			{
				$uArray[$k]=$innerArray;
				$j = 0;
				$k = $k+1;
			} else {
				$j++;
			}			
		}			
		fclose($fp);	
	} 	
	 return $uArray;		
}

function usedID($usedArray,$cid)
{		
	$ok = true;
	for($i=0;$i<count($usedArray);$i++)
	{	
		if($usedArray[$i]==$cid) {
			$ok = false;			
		}		
	}
	return $ok;
}

function defaultInfo($ip,$tPort,$port)
{
	$out = "";
	$html = "";	
	
	$fp = fsockopen($ip, $tPort, $errno, $errstr, 2);
  stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);
	if($fp) {
		fputs($fp, "sel ".$port."\n");
		fputs($fp, "si\n");
		fputs($fp, "quit\n");
		while(!feof($fp)) {
			$out .= fgets($fp, 1024);
		}
		
		$out   	= str_replace("[TS]", "", $out);
		$out   	= str_replace("OK", "", $out);
		$out 	= trim($out);
		
		$name=substr($out,indexOf($out,"server_name="),strlen($out));
		$name=substr($name,0,indexOf($name,"server_platform=")-strlen("server_platform="));
		
		$os=substr($out,indexOf($out,"server_platform="),strlen($out));
		$os=substr($os,0,indexOf($os,"server_welcomemessage=")-strlen("server_welcomemessage="));
		
		$tsType=substr($out,indexOf($out,"server_clan_server="),strlen($out));
		$tsType=substr($tsType,0,indexOf($tsType,"server_udpport=")-strlen("server_udpport="));			
		
		$welcomeMsg=substr($out,indexOf($out,"server_welcomemessage="),strlen($out));
		$welcomeMsg=substr($welcomeMsg,0,indexOf($welcomeMsg,"server_webpost_linkurl=")-strlen("server_webpost_linkurl="));
				
		
		if($tsType[0]==1) $tsTypeText = "Freeware Clan Server";
		else $tsTypeText = "Freeware Public Server";		

		$html = "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Server:</span></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\">".$name."<br /><br /></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Server IP:</span></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\">".$ip.":".$port."<br /><br /></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Version:</span></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\">".getTSVersion($ip,$tPort,$port)."<br /><br /></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Type:</span></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\">".$tsTypeText."<br /><br /></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Welcome Message:</span></td></tr>\n";
		$html .= "<tr><td id=\"contentMainFirst\">".$welcomeMsg."<br /><br /></td></tr>";
		
		fclose($fp);
	}
	return $html;
}

function channelInfo($ip,$tPort,$port,$cID)
{
	$cArray		= getChannels($ip,$port,$tPort);
	$uArray 	= getTSChannelUsers($ip,$port,$tPort);
	$html 		= "";
	$cUser		= 0;
	$ok 		= false;	
	
	for($i=0;$i<count($cArray);$i++)
	{
		$innArray = $cArray[$i];
		if($innArray[0]==$cID)
		{
			$codec  = $innArray[1];
			$max	= $innArray[4];
			$name 	= $innArray[5];				
			$topic 	= $innArray[8];
			$ok = true; 
		}
	}
	
	for($i=0;$i<count($uArray);$i++)
	{
		$innArray = $uArray[$i];
		if($innArray[1]==$cID) $cUser++;		
	}	
	if($ok) 
	{
		$html = "<tr><td><span class=\"fontBold\">Channel:</span></td></tr>\n";
		$html .= "<tr><td>".removeChar($name)."<br /><br /></td></tr>\n";
		$html .= "<tr><td><span class=\"fontBold\">Topic:</span></td></tr>\n";
		$html .= "<tr><td>".removeChar($topic)."<br /><br /></td></tr>\n";
		$html .= "<tr><td><span class=\"fontBold\">User in channel:</span></td></tr>\n";
		$html .= "<tr><td>".$cUser."/".removeChar($max)."<br /><br /></td></tr>\n";
		$html .= "<tr><td><span class=\"fontBold\">Codec:</span></td></tr>\n";
		$html .= "<tr><td>".getCodec($codec)."<br /><br /></td></tr>\n";
		$name = str_replace("'","¶",$name);
		$html .= "<tr><td><br /><input type=\"button\" id=\"submit\" onclick=\"DZCP.popup('login.php?cName=".removeChar($name)."', '420', '150');\" value=\"Join Channel\" class=\"submit\" /></td></tr>\n";
	} else {
		$html = "<tr><td>Channel is deleted!</td></tr>\n";
	}
	
	return $html;	
}

function getTSVersion($ip,$tPort,$port)
{
	$out = "";
	$fp = fsockopen($ip, $tPort, $errno, $errstr, 2);
  stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);
	if($fp) {
		fputs($fp, "sel ".$port."\n");
		fputs($fp, "ver\n");
		fputs($fp, "quit\n");
		while(!feof($fp)) {
			$out .= fgets($fp, 1024);
		}
		$out   	= str_replace("[TS]", "", $out);
		$out   	= str_replace("OK", "", $out);
		$out   	= str_replace("\n", "", $out);		
		$data  	= explode(" ", $out);
		
		fclose($fp);				
	}
	return $data[0];
}

######################################
### TS 3 Viewer###
######################################

function teamspeak3($settings) {
  $tsstatus = new TSStatus($settings['ts_ip'], $settings['ts_port'], $settings['ts_sport']);

  return  show("menu/teamspeak", array("hostname" => '',
                                       "channels" => $tsstatus->render()
                                     ));
}

class TSStatus
{
	var $_host;
	var $_qport;
	var $_port;
	var $_sid;
	var $_socket;
	var $_updated;
	var $_serverDatas;
	var $_channelDatas;
	var $_userDatas;
	var $_serverGroupFlags;
	var $_channelGroupFlags;
	
	var $error;
	var $decodeUTF8;
	
	function TSStatus($host, $port, $queryPort)
	{
		$this->_host = $host;
		$this->_port = $port;
		$this->_qport = $queryPort;
		$this->_sid = 1;
		
		$this->_socket = null;	
		$this->_updated = false;
		$this->_serverDatas = array();
		$this->_channelDatas = array();
		$this->_userDatas = array();
		$this->_serverGroupFlags = array();
		$this->_channelGroupFlags = array();
		
		$this->error = '';
    $this->serverError = '';
		$this->decodeUTF8 = true;
		
		$this->setServerGroupFlag(6, 'servergroup_300.png');
		$this->setServerGroupFlag(10, 'wappen.png');
		$this->setChannelGroupFlag(5, 'changroup_100.png');
		$this->setChannelGroupFlag(6, 'changroup_200.png');
	}
	
	function clearServerGroupFlags()
	{
		$this->_serverGroupFlags = array();
	}
	
	function setServerGroupFlag($serverGroupId, $image)
	{
		$this->_serverGroupFlags[$serverGroupId] = $image;
	}
	
	function clearChannelGroupFlags()
	{
		$this->_channelGroupFlags = array();
	}
	
	function setChannelGroupFlag($channelGroupId, $image)
	{
		$this->_channelGroupFlags[$channelGroupId] = $image;
	}
	
	function update()
	{
		$response = $this->queryServer();
		if($response !== false && empty($this->error))
		{
			$lines = explode("\n\rerror id=0 msg=ok\n\r", $response);
			if(count($lines) == 4)
			{
				$this->_serverDatas = $this->parseLine($lines[0]);
				$this->_serverDatas = $this->_serverDatas[0];
				 
				$this->_channelDatas = $this->parseLine($lines[1]);
				$this->_userDatas = $this->parseLine($lines[2]);
				usort($this->_userDatas, array($this, "sortUsers"));
				
				$this->_updated = true;
			}	else $this->error = rep2($response);
		}
	}
	
	function sendCommand($fp, $cmd)
	{
    if(empty($this->error)) {
  		@fputs($fp, "$cmd\n");
  		$response = "";
  		while(strpos($response, 'msg=') === false) {
  			$response .= @fread($fp, 8096);    
      }
    }
    if(!empty($response) && !strstr($response, 'error id=0')) {
      $this->error = strtr(rep2($response), array(' msg=' => '<br />msg=', ' extra_msg=' => '<br />extra_msg='));
    }
    
		return $response;
	}
  
	function tsvars($str)
	    {
	    $str=explode("\n",$str);
	    $vars=array();
	    for($i=0;$i<sizeof($str);$i++) $vars[trim(array_shift(explode('=',$str[$i],2)))]=trim(array_pop(explode('=',$str[$i],2)));
	    return $vars;
	    }

	function queryServer()
	{
		@set_time_limit(10);
		$fp = @fsockopen($this->_host, $this->_qport, $errno, $errstr, 2);
		@stream_set_timeout($fp, 2, 0); @stream_set_blocking($fp, true);
		
		if($fp)
		{
	
			$response = $this->sendCommand($fp, "use port=" . $this->_port);
	
			if(strstr($response, 'error id=0 msg=ok')) 
			{
				$response="error id=0 msg=ok" ;
				$response .= $this->sendCommand($fp, "serverinfo");
				
				$response .= $this->sendCommand($fp, "channellist -topic -flags -voice -limits");
				$response .= $this->sendCommand($fp, "clientlist -uid -away -voice -groups -times");
			}
	
			if($this->decodeUTF8) $response = utf8_decode($response);
	
			return $response;
		} else {
		$this->error = '<br /><center>'._error_no_teamspeak.'</center><br />';
		}
		return false;   
	}	
	function unescape($str)
	{
		$find = array('\\\\', 	"\/", 		"\s", 		"\p", 		"\a", 	"\b", 	"\f", 		"\n", 		"\r", 	"\t", 	"\v");
		$rplc = array(chr(92),	chr(47),	chr(32),	chr(124),	chr(7),	chr(8),	chr(12),	chr(10),	chr(3),	chr(9),	chr(11));
		
		return str_replace($find, $rplc, $str);
	}
	
	function parseLine($rawLine)
	{
		$datas = array();
		$rawItems = explode("|", $rawLine);
		foreach ($rawItems as $rawItem)
		{
			$rawDatas = explode(" ", $rawItem);
			$tempDatas = array();
			foreach($rawDatas as $rawData)
			{
				$ar = explode("=", $rawData, 2);
				$tempDatas[$ar[0]] = isset($ar[1]) ? $this->unescape($ar[1]) : "";
			}
			$datas[] = $tempDatas;
		}
		return $datas;
	}
	
	function sortUsers($a, $b)
	{
		return strcasecmp($a["client_nickname"], $b["client_nickname"]);
	}
	
	function renderFlags($flags)
	{
		$out = "";
		foreach ($flags as $flag) $out .= '<img src="../inc/images/tsicons/' . $flag . '" alt="" class="icon" />';
		return $out;
	}
	
	function renderUsers($parentId, $sub = 0)
	{
		$out = "";
		foreach($this->_userDatas as $user)
		{
			if($user["client_type"] == 0 && $user["cid"] == $parentId)
			{
				                                              $icon = "16x16_player_off.png";
				if($user["client_away"] == 1)                 $icon = "16x16_away.png";
				else if($user["client_flag_talking"] == 1)    $icon = "16x16_player_on.png";
				else if($user["client_output_hardware"] == 0) $icon = "16x16_hardware_output_muted.png";
				else if($user["client_output_muted"] == 1)    $icon = "16x16_output_muted.png";
				else if($user["client_input_hardware"] == 0)  $icon = "16x16_hardware_input_muted.png";
				else if($user["client_input_muted"] == 1)     $icon = "16x16_input_muted.png";
				
				$flags = array();
				if(isset($this->_channelGroupFlags[$user["client_channel_group_id"]])) $flags[] = $this->_channelGroupFlags[$user["client_channel_group_id"]];
				$serverGroups = explode(",", $user["client_servergroups"]);
				foreach ($serverGroups as $serverGroup) if(isset($this->_serverGroupFlags[$serverGroup])) $flags[] = $this->_serverGroupFlags[$serverGroup];
        
        $out .= ($sub ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '').'<img src="../inc/images/tsicons/trenner.gif" alt="" class="tsicon" /><img src="../inc/images/tsicons/'.$icon.'" alt="" class="tsicon" />'.rep2($user["client_nickname"]).'&nbsp;'.$this->renderFlags($flags).'&nbsp; <br />';

			}
		}
		return $out;
	}
	
	function renderChannels($parentId, $sub = 0, $tpl = false)
	{
		$out = "";
		foreach ($this->_channelDatas as $channel)
		{
			if($channel["pid"] == $parentId && !empty($channel['channel_name']))
			{
				$icon = "16x16_channel_green.png";
				if( $channel["channel_maxclients"] > -1 && ($channel["total_clients"] >= $channel["channel_maxclients"])) $icon = "16x16_channel_red.png";
				else if( $channel["channel_maxfamilyclients"] > -1 && ($channel["total_clients_family"] >= $channel["channel_maxfamilyclients"])) $icon = "16x16_channel_red.png";
				else if($channel["channel_flag_password"] == 1) $icon = "16x16_channel_yellow.png";

				$flags = array();
				if($channel["channel_flag_default"] == 1) $flags[] = '16x16_default.png';
				if($channel["channel_needed_talk_power"] > 0) $flags[] = '16x16_moderated.png';
				if($channel["channel_flag_password"] == 1) $flags[] = '16x16_register.png';

        $subchannels = ($sub ? '<img src="../inc/images/tsicons/trenner.gif" alt="" class="tsicon" />' : '').'<img src="../inc/images/tsicons/'.$icon.'" alt="" class="tsicon" />&nbsp;<a href="'.($tpl ? '?cID='.$channel['cid'].'' : 'javascript:DZCP.popup(\'../teamspeak/login.php?ts3&amp;cName='.rep2($channel['channel_name']).'\', \'420\', \'100\')').'" class="navTeamspeak" style="font-weight:bold;white-space:nowrap" title="'.rep2($channel['channel_name']).'">'.rep2($channel['channel_name']).' '.$this->renderFlags($flags).'</a><br />';

        $out .= show(($tpl ? $tpl : "menu/teamspeak_subchan"), array("subchannels" => $subchannels));
        $out .= (count($this->_userDatas) > 0 ? $this->renderUsers($channel["cid"], $sub) : '') . $this->renderChannels($channel['cid'], 1, $tpl);
			}
		}
		return $out;
	}
  
  function getChannelInfos($cid, $full = false) {
		foreach($this->_channelDatas as $channel) {
      if($channel['cid'] == $cid) return ($full) ? $channel : $channel['channel_name'];
    }
  }
	
	function render($tpl = false)
	{
		if(!$this->_updated) $this->update();
		if($this->error == '')
    {	
	  $out = '<table class="hperc">';
      if(!$tpl) {
  		  $out .= '
          <tr>
            <td nowrap="nowrap"><img src="../inc/images/tsicons/16x16_server_green.png" alt="" class="tsicon" /> <span class="fontBold">'.$this->_serverDatas["virtualserver_name"].'</span></td>
          </tr>
          <tr>
            <td style="height:4px"></td>
          </tr>';
        }
			if(count($this->_channelDatas) > 0) $out .= '<tr><td>'.$this->renderChannels(0, 0, $tpl).'</td></tr>';
			$out .= "</table>";
		  return $out;
		}	else return $this->error;
	}	
	
  function welcome($s, $cid)
	{
		if(!$this->_updated) $this->update();
    
		if($this->error == "")
		{
      if(empty($cid)) {
    		$out = "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Server:</span></td></tr>\n";
    		$out .= "<tr><td id=\"contentMainFirst\">".$this->_serverDatas['virtualserver_name']."<br /><br /></td></tr>\n";
    		$out .= "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Server IP:</span></td></tr>\n";
    		$out .= "<tr><td id=\"contentMainFirst\">".$s['ts_ip'].":".$s['ts_port']."<br /><br /></td></tr>\n";
    		$out .= "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Version:</span></td></tr>\n";
    		$out .= "<tr><td id=\"contentMainFirst\">".$this->_serverDatas['virtualserver_version']."<br /><br /></td></tr>\n";
    		$out .= "<tr><td id=\"contentMainFirst\"><span class=\"fontBold\">Welcome Message:</span></td></tr>\n";
    		$out .= "<tr><td id=\"contentMainFirst\">".rep2($this->_serverDatas['virtualserver_welcomemessage'])."<br /><br /></td></tr>";
      } else {
        $channel = $this->getChannelInfos($cid, true);
    		$out = "<tr><td><span class=\"fontBold\">Channel:</span></td></tr>\n";
    		$out .= "<tr><td>".rep2($channel['channel_name'])."<br /><br /></td></tr>\n";
    		$out .= "<tr><td><span class=\"fontBold\">Topic:</span></td></tr>\n";
    		$out .= "<tr><td>".(empty($channel['channel_topic']) ? '-' : rep2($channel['channel_topic']))."<br /><br /></td></tr>\n";
    		$out .= "<tr><td><span class=\"fontBold\">User in channel:</span></td></tr>\n";
    		$out .= "<tr><td>".$channel['total_clients'].($channel['channel_maxclients'] == -1 ? '' : '/'.$channel['channel_maxclients'])."<br /><br /></td></tr>\n";
    		$out .= "<tr><td><br /><input type=\"button\" id=\"submit\" onclick=\"DZCP.popup('login.php?ts3&amp;cName=".rep2($channel['channel_name'])."', '420', '150');\" value=\"Join Channel\" class=\"submit\" /></td></tr>\n";
      }
		} else return $this->error;
		
		return $out;
	}
}

 function rep2($var) {
    return strtr($var, array(
      chr(194) => '',
      '\/' => '/',
      '\s' => ' ',
      '\p' => '|',
      'Ã¶' => 'ö',
      '<' => '&lt;',
      '>' => '&gt;',
      '[URL]' => '',
      '[/URL]' => ''
    ));
}
?>
