<?php

    include_once 'init.php';

    $do = '';
    $pagetitle = 'comments';

         $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';     /*==========================
                                                                  ===== Manage Members =====
                                                                  ========================== */
        if ($do == 'Manage' ) {
            $query = '';
            if (isset($_GET['page']) && $_GET['page'] =='Pending'){
            }
            // if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $stmt = $con->prepare("SELECT comments.* , items.Name AS Item_Name , users.Username AS Member FROM comments
                                            INNER JOIN items 
                                            ON  items.Item_ID = comments.item_id
                                            INNER JOIN users 
                                            ON 
                                                users.UserID = comments.user_id
                                                ORDER BY c_id desc ");
            $stmt->execute();
            $rows = $stmt->fetchAll();      //  Assign to variable
                if (! empty($rows)) {
            ?>
            <h1 class="text-center">Manage comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="table-bordered text-center main-table table">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo " <td>" . $row['c_id'] . "   </td>";
                            echo "<td>" . $row['comment'] . "</td>";
                            echo " <td>" . $row['Item_Name'] . "  </td>";
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
            </div>
        <?php  }
                    else {
                        echo '<div class="container">';
                        echo '<div class="alert alert-danger">There is no comments to show</div>';
                        echo '</div>';
                    }}
                               /* ========================================
                                  ================ Edit page  ============
                                  ========================================
                               */
         if ( $do == 'Edit') {
             $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
             $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?") ;
             $stmt->execute(array($comid));
             $row = $stmt->fetch();
             $count = $stmt->rowCount();
             if ($count > 0) {    ?>
                         <h1 class="text-center">Edit Comment</h1>        <!--- Edit page  --->
                         <div class="container">
                         <form class="form-horizontal" action="?do=Update" method="post">
                         <div class="form-group form-group-lg">                     <!---- comment Field ---->
                             <label class="col-sm-2 control-label ">Comment :</label>
                             <div class="col-sm-10 col-md-4">
                                 <input type="hidden" value="<?php echo $comid ?>" name="comid" />
                                 <textarea class="form-control" name="comment">
                                     <?php echo $row['comment']; ?>
                                 </textarea>
                             </div>
                         </div>
                         <div class="form-group">
                             <div class="col-sm-10">
                                 <a href="comments.php"><input  type="button" value="back" class="btn btn-danger btn-lg" /></a>
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
                echo "<h1 class='text-center'> Update Comment</h1>";
                // Get Variables from the form
                $comid = $_POST['comid'];
                $comment = $_POST['comment'];

                    $stmt = $con->prepare("UPDATE comments SET comment = ?  WHERE c_id = ?");
                    $stmt->execute(array($comment, $comid));
                    //  echo success message
                    $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';
                    redirectHome($themsg,'back',3 );
                }}  else {
                $msg = '<div class="alert alert-danger">You cant Browse This Page Directly </div>';
                redirectHome($msg);
             echo '</div>';}
                                    /*========================================
                                     ================  DELETE page ==========
                                     ========================================*/
        if ($do == 'Delete') {
            echo "<h1 class='text-center'>Delete comment</h1>";
            echo "<div class='container'>";
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

            //select all data depend on this id

            $check = checkitem('c_id','comments',$comid);

            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
                $stmt->bindParam("zid", $comid);
                $stmt->execute();

                //success message
                $TheMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted</div>';
                redirectHome($TheMsg, 'back');
            } else {
                $themsg = "<div class='alert alert-danger'>This ID is Not Exist</div>";
                redirectHome($themsg,'back');
            }
            /*} else {
                            $msg = '<div class="alert alert-danger">You Cant Browse This Page Directly';
                            redirectHome($msg);
                }*/
            echo "</div>";
        } if ($do=='Approve'){
    echo "<h1 class='text-center'>Approve comment</h1>";
    echo "<div class='container'>";
    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

    //select all data depend on this id

    $check = checkitem('c_id','comments',$comid);

    if ($check > 0) {

        $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
        $stmt->execute(array($comid));

        //success message
        $TheMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>';
        redirectHome($TheMsg,'back');
    } else {
        $themsg = '<div class="alert alert-danger">You Cant Browse This Page Directly  </div>';
        redirectHome($themsg,'index.php');
    }
}
