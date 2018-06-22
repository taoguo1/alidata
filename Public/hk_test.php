<?php
$str = $_REQUEST;
$date = date('Y-m-d H:i:s');
file_put_contents('/www/web/oem_dizaozhe_cn/public_html/Logs/'.$date.'.txt', json_encode($str));
