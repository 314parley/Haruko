<?php

$load = shell_exec("top -u $(whoami)");
$load2 = sys_getloadavg();

if ($load2[0] > 13.14 )
{
    // load too high, 
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 60');
    require('error/503.html');
}else{
	echo $load2[0];
	var_dump($load);
}
?>