<?php
//Userspezifische Informationen
function infos()
{
    if(settings("persinfo") == 1)
    {
      $data = $_SERVER['HTTP_USER_AGENT'];
      if(preg_match("/Android/i",$data)) 					$system = "Android";
      elseif(preg_match ("/Linux/i",$data))           		$system = "Linux";
      elseif(preg_match("/SunOS/i",$data))        			$system = "Sun OS";
      elseif(preg_match("/Macintosh/i",$data))    			$system = "Macintosh";
      elseif(preg_match("/Mac_PowerPC/i",$data))  			$system = "Macintosh";
      elseif(preg_match("/Windows 2000/i",$data)) 			$system = "Windows 2000";
      elseif(preg_match("/Windows XP/i",$data))   			$system = "Windows XP";
      elseif(preg_match("/NT 5.2/i",$data))       			$system = "Windows XP x64";
      elseif(preg_match("/NT 5.1/i",$data))       			$system = "Windows XP";
      elseif(preg_match("/NT 5.0/i",$data))      			$system = "Windows 2000";
      elseif(preg_match("/NT 4.0/i",$data))       			$system = "Windows NT 4";
      elseif(preg_match("/NT 6.0/i",$data))       			$system = "Windows Vista";
      elseif(preg_match("/NT 6.1/i",$data))       			$system = "Windows 7";
	  elseif(preg_match("/NT 6.2/i",$data))       			$system = "Windows 8";
      elseif(preg_match("/Windows ME/i",$data))   			$system = "Windows 9x + ME";
      elseif(preg_match("/Windows 98/i",$data))   			$system = "Windows 9x + ME";
      elseif(preg_match("/Windows 95/i",$data))   			$system = "Windows 9x + ME";
      elseif(preg_match("/Win 9x/i",$data))       			$system = "Windows 9x + ME";
      elseif(preg_match("/Win95/i",$data))        			$system = "Windows 9x + ME";
      elseif(preg_match("/Win98/i",$data))        			$system = "Windows 9x + ME";
      elseif(preg_match("/OS (.*?) like Mac OS X/i",$data)) $system = "iOS";
      else                                        $system = _unknown_system;

      if(preg_match("/Opera/i",$data))          $browser = "Opera";
      elseif(preg_match("/Konqueror/i",$data))  $browser = "Konqueror";
      elseif(preg_match("/Firefox/i",$data))    $browser = "Mozilla Firefox";
	  elseif(preg_match("/chrome/i",$data))     $browser = "Google Chrome";
	  elseif(preg_match("/Safari/i",$data))     $browser = "Safari";
      elseif(preg_match("/MSIE 5/i",$data))     $browser = "Internet Explorer 5";
      elseif(preg_match("/MSIE 6/i",$data))     $browser = "Internet Explorer 6";
      elseif(preg_match("/MSIE 7/i",$data))     $browser = "Internet Explorer 7";
      elseif(preg_match("/MSIE 8/i",$data))     $browser = "Internet Explorer 8";
	  elseif(preg_match("/MSIE 9/i",$data))     $browser = "Internet Explorer 9";
	  elseif(preg_match("/MSIE 10/i",$data))     $browser = "Internet Explorer 10";
      else                                      $browser = _unknown_browser;

      $res = "<script language=\"javascript\" type=\"text/javascript\">
                  doc.write(screen.width + ' x ' + screen.height)
              </script>";

      return show("menu/pers.infos", array("ip" => ($userip=VisitorIP()),
                                             "info_ip" => _info_ip,
                                             "host" => gethostbyaddr($userip),
                                             "info_browser" => _info_browser,
                                             "browser" => $browser,
                                             "info_res" => _info_res,
                                             "res" => $res,
                                             "info_sys" => _info_sys,
                                             "sys" => $system));
    }

	return "";
}
?>
