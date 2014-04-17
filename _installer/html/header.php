<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>deV!L`z Clanportal - Installation</title>
    <link rel="stylesheet" href="html/css.css" type="text/css">  
  </head>
  <body bgcolor="#ced3d6" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <div class="main" align="center">
    <table height="100%" width="950" cellpadding="0" cellspacing="0" align="center" background="img/_08.gif">
      <tr>
        <td valign="top" height="1%">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td><img src="img/_01.gif"  border="0" alt=""></td>
              <td><img src="img/_02.gif"  border="0" alt=""></td>
            </tr>
          </table>
        </td>
      </tr>      
      <tr>
        <td valign="top">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td width="194" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td valign="top"><img src="img/_03.gif" border="0" alt=""></td>
                  </tr>
                  <tr>
                    <td>
                      <table width="100%" cellpadding="1" cellspacing="4" background="img/_05.gif">
                        <tr>
                          <td width="38"></td>
                          <td>
                          <?php   
                            if(isset($action) && $action == "")
                              echo _link_start;
                            else
                              echo _link_start_1; 
                          ?>
                          </td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>
                          <?php   
                            if(isset($action) && $action == "prepare")
                              echo _link_prepare;
                            else
                              echo _link_prepare_1; 
                          ?>
                          </td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>
                          <?php   
                            if(isset($action) && $action == "install")
                              echo _link_install;
                            else
                              echo _link_install_1; 
                          ?>
                          </td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>
                          <?php   
                            if(isset($action) && $action == "database")
                              echo _link_db;
                            else
                              echo _link_db_1; 
                          ?>
                          </td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>
                          <?php   
                            if(isset($action) && $action == "done")
                              echo _link_done;
                            else
                              echo _link_done_1; 
                          ?>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td><img src="img/_07.gif" border="0" alt=""></td>
                  </tr>
                  <tr>
                    <td height="7"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Version:</b> <?php echo _version; ?></td>
                  </tr>
                  <tr>
                    <td height="3"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Released:</b> <?php echo _release; ?></td>
                  </tr>
                  <tr>
                    <td height="255"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy; <?php echo date("Y", time()); ?> <a href="http://www.dzcp.de" target="_blank">www.dzcp.de</a></td>
                  </tr>
                </table>
              </td>
              <td valign="50"></td>
              <td valign="top">
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="main">