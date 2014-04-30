<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SECTIONS ##
    $uip         = settings('ts_ip');
    $tPort     = settings('ts_sport');
    $port     = settings('ts_port');
    $version     = settings('ts_version');

    if($_POST) {
        $ok = false;
          $nickname    = $_POST['nickname'];
          $reg = $_POST['reg'];

        if($reg=="re") $loginname    = $_POST['loginname'];
          else $loginname    = "";

        $password    = $_POST['password'];
        $channel  = $_POST['channel'];
        $channel  = str_replace("¶","'",$channel);
        $channelpassword = $_POST['channelpassword'];
        $time = time();

           $cookie_data =  $nickname.'¶'.$reg.'¶'.$loginname.'¶'.$password;
           cookie::put('Teamspeakdata', $cookie_data);
           cookie::save(); //Save Cookie
      } elseif($_GET) {
          $ok = true;
          $channel = $_GET['cName'];
          $nickname    = "";
          $reg = "";
           $loginname = "";
           $password = "";
        if( !empty(cookie::get('Teamspeakdata'))) {
            $cookie_info = explode("¶", cookie::get('Teamspeakdata'));
            $nickname = $cookie_info[0];
            $reg = $cookie_info[1];
            $loginname = $cookie_info[2];
            $password = $cookie_info[3];
        }
    } else {
        $ok = false;
      }
?>
<html>
<head>
    <title>Teamspeak Login</title>
  <link rel="stylesheet" type="text/css" href="../inc/_templates_/<?php echo $tmpdir?>/_css/stylesheet.css">
</head>
<?php
if($_POST) {
  if(isset($_GET['ts3'])) $tsIP = 'ts3server://'.$uip.'/?port='.$port.'&amp;nickname='.$nickname.'&amp;channel='.$channel;
  else $tsIP = 'teamspeak://'.$uip.':'.$port.'/?nickname='.$nickname.'&amp;loginname='.$loginname.'&amp;password='.$password.'&amp;channel='.$channel.'&amp;channelpassword='.$channelpassword;
?>
<iframe src="<?=$tsIP?>" height="0" width="0"></iframe>
<?php }?>
<script language="javascript" type="text/javascript">
    function doSubmit()
    {
      var tID = document.getElementById('tsSubmit');
          tID.disabled = true;
          tID.style.color = "#888";
          tID.style.cursor = "default";

        document.frm.submit();
    }
    function resizeMe()
    {
      var smDiv = document.getElementById('tslogin');
      if (navigator.appName.indexOf('Netscape') != -1)
      {
        winB = smDiv.clientWidth;
        winH = smDiv.clientHeight + 10;
      } else if(navigator.appName.indexOf('Opera') != -1) {
        winB = smDiv.offsetWidth+30;
        winH = smDiv.offsetHeight+10;
      } else {
        winB = smDiv.offsetWidth+20;
        winH = smDiv.offsetHeight+20;
      }

      window.resizeTo(winB + 20,winH + 70);
    }
</script>
<body onLoad="resizeMe()">
<table id="tslogin" class="hperc" cellspacing="1">
<tr>
    <td>
    <form name="frm" action="" method="post">
      <input type="hidden" name="autoLog" value="false">
      <input type="hidden" name="channel" value="<?php echo $channel?>">
    <table class="hperc" cellspacing="1">
    <?php if($ok) { ?>
  <tr>
    <td class="contentHead" colspan="2"><span class="fontBold">Channel: <?php echo rawurldecode($_GET['cName'])?></span></td>
  </tr>
    <tr>
        <td class="contentMainTop"><span class="fontBold">Nickname:</span></td>
        <td class="contentMainFirst" style="text-align:center">
      <input type="text" name="nickname" class="inputField_dis"
       onfocus="this.className='inputField_en';"
       onblur="this.className='inputField_dis';"
       style="width:180px;" value="<?php echo $nickname?>" />
    </td>
    </tr>
    <?php if($version == 2) { ?>
    <tr>
        <td colspan="2" class="contentMainTop">
        <table class="hperc" cellspacing="0">
        <tr>
            <td width="1%">
            <?php if($reg == "an" || $reg == "") { ?>
            <input type="radio" id="reg1" name="reg" value="an" checked="checked" class="checkbox" />
            <?php } else { ?>
            <input type="radio" id="reg1" name="reg" value="an" class="checkbox" />
            <?php } ?>
            </td>
            <td style="vertical-align:middle"><label for="reg1">Anonymous</label></td>
            <td width="1%">
            <?php if($reg == "an" || $reg == "") { ?>
            <input type="radio" id="reg2" name="reg" value="re" class="checkbox" />
            <?php } else { ?>
            <input type="radio" id="reg2" name="reg" value="re" checked="checked" class="checkbox" />
            <?php } ?>
            </td>
            <td style="vertical-align:middle"><label for="reg2">Registered</label></td>
        </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td class="contentMainTop"><span class="fontBold">Login name:</span></td>
        <td class="contentMainFirst" style="text-align:center">
      <input type="text" name="loginname" class="inputField_dis"
       onfocus="this.className='inputField_en';"
       onblur="this.className='inputField_dis';"
       style="width:180px;" value="<?php echo $loginname?>" />
    </td>
    </tr>
    <tr>
        <td class="contentMainTop"><span class="fontBold">Password:</span></td>
        <td class="contentMainFirst" style="text-align:center">
      <input type="password" name="password" class="inputField_dis"
       onfocus="this.className='inputField_en';"
       onblur="this.className='inputField_dis';"
       style="width:180px;" value="<?php echo $password?>" />
    </td>
    </tr>
    <?php } if($version == 2 || ($_GET['pw'] == 1 && $version == 3)) { ?>
    <tr>
        <td class="contentMainTop"><span class="fontBold">Channel password:</span></td>
        <td class="contentMainFirst" style="text-align:center">
      <input type="password" name="channelpassword" class="inputField_dis"
       onfocus="this.className='inputField_en';"
       onblur="this.className='inputField_dis';"
       style="width:180px;" />
    </td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="2" class="contentBottom">
      <input id="tsSubmit" type="button" onClick="javascript:doSubmit();" value="Connect" class="submit" />
    </td>
  </tr>
    </form>
    <?php } else { ?>
  <script language="javascript" type="text/javascript">
    javascript:window.close();
  </script>
    <?php } ?>
    </table>
    </td>
</tr>
</table>
</body>
</html>