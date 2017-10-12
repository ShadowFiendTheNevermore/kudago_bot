<?php

$root_path = realpath(__DIR__ . './../');

require $root_path . './vendor/autoload.php';

$dotenv = new Dotenv\Dotenv($root_path);
$dotenv->load();
