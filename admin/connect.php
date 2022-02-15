<?php

    $user= 'root';       // database source name
    $dbname ='shop';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

try {
    $con = new PDO("mysql:host=localhost; dbname=$dbname", $user, $pass, $option );
    $con -> setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

}
catch (PDOException $e){
    echo 'Failed to connect ' . $e ->getMessage();
}