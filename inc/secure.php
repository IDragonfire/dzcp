<?php
// Start session if no headers were sent
  if(!headers_sent())
  {
    @session_start();
  # Patch by David Vieira-Kurz of majorsecurity.de
    #@session_regenerate_id();
    if(!isset($_SESSION['PHPSESSID']) || !isset($_COOKIE['PHPSESSID']))
    {
      @session_destroy();
      @session_start();
     # @session_regenerate_id();
      $_SESSION['PHPSESSID'] = true;
      $_COOKIE['PHPSESSID']  = true;
    }
  }
/*
// prevent network traffic flooding
  if(!isset($_SESSION['time']) || empty($_SESSION['time'])) $_SESSION['time'] = round(mtime(),1);
  else {
    if(round(mtime(),1) == $_SESSION['time']) die(header('HTTP/1.1 404 Not Found'));
    else $_SESSION['time'] = round(mtime(),1);
  }
*/
// functions needed
  function mtime()
  {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }
  function secure($string)
  {
    $string = trim($string);
    $string = str_replace("#","&#35;",$string);
    $string = str_replace("(","&#40;",$string);
    $string = str_replace(")","&#41;",$string);
    $string = str_replace("<","&#60;",$string);
    $string = str_replace(">","&#62;",$string);

    return $string;
  }
// filter the $_GET var
  for(reset($_GET);list($key,$value)=each($_GET);)
    $_GET[$key] = secure($value);
// set a backslash before a quote in $_POST, $_GET and $_COOKIE var, if magic_quotes_gpc is disabled in php.ini
  if(!get_magic_quotes_gpc())
  {
    foreach($_GET AS $key => $value)
      $_GET[$key]    = addslashes($value);
    foreach($_POST AS $key => $value)
    {
      if(is_array($_POST[$key]))
      {
        foreach($_POST[$key] AS $key1 => $value1)
          $_POST[$key][$key1] = addslashes($value1);
      } else $_POST[$key] = addslashes($value);
    }
    foreach($_COOKIE AS $key => $value)
    {
      if(is_array($_COOKIE[$key]))
      {
        foreach($_COOKIE[$key] AS $key1 => $value1)
          $_COOKIE[$key][$key1] = addslashes($value1);
      } else $_COOKIE[$key] = addslashes($value);
    }
  }

// checks validation of uploaded files (only images are allowed!)
  for(reset($_FILES);list($key,$value)=each($_FILES);)
  {
    if(!empty($value['tmp_name']))
    {
      $end  = explode(".", $value['name']);
      $end  = strtolower($end[count($end)-1]);
      $info = getimagesize($value['tmp_name']);
      
      if($end != 'rar' && $end != 'zip')
      {
        if(
           ($info[2] == 1 || $info[2] == 2 || $info[2] == 3)
           &&
           ($end == 'jpg' || $end == 'jpeg' || $end == 'gif' || $end == 'png')
           &&
           $value['error'] == 0
        ) $_FILES[$key] = $value;
        else {
          @unlink($value['tmp_name']);
          $_FILES[$key] = 'notvalid';
        }
      }
    }
  }
?>