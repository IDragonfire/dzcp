/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('dzcp');
	tinymce.PluginManager.requireLangPack('dzcp');

	tinymce.create('tinymce.plugins.DZCP', {

		init : function(ed, url) {
		// Smileys
			ed.addCommand('mceSmileys', function() {
				ed.windowManager.open({
					file : url + '/smileys.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('smileys', {
				title : 'dzcp.desc', cmd : 'mceSmileys',
				image : url + '/images/smilies.gif'
			});
    
		// DZCP User
			ed.addCommand('mceDZCPUser', function() {
				ed.windowManager.open({
					file : url + '/users.php',
					width : 280 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('dzcpuser', {
				title : 'dzcp.users', cmd : 'mceDZCPUser',
				image : url + '/images/users.gif'
			});
    
		// Flaggen
			ed.addCommand('mceFlags', function() {
				ed.windowManager.open({
					file : url + '/flags.php',
					width : 400 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('flags', {
				title : 'dzcp.fldesc', cmd : 'mceFlags',
				image : url + '/images/flags.gif'
			});
    
		// PHP-Code einfuegen
			ed.addCommand('mcePastePHP', function() {
				ed.windowManager.open({
					file : url + '/pastephp.htm',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('pastephp', {
				title : 'dzcp.php_desc', cmd : 'mcePastePHP',
				image : url + '/images/pastephp.gif'
			});
    
		// Klapptext
			ed.addCommand('mceClipMe', function() {
				ed.windowManager.open({
					file : url + '/clip.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('clip', {
				title : 'dzcp.clip', cmd : 'mceClipMe',
				image : url + '/images/clip.gif'
			});
    
		// Youtube Videos
			ed.addCommand('mceYoutube', function() {
				ed.windowManager.open({
					file : url + '/youtube.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 90 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('youtube', {
				title : 'dzcp.youtube', cmd : 'mceYoutube',
				image : url + '/images/youtube.gif'
			});

		},

		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
  			longname :  'Plugins for DZCP',
  			author :    'Frank "deV!L" Herrmann',
  			authorurl : 'http://www.dzcp.de',
  			version :   '1.5'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('dzcp', tinymce.plugins.DZCP);
})();