<?php
// Wo bin ich
function where()
{
    global $where;
    $where = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return data("$id[1]","nick");'),$where);
    return empty($where) ? '' : $where;
}