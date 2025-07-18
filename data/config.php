<?php

$user= "root";
$password= "modiboKane994";
$host= "localhost";
$dbname = "biblioteque";

$connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

$connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

