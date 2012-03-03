<?php
  $server_timeout = 5;

  function validate($string, $pattern = 'a-zA-Z0-9')
  {
    return strtolower(str_replace(' ', '', preg_replace("#[^".$pattern."+]#Uis", '', $string)));
  }
  
  function cut_string(&$buffer, $end_marker = "\x00")
  {
    $length = strpos($buffer, $end_marker);

    if ($length === FALSE)
    {
      $length = strlen($buffer);
    }

    $string = substr($buffer, 0, $length);

    $buffer = substr($buffer, $length + strlen($end_marker));

    return $string;
  }
  
  function _unpack($string, $format)
  {
    list(,$string) = unpack($format, $string);

    return $string;
  }
  
  function cut_byte(&$buffer, $length)
  {
    $string = substr($buffer, 0, $length);

    $buffer = substr($buffer, $length);

    return $string;
  }
  
  function _time($seconds)
  {
    if ($seconds < 0) { return ""; }

    $h = intval(intval($seconds) / 3600);
    $m = intval(($seconds / 60) % 60);
    $s = intval($seconds % 60);

    $h = str_pad($h, "2", "0", STR_PAD_LEFT);
    $m = str_pad($m, "2", "0", STR_PAD_LEFT);
    $s = str_pad($s, "2", "0", STR_PAD_LEFT);

    return "{$h}:{$m}:{$s}";
  }
  
  function parse_color($string, $type)
  {
    switch($type)
    {
      case "swat4":
        $string = preg_replace("/\[c=......\]/Usi", "", $string);
      break;

      case "farcry":
        $string = preg_replace("/\\$\d/", "", $string);
      break;

    }
    return $string;
  }
  
  function cut_pascal(&$buffer, $start_byte = 1, $length_adjust = 0, $end_byte = 0)
  {
    $length = ord(substr($buffer, 0, $start_byte)) + $length_adjust;
    $string = substr($buffer, $start_byte, $length);
    $buffer = substr($buffer, $start_byte + $length + $end_byte);

    return $string;
  }  
?>