<?php
$url = server::get('server_name');
$parts = explode('.', $url);
$final = 'http://'.$parts[1].'.'.$parts[2];

go($final);
?>