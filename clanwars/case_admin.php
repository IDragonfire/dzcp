<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_Clanwars')) {
    if($do == 'edit')
        header("Location: ../admin/?admin=cw&do=edit&id=".$_GET['id']);
}