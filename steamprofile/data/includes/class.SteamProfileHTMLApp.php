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
require_once 'class.SteamProfileHTML.php';
require_once 'class.ErrorHTML.php';

class SteamProfileHTMLApp extends SteamProfileApp
{
	public function Run()
	{
		try
		{
			parent::Run();
			
			$PageCache = new Cache($this->aConfig['sCacheDir'], $this->aConfig['iCacheLifetime']);
			$AvatarCache = new Cache($this->aConfig['sAvatarCacheDir']);
			$Limiter = new RequestLimiter($this->aConfig['iMaxRequestsPerMin']);
			
			$SteamProfileHTML = new SteamProfileHTML($this->sID, $PageCache, $AvatarCache, $Limiter);
			$SteamProfileHTML->SetBackground($this->aConfig['sBackground']);
			$SteamProfileHTML->ToHTML($this->aConfig['bBodyOnly']);
		}
		catch(Exception $e)
		{
			$ErrorHTML = new ErrorHTML($e);
			$ErrorHTML->ToHTML($this->aConfig['bBodyOnly'], $this->aConfig['sBackground']);
		}
	}
}
?>