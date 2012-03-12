/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('dzcp');

	tinymce.create('tinymce.plugins.DZCP', {

		init : function(ed, url) {
		// Smileys
			ed.addCommand('mceSmileys', function() {
				ed.windowManager.open({
					file : url + '/smileys.php',
					width : 700 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 550 + parseInt(ed.getLang('dzcp.delta_height', 0)),
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
			
		// Google Videos
			ed.addCommand('mceGoogleVideo', function() {
				ed.windowManager.open({
					file : url + '/googlevideo.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('googlevideo', {
				title : 'dzcp.googlevideo', cmd : 'mceGoogleVideo',
				image : url + '/images/googlevideo.gif'
			});
			
		//	Golem Video
			ed.addCommand('mceGolemVideo', function() {
				ed.windowManager.open({
					file : url + '/golemvideo.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('golemvideo', {
				title : 'dzcp.golemvideo', cmd : 'mceGolemVideo',
				image : url + '/images/golemvideo.gif'
			});			

		// MyVideo
			ed.addCommand('mceMyVideo', function() {
				ed.windowManager.open({
					file : url + '/myvideo.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('myvideo', {
				title : 'dzcp.myvideo', cmd : 'mceMyVideo',
				image : url + '/images/myvideo.gif'
			});
			
		//Pic Upload	
		ed.addCommand('mcePicUpload', function() {
				ed.windowManager.open({
					file : url + '/picupload.htm',
					width : 530 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 410 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('picupload', {
				title : 'dzcp.picupload', cmd : 'mcePicUpload',
				image : url + '/images/picupload.gif'
			});
			
		// Vimeo Video
			ed.addCommand('mceVimeo', function() {
				ed.windowManager.open({
					file : url + '/vimeo.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('vimeo', {
				title : 'dzcp.vimeo', cmd : 'mceVimeo',
				image : url + '/images/vimeo.gif'
			});
			
		// XFire Video
			ed.addCommand('mceXFire', function() {
				ed.windowManager.open({
					file : url + '/xfire.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('xfire', {
				title : 'dzcp.xfire', cmd : 'mceXFire',
				image : url + '/images/xfire.gif'
			});
			
		// Game Trailers Video
			ed.addCommand('mceGameTrailers', function() {
				ed.windowManager.open({
					file : url + '/gt.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('gt', {
				title : 'dzcp.gt', cmd : 'mceGameTrailers',
				image : url + '/images/gt.gif'
			});			

		// DivX
			ed.addCommand('mceDivX', function() {
				ed.windowManager.open({
					file : url + '/divx.php',
					width : 500 + parseInt(ed.getLang('dzcp.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
				}, { plugin_url : url });
			});

			ed.addButton('divx', {
				title : 'dzcp.divx', cmd : 'mceDivX',
				image : url + '/images/divx.gif'
			});
			
			ed.addCommand('mceInsertCode', function() {
            var e = ed.selection.getNode();
                ed.windowManager.open({
                    file : url + '/insertcode.htm',
                   	width : 550 + parseInt(ed.getLang('dzcp.delta_width', 0)),
                    height : 450 + parseInt(ed.getLang('dzcp.delta_height', 0)),
					inline : 1, resizable : 1, scrollbars : 1
                }, 
				{ plugin_url : url });
            });
			
            ed.addButton('insertcode', {
                title : 'dzcp.insertcode_desc',
                cmd : 'mceInsertCode',
                image : url + '/images/code.png'
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