<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#dzcp.divx}</title>
	<script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="jscripts/divx.js"></script>
	<base target="_self" />
</head>
<body onLoad="tinyMCEPopup.executeOnLoad('onLoadInit();');" onResize="resizeInputs();" style="display: none">
<form name="source" onSubmit="saveContent();">
	<div id="clip">
    <input type="hidden" name="idSource" value="<?php echo rand(0,100000);?>" />
    <b>{#dzcp.divx_link}:</b> <input id="linkSource" type="text" value="" style="width:420px" /> <br />
    e.g. http://www.domain.de/video.avi or http://www.domain.de/video.divx

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

