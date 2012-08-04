<?php
/**
* Eine Liste der Dateien oder Verzeichnisse zusammenstellen, die sich im angegebenen Ordner befinden.
*
* @return array
*/
function get_files($dir=null,$only_dir=false,$only_files=false,$file_ext=array())
{
	$files = array();
	if($handle = @opendir($dir))
	{
		if($only_dir) ## Ordner ##
		{
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..' && !is_file($dir.'/'.$file))
					$files[] = $file;
			}
		}
		else if($only_files) ## Dateien ##
		{
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..' && is_file($dir.'/'.$file))
				{
					if(count($file_ext) == 0)
						$files[] = $file;
					else
					{
						## Extension Filter ##
						$exp_string = explode(".", $file);
						if(in_array(strtolower($exp_string[1]), $file_ext))
							$files[] = $file;
					}
				}
			}
		}
		else ## Ordner & Dateien ##
		{
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..' && is_file($dir.'/'.$file))
				{
					if(count($file_ext) == 0)
						$files[] = $file;
					else
					{
						## Extension Filter ##
						$exp_string = explode(".", $file);
						if(in_array(strtolower($exp_string[1]), $file_ext))
							$files[] = $file;
					}
				}
				else
				{
					if($file != '.' && $file != '..')
						$files[] = $file;
				}
			}
		}
			
		if(!count($files))
			return false;
			
		@closedir($handle);
		return $files;
	}
	else
		return false;
}

/**
* Erkennen welche PHP Version ausgeführt wird.
*
* @return boolean
*/
function is_php($version)
{ return (floatval(phpversion()) >= $version); }

/**
* Erkennen ob eine PHP 5 Version ausgeführt wird.
*
* @return boolean
*/
function is_php_5()
{ return is_php('5.0.0'); }
?>