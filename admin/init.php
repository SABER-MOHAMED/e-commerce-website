<?php

    include_once 'connect.php';

    // Routes

    $tpl =  'inc/templates/' ;       //templates directory
    $js  =   'layout/js/';           // js directory
    $css =  'layout/css/';           // css directory
    $languages = 'inc/languages/';   // languages directory
    $fun = 'inc/functions/';                       // functions directory

   // include $languages . 'english.php';
// include the important files

    include_once $fun . 'function.php';
    include_once $tpl .  'header.php';
   /* include_once $languages . 'english.php';  */

    //include Navbar on all pages expect the one with $nonavbar Variable

        if(!isset($nonavbar)) {
            include_once $tpl . 'navbar.php';
        }
