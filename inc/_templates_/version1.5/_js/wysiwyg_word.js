// full wysiwyg editor
  tinyMCE.init({
    mode                              : 'specific_textareas',
    editor_selector                   : 'editorStyleWord',
  	theme                             : 'advanced',
	elements                          : "ajaxfilemanager",
    plugins                           : 'contextmenu,dzcp,advimage,paste,table,fullscreen,inlinepopups,spellchecker,searchreplace,insertdatetime,',
    language                          : (lng == 'de' ? lng : 'en'),
  	theme_advanced_buttons1           : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,undo,redo,|,bullist,numlist,|,link,unlink,|,pastephp,|,forecolor,'
                                      + 'backcolor,|,smileys,flags,',
  	theme_advanced_buttons2           : 'paste,pastetext,pasteword,|,search,replace,|,image,|,tablecontrols,|,dzcpuser',
   	theme_advanced_buttons3           : 'fontselect,fontsizeselect,|,insertdate,inserttime,|,sub,sup,|,outdent,indent,|,fullscreen,clip,spellchecker,code,youtube',
    extended_valid_elements           : 'img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],'
                                      + 'hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]',
   	theme_advanced_toolbar_location   : 'top',
    spellchecker_languages            : 'English=en,+Deutsch=de',
   	theme_advanced_toolbar_align      : 'center',
    theme_advanced_statusbar_location : 'bottom',
    theme_advanced_resizing           : true,
    theme_advanced_resize_horizontal  : false,
    accessibility_warnings            : false,
    button_tile_map                   : true,
    entity_encoding                   : 'raw',
    verify_html                       : false,
    file_browser_callback             : 'ajaxfilemanager'
  });
  
// filebrowser callback
 		function ajaxfilemanager(field_name, url, type, win) {
			var ajaxfilemanagerurl = "../inc/tinymce/plugins/ajaxfilemanager/ajaxfilemanager.php";
			var view = 'detail';
			switch (type) {
				case "image":
				view = 'thumbnail';
					break;
				case "media":
					break;
				case "flash": 
					break;
				case "file":
					break;
				default:
					return false;
			}
            tinyMCE.activeEditor.windowManager.open({
                url: "../inc/tinymce/plugins/ajaxfilemanager/ajaxfilemanager.php?view=" + view,
                width: 850,
                height: 478,
                inline : "yes",
                close_previous : "no"
            },{
                window : win,
                input : field_name
            });
            
/*            return false;			
			var fileBrowserWindow = new Array();
			fileBrowserWindow["file"] = ajaxfilemanagerurl;
			fileBrowserWindow["title"] = "Ajax File Manager";
			fileBrowserWindow["width"] = "782";
			fileBrowserWindow["height"] = "440";
			fileBrowserWindow["close_previous"] = "no";
			tinyMCE.openWindow(fileBrowserWindow, {
			  window : win,
			  input : field_name,
			  resizable : "yes",
			  inline : "yes",
			  editor_id : tinyMCE.getWindowArg("editor_id")
			});
			
			return false;*/
		} 