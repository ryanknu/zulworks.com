<?php

$url = $_GET['url'];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$r = curl_exec($curl);

$json = json_encode(array('response' => $r));

echo sprintf('%s(%s);', $_GET['callback'], $json);
