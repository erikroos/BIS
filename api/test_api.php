<?php
$username = $_GET['username'];
$password = $_GET['password'];

// 1. get token
$url = "http://www.hunze.nl/test_bis/api/index.php?getToken=1&username=" . $username . "&password=" . md5($password);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$token = curl_exec($ch);
curl_close($ch);
echo "My token is: " . $token . "<br />";

// 2. get boats
$url = "http://www.hunze.nl/test_bis/api/index.php?token=" . $token . "&entity=boten";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$boats = curl_exec($ch);
curl_close($ch);
$boatsArray = json_decode($boats);
echo "I got " . sizeof($boatsArray) . " boats from BIS<br />";

// 3. make valid reservation

// 4. change reservation

// 5. delete reservation

// 6. make a deliberately wrong reservation