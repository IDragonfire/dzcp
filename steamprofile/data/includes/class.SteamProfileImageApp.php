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

require_once 'class.SteamProfileApp.php';
require_once 'class.SteamProfileImage.php';
require_once 'class.ErrorImage.php';

class SteamProfileImageApp extends SteamProfileApp
{
	public function run()
	{
		// check required extensions
		$sPrefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
		if(!extension_loaded('gd') && !dl($sPrefix.'gd.'.PHP_SHLIB_SUFFIX))
			throw new Exception('This script requires GDlib 2.0 or higher!');
		
		if(!in_array($this->aConfig['sImageType'], array('png', 'jpg', 'gif')))
			throw new Exception('Invalid image type (sImageType)!');

		// get suitable method name for selected image type
		$sToCall = 'To'.strtoupper($this->aConfig['sImageType']);
		
		try
		{
			parent::Run();

			$ImageCache = new Cache($this->aConfig['sCacheDir'], $this->aConfig['iCacheLifetime']);
			$AvatarCache = new Cache($this->aConfig['sAvatarCacheDir']);
			$Limiter = new RequestLimiter($this->aConfig['iMaxRequestsPerMin']);

			$SteamProfileImage = new SteamProfileImage($this->sID, $ImageCache, $AvatarCache, $Limiter);
			$SteamProfileImage->SetGraphicsDir($this->aConfig['sGraphicsDir']);
			$SteamProfileImage->SetHeadFont($this->aConfig['sHeadFontFile'], $this->aConfig['fHeadFontSize']);
			$SteamProfileImage->SetBodyFont($this->aConfig['sBodyFontFile'], $this->aConfig['fBodyFontSize']);
			$SteamProfileImage->SetMaxRequestsPerMin($this->aConfig['iMaxRequestsPerMin']);
			$SteamProfileImage->$sToCall();
		}
		catch(Exception $e)
		{
			$ErrorImage = new ErrorImage('Error: '.strip_tags($e->getMessage()));
			$ErrorImage->SetFont($this->aConfig['sErrorFontFile'], $this->aConfig['fErrorFontSize']);
			$ErrorImage->$sToCall();
		}
	}
}
?>