function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function insertEmotion(file_name) {
  var ed = tinyMCEPopup.editor, dom = ed.dom;
  tinyMCE.execCommand('mceBeginUndoLevel');

  tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
		src : '../inc/images/smileys/' + file_name,
		alt : file_name,
		border : 0
	}));

  tinyMCE.execCommand('mceEndUndoLevel');
	tinyMCEPopup.close();
}

function insertFlag(file_name) {
  var ed = tinyMCEPopup.editor, dom = ed.dom;
  tinyMCE.execCommand('mceBeginUndoLevel');
  
  tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
		src : '../inc/images/flaggen/' + file_name,
		alt : file_name,
		border : 0
	}));
    
  tinyMCE.execCommand('mceEndUndoLevel');
	tinyMCEPopup.close();
}
