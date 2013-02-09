<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{#dzcp.youtube}</title>
    <script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
    <script language="javascript" type="text/javascript" src="jscripts/youtube.js"></script>
    <base target="_self" />
</head>
<body onload="tinyMCEPopup.executeOnLoad('onLoadInit();');" onresize="resizeInputs();" style="display: none">
<form name="source" onsubmit="saveContent();">
    <div id="clip">
    <input type="hidden" name="idSource" value="<?php
echo rand(0, 100000);
?>" />
    <b>{#dzcp.youtube_link}:</b> <input id="linkSource" type="text" value="" style="width:420px" /> <br />
    e.g. http://www.youtube.com/watch?v=hAok2_z8mSQ

    <div class="mceActionPanel">
        <div style="float: left">
            <input type="button" name="insert" value="{#insert}" onclick="DZCPDialog.insert();" id="insert" />
        </div>

        <div style="float: right">
            <input type="button" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" id="cancel" />
        </div>
  </div>
</form>
</body>
</html>

