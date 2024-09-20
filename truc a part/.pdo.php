<?php

try{
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce_test', '', '');
}catch(Exception $e){
    echo $e->getMessage();
}