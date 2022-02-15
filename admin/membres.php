<?php
    include_once 'init.php';

    $do = '';
    $pagetitle = 'members';

         $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';     /*==========================
                                                                  ===== Manage Members =====
                                                                  ========================== */
        if ($do == 'Manage' ) {
            $query = '';
            if (isset($_GET['page']) && $_GET['page'] =='Pending'){
                $query = 'AND RegStatus = 0 ';
        }
            // if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                         $stmt = $con->prepare("SELECT * FROM users WHERE GroupID !=1 $query"); // SELECT all users expect admins
                         $stmt->execute();
                         $rows = $stmt->fetchAll();      //  Assign to variable
            if(! empty($rows)) {
?>
                      <h1 class="text-center">Manage Members Page</h1>
                        <div class="container">
                           <div class="table-responsive">
                              <table class="table-bordered text-center main-table table">
                                  <tr>
                                      <td>#ID</td>
                                      <td>Username</td>
                                      <td>Email</td>
                                      <td>Full Name</td>
                                      <td>Registerd Date</td>
                                      <td>Control</td>
                                  </tr>
<?php
                                foreach ($rows as $row) {
                                  echo "<tr>";
                                  echo " <td>" . $row['UserID'] . "   </td>";
                                  echo "<td>" . $row['Username'] . "</td>";
                                  echo " <td>" . $row['Email'] . "  </td>";
                                  echo " <td>" . $row['FullName'] . "</td>";
                                  echo " <td>" .$row['Date'] . "</td>";
                                  echo " <td><a href='membres.php?do=Edit&userid=" . $row['UserID']. "' class='btn btn-success'><i class='fa fa-edit'></i>Edit </a> 
<!--hna normalement khasso y3tik msg -->      <a href='membres.php?do=Delete&userid=" . $row['UserID']. "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                                  if ($row['RegStatus'] == 0) {
                                      echo "<a href='membres.php?do=Activate&userid=" . $row['UserID']. "' class='btn btn-info activate'><i class='fa fa-info'></i>Approve</a></td>";
                                  }
/*de confirm walakin mabghatch tkhdem liya jquery*/  echo "</tr>"; }
?>
                           </table>
                        </div>
                     <a href="membres.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"> Add new member </i></a>
                  </div>
<?php  } else {
                    echo '<div class="container">';
                        echo '<div class="alert alert-danger">There is no Record to show</div>';
                        echo '<a href="membres.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"> Add new member </i></a>';
                    echo '</div>';

        }}
                      /* ========================================
                         ================ Add page  =============
                         ========================================
                      */
           if ( $do == 'Add') {
               if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
                         <h1 class="text-center">Add new member</h1>        <!--- Add page  --->
                             <div class="container">
                               <form class="form-horizontal" action="?do=insert" method="post">
                                    <div class="form-group form-group-lg">                     <!---- Username Field ---->
                                     <label class="col-sm-2 control-label ">Username :</label>
                                       <div class="col-sm-10 col-md-4">
                                         <input type="text" name="Username" class="form-control" autocomplete="off" required="required" />
                                             </div></div>
                                                 <div class="form-group form-group-lg">                        <!---- Password Field ---->
                                             <label class="col-sm-2 control-label ">Password :</label>
                                             <div class="col-sm-10 col-md-4">
                                             <input type="password" name="password" class="password form-control" autocomplete="off" required/>
                                            <!---- deq l3iba dyal iban password raha makhdamach liya dert liha function f backenf.js mabghatch tkhdem
                                           <i class="show_pass fa fa-eye fa-8px"></i>--->
                                          </div></div>
                                      <div class="form-group form-group-lg">                      <!---- Email Field ---->
                                     <label class="col-sm-2 control-label ">Email :</label>
                                    <div class="col-sm-10 col-md-4">
                                   <input type="email" name="email" class="form-control" required="required" autocomplete="off"/>
                                  </div></div>
                                <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label ">Full Name :</label>        <!---- Full name Field ---->
                                <div class="col-sm-10 col-md-4">
                                 <input type="text" name="full" class="form-control" autocomplete="off" required="required"/>
                                  </div></div>
                                    <div class="form-group">
                                     <div class="col-sm-10">
                                       <input type="submit" value="Add" class="btn btn-primary btn-lg" />
                                         </div></div>
<?php } else {
               $msg = 'You Will Be Redirected To Homepage ';
               redirectHome($msg,'index.php',5);
           }}
         elseif ($do == 'insert') {  /* ========================================
                                        ========= Insert member page  ==========
                                        ========================================
                                      */
              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $user = $_POST['Username'];
                  $name = $_POST['full'];
                  $email = $_POST['email'];
                  $pass = $_POST['password'];
                  $hashpass = sha1($pass);
                        echo "<h1 class='text-center'>Add member</h1>";
                        echo "<div class='container'>";
                 $error = array();                          //handle errors
              if ( empty($user) && strlen($user) < 4  )  {
                  $error [] = 'username cant be less than <strong>4 character</strong>';
              }

              if ( empty($email) )  {
                  $error [] = 'E-mail cant be <strong>empty</strong>';
              }

               if ( empty($name) )  {
                  $error [] = 'name cant be <strong>empty</strong>';
              }
              if ( empty($pass) )  {
                      $error [] = 'password cant be <strong>empty</strong>';
                  }

              // foreach loop to show errors

              foreach ($error as $er) {
                  echo '<div class="alert alert-danger">' . $er . '</div>' ;
              }
                // check if there's no error proceed the Update Operation
                  if (empty($error)) {

                     $check = checkitem("Username" , "users" , $user);
                     if ($check == 1) {
                         $themsg = '<div class="alert alert-danger">Sorry this username exist</div>';
                         redirectHome($themsg,'back');
                     }
                     else {
                     // Insert user to Database
                      $stmt = $con->prepare("INSERT INTO users (Username , Password , Email , Fullname, RegStatus, Date) VALUES (:zuser , :zpass , :zmail , :zname, 1, now())");
                      $stmt->execute(array('zuser' => $user, 'zpass' => $hashpass , 'zmail' => $email,'zname' => $name));
                        // Echo success Message
                         echo '<div class="container">';
                     $themsg='<div class="alert alert-success">' . $stmt->rowCount() . 'Record Inserted </div>' ;
                     redirectHome($themsg);
                  }}
         } else {
                  $errmsg = '<div class="alert alert-danger">You cant Browse This Page Directly</div>';
                  redirectHome($errmsg,'index.php',3);
                 echo '</div>';
         }}
                               /* ========================================
                                  ================ Edit page  ============
                                  ========================================
                               */
         if ( $do == 'Edit') {
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
                    $stmt = $con->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1") ;
                    $stmt->execute(array($userid));
                    $row = $stmt->fetch();
                    $count = $stmt->rowCount();
              if ($count > 0) {    ?>
                         <h1 class="text-center">Edit Information</h1>        <!--- Edit page  --->
                          <div class="container">
                            <form class="form-horizontal" action="?do=Update" method="post">
                              <div class="form-group form-group-lg">                     <!---- Username Field ---->
                               <label class="col-sm-2 control-label ">Username :</label>
                                <div class="col-sm-10 col-md-4">
                                <input type="text" value="<?php echo $row['Username'] ?>" name="Username" class="form-control" autocomplete="off" required="required" />
                                </div>
                              </div>
                             <input type="hidden" value="<?php echo $row['UserID'] ?>" name="id" />
                            <div class="form-group form-group-lg">                        <!---- Password Field ---->
                            <label class="col-sm-2 control-label ">Password :</label>
                             <div class="col-sm-10 col-md-4">
                              <input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>" />
                               <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="leave it blank if you won't change it" />
                                 </div></div>
                                <div class="form-group form-group-lg">                      <!---- Email Field ---->
                               <label class="col-sm-2 control-label ">Email :</label>
                               <div class="col-sm-10 col-md-4">
                              <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
                             </div>
                            </div>
                             <div class="form-group form-group-lg">
                              <label class="col-sm-2 control-label ">Full Name :</label>        <!---- Full name Field ---->
                               <div class="col-sm-10 col-md-4">
                               <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required"/>
                              </div>
                              </div>
                             <div class="form-group">
                             <div class="col-sm-10">
                             <input  type="submit" value="Save" class="btn btn-primary btn-lg" />
                            </div>
                            </div>
                <?php } else {
                  echo "<div class='container'>";
                  $themsg = "<div class'alert alert-danger'>Theres no such ID</div>";
                  redirectHome($themsg,'index.php');
                  echo '</div>';
              }
         }

        if ($do == 'Update') {          // Update page
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h1 class='text-center'> Update Member </h1>";
                       // Get Variables from the form
                       $id = $_POST['id'];
                       $user = $_POST['Username'];
                       $email = $_POST['email'];
                       $name = $_POST['full'];

                       // Password Trick

                       $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                       //handle errors
                       $error = array() ;

                       if ( empty($user) && strlen($user) < 4  )  {
                           $error [] = '<div class ="alert alert-danger">username cant be less than <strong>4 character</strong></div>';
                       }
                           $checkemail = checkitem('email' , 'users' , $email) ;
                       if ($checkemail == 1 ) {
                           $error [] = '<div class ="alert alert-danger">This E-mail has been <strong>Token</strong></div>';
                       }
                       if ( empty($email)  )  {
                           $error [] = '<div class ="alert alert-danger">E-mail cant be <strong>empty</strong></div>';
                       }

                       if ( empty($name) )  {
                           $error [] = '<div class ="alert alert-danger">name cant be <strong>empty</strong></div>';
                       }

                           // foreach loop to show errors

                           foreach ($error as $er) {
                           echo $er ;
                           }
                           // check if there is no error
                       if (empty($error)) {
                           // Update infos in Database
                           $stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , Fullname = ? , Password = ? WHERE UserID = ?");
                           $stmt->execute(array($user,$email,$name,$pass , $id));
                           //  echo success message
                           $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';
                           redirectHome($themsg,'back',3 );
                           } else {
                                    redirectHome('','back');
                       }
                }  else {
                           $msg = '<div class="alert alert-danger">You cant Browse This Page Directly </div>';
                           redirectHome($msg);
                   }       echo '</div>';
        }
              /*========================================
               ================  DELETE page ==========
               ========================================*/
        elseif ($do == 'Delete') {
                                 echo "<h1 class='text-center'>Delete member</h1>";
                                 echo "<div class='container'>";
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

                //select all data depend on this id

                $check = checkitem('userid','users',$userid);

                if ($check > 0) {

                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :userid");
                    $stmt->bindParam("userid", $userid);
                    $stmt->execute();

                    //success message
                    $TheMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted</div>';
                    redirectHome($TheMsg, 'back');
                    } else {
                    $themsg = "<div class='alert alert-danger'>This ID is Not Exist</div>";
                    redirectHome($themsg);
                }
        /*} else {
                        $msg = '<div class="alert alert-danger">You Cant Browse This Page Directly';
                        redirectHome($msg);
            }*/
                echo "</div>";
          } if ($do=='Activate'){
                    echo "<h1 class='text-center'>Activate member</h1>";
                    echo "<div class='container'>";
                    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

                    //select all data depend on this id

                    $check = checkitem('UserID','users',$userid);

                    if ($check > 0) {

                        $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE userID = ?");
                        $stmt->execute(array($userid));

                        //success message
                        $TheMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted</div>';
                        redirectHome($TheMsg,'back');
                    } else {
                    $themsg = '<div class="alert alert-danger">You Cant Browse This Page Directly  </div>';
                    redirectHome($themsg,'index.php');
                    }
               }
