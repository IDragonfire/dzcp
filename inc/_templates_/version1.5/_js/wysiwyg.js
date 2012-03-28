// default wysiwyg editor
  tinyMCE.init({
    mode                                : 'specific_textareas',
    editor_selector                     : 'editorStyle',
  	theme                               : 'advanced',
    plugins                             : 'contextmenu,dzcp,inlinepopups,spellchecker',
    language                            : (lng == 'de' ? lng : 'en'),
  	theme_advanced_buttons1             : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,|,image,youtube,forecolor,'
                                        + 'backcolor,|,smileys,spellchecker',
  	theme_advanced_buttons2             : '',
   	theme_advanced_buttons3             : '',
   	theme_advanced_toolbar_location     : 'top',
   	theme_advanced_toolbar_align        : 'center',
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
    plugins                             : 'contextmenu,dzcp,inlinepopups',
    language                            : (lng == 'de' ? lng : 'en'),
  	theme_advanced_buttons1             : 'bold,italic,underline,|,link,unlink,|,image',
  	theme_advanced_buttons2             : '',
   	theme_advanced_buttons3             : '',
   	theme_advanced_toolbar_location     : 'top',
	theme_advanced_toolbar_align        : 'center',
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
    plugins                             : 'contextmenu,dzcp',
    language                            : (lng == 'de' ? lng : 'en'),
  	theme_advanced_buttons1             : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,|,image,forecolor,backcolor',
  	theme_advanced_buttons2             : '',
   	theme_advanced_buttons3             : '',
   	theme_advanced_toolbar_location     : 'top',
   	theme_advanced_toolbar_align      : 'center',
    theme_advanced_statusbar_location   : 'bottom',
    theme_advanced_resizing             : true,
    theme_advanced_resize_horizontal    : false,
    theme_advanced_resizing_use_cookie  : false,
    accessibility_warnings              : false,
    entity_encoding                     : 'raw',
    verify_html                         : false,
    button_tile_map                     : true
  });