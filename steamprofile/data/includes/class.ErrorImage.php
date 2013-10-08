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

class ErrorImage extends GDImage
{
	private $sMessage			= '';
	private $aBGColor			= array(255, 255, 255);
	private $aBorderColor		= array(255, 0, 0);
	private $aFontColor			= array(255, 0, 0);
	private $iFontSize			= 10;
	private $sFontFile			= 'Arial.ttf';
	private $bFontAA			= true;
	private $sErrorImagePath	= 'images/cross.png';
	
	public function __construct($sMessage, $iWordWrapCount = 50)
	{
		$this->sMessage = wordwrap($sMessage, $iWordWrapCount);
	}
	
	public function SetFont($sFontFile, $iFontSize)
	{
		$this->sFontFile = $sFontFile;
		$this->iFontSize = (int)$iFontSize;
	}
	
	public function SetFontAntiAliasing($bFlag)
	{
		$this->bFontAA = (bool)$bFlag;
	}
	
	public function SetBGColor($iR, $iG, $iB)
	{
		$this->aBGColor = array($iR, $iG, $iB);
	}
	
	public function SetBorderColor($iR, $iG, $iB)
	{
		$this->aBorderColor = array($iR, $iG, $iB);
	}
	
	public function SetFontColor($iR, $iG, $iB)
	{
		$this->aFontColor = array($iR, $iG, $iB);
	}
	
	public function SetErrorImage($sErrorImagePath)
	{
		$this->sErrorImagePath = $sErrorImagePath;
	}
	
	public function Draw()
	{
		$iTextAngle		= 0;
		$iPadding		= 10;
		$iErrorIconSize	= 16;
		$iFontAA = ($this->bFontAA)? 1 : -1;
		
		// get size of text and image
		$aTextBoxSize 		= $this->imagettfbboxextended($this->iFontSize, $iTextAngle, $this->sFontFile, $this->sMessage);
		$iImageSizeXLimit	= 1024;
		
		// limit image size as a precaution
		if($aTextBoxSize['width'] > $iImageSizeXLimit)
			$aTextBoxSize['width'] = $iImageSizeXLimit;
		
		// create image
		$iImageSizeX	= $aTextBoxSize['width'] + $iPadding + $iErrorIconSize;
		$iImageSizeY	= $aTextBoxSize['height'] + $iPadding;
		$this->rImage	= imagecreatetruecolor($iImageSizeX, $iImageSizeY);

		// create colors
		$iBGColor		= imagecolorallocate($this->rImage, $this->aBGColor[0], $this->aBGColor[1], $this->aBGColor[2]);
		$iFontColor		= imagecolorallocate($this->rImage, $this->aFontColor[0], $this->aFontColor[1], $this->aFontColor[2]);
		$iBorderColor	= imagecolorallocate($this->rImage, $this->aBorderColor[0], $this->aBorderColor[1], $this->aBorderColor[2]);
		
		// draw background
		imagefilledrectangle($this->rImage, 0, 0, $iImageSizeX - 1, $iImageSizeY - 1, $iBGColor);
		
		// draw border
		imagerectangle($this->rImage, 0, 0, $iImageSizeX - 1, $iImageSizeY - 1, $iBorderColor);
		
		// draw error icon
		imagecopy($this->rImage, imagecreatefrompng($this->sErrorImagePath), 2, $iImageSizeY / 2 - $iErrorIconSize / 2, 0, 0, $iErrorIconSize, $iErrorIconSize);
		
		// draw text
		imagettftext($this->rImage, $this->iFontSize, $iTextAngle, $aTextBoxSize['x'] + $iPadding / 2 + $iErrorIconSize, $aTextBoxSize['y'] + $iPadding / 2, $iFontColor * $iFontAA, $this->sFontFile, $this->sMessage);
	}
}
?>