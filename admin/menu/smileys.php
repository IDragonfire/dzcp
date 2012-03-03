<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       settingsmenu
// Rechte:    $chkMe == 4
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._smileys_head;
    if($chkMe != 4)
    {
      $show = error(_error_wrong_permissions,1);
    } else {
      if($_GET['do'] == "add")
      {
        $show = show($dir."/form_smileys", array("head" => _smileys_head_add,
                                                 "what" => _button_value_add,
                                                 "do" => "addsmiley",
                                                 "smiley" => _smileys_smiley,
                                                 "bbcode" => _smileys_bbcode));
      } elseif($_GET['do'] == "addsmiley") {
        $tmpname = $_FILES['smiley']['tmp_name'];
        $name = $_FILES['smiley']['name'];
        $type = $_FILES['smiley']['type'];
        $size = $_FILES['smiley']['size'];
        $imageinfo = getimagesize($tmpname);
        $spfad = "../inc/images/smileys/";
        
        if(!$tmpname || empty($_POST['bbcode']) || $type == "image/pjpeg" || $type == "image/jpeg" || !$imageinfo[0] || preg_match("#[[:punct:]]|[[:space:]]#",$_POST['bbcode']) || file_exists($spfad.$_POST['bbcode'].".gif"))
        {
          if(!$tmpname)                                                    $show = error(_smileys_error_file, 1);
          elseif(empty($_POST['bbcode']))                                  $show = error(_smileys_error_bbcode,1);
          elseif($type == "image/pjpeg" || $type == "image/jpeg")          $show = error(_smileys_error_type,1);
          elseif(preg_match("#[[:punct:]]|[[:space:]]#",$_POST['bbcode'])) $show = error(_smileys_specialchar,1);
          elseif(file_exists($spfad.$_POST['bbcode'].".gif"))               $show = error(_admin_smiley_exists);
        } else {
          @copy($tmpname, basePath."/inc/images/smileys/".$_POST['bbcode'].".gif");
          @unlink($_FILES['smiley']['tmp_name']);

          $show = info(_smileys_added, "?admin=smileys");
        }
      } elseif($_GET['do'] == "delete")
      {
        @unlink(basePath."/inc/images/smileys/".$_GET['id']."");
        $show = info(_smileys_deleted, "?admin=smileys");
      } elseif($_GET['do'] == "edit")
      {
        $akt = preg_replace("#.gif#Uis","",$_GET['id']);
        $show = show($dir."/form_smileys_edit", array("head" => _smileys_head_edit,
                                                      "bbcode" => _smileys_bbcode,
                                                      "id" => $_GET['id'],
                                                      "value" => _button_value_edit,
                                                      "akt" => $akt));
      } elseif($_GET['do'] == "editsmiley"){
        if(empty($_POST['bbcode']))
        {
          $show = error(_smileys_error_bbcode);
        } else {
          $spfad = "../inc/images/smileys/";
          if(!file_exists($pfad.$_POST['bbcode'].".gif"))
          {
            @rename($spfad.$_GET['id'], $spfad.$_POST['bbcode'].".gif");
            $show = info(_smileys_edited, "?admin=smileys");
          } else {
            $show = error(_admin_smiley_exists);
          }
        }
      } else {
        $files = get_files('../inc/images/smileys');
        for($i=0; $i<count($files); $i++)
        {
          if($files[$i] != '^^.gif')
          {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $smileys = "../inc/images/smileys/".$files[$i];
            $bbc = ":".preg_replace("=.gif=Uis","",$files[$i]).":";

            $edit = show("page/button_edit_single", array("id" => $files[$i],
                                                          "action" => "admin=smileys&amp;do=edit",
                                                          "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $files[$i],
                                                              "action" => "admin=smileys&amp;do=delete",
                                                              "title" => _button_title_del,
                                                              "del" => convSpace(_confirm_del_smiley)));

            $show_ .= show($dir."/smileys_show", array("bbcode" => $bbc,
                                                       "smiley" => $smileys,
                                                       "class" => $class,
                                                       "del" => $delete,
                                                       "edit" => $edit,
                                                       "id" => $files[$i]));
          }
        }

        $show = show($dir."/smileys", array("head" => _smileys_head,
                                            "bbcode" => _smileys_bbcode,
                                            "show" => $show_,
                                            "info" => _smileys_info,
                                            "customs" => _smileys_customs,
                                            "normals" => _smileys_normals,
                                            "add" => _smileys_head_add,
                                            "smiley" => _smileys_smiley));
      }
    }
?>