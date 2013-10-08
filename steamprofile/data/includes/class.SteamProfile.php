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

// thanks to voogru for the id transformation algorithm (http://forums.alliedmods.net/showthread.php?t=60899)

require 'class.SteamProfileUserAgent.php';

class SteamProfileException extends Exception {}
 
class SteamProfile
{
	private $sSteamComAlias = '';
	private $sSteamComID = '';
	
	private $SteamXML;

	const ODD_VALVE_NUMBER = '76561197960265728';

	public function __construct($sID)
	{
		$sPrefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
		if(!extension_loaded('bcmath') && !dl($sPrefix.'bcmath.'.PHP_SHLIB_SUFFIX))
			throw new SteamProfileException("BCMath extension is not available.");
	

		if(self::IsValidSteamID($sID))
		{
			$this->sSteamComID = self::SteamIDToSteamComID($sID);
		}
		elseif(self::IsValidSteamComID($sID))
		{
			$this->sSteamComID = $sID;
		}
		else
		{
			// probably a community alias
			$this->sSteamComAlias = $sID;
		}
	}
	
	public function GetID()
	{
		return ($this->sSteamComID == '')? $this->sSteamComAlias : $this->sSteamComID;
	}
	
	public function Fetch($sXMLFile = '')
	{
		if($sXMLFile == '')
		{
			$UA = new SteamProfileUserAgent();
			$UA->maxredirs = 0; // disable redirecting
			
			// try to get XML code
			$UA->fetch($this->GetProfileXMLURL());

			// quick'n dirty workaround for the redirection bug in the Steam Community
			foreach($UA->headers as $sHeader)
			{
				// search for Location-header
				if(preg_match('/^Location: (.+)$/',$sHeader, $aMatches))
				{
					$sRedirectURL = trim($aMatches[1]);
				
					// add xml parameter, if missing
					if(substr($sRedirectURL, -6) != '?xml=1')
						$sRedirectURL .= '?xml=1';
					
					// retry
					$UA->fetch($sRedirectURL);
					
					break;
				}
			}

			$sContent = &$UA->results;
			
			if($sContent === null || $sContent === '')
				throw new SteamProfileException("Failed to load profile: HTTP Error");
			
			if(strpos($sContent, 'Failed loading profile data, please try again later') !== false)
				throw new SteamProfileException("Failed to load profile: Steam Community Error");
			
			if(strpos($sContent, 'The specified profile could not be found') !== false)
				throw new SteamProfileException("Failed to load profile: Profile not found");
			
			if(strpos($sContent, 'has not yet set up their Steam Community profile') !== false)
				throw new SteamProfileException("Failed to load profile: Profile not set up");
			
			$this->SteamXML = simplexml_load_string($sContent);
			
			if($this->SteamXML === false)
				throw new SteamProfileException("Failed to load profile: XML Parse Error");
		}
		else
		{
			$this->SteamXML = simplexml_load_file($sXMLFile);
			
			if($this->SteamXML === false)
				throw new SteamProfileException("Failed to load profile: File Access Error");
		}
		
		// get some missing information
		if($this->sSteamComAlias != '')
		{
			$this->sSteamComID = (string)$this->SteamXML->steamID64;
		}
		else
		{
			$this->sSteamComAlias = (string)$this->SteamXML->customURL;
		}
	}
	
	public static function IsValidSteamID($sSteamID)
	{
		return preg_match('/^STEAM_0:[0-9]:\d+$/i', $sSteamID);
	}
	
	public static function SteamIDToSteamComID($sSteamID)
	{
		if(!self::IsValidSteamID($sSteamID))
			throw new SteamProfileException("Invalid Steam-ID: \"$sSteamID\"");
	
		$aTMP = explode(':', $sSteamID);
		
		$sServer = $aTMP[1];
		$sAuth = $aTMP[2];
		
		if((count($aTMP) == 3) && $sAuth != '0' && is_numeric($sServer) && is_numeric($sAuth))
		{
			$sCommunityID = bcmul($sAuth, "2"); // multipy Auth-ID with 2
			$sCommunityID = bcadd($sCommunityID, $sServer); // add Server-ID
			$sCommunityID = bcadd($sCommunityID, self::ODD_VALVE_NUMBER); // add this odd long number
			return $sCommunityID;
		}
		else
			throw new SteamProfileException("An error occurred while converting Steam-ID $sSteamID");
	}
	
	public static function IsValidSteamComID($sSteamComID)
	{
		if(!preg_match('/^\d+$/i', $sSteamComID))
			return false;
		
		// the community id must be bigger than ODD_VALVE_NUMBER
		if(bccomp(self::ODD_VALVE_NUMBER, $sSteamComID) == 1)
			return false;
		
		return true;
	}
	
	public static function SteamComIDToSteamID($sSteamComID)
	{
		if(!self::IsValidSteamComID($sSteamComID))
			throw new SteamProfileException("Invalid Steam Community-ID: \"$sSteamComID\"");
	
		$sServer = bcmod($sSteamComID, '2') == '0' ? '0' : '1';
		$sCommunityID = bcsub($sSteamComID, $sServer);
		$sCommunityID = bcsub($sCommunityID, self::ODD_VALVE_NUMBER);
		$sAuth = bcdiv($sCommunityID, '2');
		
		return "STEAM_0:$sServer:$sAuth";
	}
	
	private function GetProfileXMLURL()
	{
		if($this->sSteamComAlias != '')
			return 'http://steamcommunity.com/id/'.$this->sSteamComAlias.'/?xml=1';
		else
			return 'http://steamcommunity.com/profiles/'.$this->sSteamComID.'/?xml=1';
	}
	
	public function GetPlayer()
	{
		if($this->SteamXML == null)
		{
			throw new SteamProfileException("Fetch() not yet called!");
		}
	
		return new SteamProfilePlayer($this->SteamXML);
	}
}


class SteamProfileXMLElement
{
	protected $XMLElement;
	
	public function __construct(SimpleXMLElement $Element)
	{
		$this->XMLElement = $Element;
	}
}

class SteamProfileAvatarElement extends SteamProfileXMLElement
{
	public function GetAvatarURL($iSize = -1)
	{
		$aAvatars = array();
		$aAvatars[0] = (string)$this->XMLElement->avatarFull; // full size
		$aAvatars[1] = (string)$this->XMLElement->avatarMedium;; // medium size
		$aAvatars[2] = (string)$this->XMLElement->avatarIcon;; // icon size
		return ($iSize != -1 && isset($aAvatars[$iSize]))? $aAvatars[$iSize] : $aAvatars;
	}
	
	public function FetchAvatar($iSize)
	{
		$UA = new SteamProfileUserAgent();
		$UA->fetch($this->GetAvatarURL($iSize));
		
		return $UA->results;
	}
}

class SteamProfileGame extends SteamProfileXMLElement
{	
	public function GetName()
	{
		return (string)$this->XMLElement->gameName;
	}
	
	public function GetAppLink()
	{
		return (string)$this->XMLElement->gameLink;
	}
	
	public function GetIconURL()
	{
		return (string)$this->XMLElement->gameIcon;
	}
	
	public function GetLogoURL()
	{
		return (string)$this->XMLElement->gameLogo;
	}
	
	public function GetLogoSmallURL()
	{
		return (string)$this->XMLElement->gameLogoSmall;
	}
	
	public function GetHoursPlayed()
	{
		return (float)$this->XMLElement->hoursPlayed;
	}
	
	public function GetStatsName()
	{
		return (string)$this->XMLElement->statsName;
	}
}

class SteamProfileMostPlayedGame extends SteamProfileGame
{
	public function GetHoursPlayed()
	{
		return (float)$this->XMLElement->hoursPlayed;
	}

	public function GetStatsName()
	{
		return (string)$this->XMLElement->statsName;
	}
}

class SteamProfileFriend extends SteamProfileAvatarElement
{
	private $sSteamComAlias = '';
	private $sSteamComID = '';
	private $sSteamID = '';

	public function __construct(SimpleXMLElement $XMLElement)
	{
		parent::__construct($XMLElement);
		$this->sSteamComID = $XMLElement->steamID64;
		$this->sSteamComAlias = $XMLElement->customURL;
		$this->sSteamID = SteamProfile::SteamComIDToSteamID($this->sSteamComID);
	}
	
	public function GetProfileURL()
	{
		if($this->sSteamComAlias != '')
			return 'http://steamcommunity.com/id/'.$this->sSteamComAlias;
		else
			return 'http://steamcommunity.com/profiles/'.$this->sSteamComID;
	}
	
	public function GetAddFriendURL()
	{
		return 'steam://friends/add/'.$this->sSteamComID;
	}
	
	public function GetJoinGameURL()
	{
		return 'steam://friends/joingame/'.$this->sSteamComID;
	}
	
	public function GetName()
	{
		return (string)$this->XMLElement->steamID;
	}
	
	public function GetSteamID()
	{
		return SteamProfile::SteamComIDToSteamID((string)$this->XMLElement->steamID64);
	}
	
	public function GetSteamComID()
	{
		return (string)$this->XMLElement->steamID64;
	}
	
	public function GetSteamComAlias()
	{
		return (string)$this->XMLElement->customURL;
	}
	
	public function GetSteamComIDAuto()
	{
		return ($this->XMLElement->customURL != '')? (string)$this->XMLElement->customURL : (string)$this->XMLElement->steamID64;
	}
	
	public function GetStatus()
	{
		return (string)$this->XMLElement->onlineState;
	}
	
	public function GetStatusMessage()
	{
		return (string)$this->XMLElement->stateMessage;
	}
	
	public function GetInGame()
	{
		return ((string)$this->XMLElement->inGameInfo == '')? null : new SteamProfileGame($this->XMLElement->inGameInfo);
	}
}

class SteamProfilePlayer extends SteamProfileFriend
{
	public function IsVACBanned()
	{
		return (int)$this->XMLElement->vacBanned == 1;
	}
	
	public function GetSteamRating($bAsFloat = false)
	{
		if($bAsFloat)
		{
			preg_match('/([0-9.]{3,4}) -/', $this->XMLElement->steamRating, $aMatches);
				
			if(isset($aMatches[1]))
				return (float)$aMatches[1];
			else
				return '';
		}
		else
		{
			return (string)$this->XMLElement->steamRating;
		}
	}
	
	public function GetMemberSince()
	{
		return (string)$this->XMLElement->memberSince;
	}
	
	public function GetHoursPlayed()
	{
		return (float)$this->XMLElement->hoursPlayed2Wk;
	}
	
	public function GetHeadline()
	{
		return (string)$this->XMLElement->headline;
	}
	
	public function GetLocation()
	{
		return (string)$this->XMLElement->location;
	}
	
	public function GetRealName()
	{
		return (string)$this->XMLElement->realname;
	}
	
	public function GetSummary()
	{
		return (string)$this->XMLElement->summary;
	}
	
	public function GetMostPlayedGames()
	{
		$aGames = array();
		
		if($this->XMLElement->mostPlayedGames->mostPlayedGame == null)
			return $aGames;
	
		foreach($this->XMLElement->mostPlayedGames->mostPlayedGame as $XMLElement)
		{
			$aGames[] = new SteamProfileMostPlayedGame($XMLElement);
		}
		
		return $aGames;
	}
	
	public function GetFriends()
	{
		$aFriends = array();
		
		if($this->XMLElement->friends->friend == null)
			return $aFriends;
	
		foreach($this->XMLElement->friends->friend as $XMLElement)
		{
			$aFriends[] = new SteamProfileFriend($XMLElement);
		}
		
		return $aFriends;
	}
	
	public function GetGroups()
	{
		$aGroups = array();
		
		if($this->XMLElement->groups->group == null)
			return $aGroups;
	
		foreach($this->XMLElement->groups->group as $XMLElement)
		{
			$aGroups[] = new SteamProfileGroup($XMLElement);
		}
		
		return $aGroups;
	}
	
	public function GetWeblinks()
	{	
		$aWeblinks = array();
	
		if($this->XMLElement->weblinks->weblink == null)
			return $aWeblinks;
	
		foreach($this->XMLElement->weblinks->weblink as $XMLElement)
		{
			$aWeblinks[] = array(
				'link' => (string)$XMLElement->link,
				'title' => (string)$XMLElement->title
			);
		}
		
		return $aWeblinks;
	}
}

class SteamProfileGroup extends SteamProfileAvatarElement
{
	public function GetID()
	{
		return (string)$this->XMLElement->groupID64;
	}

	public function GetName()
	{
		return (string)$this->XMLElement->groupName;
	}

	public function GetURL()
	{
		return (string)$this->XMLElement->groupURL;
	}
	
	public function GetHeadline()
	{
		return (string)$this->XMLElement->headline;
	}
	
	public function GetMemberCount()
	{
		return (int)$this->XMLElement->memberCount;
	}
	
	public function GetMembersInChat()
	{
		return (int)$this->XMLElement->membersInChat;
	}
	
	public function GetMembersInGame()
	{
		return (int)$this->XMLElement->membersInGame;
	}
	
	public function GetMembersOnline()
	{
		return (int)$this->XMLElement->membersOnline;
	}
}
?>