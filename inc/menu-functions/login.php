<?php
if ($secureLogin == 1)
    $secure = show("menu/secure", array(
        "help" => _login_secure_help
    ));

$login = show("menu/login", array(
    "register" => _register,
    "what" => _login_login,
    "dis" => $dis,
    "secure" => $secure,
    "signup" => _login_signup,
    "permanent" => _login_permanent,
    "lostpwd" => _login_lostpwd
));
?> 
             "signup" => _login_signup,
                                        "permanent" => _login_permanent,
                                        "lostpwd" => _login_lostpwd));
?>
