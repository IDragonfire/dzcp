<?php
ob_start();
define('basePath', '../../../../');
require_once(basePath.'/inc/kernel.php');

if(empty($_GET['sort']) || $_GET['sort'] == 'clan')
{
    $sel   = 'selected';
    $order = 'WHERE level > 1';
} 
else 
      $order = 'WHERE level != \'banned\'';

function rawflag($code)
{
	global $picformat;
    foreach($picformat AS $end)
    {
        if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
            return $code;
    }
	
	return 'nocountry';
}
  
function flag($code)
{
    global $picformat;
    foreach($picformat AS $end)
    {
        if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
            return'<img src="../../inc/images/flaggen/'.$code.'.'.$end.'" alt="" style="vertical-align:middle" />';
    }
      
    return '<img src="../../inc/images/flaggen/nocountry.gif" alt="" style="vertical-align:middle" />';
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#dzcp.users}</title>
	<script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="jscripts/users.js"></script>
	<base target="_self" />
</head>
<body>
	<div id="users" style="padding:2px" align="center">
		<table style="width:230px" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td colspan="2" style="text-align:center">
          <select name="sort" style="width:190px"  class="mceSelect" onchange="sort(this.value)">
            <option value="all">{#dzcp.users_all}</option>
            <option value="clan" <?php echo $sel?>>{#dzcp.users_clan}</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
<?php
    $qry = db("SELECT id,nick,country FROM ".$db['users']." ".$order." ORDER BY nick");
    while($get = _fetch($qry))
    {
        echo "<tr>\n";
        echo "  <td>".flag($get['country'])." ".$get['nick']."</td>\n";
        echo "  <td style=\"text-align:right\"><a href=\"javascript:insertUser(".$get['id'].",'".addslashes($get['nick'])."','".rawflag($get['country'])."')\"><img src=\"images/insert.gif\" alt=\"insert\" title=\"{#dzcp.users_add_en}".$get['nick']."{#dzcp.users_add_de}\" border=\"0\"></a></td>\n";
        echo "</tr>\n";
    }
?>
		</table>
	</div>
</body>
</html>
<?php
ob_end_flush();
?>