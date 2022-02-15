<?php
        include 'init.php';
?>




<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Direct Dari</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="categories.php">Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="items.php">Items</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="comments.php">Comments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Statistics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="membres.php">Members</a>
            </li>
            <li class="nav-item dropdown nav navbar-nav ">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Mohamed Saber
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="membres.php?do=Edit&userid=<?php echo $_SESSION['ID']?>">Edit Profile</a>
                    <a class="dropdown-item" href="#">Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo $tpl . 'index.php' ?>">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
