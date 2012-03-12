<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: config.php
 * 	Configuration file for the File Manager Connector for PHP.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */
global $Config ;

  $basePath = $_SERVER['PHP_SELF'];
  $basePath = preg_replace("#^(.*?)\/tinymce\/(.*?).php#i","$1",$basePath);
  $basePath = preg_replace('#connector.php$#','',$basePath).'tinymce_files/';
  $basePath = str_replace("///","/",$basePath);

// SECURITY: You must explicitelly enable this "connector". (Set it to "true").
  $Config['Enabled'] = true ;
// Path to user files relative to the document root.
  $Config['UserFilesPath'] = $basePath ;

  $Config['UserFilesAbsolutePath'] = '' ;

  $Config['AllowedExtensions']	= array('psd','7z', 'aiff', 'exe', 'ace','asf', 'avi', 'bmp', 'csv', 'doc', 'fla', 'flv', 'gif', 'gz', 'gzip', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'ods', 'odt', 'pdf', 'png', 'ppt', 'pxd', 'qt', 'ram', 'rar', 'rm', 'rmi', 'rmvb', 'rtf', 'sdc', 'sitd', 'swf', 'sxc', 'sxw', 'tar', 'tgz', 'tif', 'tiff', 'txt', 'vsd', 'wav', 'wma', 'wmv', 'xls', 'xml', 'zip') ;
  $Config['DeniedExtensions']  	= array('php','php3','php5','phtml','asp','aspx','ascx','jsp','pl','bat','reg','cgi');

?>