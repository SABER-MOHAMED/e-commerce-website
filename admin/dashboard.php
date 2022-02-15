<?php
  session_start();

    if(isset( $_SESSION['email'] ))
    {
        $pagetitle = 'Dashboard';
        include_once 'init.php';

        /* Start Dashboard Page */

         $numItems = 6;
        $latestItems = getLatest("*" ,"items" , "item_ID" , $numItems); // Latest items array
        $numcomments = 4 ;
        ?>
            <div class="container home-stats text-center">
                <h1>Dashboard</h1>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat st-members">
                            <i class="fa fa-users"></i>
                            <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo countItems('UserID','users');?></a></span>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="stat st-pending">
                            <i class="fa fa-user-plus"></i>
                            <div class="info">
                            iPending Members
                            <span><a href="membres.php?do=Manage&page=Pending"><?php echo countItems('RegStatus','users');?></a></span>
                            </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-items">
                            <i class="fa fa-tag"></i>
                            <div class="info">
                            Total Items
                            <span><?php echo countItems('Item_ID','items');?></span>
                        </div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-comments">
                                <i class="fa fa-comments"></i>
                                <div class="info"> Total Comments
                                <span>
                                    <a href="comments.php"><?php echo countItems('c_id' , 'comments')?> </a>
                                </span>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container latest">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> latest <?php echo $latestUsers = 5 ?> Registred Users
                                <span class=" pull-righttoggle-info ">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                   <?php
                                   $numUsers = 6 ;  // Number of Latest Users
                                   $latestUsers = getLatest("*" , "users" ,"UserID" , $numUsers); // Latest users array

                                   foreach ($latestUsers as $user) {
                                            echo '<li>' ;
                                                echo $user['Username'] ;
                                                echo '<a href="membres.php?do=Edit&userid=' . $user['UserID'] . '"><span class="btn btn-success pull-right">';
                                                echo '<i class="fa fa-edit"></i>Edit</span></a>';
                                            if ($user['RegStatus'] == 0) {
                                                echo "<a href='membres.php?do=Activate&userid=" . $user['UserID']. "' class='btn btn-info activate pull-right'><i class='fa fa-info'></i>Approve</a></td>";
                                            }
                                            echo '</li>' ;
                                      }
                                   ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> latest <?php echo $numItems ?> Items
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                    $numItems = 6;
                                    $latestItems = getLatest("*" ,"items" , "item_ID" , $numItems); // Latest items array
                                    if (! empty($latestItems)) {
                                        foreach ($latestItems as $item) {
                                            echo '<li>';
                                            echo $item['Name'];
                                            echo '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] . '"><span class="btn btn-success pull-right">';
                                            echo '<i class="fa fa-edit"></i>Edit</span></a>';
                                            if ($item['Approve'] == 0) {
                                                echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' class='btn btn-info activate pull-right'><i class='fa fa-check'></i>Approve</a></td>";
                                            }
                                            echo '</li>';
                                        }
                                    } else  {
                                          echo ' There is no items to show';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Start Latest comments --->
       <div class="latest">
        <div class="container">
         <div class="row">
            <div class="col-sm-6">
                 <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comment-o" ></i> latest <?php echo $numcomments ?> Comments
                        <span class=" pull-righttoggle-info ">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body ">
                      <?php
                        $stmt = $con->prepare("SELECT comments.* , users.Username AS Member From comments INNER JOIN  users ON users.UserID = comments.user_id ORDER BY c_id DESC LIMIT $numcomments ")  ;
                        $stmt->execute();
                        $comments = $stmt->fetchAll();
                        if (! empty($comments)) {
                            foreach ($comments as $comment) {
                                echo '<div class="comment-box">';
                                echo '<span class="member-n"> ' . $comment['Member'] . '</span>';
                                echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo 'There is No comments to show';
                        }
                        ?>
           </div>
        </div>
       </div>
     </div>
        <!-- End Latest comments --->

        <?php
        include_once $tpl . 'footer.php';

    }  else {

        header('Location: index.php');
        exit();
    }