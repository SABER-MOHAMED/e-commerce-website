<?php
        session_start();      // start session()
        session_unset();      // unset data of session()
        session_destroy();    // destroy session()

        header('location: index.php'); // redirect to login page
    exit();
