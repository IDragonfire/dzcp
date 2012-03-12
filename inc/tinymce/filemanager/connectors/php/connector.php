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
 * File Name: connector.php
 * 	This is the File Manager Connector for PHP.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

function convertFileName($txt)
{
  $txt = str_replace("Ä","ae",$txt);
  $txt = str_replace("ä","ae",$txt);
  $txt = str_replace("Ü","ue",$txt);
  $txt = str_replace("ü","ue",$txt);
  $txt = str_replace("Ö","oe",$txt);
  $txt = str_replace("ö","oe",$txt);
  $txt = str_replace("ß","ss",$txt);
  $txt = str_replace(" ","_",$txt);
  return $txt;
}

include('config.php') ;
include('util.php') ;
include('io.php') ;
include('basexml.php') ;
include('commands.php') ;

// Get the "UserFiles" path.
if ( isset( $Config['UserFilesPath'] ) )
	$GLOBALS["UserFilesPath"] = $Config['UserFilesPath'] ;
else if ( isset( $_GET['ServerPath'] ) )
	$GLOBALS["UserFilesPath"] = $_GET['ServerPath'] ;
else
	$GLOBALS["UserFilesPath"] = '/UserFiles/' ;

if ( ! preg_match( '#/$#', $GLOBALS["UserFilesPath"] ) )
	$GLOBALS["UserFilesPath"] .= '/' ;

if ( strlen( $Config['UserFilesAbsolutePath'] ) > 0 ) 
{
	$GLOBALS["UserFilesDirectory"] = $Config['UserFilesAbsolutePath'] ;

	if ( ! preg_matchg( '#/$#', $GLOBALS["UserFilesDirectory"] ) )
		$GLOBALS["UserFilesDirectory"] .= '/' ;
}
else
{
	// Map the "UserFiles" path to a local directory.
	$GLOBALS["UserFilesDirectory"] = GetRootPath() . $GLOBALS["UserFilesPath"] ;
}

DoResponse() ;

function DoResponse()
{
	if ( !isset( $_GET['Command'] ) || !isset( $_GET['Type'] ) || !isset( $_GET['CurrentFolder'] ) )
		return ;

	// Get the main request informaiton.
	$sCommand		= $_GET['Command'] ;
	$sResourceType	= $_GET['Type'] ;
	$sCurrentFolder	= $_GET['CurrentFolder'] ;

	// Check if it is an allowed type.
	if ( !in_array( $sResourceType, array('/') ) )
		return ;

	// Check the current folder syntax (must begin and start with a slash).
	if ( ! preg_match( '#/$#', $sCurrentFolder ) ) $sCurrentFolder .= '/' ;
	if ( strpos( $sCurrentFolder, '/' ) !== 0 ) $sCurrentFolder = '/' . $sCurrentFolder ;
	
	// Check for invalid folder paths (..)
	if ( strpos( $sCurrentFolder, '..' ) )
		SendError( 102, "" ) ;

	// File Upload doesn't have to Return XML, so it must be intercepted before anything.
	if ( $sCommand == 'FileUpload' )
	{
		FileUpload( $sResourceType, convertFileName($sCurrentFolder) ) ;
		return ;
	}
  
	CreateXmlHeader( $sCommand, $sResourceType, $sCurrentFolder ) ;

	// Execute the required command.
	switch ( $sCommand )
	{
		case 'GetFolders' :
			GetFolders( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'GetFoldersAndFiles' :
			GetFoldersAndFiles( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'CreateFolder' :
			CreateFolder( $sResourceType, convertFileName($sCurrentFolder) ) ;
			break ;
	}

	CreateXmlFooter() ;

	exit ;
}
?>