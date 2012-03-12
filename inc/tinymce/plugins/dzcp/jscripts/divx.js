tinyMCEPopup.requireLangPack();
var ed;

var DZCPDialog = {
	init : function() {
		var f = document.forms[0];
  	ed = tinyMCEPopup.editor;
	},

	insert : function() {
    if (document.forms[0].linkSource.value == '') {
  		alert(ed.getLang('dzcp.divx_missing_link'));
  		return false;
  	}
  
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, '[divx]' + document.forms[0].linkSource.value.replace (/^\s+/, '').replace (/\s+$/, '') + '[/divx]');
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(DZCPDialog.init, DZCPDialog);
