<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{#dzcp.clip}</title>
    <script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
    <script language="javascript" type="text/javascript" src="jscripts/clip.js"></script>
    <base target="_self" />
</head>
<body onload="tinyMCEPopup.executeOnLoad('onLoadInit();');" onresize="resizeInputs();" style="display: none">
<form name="source" onsubmit="saveContent();">
    <div id="clip">
    <input type="hidden" name="idSource" value="<?php echo mt_rand(0,100000);?>" />
    <b>{#dzcp.clip_link}:</b> <img src="../../../images/expand.gif" alt="" /> <input id="linkSource" type="text" value="more" />
    <textarea name="htmlSource" id="htmlSource" rows="15" cols="100" style="width: 100%; height: 380px; font-family: 'Courier New',Courier,mono; font-size: 12px;" dir="ltr" wrap="soft"></textarea>

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

