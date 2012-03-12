// default wysiwyg editor
  tinyMCE.init({
    mode                                : 'specific_textareas',
    editor_selector                     : 'editorStyle',
  	theme                               : 'advanced',
	skin 								: 'o2k7',
	skin_variant 						: 'silver',  	
    plugins                             : 'contextmenu,dzcp,inlinepopups,spellchecker,advhr,fullscreen,visualchars,insertdatetime,searchreplace,paste,directionality,table',
    language                            : (lng == 'de' ? lng : 'en'),
	theme_advanced_buttons1 			: 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,undo,redo,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent',					
  	theme_advanced_buttons2             : 'forecolor,backcolor,|,link,unlink,pastephp,anchor,image,clip,code,|,insertdate,inserttime,|,dzcpuser,smileys,flags,youtube,googlevideo,myvideo,vimeo,xfire,gt,divx,golemvideo',
   	theme_advanced_buttons3             : 'tablecontrols,|,hr,visualaid,|,charmap,advhr,|,ltr,rtl,|,fullscreen',
   	theme_advanced_toolbar_location     : 'top',
   	theme_advanced_toolbar_align        : 'left',
    theme_advanced_statusbar_location   : 'bottom',
    spellchecker_languages              : 'English=en,+Deutsch=de',
    theme_advanced_resizing             : true,
    theme_advanced_resize_horizontal    : false,  
    theme_advanced_resizing_use_cookie  : false,
    accessibility_warnings              : false,
    entity_encoding                     : 'raw',
    verify_html                         : false,
    button_tile_map                     : true
  });

// mini wysiwyg editor
  tinyMCE.init({
    mode                                : 'specific_textareas',
    editor_selector                     : 'editorStyleMini',
  	theme                               : 'advanced',
	skin 								: 'o2k7',
	skin_variant 						: 'silver',
    plugins                             : 'contextmenu,dzcp,inlinepopups,spellchecker,advhr,fullscreen,visualchars,insertdatetime,searchreplace,paste,directionality,table',
    language                            : (lng == 'de' ? lng : 'en'),
  	theme_advanced_buttons1             : 'bold,italic,underline,strikethrough,|,undo,redo,|,link,unlink,|,image,|,fullscreen,|,hr,visualaid,|,charmap,advhr,|,ltr,rtl,',
  	theme_advanced_buttons2             : '|,justifyleft,justifycenter,justifyright,justifyfull,|,tablecontrols',
   	theme_advanced_buttons3             : '',
   	theme_advanced_toolbar_location     : 'top',
    theme_advanced_resizing             : true,
    theme_advanced_resize_horizontal    : false,
    theme_advanced_resizing_use_cookie  : false,
    accessibility_warnings              : false,
    entity_encoding                     : 'raw',
    verify_html                         : false,
    button_tile_map                     : true
  });

// newsletter wysiwyg editor
  tinyMCE.init({
    mode                                : 'specific_textareas',
    editor_selector                     : 'editorStyleNewsletter',
  	theme                               : 'advanced',
	skin 								: 'o2k7',
	skin_variant 						: 'silver',  	
    plugins                             : 'contextmenu,inlinepopups,spellchecker,advhr,fullscreen,visualchars,insertdatetime,searchreplace,paste,directionality,table',
    language                            : (lng == 'de' ? lng : 'en'),
	theme_advanced_buttons1 			: 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,undo,redo,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent',					
  	theme_advanced_buttons2             : 'forecolor,backcolor,|,link,unlink,anchor,image,clip,code,|,insertdate,inserttime',
   	theme_advanced_buttons3             : 'tablecontrols,|,hr,visualaid,|,charmap,advhr,|,ltr,rtl,|,fullscreen',
   	theme_advanced_toolbar_location     : 'top',
   	theme_advanced_toolbar_align        : 'left',
    theme_advanced_statusbar_location   : 'bottom',
    theme_advanced_resizing             : true,
    theme_advanced_resize_horizontal    : false,
    theme_advanced_resizing_use_cookie  : false,
    accessibility_warnings              : false,
    entity_encoding                     : 'raw',
    verify_html                         : false,
    button_tile_map                     : true
  });