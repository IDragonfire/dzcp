<?php
/**
 *	This file is part of SteamProfile.
 *
 *	Written by Nico Bergemann <barracuda415@yahoo.de>
 *	Copyright 2008 Nico Bergemann
 *
 *	SteamProfile is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	SteamProfile is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with SteamProfile.  If not, see <http://www.gnu.org/licenses/>.
 */

$config = array();

// common configuration
$config['sGraphicsDir']			= 'images';		 					// directory for the image set, must be accessible
$config['sCacheDir']			= 'data/cache'; 					// common cache dir, must be writable
$config['sAvatarCacheDir']		= 'images/avatars'; 				// cache dir for avatar images, must be writable and accessible
$config['iCacheLifetime']		= 300;								// time in seconds where profile images and pages are cached for future requests
$config['iMaxRequestsPerMin']	= 5; 								// allowed connections to steam webserver per minute and user, 0 = unlimited
$config['bHotlinkCheck']		= false;							// if set to true, hotlinking to the script will be prevented by checking the client's session variable 'sp_allowed'

// configuration for image generation
$config['sHeadFontFile']		= 'data/fonts/DejaVuSans-Bold.ttf';	// true-type font file for the heading (nickname)
$config['fHeadFontSize']		= 7;								// font size of the heading
$config['sBodyFontFile']		= 'data/fonts/DejaVuSans.ttf'; 		// true-type font file for the body (status, currently playing)
$config['fBodyFontSize']		= 7;								// font size of the body
$config['sErrorFontFile']		= $config['sBodyFontFile'];			// true-type font file for the fall-back error text
$config['fErrorFontSize']		= 10;								// font size of the error text
$config['sImageType']			= 'png';							// output image format, can be png, jpg or gif
$config['iQuality']				= 8;								// compression level, for jpeg: 0 to 100, for png: 0 to 9

// configuration for HTML generation
$config['bBodyOnly']			= false;							// if set to true, only the body part of the HTML page will be generated
$config['sBackground']			= '#ffffff';						// page background CSS style if $bBodyOnly is false
?>