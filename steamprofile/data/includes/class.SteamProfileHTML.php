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

require_once 'class.SteamProfile.php';
require_once 'class.Cache.php';
require_once 'class.RequestLimiter.php';
require_once 'class.TemplateHTML.php';

class SteamProfileHTMLException extends Exception {}

class SteamProfileHTML
{
	private $SteamProfile;
	private $PageCache;
	private $AvatarCache;
	private $Limiter;
	
	private $sBGStyle;
	
	public function __construct($sID, Cache $PageCache, Cache $AvatarCache, RequestLimiter $Limiter)
	{
		$this->SteamProfile = new SteamProfile($sID);
		$this->PageCache = $PageCache;
		$this->AvatarCache = $AvatarCache;
		$this->Limiter = $Limiter;
	}
	
	public function SetBackground($sBGStyle)
	{
		$this->sBGStyle = $sBGStyle;
	}
	
	public function ToHTML($bBodyOnly = false)
	{
		// delete old cache files
		$this->PageCache->Cleanup();
		$this->AvatarCache->Cleanup();
		
		// profile cache identifier
		$sCacheIdent = 'html_'.$this->SteamProfile->GetID().'_'.(string)$bBodyOnly.$this->sBGStyle;
		
		// get cache entry
		$CacheEntry = $this->PageCache->GetEntry($sCacheIdent);
		
		// do we have a cached version of the profile?
		if(!$CacheEntry->IsCached())
		{
			try
			{
				// check how may requests have been done in this minute
				if($this->Limiter->IsLimitReached())
					throw new SteamProfileHTMLException('Requests per minute limit reached!');
				
				$this->SteamProfile->Fetch();
				$this->Limiter->AddRequest();
			}
			catch(Exception $e)
			{
				// we failed to load the profile, try to use last working version
				if(file_exists($CacheEntry->GetPath()))
				{
					echo $CacheEntry->GetString();
					return;
				}
				else
				{
					// otherwise re-throw exception
					throw $e;
				}
			}
			
			// get the player of the profile
			$Player = $this->SteamProfile->GetPlayer();
			
			$sAvatarImageIdent = $Player->GetAvatarURL(2);
			
			// get avatar image cache entry
			$AvatarCacheEntry = $this->AvatarCache->GetEntry($sAvatarImageIdent, 'jpg');
			
			// do we have a cached version of the avatar image?
			if(!$AvatarCacheEntry->IsCached())
			{
				// we need to load and cache it from the server
				$AvatarCacheEntry->SetString($Player->FetchAvatar(2));
			}
			
			$sCachedAvatarImageURL = $AvatarCacheEntry->GetPath();
	
			// begin creating page
			switch($Player->GetStatus())
			{
				case 'in-game':
					$sStatusClass = 'ingame';
				break;
				
				case 'online':
					$sStatusClass = 'online';
				break;

				case 'offline':
					$sStatusClass = 'offline';
				break;
				
				default:
					throw new SteamProfileHTMLException("Unable to determinate player status!");
			}
			
			$sName = strip_tags($Player->GetName());
			$sStatusMessage = strip_tags($Player->GetStatusMessage(), '<a><br>');
			
			$Template = new Template('data/templates/profile.html');
			$Template->replaceTag('StatusClass', $sStatusClass, true);
			$Template->replaceTag('StatusMessage', $sStatusMessage, false);
			$Template->replaceTag('CommunityURL', $Player->GetProfileURL(), true);
			$Template->replaceTag('AvatarURL', $sCachedAvatarImageURL, true);
			$Template->replaceTag('Name', $sName, false);
			$Template->replaceTag('JoinGameURL', $Player->GetJoinGameURL(), true);
			$Template->replaceTag('AddFriendURL', $Player->GetAddFriendURL(), true);
			$sHTML = $Template->getPage();
			
			if(!$bBodyOnly)
			{
				$TemplateBody = new Template('data/templates/body.html');
				$TemplateBody->replaceTag("Title", 'SteamProfile - '.strip_tags($Player->GetName()), false);
				$TemplateBody->replaceTag("Profile", $sHTML);
				
				if($this->sBGStyle !== null)
					$TemplateBody->replaceTag("BodyProp", sprintf(' style="background: %s;"', $this->sBGStyle));
				
				$sHTML = $TemplateBody->getPage();
			}
			
			// create page and save it to cache
			$CacheEntry->SetString($sHTML);
			unset($sHTML);
		}
		
		// seems to be required on some webservers to make UTF-8 work
		header('Content-Type: text/html; charset=utf-8');
		
		echo $CacheEntry->GetString($sCacheIdent);
	}
}
?>