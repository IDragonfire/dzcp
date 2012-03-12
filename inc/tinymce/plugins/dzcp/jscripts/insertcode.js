tinyMCEPopup.requireLangPack();

var DZCPDialog = {
	init : function() {
		var f = document.forms[0];
	},

	insert : function() {
  	if (document.forms[0].htmlSource.value == '') {
  		tinyMCEPopup.close();
  		return false;
  	}
  
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, '[code]' + document.forms[0].htmlSource.value + '[/code]');
		tinyMCEPopup.close();
	}, 
};

tinyMCEPopup.onInit.add(DZCPDialog.init, DZCPDialog);
