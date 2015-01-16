<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

include("../inc/config.php");
include("../inc/debugger.php");
include("../inc/bbcode.php");
?>
<html>
<head>
<title>Tactic</title>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
<meta http-equiv="expires" content="7">
<link rel="stylesheet" type="text/css" href="../inc/_templates_/<?php echo $tmpdir;  ?>/css.css">
</head>
<?php
$constraints=getimagesize($_GET['screen']);
$x=$constraints[0]+7;
$y=$constraints[1]+60;

echo'<body onload="window.resizeTo('.$x.','.$y.');window.moveTo((screen.width-'.$x.')/2,(screen.height-'.$y.')/2);focus();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<a href="javascript:window.close()">
  <img src="'.$_GET['screen'].'" border="0" title="click to close"></a>';
?>
</body>
</html>