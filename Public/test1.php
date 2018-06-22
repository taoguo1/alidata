<?php
$date = date("YmdHis");
exit();
file_put_contents('/www/web/manage_dizaozhe_cn/public_html/Public/Logs/'.$date."txt",$date);
?>