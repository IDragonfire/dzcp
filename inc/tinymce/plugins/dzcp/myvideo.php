<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#dzcp.myvideo}</title>
	<script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="jscripts/myvideo.js"></script>
	<base target="_self" />
</head>
<body onLoad="tinyMCEPopup.executeOnLoad('onLoadInit();');" onResize="resizeInputs();" style="display: none">
<form name="source" onSubmit="saveContent();">
	<div id="clip">
    <input type="hidden" name="idSource" value="<?php echo rand(0,100000);?>" />
    <b>{#dzcp.myvideo_link}:</b> <input id="linkSource" type="text" value="" style="width:420px" /> <br />
    e.g. http://www.myvideo.de/watch/7288169/Techno_Trance_Dance_Forever_HQ <br>ID: 7288169

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" name="insert" value="{#insert}" onClick="DZCPDialog.insert();" id="insert" />
		</div>

		<div style="float: right">
			<input type="button" name="cancel" value="{#cancel}" onClick="tinyMCEPopup.close();" id="cancel" />
		</div>
  </div>
</form>
</body>
</html>

