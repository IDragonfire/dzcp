<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Begrüßung nach Tageszeit
 */
function welcome() {
    global $chkMe,$userid;

    $return = "<script language=\"javascript\" type=\"text/javascript\">
               <!--
                 date = new Date();
                 hour = date.getHours();
                 if(hour>=18)      document.write('"._welcome_18."');
                 else if(hour>=13) document.write('"._welcome_13."');
                 else if(hour>=11) document.write('"._welcome_11."');
                 else if(hour>=5)  document.write('"._welcome_5."');
                 else if(hour>=0)  document.write('"._welcome_0."');
               //-->
             </script>";

    if(!$chkMe)
        return $return.' '._welcome_guest;

    return $return.' '.autor($userid, "welcome");
}