<?php
session_start();
## OUTPUT BUFFER START ##
include("../../../../buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## FUNCTIONS ##
function dir_delete($verz, $folder = array())
{
  $folder[] = $verz;

  $fp = @opendir($verz);
  while ($dir_file = @readdir($fp))
  {
    if(($dir_file == '.') || ($dir_file == '..'))
      continue;

    $neu_file = $verz . '/' . $dir_file;

    if(is_dir($neu_file)) $folder = dir_delete($neu_file, $folder);
    else                  @unlink($neu_file);
  }
  @closedir($fp);

  return $folder;
} 
function all_delete($dir_file)
{
  if(is_dir($dir_file))
  {
    $array = dir_delete($dir_file);
    $array = array_reverse($array);

    foreach($array as $elem)
      @rmdir($elem);
  } elseif (is_file($dir_file)) @unlink($dir_file);
} 
## DELETE FUNCIONS ##
if(!admin_perms($userid))
{
  echo '<b>Wrong permissions!</b>';
} else {
  $file = str_replace("///","/",$_GET['delete']);
  $file = preg_replace("#(.*?)/tinymce_files/(.*?)#","$2",$file);
  $type = preg_replace("#(.*?)/(.*).(.*?)#","$1",$file);

  if(!empty($_GET['delete'])) all_delete("../../../../tinymce_files/".$file);

  echo('<script language="javascript">parent.location.href=\'../../browser.php?Connector=connectors/php/connector.php?Type=/\'</script>');
}
ob_end_flush();
?>