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

class ErrorHTML
{
	private $Exception;

	public function __construct(Exception $e)
	{
		$this->Exception = $e;
	}

	public function ToHTML($bBodyOnly = false, $sBGStyle = null)
	{
		$Template = new Template('data/templates/error.html');
		$Template->replaceTag('Message', $this->Exception->getMessage(), true);
		$sHTML = $Template->getPage();
		
		if(!$bBodyOnly)
		{
			$TemplateBody = new Template('data/templates/body.html');
			$TemplateBody->replaceTag("Title", 'SteamProfile - Error', true);
			$TemplateBody->replaceTag("Profile", $sHTML);
			
			if($sBGStyle !== null)
				$TemplateBody->replaceTag("BodyProp", sprintf(' style="background: %s;"', $sBGStyle));
			
			$sHTML = $TemplateBody->getPage();
		}
		
		echo $sHTML;
	}
}
?>