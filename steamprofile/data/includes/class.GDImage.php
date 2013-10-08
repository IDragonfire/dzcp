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

class GDImageException extends Exception {}

abstract class GDImage
{
	protected $rImage;
	
	abstract public function Draw();
	
	protected function Render($sImageType, $sOutputFile = null, $iQuality = 80)
	{
		if(!preg_match('#^jpeg|png|gif|wbmp|xbm|gd|gd2$#i', $sImageType))
			throw new GDImageException("Invalid image type: \"$sImageType\"");
		
		$sImageType = strtolower($sImageType);
		
		if(!function_exists('image'.$sImageType))
			throw new GDImageException("Unsupported image type: \"$sImageType\"");
	
		if($this->rImage == null)
			$this->Draw();
			
		call_user_func(
			'image'.$sImageType,
			$this->rImage,
			$sOutputFile,
			($sImageType == 'jpeg' || $sImageType == 'png')? $iQuality : null
		);
	}
	
	// from http://de2.php.net/manual/de/function.imagettfbbox.php#75439
	/*this function extends imagettfbbox and includes within the returned array
	the actual text width and height as well as the x and y coordinates the
	text should be drawn from to render correctly.  This currently only works
	for an angle of zero and corrects the issue of hanging letters e.g. jpqg*/
	protected function imagettfbboxextended($size, $angle, $fontfile, $text)
	{
		$bbox = imagettfbbox($size, $angle, $fontfile, $text);

		//calculate x baseline
		if($bbox[0] >= -1)
		{
			$bbox['x'] = abs($bbox[0] + 1) * -1;
		} else
		{
			//$bbox['x'] = 0;
			$bbox['x'] = abs($bbox[0] + 2);
		}

		//calculate actual text width
		$bbox['width'] = abs($bbox[2] - $bbox[0]);
		if($bbox[0] < -1)
		{
			$bbox['width'] = abs($bbox[2]) + abs($bbox[0]) - 1;
		}

		//calculate y baseline
		$bbox['y'] = abs($bbox[5] + 1);

		//calculate actual text height
		$bbox['height'] = abs($bbox[7]) - abs($bbox[1]);
		if($bbox[3] > 0)
		{
			$bbox['height'] = abs($bbox[7] - $bbox[1]) - 1;
		}

		return $bbox;
	}
	
	public function ToPNG($sOutputFile = null, $iQuality = 8)
	{
		if($sOutputFile === null)
			header('Content-Type: image/png');
		
		return $this->Render('png', $sOutputFile, $iQuality);
	}
	
	public function ToJPG($sOutputFile = null, $iQuality = 80)
	{
		if($sOutputFile === null)
			header('Content-Type: image/jpeg');
		
		return $this->Render('jpeg', $sOutputFile, $iQuality);
	}
	
	public function ToGIF($sOutputFile = null)
	{
		if($sOutputFile === null)
			header('Content-Type: image/gif');
		
		return $this->Render('gif', $sOutputFile);
	}
}
?>