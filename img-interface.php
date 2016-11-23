<?php
$abc1=($_GET['value1']);
header("Content-Type: image/png");
header("Content-Length: " . filesize("$abc1"));
$fp = fopen($abc1, 'rb');
fpassthru($fp);
unlink($abc1);
exit();
?>
