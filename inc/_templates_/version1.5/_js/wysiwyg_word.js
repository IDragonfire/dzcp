// full wysiwyg editor
  tinyMCE.init({
    mode                              : 'specific_textareas',
    editor_selector                   : 'editorStyleWord',
  	theme                             : 'advanced',
    plugins                           : 'contextmenu,dzcp,advimage,paste,flash,table,fullscreen,inlinepopups,spellchecker',
    language                          : (lng == 'de' ? lng : 'en'),
  	theme_advanced_buttons1           : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,|,pastephp,|,forecolor,'
                                      + 'backcolor,|,smileys,flags,',
  	theme_advanced_buttons2           : 'paste,pastetext,pasteword,|,image,|,tablecontrols,|,dzcpuser',
   	theme_advanced_buttons3           : 'formatselect,fontselect,fontsizeselect,|,sub,sup,|,outdent,indent,|,fullscreen,clip,spellchecker,code,youtube',
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
    file_browser_callback             : 'fileBrowserCallBack'
  });
  
// filebrowser callback
  function fileBrowserCallBack(field_name, url, type, win) 
  {
    var prefix = (ie4) ? '../inc/tinymce/' : '../../';
  	var connector = prefix + 'filemanager/browser.php?Connector=connectors/php/connector.php?Type=/';
    	
  	tinyfck_field = field_name;
  	tinyfck = win;
    downloadForm = false;
    
  	window.open(connector, 'tinyfck' + new Date().getTime(), 'modal,width=670,height=400');
  }