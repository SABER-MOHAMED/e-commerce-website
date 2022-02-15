<?php
    /*
     ====================================================
     ========== Category Page
     ====================================================
     */
     ob_start();

     session_start();
     $pageTitle ='Categories';

     if (isset($_SESSION['email'])) {
         include_once 'init.php';
         $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
         if($do == 'Manage') {
             $sort ='ASC';
             $sort_array = array('ASC', 'DESC');
             if (isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)) {
                 $sort = $_GET['sort'];
             }
             $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY ?");
             $stmt2 ->execute(array($sort));
             $cats = $stmt2->fetchAll();?>

             <h1 class="text-center">Manage Categories</h1>
                <div class="container categories">
                    <div class="panel panel-default">
                        <div class="panel-heading">Manage Categories</div>
                        <div class="ordering pull-right">Ordering:
                            <a href="?sort=ASC" class="<?php if ($sort =='ASC') { echo 'active';} ?>">Asc</a>
                            <a href="?sort=DESC" class="<?php if ($sort =='DESC') { echo 'active';} ?>">Desc</a>
                            View:
                            <span>Classic</span>
                            <span>Full</span>
                        </div>
                        <div class="panel-body">
                            <?php foreach ($cats as $cat) {
                                  echo '<div class="cat">';
                                  echo "<div class='hidden-buttons'>";
                                    echo "<a href='categories.php?do=Edit&catid=" .$cat['ID']. "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                                    echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] ."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                                echo "</div>";
                                  echo  "<h3>" . $cat['Name'] .'</h3>';
                                  echo "<div class='full-view'>";
                                  if ($cat['Description'] == ''){echo '<p>This Category has no description</p>' ;} else echo  '<p>' .$cat['Description'] .'</p>' ;
                                  if ($cat['Allow_Comment'] == 1){echo '<span class="commenting">Comments Disable </span>' ;}
                                  if ($cat['Visibility'] == 1){echo '<span class="visibility"> Hidden </span>' ;}
                                  if ($cat['Allow_Ads'] == 1){echo '<span class="ads">Ads Disable</span>'  ;}
                                  echo "</div></div>";
                                  echo "<hr>";
                            }
                            ?>
                        </div>
                    </div>
                    <a class="btn btn-primary add-category" href="categories.php?do=Add"><i class="fa fa-plus"</a>Add New Category
                </div>


         <?php }
         if($do == 'Add') {
             ?>
             <h1 class="text-center">Add new Category</h1>        <!--- Add page  --->
             <div class="container">
                 <form class="form-horizontal" action="?do=insert" method="post">
                     <div class="form-group form-group-lg">                     <!---- name Field ---->
                         <label class="col-sm-2 control-label ">Name :</label>
                         <div class="col-sm-10 col-md-4">
                             <input type="text" name="name" class="form-control" autocomplete="off"  />
                         </div></div>
                     <div class="form-group form-group-lg">                        <!---- Description Field ---->
                         <label class="col-sm-2 control-label ">Description :</label>
                         <div class="col-sm-10 col-md-4">
                             <input type="text" name="description" class=" form-control" autocomplete="off" />
                         </div></div>
                     <div class="form-group form-group-lg">                      <!---- Ordering Field ---->
                         <label class="col-sm-2 control-label ">Ordering :</label>
                         <div class="col-sm-10 col-md-4">
                             <input type="text" name="ordering" class="form-control" autocomplete="off"/>
                         </div></div>
                     <div class="form-group form-group-lg">
                         <label class="col-sm-2 control-label ">Visible :</label>        <!---- Visibility Field ---->
                         <div class="col-sm-10 col-md-4">
                             <div>
                                 <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                 <label for="vis-yes">Yes</label>
                             </div>
                             <div>
                                 <input id="vis-no" type="radio" name="visibility" value="1" />
                                 <label for="vis-no">No</label>
                             </div>
                         </div></div>
                     <div class="form-group form-group-lg">
                         <label class="col-sm-2 control-label ">Allow Comments :</label>        <!---- Commenting Field ---->
                         <div class="col-sm-10 col-md-4">
                             <div>
                                 <input id="com-yes" type="radio" name="comments" value="0" checked />
                                 <label for="com-yes">Yes</label>
                             </div>
                             <div>
                                 <input id="com-no" type="radio" name="comments" value="1" />
                                 <label for="com-no">No</label>
                             </div>
                         </div></div>
                     <div class="form-group form-group-lg">
                         <label class="col-sm-2 control-label ">Allow Ads :</label>        <!---- Allow Ads Field ---->
                         <div class="col-sm-10 col-md-4">
                             <div>
                                 <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                 <label for="ads-yes">Yes</label>
                             </div>
                             <div>
                                 <input id="ads-no" type="radio" name="ads" value="1" />
                                 <label for="ads-no">No</label>
                             </div>
                         </div></div>
                     <div class="form-group">
                         <div class="col-sm-10">
                             <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                         </div></div>
             <?php
         }
         if($do == 'insert') {
                                                 /* ========================================
                                                    ========= Insert member page  ==========
                                                    ========================================
                                                  */
             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                 $name = $_POST['name'];
                 $desc = $_POST['description'];
                 $ordering = $_POST['ordering'];
                 $visibility = $_POST['visibility'];
                 $comments = $_POST['comments'];
                 $ads = $_POST['ads'];
                            //name cant be empty solution server side
                 if ( empty($name) )  {
                     $error  = '<div class="container"><div class="alert alert-danger"> name cant be <strong>empty</strong></div>';
                 }
                 if (empty($error)) {
                 echo "<h1 class='text-center'>Insert member</h1>";
                 echo "<div class='container'>";

                     $check = checkitem("Name" , "categories" , $name);
                     if ($check == 1) {
                         $themsg = '<div class="alert alert-danger">Sorry this Category is already exist</div>';
                         redirectHome($themsg,'back');
                     }
                     else {
                             // Insert category to Database
                         $stmt = $con->prepare("INSERT INTO categories (Name , Description , Ordering , Visibility, Allow_Comment, Allow_ADS) VALUES (:zname , :zdesc , :zorder , :zvis, :zcomments, :zads)");
                         $stmt->execute(array('zname' => $name, 'zdesc' => $desc , 'zorder' => $ordering,'zvis' => $visibility,'zcomments'=> $comments , 'zads' => $ads));
                         // Echo success Message
                         echo '<div class="container">';
                         $themsg='<div class="alert alert-success">' . $stmt->rowCount() . 'Record Inserted </div>' ;
                         redirectHome($themsg,'back');
                     }} else {
                        redirectHome($error,'back');
                 }
             } else {
                 $errmsg = '<div class="alert alert-danger">You cant Browse This Page Directly</div>';
                 redirectHome($errmsg,'index.php',3);
                 echo '</div>';
             }}

         if($do == 'Edit') {
             $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;
             $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?") ;
             $stmt->execute(array($catid));
             $cat = $stmt->fetch();
             $count = $stmt->rowCount();
             if ($count > 0) { ?>
                 <h1 class="text-center">Edit Category</h1>        <!--- Edit page  --->
                 <div class="container">
                     <form class="form-horizontal" action="?do=Update" method="POST">
                         <div class="form-group form-group-lg">                     <!---- name Field ---->
                             <label class="col-sm-2 control-label">Name :</label>
                             <div class="col-sm-10 col-md-4">
                                 <input type="text" name="name" class="form-control" value="<?php echo $cat['Name']; ?>"/>
                                 <input type="hidden" name="catid" class="form-control" value="<?php echo $catid; ?>"/>
                             </div></div>
                         <div class="form-group form-group-lg">                        <!---- Description Field ---->
                             <label class="col-sm-2 control-label ">Description :</label>
                             <div class="col-sm-10 col-md-4">
                                 <input type="text" name="description" class=" form-control" value="<?php echo $cat['Description']; ?>" />
                             </div></div>
                         <div class="form-group form-group-lg">                      <!---- Ordering Field ---->
                             <label class="col-sm-2 control-label ">Ordering :</label>
                             <div class="col-sm-10 col-md-4">
                                 <input type="text" name="ordering" class="form-control" value="<?php echo $cat['Ordering']; ?>" />
                             </div></div>
                         <div class="form-group form-group-lg">
                             <label class="col-sm-2 control-label ">Visible :</label>        <!---- Visibility Field ---->
                             <div class="col-sm-10 col-md-4">
                                 <div>
                                     <input id="vis-yes" type="radio" name="visibility" value="0" required />
                                     <label for="vis-yes">Yes</label>
                                 </div>
                                 <div>
                                     <input id="vis-no" type="radio" name="visibility" value="1" required />
                                     <label for="vis-no">No</label>
                                 </div>
                             </div></div>
                         <div class="form-group form-group-lg">
                             <label class="col-sm-2 control-label ">Allow Comments :</label>        <!---- Commenting Field ---->
                             <div class="col-sm-10 col-md-4">
                                 <div>
                                     <input id="com-yes" type="radio" name="comments" value="0"  required/>
                                     <label for="com-yes">Yes</label>
                                 </div>
                                 <div>
                                     <input id="com-no" type="radio" name="comments" value="1"required />
                                     <label for="com-no">No</label>
                                 </div>
                             </div></div>
                         <div class="form-group form-group-lg">
                             <label class="col-sm-2 control-label ">Allow Ads :</label>        <!---- Allow Ads Field ---->
                             <div class="col-sm-10 col-md-4">
                                 <div>
                                     <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                     <label for="ads-yes">Yes</label>
                                 </div>
                                 <div>
                                     <input id="ads-no" type="radio" name="ads" value="1" />
                                     <label for="ads-no">No</label>
                                 </div>
                             </div></div>
                         <div class="form-group">
                             <div class="col-sm-10">
                                 <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                             </div></div>
                     <?php }
             else {
                 echo "<div class='container'>";
                 $theMsg = "<div class'alert alert-danger'>Theres no such ID</div>";
                 redirectHome($theMsg,'back');
                 echo '</div>';
             }
         }

         if($do == 'Update') {
             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                 echo "<h1 class='text-center'> Edit Member </h1> <div class='container'>";
                 // Get Variables from the form
                 $id = $_POST['catid'];
                 $name = $_POST['name'];
                 $desc = $_POST['description'];
                 $order = $_POST['ordering'];
                 $vis = $_POST['visibility'];
                 $comment = $_POST['comments'];
                 $ads = $_POST['ads'];

                     // Update infos in Database
                     $stmt = $con->prepare("UPDATE categories SET Name = ? , Description = ? , Ordering = ? , Visibility = ? , Allow_comment = ? ,Allow_Ads = ? WHERE ID = ?");
                     $stmt->execute(array($name, $desc , $order , $vis , $comment , $ads, $id));
                     //  echo success message
                     $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';
                     redirectHome($themsg,'back',3 );
                 }}  else {
                 $msg = '<div class="alert alert-danger">You cant Browse This Page Directly </div>';
                 redirectHome($msg);
             }       echo '</div>';


         if($do == 'Delete') {
             echo "<h1 class='text-center'>Delete Category</h1>";
             echo "<div class='container'>";
             $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;

             //select all data depend on this id

             $check = checkitem('ID','categories',$catid);

             if ($check > 0) {

                 $stmt = $con->prepare("DELETE FROM categories WHERE UserID = :catid");
                 $stmt->bindParam(":catid", $catid);
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
         }

         include $tpl . 'footer.php';
         }else {
         header('Location: index.php');
         exit();
     }

     ob_end_flush();
?>
