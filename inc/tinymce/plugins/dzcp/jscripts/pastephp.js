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
  
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, '<pre>[php]<br />' + DZCPDialog.repl(document.forms[0].htmlSource.value) + '<br />[/php]</pre>');
		tinyMCEPopup.close();
	}, 
  
  repl: function(txt) {
  //whitespace entfernen
    txt = txt.replace(/^\s+/, '');
    txt = txt.replace(/\s+$/, '');
  
  //remove PHP-Tags
    txt = txt.replace(/^<\?php/g,'');
    txt = txt.replace(/^<\?/g,'');
    txt = txt.replace(/\?>$/g,'');
  
  //convert < & >
    txt = txt.replace(/</g,'&lt;');
    txt = txt.replace(/>/g,'&gt;');
  
    return txt;
  }
};

tinyMCEPopup.onInit.add(DZCPDialog.init, DZCPDialog);
