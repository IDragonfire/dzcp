<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       rootmenu
// Rechte:    $chkMe == 4
///////////////////////////////
if(_adminMenu != 'true') exit;
die('IN DEV');

/*
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
                                         "lbackup" => $lbackup,
                                         "info" => _backup_info));
      if($_GET['do'] == "backup")
      {
        $txt  = "# --------------------------------------------------------\r\n";
        $txt .= "# Datenbank Backup von deV!L`z Clanportal v."._version."\r\n";
        $txt .= "# Host: ".$db['host']."\r\n";
        $txt .= "# Erstellt am: ".date("d.m.Y")." um ".date("H:i")."\r\n";
        $txt .= "# Server Betriebssystem: ".@php_uname()."\r\n";
        $txt .= "# MySQL-Version: ".mysqli_get_server_info()."\r\n";
        $txt .= "# PHP-Version: ".phpversion()."\r\n";
        $txt .= "# Datenbank: `".$db['db']."`\r\n";
        $txt .= "# --------------------------------------------------------\r\n\r\n";

        $fd = fopen($file_name,"a+");
        fwrite($fd, $txt);
        fclose($fd);

        $c = 0;
        $qry = mysql_list_tables($db['db']);

        for ($i = 0; $i < _rows($qry); $i++)
        {
          $tabelle = mysql_tablename($qry,$i);
          if($tabelle != "")
          {
            $tbl_array[$c] = mysql_tablename($qry,$i);
            $c++;
          }
        }

        for ($y = 0; $y < $c; $y++)
        {
          $tabelle = $tbl_array[$y];

          unset($def,$global);
          $def .= "DROP TABLE IF EXISTS $tabelle;\r\n";
          $def .= "CREATE TABLE $tabelle (\r\n";
          $qry_f = db("SHOW FIELDS FROM $tabelle");
          while($get = _fetch($qry_f))
          {
            $def .= "    ".$get["Field"]." ".$get["Type"];
            if($get["Default"] != "") $def .= " DEFAULT '".$get["Default"]."'";
            if($get["Null"] != "YES") $def .= " NOT NULL";
            if($get["Extra"] != "") $def .= " ".$get["Extra"];
            $def .= ",\r\n";
          }
          $def = preg_replace("#,\r\n$#", "", $def);
          $qry_f = db("SHOW KEYS FROM $tabelle");

          while($get = _fetch($qry_f))
          {
            $kname = $get["Key_name"];
            if(($kname != "PRIMARY") AND ($get["Non_unique"] == 0)) $kname = "UNIQUE|".$kname;
            if(!isset($global[$kname])) $global[$kname][] = $get["Column_name"];

            list($xy, $columns) = each($global);
            $def .= ",\r\n";
            if($xy == "PRIMARY") $def .= "    PRIMARY KEY (".implode($columns, ", ").")";
            elseif(substr($xy,0,6) == "UNIQUE") $def .= "    UNIQUE ".substr($xy,7)." (".implode($columns, ", ").")";
            else $def .= "    KEY $xy (".implode($columns, ", ").")";
          }
          $def .= "\r\n);\r\n\r\n";

          $tabelle = "".$tabelle;
          $txt = "#\r\n# Struktur der Tabelle `$tabelle`\r\n#\r\n\r\n";

          $fd = fopen($file_name,"a+");
          fwrite($fd, $txt.$def);
          fclose($fd);

          unset($data);
          if($tabelle > "")
          {
            $qry_i = db("SELECT * FROM $tabelle");
            $anzahl = _rows($qry_i);
            $spaltenzahl = mysql_num_fields($qry_i);
            for($i = 0; $i < $anzahl; $i++)
            {
              $zeile = mysqli_fetch_array($qry_i);
              $data .= "INSERT INTO $tabelle (";
              for($spalte = 0; $spalte < $spaltenzahl; $spalte++)
              {
                $feldname = mysql_field_name($qry_i, $spalte);
                if($spalte == ($spaltenzahl - 1)) $data.= $feldname;
                else $data.= $feldname.",";
              }
              $data .= ") VALUES (";
              for ($k=0;$k < $spaltenzahl;$k++)
              {
                if($k == ($spaltenzahl - 1)) $data .= "'".addslashes($zeile[$k])."'";
                else $data .= "'".addslashes($zeile[$k])."',";
              }
              $data .= ");\r\n";
              $txt = "#\r\n# Daten der Tabelle `$tabelle`\r\n#\r\n\r\n";
            }
            $data .= "\r\n";
          }
          $fd = fopen($file_name,"a+");
          fwrite($fd, $txt.$data);
          fclose($fd);
        }

        header("Location: ?admin=backup");
      }
    } else {
      $show = error(_error_wrong_permissions, 1);
    }
    */
?>