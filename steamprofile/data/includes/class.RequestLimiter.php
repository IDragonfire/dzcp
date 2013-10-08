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
 
class RequestLimiter
{
	private $iMaxRequestsPerMin;
	private $sSessionIndex;
	
	public function __construct($iMaxRequestsPerMin = 5, $sSessionIndex = 'request_limit')
	{
		$this->iMaxRequestsPerMin = abs((int)$iMaxRequestsPerMin);
		$this->sSessionIndex = $sSessionIndex;
		
		// set request session array or reset requests per minute limit, if one minute has been elapsed
		if(!isset($_SESSION[$this->sSessionIndex]) || floor((time() - $_SESSION[$this->sSessionIndex][1]) / 60) >= 1)
			$_SESSION[$this->sSessionIndex] = array(0, time());
	}
	
	public function AddRequest($iCount = 1)
	{
		$_SESSION[$this->sSessionIndex][0] += $iCount;
	}
	
	public function IsLimitReached()
	{
		return ($this->iMaxRequestsPerMin != 0 && $_SESSION[$this->sSessionIndex][0] >= $this->iMaxRequestsPerMin);
	}
}
?>