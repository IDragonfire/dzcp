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

class CacheException extends Exception {}

class Cache
{
	private $sCacheDir = '';
	private $iCleanupATime = 604800; // 1 week
	private $iDefaultLifetime = -1;

	public function __construct($sCacheDir, $iDefaultLifetime = -1)
	{
		if(!file_exists($sCacheDir))
			throw new CacheException("Cache directory \"$sCacheDir\" does not exist.");
		
		if(!is_writable($sCacheDir))
			throw new CacheException("Cache directory \"$sCacheDir\" is not writable.");
		
		$this->sCacheDir = $sCacheDir;
		$this->iDefaultLifetime = $iDefaultLifetime;
	}
	
	public function GetPath()
	{
		return $this->sCacheDir;
	}
	
	public function GetEntry($sIdentifier, $sExtension = '', $iLifetime = null)
	{
		return new CacheEntry($this, $sIdentifier, $sExtension, ($iLifetime === null)? $this->iDefaultLifetime : $iLifetime);
	}
	
	public function Cleanup($bWipe = false)
	{
		$d = dir($this->sCacheDir);
		
		while(false !== ($sEntry = $d->read()))
		{
			if(!preg_match('#^[0-9a-f]{32}$#', $sEntry))
				continue;
			
			if($bWipe || time() - fileatime($this->sCacheDir.'/'.$sEntry) > $this->iCleanupATime)
				unlink($this->sCacheDir.'/'.$sEntry);
		}
		
		$d->close();
	}
}

class CacheEntry
{
	private $Cache;
	private $sIdentifier = '';
	private $iLifetime = -1;
	private $sExtension = '';

	public function __construct(Cache $Cache, $sIdentifier, $sExtension = '', $iLifetime = -1)
	{
		$this->Cache = $Cache;
		$this->sIdentifier = $sIdentifier;
		
		$this->SetLifetime($iLifetime);
		$this->SetExtension($sExtension);
	}
	
	public function SetLifetime($iLifetime = -1)
	{
		$this->iLifetime = (int)$iLifetime;
	}
	
	public function SetExtension($sExtension)
	{
		$this->sExtension = ($sExtension == '')? '' : '.'.(string)$sExtension;
	}
	
	public function IsCached()
	{
		$sCachePath = $this->GetPath();
		
		return file_exists($sCachePath) && ($this->iLifetime == -1 || time() - filemtime($sCachePath) <= $this->iLifetime);
	}
	
	public function GetPath()
	{
		return $this->Cache->GetPath().'/'.md5($this->sIdentifier).$this->sExtension;
	}
	
	public function GetString()
	{
		$sCachePath = $this->GetPath();
		
		if(!file_exists($sCachePath))
			throw new CacheException("No content for cache entry \"{$this->sIdentifier}\"");
		
		return file_get_contents($sCachePath);
	}
	
	public function SetString($sString)
	{
		return file_put_contents($this->GetPath(), $sString);
	}
	
	public function CopyFrom($sPath)
	{
		return copy($sPath, $this->GetPath());
	}
	
	public function CopyTo($sPath)
	{
		return copy($this->GetPath(), $sPath);
	}
}
?>