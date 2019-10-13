<?php

$sendMsg = 'hello world';
$port = '10001';
$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
socket_sendto($sock, $sendMsg, strlen($sendMsg), 0, '255.255.255.255', $port);

?>