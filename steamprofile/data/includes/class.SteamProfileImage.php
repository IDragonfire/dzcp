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

require_once 'class.GDImage.php';
require_once 'class.SteamProfile.php';
require_once 'class.Cache.php';
require_once 'class.RequestLimiter.php';

class SteamProfileImageException extends Exception {}

class SteamProfileImage extends GDImage
{
	private $SteamProfile;
	private $ImageCache;
	private $AvatarCache;
	private $Limiter;

	private $sAvatarImageFile = '';
	
	private $sHeadFontFile = 'Verdana_Bold.ttf';
	private $fHeadFontSize = 8;
	private $sBodyFontFile = 'Verdana.ttf';
	private $fBodyFontSize = 8;
	private $bFontAA = true;
	
	private $sGraphicsDir = '';
	
	public function __construct($sID, Cache $ImageCache, Cache $AvatarCache, RequestLimiter $Limiter)
	{
		$this->SteamProfile = new SteamProfile($sID);
		$this->ImageCache = $ImageCache;
		$this->AvatarCache = $AvatarCache;
		$this->Limiter = $Limiter;
	}
	
	public function SetGraphicsDir($sGraphicsDir)
	{
		$this->sGraphicsDir = $sGraphicsDir;
	}
	
	public function SetBodyFont($sFontFile, $iFontSize)
	{
		$this->sBodyFontFile = $sFontFile;
		$this->fBodyFontSize = (int)$iFontSize;
	}
	
	public function SetHeadFont($sFontFile, $iFontSize)
	{
		$this->sHeadFontFile = $sFontFile;
		$this->fHeadFontSize = (int)$iFontSize;
	}
	
	private function SetAvatarImage($sAvatarImageFile)
	{
		$this->sAvatarImageFile = file_exists($sAvatarImageFile)? $sAvatarImageFile : $this->sGraphicsDir.'/default_av.jpg';
	}
	
	public function SetAntiAliasing($bFlag)
	{
		$this->bFontAA = (bool)$bFlag;
	}
	
	public function SetMaxRequestsPerMin($iMaxRequestsPerMin)
	{
		$this->iMaxRequestsPerMin = (int)$iMaxRequestsPerMin;
	}
	
	public function Draw()
	{
		// get the player of the profile
		$Player = $this->SteamProfile->GetPlayer();
	
		// get the player's status for text and icon holder color
		switch($Player->GetStatus())
		{
			case 'in-game':
				$sCurrentGame = $Player->GetInGame()->GetName();
			
				$sStatus = 'ingame';
				$sContent = 'In-Game';
				$sContentExtra = $sCurrentGame;
				$aFontColor = array(139, 197, 63);
			break;
			
			case 'online':
				$sStatus = 'online';
				$sContent = 'Online';
				$aFontColor = array(142, 202, 254);
			break;
			
			case 'offline':
				$sStatus = 'offline';
				$sContent = $Player->GetStatusMessage();
				$aFontColor = array(137, 137, 137);
			break;
			
			default:
				throw new SteamProfileImageException('Unable to determinate player status.');
		}
		
		// some hard-coded variables
		$iTextBaseX = 50;
		$iTextBaseY = 14;
		$iTextPadding = 12;
		$iFontAA = ($this->bFontAA)? 1 : -1;
		
		// load blank background
		$this->rImage = imagecreatefrompng($this->sGraphicsDir.'/background.png');
		
		// enable alpha blending
		imagealphablending($this->rImage, true);
		imagesavealpha($this->rImage, true);
		
		if($this->rImage == '')
			throw new SteamProfileImageException("Failed to load background image \"{$this->sGraphicsDir}/background.png\"");
		
		 // create iconholder
		$rIconHolderImage = imagecreatefromjpeg($this->sGraphicsDir."/iconholder_$sStatus.jpg");
		
		if($rIconHolderImage == '')
			throw new SteamProfileImageException("Failed to load icon holder image \"{$this->sGraphicsDir}/iconholder_$sStatus.jpg\"");
		
		imagecopy($this->rImage, $rIconHolderImage, 4, 4, 0, 0, 40, 40);
		imagedestroy($rIconHolderImage);
		
		// create avatar icon
		$rAvatarIconImage = imagecreatefromjpeg($this->sAvatarImageFile);
		
		if($rAvatarIconImage == '')
			throw new SteamProfileImageException("Failed to load avatar icon image \"{$this->sAvatarImageFile}\"");
		
		imagecopy($this->rImage, $rAvatarIconImage, 8, 8, 0, 0, 32, 32);
		imagedestroy($rAvatarIconImage);
		
		// create text
		$rFontColor = imagecolorallocate($this->rImage, $aFontColor[0], $aFontColor[1], $aFontColor[2]) * $iFontAA;
		
		imagefttext($this->rImage, $this->fHeadFontSize, 0, $iTextBaseX, $iTextBaseY, $rFontColor, $this->sHeadFontFile, $Player->GetName());
		imagefttext($this->rImage, $this->fBodyFontSize, 0, $iTextBaseX, $iTextBaseY + $iTextPadding, $rFontColor, $this->sBodyFontFile, $sContent);
		
		if(isset($sContentExtra))
			imagettftext($this->rImage, $this->fBodyFontSize, 0, $iTextBaseX, $iTextBaseY + $iTextPadding * 2, $rFontColor, $this->sBodyFontFile, $sContentExtra);
	}
	
	public function Render($sImageType, $sOutputFile = null, $iQuality = 80)
	{
		// delete old cache files
		$this->ImageCache->Cleanup();
		$this->AvatarCache->Cleanup();
		
		// profile image cache identifier
		$sImageIdent = $sImageType.'_'.$this->SteamProfile->GetID().'_'.$iQuality;
		
		// get image cache entry
		$ImageCacheEntry = $this->ImageCache->GetEntry($sImageIdent);
		
		// do we have a cached version of the profile image?
		if(!$ImageCacheEntry->IsCached())
		{
			try
			{
				// check how may requests have been done in this minute
				if($this->Limiter->IsLimitReached())
					throw new SteamProfileImageException('Requests per minute limit reached!');
				
				$this->SteamProfile->Fetch();
				$this->Limiter->AddRequest();
			}
			catch(Exception $e)
			{
				// we failed to load the profile, try to use last working version
				if(file_exists($ImageCacheEntry->GetPath()))
				{
					echo $ImageCacheEntry->GetString();
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
			
			$this->SetAvatarImage($AvatarCacheEntry->GetPath());
			
			// create image and save it to cache
			parent::Render($sImageType, $ImageCacheEntry->GetPath(), $iQuality);
		}
		
		if($sOutputFile == null)
		{
			// set content-length header
			$sStat = stat($ImageCacheEntry->GetPath());
			header('Content-Length: '.$sStat[7]);
			
			echo $ImageCacheEntry->GetString($sImageIdent);
		}
		else
		{
			$ImageCacheEntry->CopyTo($sOutputFile);
		}
	}
}
?>