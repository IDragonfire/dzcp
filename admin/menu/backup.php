<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       rootmenu
// Rechte:    $chkMe == 4
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._backup_head;
    if($chkMe == 4)
    {
      $v = str_replace(" ","_",_version);
      $file_name = 'backup_dzcp_v.'.$v.'_'.date("d.m.y").'.sql';
      if(file_exists($file_name))
      {
      //Ausgabe der Datei
        header('Cache-Control:  must-revalidate, post-check=0, pre-check=0');
        header("Content-type: application/txt");
        header('Content-Length: '.filesize($file_name));
        header("Content-Disposition: attachment; filename=".$file_name);
        readfile($file_name);

        @unlink($file_name);
        exit;
      }

      $show = show($dir."/backup", array("head" => _backup_head,
                                         "backup" => _backup_link,
                                         "info_head" => _backup_info_head,
                                         "lastbackup" => _backup_last_head,
                                         "info" => _backup_info));
      if($_GET['do'] == "backup")
      {
        file_put_contents($file_name,sql_backup());
        header("Location: ?admin=backup");
      }
    } else {
      $show = error(_error_wrong_permissions, 1);
    }
?>