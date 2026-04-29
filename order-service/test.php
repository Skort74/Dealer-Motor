<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/motors");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
echo $output;
