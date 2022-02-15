<?php

    /*
    =======================================
    ========= Items Page
    =======================================
    */
    ob_start();

    session_start();
    $pagetitle = 'Items';

    if (isset($_SESSION['email'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') {
            $stmt = $con->prepare("SELECT * FROM items ");
            $stmt-> execute();
            $items = $stmt->fetchAll();      //  Assign to variable
           if (! empty($items)) {
            ?>
            <h1 class="text-center" xmlns="http://www.w3.org/1999/html">Manage Items Page</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="table-bordered text-center main-table table">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td> Price</td>
                            <td>Adding Date</td>
                            <td>Category Name </td>
                            <td>Username </td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($items as $item) {
                            echo "<tr>";
                            echo " <td>" . $item['item_ID'] . "   </td>";
                            echo "<td>" .  $item['Name'] . "</td>";
                            echo " <td>" . $item['Description'] . "  </td>";
                            echo " <td>" . $item['Price'] . "</td>";
                            echo " <td>" . $item['Add_Date'] . "</td>";
                            echo "<td> ".  $item['category_name'] ."</td>";
                            echo "<td> ".  $item['Username'] ."</td>";
                            echo " <td><a href='items.php?do=Edit&itemid=" . $item['item_ID']. "' class='btn btn-success'><i class='fa fa-edit'></i>Edit </a> 
<!--hna normalement khasso y3tik msg -->  <a href='items.php?do=Delete&itemid=" . $item['item_ID']. "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                            if ( $item['Approve'] == 0) {
                            echo "<a href='items.php?do=Approve&id=" . $item['item_ID']. "' class='btn btn-info'><i class='fa fa-info'></i>Approve </a>";
                             }
                            echo "</td>";
                            echo "</tr>";
                        }
           }        else {
                               echo '<div class="container">';
                               echo '<div class="alert alert-danger">There is no Items to show</div>';
                               echo '<a href="items.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"> Add new item </i></a>';
                               echo '</div>';
                             }
                        ?>
                    </table>
                </div>
                <a href="items.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"> Add new Item </i></a>
            </div>
        <?php  }


        elseif ($do == 'Add') {
            ?>
            <h1 class="text-center">Add new Item</h1>        <!--- Add page  --->
            <div class="container">
                <form class="form-horizontal" action="?do=insert" method="POST">
                    <!---- name Field ---->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label ">Name :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" />
                        </div></div>
                    <!---- description Field ---->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label ">Description :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" required />
                        </div></div>
                    <!---- price Field ---->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label ">Price :</label>
                        <div class="col-sm-10 col-md-4">
                                <input type="text" name="price" class="form-control" />
                        </div></div>
                    <!---- End PRICE Field ---->
                    <!---- Country of production Field ---->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label ">Country :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="country" class="form-control" />
                        </div></div>
                    <!---- End country of production Field ---->
                    <!---- Status Field ---->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label ">Status :</label>
                        <div class="col-sm-10 col-md-4">
                            <select class="form-control" name="status" class="form-control" />
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4"> Very Old</option>
                        </div></div>
                    <!---- End Status Field ---->

                    <div class="col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                    </div></div>

            <?php
        }

  elseif ($do == 'insert') {
                                                 /* ========================================
                                                    ========= Insert item page  ==========
                                                    ========================================
                                                  */
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $status = $_POST['status'];
                $country = $_POST['country'];
                echo "<h1 class='text-center'>Add Item</h1>";
                echo "<div class='container'>";
                $error = array();                          //handle errors
                if ( empty($name))  {
                    $error [] = 'name cant be <strong>empty</strong>';
                }

                if ( empty($desc) )  {
                    $error [] = 'Description cant be <strong>empty</strong>';
                }

                if ( empty($price) )  {
                    $error [] = 'Price cant be <strong>empty</strong>';
                }
                if ( $status == 0)  {
                    $error [] = 'You must choose a <strong>Status</strong>';
                }
                if ( empty($country) )  {
                    $error [] = 'country cant be <strong>empty</strong>';
                }
                // foreach loop to show errors

                foreach ($error as $er) {
                    echo '<div class="alert alert-danger">' . $er . '</div>' ;
                }
                // check if there's no error proceed the Update Operation
                if (empty($error)) {

                        // Insert item to Database
                        $stmt = $con->prepare("INSERT INTO 
                                                        items (Name , Description , Price , Country_Made, Status ,Add_Date) 
                                                        VALUES 
                                                               (:zname , :zdesc , :zprice , :zcountry, :zstatus, now())");
                        $stmt->execute(array(
                                            'zname' => $name,
                                            'zdesc' => $desc ,
                                            'zprice' => $price,
                                            'zcountry' => $country ,
                                            'zstatus' => $status ));
                        // Echo success Message
                        echo '<div class="container">';
                        $theMsg='<div class="alert alert-success">' . $stmt->rowCount() . 'Record Inserted </div>' ;
                        redirectHome($theMsg,'back');
                    }
            } else {
                $errmsg = '<div class="alert alert-danger">You cant Browse This Page Directly</div>';
                redirectHome($errmsg,'index.php',3);
                echo '</div>';
            }}

  elseif ($do == 'Edit') {
                   $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
                   $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? ") ;
                   $stmt->execute(array($itemid));
                   $item  = $stmt->fetch();
                   $count = $stmt->rowCount();
                   if ($count > 0) {    ?>
                       <h1 class="text-center">Edit new Item</h1>        <!--- Add page  --->
                       <div class="container">
                           <form class="form-horizontal" action="?do=insert" method="POST">
                               <!---- name Field ---->
                               <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label ">Name :</label>
                                   <div class="col-sm-10 col-md-4">
                                       <input type="hidden" name="itemid" value="<?php echo $itemid; ?>" class="form-control" />
                                       <input type="text" name="name" value="<?php echo $item['Name']; ?>" class="form-control" />
                                   </div></div>
                               <!---- description Field ---->
                               <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label ">Description :</label>
                                   <div class="col-sm-10 col-md-4">
                                       <input type="text" name="description" value="<?php echo $item['Description']; ?>" class="form-control" required />
                                   </div></div>
                               <!---- price Field ---->
                               <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label ">Price  :</label>
                                   <div class="col-sm-10 col-md-4">
                                       <input type="text" name="price" value="<?php echo $item['Price']; ?>" class="form-control" />
                                   </div></div>
                               <!---- End PRICE Field ---->
                               <!---- Country of production Field ---->
                               <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label ">Country :</label>
                                   <div class="col-sm-10 col-md-4">
                                       <input type="text" name="country" value="<?php echo $item['Country_Made']; ?>" class="form-control" />
                                   </div></div>
                               <!---- End country of production Field ---->
                               <!---- Status Field ---->
                               <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label ">Status :</label>
                                   <div class="col-sm-10 col-md-4">
                                       <select class="form-control" name="status" class="form-control" />
                                       <option value="0">...</option>
                                       <option value="1">New</option>
                                       <option value="2">Like New</option>
                                       <option value="3">Used</option>
                                   <option value="4"> Very Old</option></select>
                                   </div></div>
                               <!---- End Status Field ---->

                               <div class="col-sm-10">
                                   <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                               </div>
                             </div>
                         </form>
                        <?php }
                       $stmt = $con->prepare("SELECT comments.* , users.Username AS Member FROM comments
                       INNER JOIN users
                       ON
                       users.UserID = comments.user_id WHERE item_id = ?");
                       $stmt->execute(array($itemid));
                       $rows = $stmt->fetchAll();

                       if (! empty($rows)) {
                       ?>
                       <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] comments</h1>
                       <div class="container">
                           <div class="table-responsive">
                               <table class="table-bordered text-center main-table table">
                                   <tr>
                                       <td>Comment</td>
                                       <td>User Name</td>
                                       <td>Added Date</td>
                                       <td>Control</td>
                                   </tr>
                                   <?php
                                   foreach ($rows as $row) {
                                       echo "<tr>";
                                       echo "<td>" . $row['comment'] . "</td>";
                                       echo " <td>" . $row['Member'] . "</td>";
                                       echo " <td>" .$row['comment_date'] . "</td>";
                                       echo " <td><a href='comments.php?do=Edit&comid=" . $row['c_id']. "' class='btn btn-success'><i class='fa fa-edit'></i>Edit </a> 
<!--hna normalement khasso y3tik msg -->      <a href='comments.php?do=Delete&comid=" . $row['c_id']. "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete </a>";
                                       if ($row['status'] == 0) {
                                           echo "<a href='comments.php?do=Approve&comid=" . $row['c_id']. "' class='btn btn-info activate'><i class='fa fa-info'></i>Approve</a></td>";
                                       }
                                       /*de confirm walakin mabghatch tkhdem liya jquery*/  echo "</tr>"; }
                                   ?>
                               </table>
                           </div>
                           <?php } ?>
                       </div>
                       </div>
                   <?php
        } else {
                       echo "<div class='container'>";
                       $themsg = "<div class'alert alert-danger'>Theres no such ID</div>";
                       redirectHome($themsg,'index.php');
                       echo '</div>';
                   }

        }
                  elseif ($do == 'Update') {
                      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                          echo "<h1 class='text-center'> Update item </h1>";
                          // Get Variables from the form
                          $id        = $_POST['itemid'];
                          $name      = $_POST['name'];
                          $desc      = $_POST['description'];
                          $price     = $_POST['Price'];
                          $country   = $_POST['country'];
                          $status    = $_POST['status'];
                          $member    =$_POST['member'];
                          $cat       = $_POST['category'];
                          //handle errors
                          $error = array();                          //handle errors
                          if ( empty($name))  {
                              $error [] = 'name cant be <strong>empty</strong>';
                          }

                          if ( empty($desc) )  {
                              $error [] = 'Description cant be <strong>empty</strong>';
                          }

                          if ( empty($price) )  {
                              $error [] = 'Price cant be <strong>empty</strong>';
                          }
                          if ( $status == 0)  {
                              $error [] = 'You must choose a <strong>Status</strong>';
                          }
                          if ( empty($country) )  {
                              $error [] = 'country cant be <strong>empty</strong>';
                          }
                          // foreach loop to show errors

                          foreach ($error as $er) {
                              echo '<div class="alert alert-danger">' . $er . '</div>' ;
                          }

                          // check if there is no  error
                          if (empty($error)) {
                              // Update infos in Database
                              $stmt = $con->prepare("UPDATE 
                                                                users 
                                                            SET 
                                                                Username = ? , Email = ? , Fullname = ? , Password = ? WHERE UserID = ?");
                              $stmt->execute(array($user,$email,$name,$pass , $id));
                              //  echo success message
                              $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';
                              redirectHome($themsg,'back',3 );
                          }}  else {
                          $msg = '<div class="alert alert-danger">You cant Browse This Page Directly </div>';
                          redirectHome($msg);
                      }       echo '</div>';
                  }
           elseif ($do == 'Delete') {
                           echo "<h1 class='text-center'>Delete Item</h1>";
                           echo "<div class='container'>";
                           $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

                           //select all data depend on this id

                           $check = checkitem('item_ID','items',$itemid);

                           if ($check > 0) {

                               $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :id");
                               $stmt->bindParam("id", $itemid);
                               $stmt->execute();

                               //success message
                               $TheMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted</div>';
                               redirectHome($TheMsg, 'back');
                           } else {
                               $themsg = "<div class='alert alert-danger'>This ID is Not Exist</div>";
                               redirectHome($themsg);
                           }
                           echo "</div>";
                    }
  if ($do == 'Approve') {

               echo "<h1 class='text-center'>Approve item</h1>";
               echo "<div class='container'>";
               $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0 ;

               //select all data depend on this id

               $check = checkitem('item_ID','items',$id);

               if ($check > 0) {

                   $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
                   $stmt->execute(array($id));

                   //success message
                   $TheMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Approved</div>';
                   redirectHome($TheMsg,'back');

               } else {
                   $themsg = '<div class="alert alert-danger">You Cant Browse This Page Directly  </div>';
                   redirectHome($themsg,'index.php');
               }
        }
  /*
    else {
        header('Location: index.php');
    }*/
include $tpl . 'footer.php';
