tinyMCEPopup.requireLangPack();
var ed;

var DZCPDialog = {
	init : function() {
		var f = document.forms[0];
  	ed = tinyMCEPopup.editor;
	},

	insert : function() {
    if (document.forms[0].linkSource.value == '') {
  		alert(ed.getLang('dzcp.clip_missing_link'));
  		return false;
  	}
  	if (document.forms[0].htmlSource.value == '') {
  		tinyMCEPopup.close();
  		return false;
  	}
  
    id = document.forms[0].idSource.value;
    lnk = document.forms[0].linkSource.value;
    html = document.forms[0].htmlSource.value;
    
    ret = '<br /><a href="javascript:DZCP.toggle(' + id + ')"><img id="img' + id + '" name="img' + id + '" class="cliptext" src="../inc/images/expand.gif" alt="" />' + lnk + '</a>&nbsp;<div class="clipMore" id="more' + id + '" style="display:none">' + html + '</div><br />';

		tinyMCEPopup.editor.execCommand('mceInsertContent', false, ret);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(DZCPDialog.init, DZCPDialog);
