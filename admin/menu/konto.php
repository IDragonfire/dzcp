<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('clankasse')
///////////////////////////////
if (_adminMenu != 'true')
    exit;

$where = $where . ': ' . _config_clankasse_head;
if (!permission("clankasse")) {
    $show = error(_error_wrong_permissions, 1);
} else {
    $qry = db("SELECT k_inhaber,k_nr,k_blz,k_bank,iban,bic,k_waehrung,k_vwz FROM " . $db['settings'] . "");
    $get = _fetch($qry);
    
    $waehrung      = re($get['k_waehrung']);
    $waehrung_list = _select_field_waehrung;
    $waehrung_list = str_replace("<option value=\"" . $waehrung . "\">", "<option value=\"" . $waehrung . "\" selected=\"selected\">", $waehrung_list);
    
    $konto_show = show($dir . "/form_konto", array(
        "kinhaber" => _clankasse_inhaber,
        "inhaber" => re($get['k_inhaber']),
        "kkontonr" => _clankasse_nr,
        "kontonr" => $get['k_nr'],
        "kblz" => _clankasse_blz,
        "kvwz" => _clankasse_vwz,
        "head_waehrung" => _head_waehrung,
        "waehrung" => $waehrung_list,
        "blz" => $get['k_blz'],
        "kbank" => _clankasse_bank,
        "bank" => re($get['k_bank']),
        "vwz" => re($get['k_vwz']),
        "iban" => re($get['iban']),
        "bic" => re($get['bic'])
    ));
    
    $konto = show($dir . "/form", array(
        "head" => _config_konto_head,
        "what" => "konto",
        "top" => _config_c_clankasse,
        "value" => _button_value_save,
        "show" => $konto_show
    ));
    
    $qryk = db("SELECT * FROM " . $db['c_kats'] . "");
    while ($getk = _fetch($qryk)) {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst";
        $color++;
        $edit   = show("page/button_edit_single", array(
            "id" => $getk['id'],
            "action" => "admin=konto&amp;do=edit",
            "title" => _button_title_edit
        ));
        $delete = show("page/button_delete_single", array(
            "id" => $getk['id'],
            "action" => "admin=konto&amp;do=delete",
            "title" => _button_title_del,
            "del" => convSpace(_confirm_del_entry)
        ));
        
        $show_ .= show($dir . "/clankasse_show", array(
            "name" => re($getk['kat']),
            "class" => $class,
            "edit" => $edit,
            "delete" => $delete
        ));
    }
    
    $show = show($dir . "/clankasse", array(
        "head" => _config_clankasse_head,
        "edit" => _editicon_blank,
        "bez" => _admin_download_kat,
        "top" => _config_c_clankasse,
        "add" => _clankasse_new_head,
        "delete" => _deleteicon_blank,
        "show" => $show_,
        "konto" => $konto
    ));
    if ($_GET['do'] == "update") {
        $qry  = db("UPDATE " . $db['settings'] . "
                   SET `k_inhaber`    = '" . up($_POST['inhaber']) . "',
                       `k_nr`         = '" . up($_POST['kontonr']) . "',
                       `k_waehrung`   = '" . up($_POST['waehrung']) . "',
                       `k_bank`       = '" . up($_POST['bank']) . "',
                       `k_blz`        = '" . up($_POST['blz']) . "',
                       `k_vwz`        = '" . up($_POST['vwz']) . "',
                       `iban`         = '" . up($_POST['iban']) . "',
                       `bic`          = '" . up($_POST['bic']) . "'
                   WHERE id = 1");
        $show = info(_config_set, "?admin=konto");
    } elseif ($_GET['do'] == "new") {
        $show = show($dir . "/form_clankasse", array(
            "newhead" => _clankasse_new_head,
            "do" => "add",
            "kat" => "",
            "what" => _button_value_add,
            "dlkat" => _admin_download_kat
        ));
    } elseif ($_GET['do'] == "add") {
        if (empty($_POST['kat'])) {
            $show = error(_clankasse_empty_kat, 1);
        } else {
            $qry = db("INSERT INTO " . $db['c_kats'] . "
                     SET `kat` = '" . up($_POST['kat']) . "'");
            
            $show = info(_clankasse_kat_added, "?admin=konto");
        }
    } elseif ($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM " . $db['c_kats'] . "
                   WHERE id = '" . intval($_GET['id']) . "'");
        $get = _fetch($qry);
        
        $show = show($dir . "/form_clankasse", array(
            "newhead" => _clankasse_edit_head,
            "do" => "editkat&amp;id=" . $_GET['id'] . "",
            "kat" => re($get['kat']),
            "top" => _config_c_clankasse,
            "what" => _button_value_edit,
            "dlkat" => _admin_download_kat
        ));
    } elseif ($_GET['do'] == "editkat") {
        if (empty($_POST['kat'])) {
            $show = error(_clankasse_empty_kat, 1);
        } else {
            $qry = db("UPDATE " . $db['c_kats'] . "
                     SET `kat` = '" . up($_POST['kat']) . "'
                     WHERE id = '" . intval($_GET['id']) . "'");
            
            $show = info(_clankasse_kat_edited, "?admin=konto");
        }
    } elseif ($_GET['do'] == "delete") {
        $qry = db("DELETE FROM " . $db['c_kats'] . "
                   WHERE id = '" . intval($_GET['id']) . "'");
        
        $show = info(_clankasse_kat_deleted, "?admin=konto");
    }
}
?>