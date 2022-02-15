<?php
    session_start();
    $nonavbar= '' ; // this page doesn't need a navbar .
    $pagetitle = 'Login';
    include_once 'init.php';
    include_once  $tpl . 'header.php';
    // include  $languages .'english.php' ;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashedPass = sha1($password);

        //check if the user exist in the database

        $stmt = $con->prepare("SELECT
                                           UserID , Email , Password 
                                     FROM 
                                          users
                                     WHERE 
                                           Email = ? 
                                     AND 
                                           Password =? 
                                    LIMIT 1") ;
        $stmt->execute(array($email ,$hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // If count > 0 this means database contain Record About This User

        if($count > 0 ){
            $_SESSION['email'] = $email;               //Register Session email
            $_SESSION['ID'] = $row['UserID'];
            header('Location: dashboard.php');  // redirect to Dashboard page
            exit();
        }
    }

?>

        <!---Start login form --->

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center -lg"> Admin Login</h4>
    <input class="form-control input-lg" type="email" name="email" autocomplete="off" placeholder="E-mail">
    <input class="form-control input-lg" type="password" name="password" autocomplete="off" placeholder="Password">
    <input class="btn btn-primary btn-block" type="submit" value="Login">
</form>
        <!---end login form -->



<?php include $tpl . 'footer.php';  ?>
