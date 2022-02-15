<?php
/*
 * * Title Function v1.0
 * * Function that echo title in every page
 */
   function gettitle() {
       global $pagetitle ;
       if (isset($pagetitle)) {
           echo $pagetitle;
       } else {
           echo 'Default';
       }}

       /*
        * * Redirect Function v2.0
        * * Home  Redirect Function [ This Function Accept Parameters ]
        * * $theMsg = Echo the  message [ Example : Error , Success .. ]
        * * Url = the Link you want to redirect To
        * * $seconds = Seconds Before Redirecting
        */

       function redirectHome($theMsg , $url = null , $seconds = 3) {

       if ($url === null ) {
           $url = 'index.php';
       }    else {
                   $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : $url = 'index.php';

           echo $theMsg;
           echo "<div class='alert alert-info'>You will be redirected to Homepage After $seconds Seconds.</div>";
           header("refresh:$seconds;url=$url");
           exit();
       }}

       /*
        * * Check Items Function v1.0
        * * Function to check item in database [ Function Accept Parameters ]
        * * $SELECT = item to select [ Example : user , item]
        * * $from = The table To select From [ Example : Table ]
        * * $WHERE = The condition of Select statement
        * * $VALUE = Value of Select
        */

        function checkitem($select , $from , $value ) {
            global  $con;
            $stmt2 =  $con->prepare("SELECT $select FROM $from WHERE $select = ? ");
            $stmt2 -> execute(array($value));
            $count =  $stmt2->rowCount();
            return $count;
        }

        /* Count number of items Functions v1.0
         ** Function To Count number of items Rows
         ** $item = the item to count
         ** $table = the table to choose From
         */

        function countItems($item , $table) {
            global $con;
            $stmt2 = $con->prepare("SELECT count($item) FROM $table");

            $stmt2->execute();

            return $stmt2->fetchColumn();
        }

        /*
         ** Get Latest Records Function v1.0
         ** Function To Get Latest Items From Database [ Users , Items , Comments ]
         ** $select = item to select
         ** $table = the table to chose from
         ** $limit = limit of items to choose
         */

            function getLatest($select , $table , $order, $limit = 5) {
                global $con;
                $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
                $getStmt->execute();
                $rows = $getStmt->fetchAll();
                return $rows;
            }




