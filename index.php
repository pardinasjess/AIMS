<?php
include_once "class/MySqlLeaf.php";
include_once "class/AccountHandler.php";
include_once "class/FlashCard.php";

// Check if the account is logged or not
if(AccountHandler::isLogin()){
    switch (AccountHandler::getAccountType()){
        case 'admin':
	        header("location: admin.php");
            break;
        case 'employee':
	        header("location: employee.php");
	        break;
        case 'code':
	        header("location: enlistment.php");
	        break;
    }
    exit;
}

@$rand_code = $_POST["code"];
// Trigger for Code Submit
if (isset($rand_code)){
    $result = mysqli_query(MySqlLeaf::getCon(),
        "SELECT * FROM codes WHERE code = '$rand_code'
    ");
    $fetch = mysqli_fetch_array($result);

    if (mysqli_num_rows($result) < 1){
        FlashCard::setFlashCard("codeWrong");
    } else {
        if ($fetch["status"] == 0){
	        $_SESSION['username'] = $rand_code;
	        $_SESSION['type'] = "code";
	        $_SESSION['id'] = 0;

	        setcookie( "username", $rand_code, time() + (10 * 365 * 24 * 60 * 60) );
	        setcookie( "type", "code", time() + (10 * 365 * 24 * 60 * 60) );
	        setcookie( "id", 0, time() + (10 * 365 * 24 * 60 * 60) );

        }else{
            FlashCard::setFlashCard("codeUsed");
        }
        
    }
    
    // Reload the page
    header("location: /");
    exit();
}

@$username = $_POST["username"];
@$password = $_POST["password"];
// Trigger for Username, Password - Submit
if (isset($username) && isset($password)){

    $result = mysqli_query(MySqlLeaf::getCon(),
        "SELECT * FROM accounts WHERE username = '$username' AND status='active'"
    );
    $fetch = mysqli_fetch_array($result);

    if (mysqli_num_rows($result) < 1) {
        // Add FlashCard
        FlashCard::setFlashCard("errorUsername");
        
    } else {
        if ($password == $fetch['password']) {

            $emp_type = $fetch['acct_type'];
            $id = $fetch['id'];
            $_SESSION["username"] = $username;
            $_SESSION["type"] = $emp_type;
            $_SESSION["id"] = $id;

            setcookie( "username", $username, time() + (10 * 365 * 24 * 60 * 60) );
            setcookie( "type", $emp_type, time() + (10 * 365 * 24 * 60 * 60) );
            setcookie( "id", $id, time() + (10 * 365 * 24 * 60 * 60) );

            // Redirect Depend on accountType
            if ($emp_type == "admin"){
	            header("location: admin.php");
            }else{
	            header("location: employee.php");
            }
            exit;
        } else {
            FlashCard::setFlashCard("errorPassword");
        }
    }

    // Reload the Page
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<!--  MAIN  -->
    <title>Welcome to AOPEEPS</title>
<!--  META TAGS  -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--  CSS  -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/index.css">
<!--  JS LIBRARIES  -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.formatter.js"></script>
    <script src="js/jquery.formatter.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="/js/fontawesome-all.js"></script>
    <script src="/js/parallax.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg text-white fixed-top justify-content-center">
    <img src="img/logo.png" alt="LOGO" height="90">
    <form action="#" method="post" class="navbar-nav ml-auto">
        <ul class="form-inline" >
            <li class="mt-1">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" placeholder="Username" name="username" required>
            </li>
            <li class="mt-1 ml-2">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Password" name="password" required>
            </li>
            <li class="mt-4 ml-2">
                <label for=""></label>
                <input type="submit" name="submit" class="btn  btn-outline-info" value="Sign in" />
            </li>
        </ul>
    </form>
</nav>

<div class="jumbotron mb-0 bg-transparent" id="mainJumbotron" data-parallax="scroll" data-image-src="img/4.jpg">
    <div class="container text-white text-center">
        <h1 class="title text-primary">AIMS AGRI VENTURES, INC.</h1>
        <h2 class="">Online Performance Evaluating 	& Employee Profiling System</h2>
    </div>
</div>

<div class="jumbotron mb-0" id="codeJumbotron">
    <div class="container text-center mb-3 font-weight-bold">
        <h2>
            The AOPEEPS serves as an online system that helps the
            human resource management of AIMS Agri Ventures, Inc. in hiring
            and monitoring the performance of their employees.
        </h2>
    </div>

    <p class="mb-1" style="text-align: center; font-size: medium">For applicants kindly enter your code here.</p>
    <form action="#" method="post" style="width: 300px; margin: auto" class="mt-0">
        <input type="text" name="code" placeholder="Enter Code Here" class="mb-2 form-control">
        <input type="submit" name="submit" class="btn btn-info form-control" value="Get Started">
    </form>

</div>

<div class="jumbotron mb-0 bg-transparent" id="secondaryJumbotron" data-parallax="scroll" data-speed="0.5" data-image-src="img/6.jpg">
    <div class="container">
        <div class="row">
            <div class="col-7 text-white">
                <h3 class="font-weight-bold text-info">Theodore Roosevelt</h3>
                <h5>
                    “Do what you can, with what you have, where you are”.
                </h5>
                <h3 class="mt-4 font-weight-bold text-info">Thomas Jefferson</h3>
                <h5>
                    “Agriculture is our wisest pursuit, because it will in the end contribute most to real wealth, good morals, and happiness.”
                </h5>
            </div>
            <div class="col-5"></div>
        </div>
    </div>
</div>

<div class="jumbotron mb-0">
    <div class="container text-center">
        <h1 class="font-weight-bold">“Farming is not just a job, it’s a way of life.” </h1>
        <h3>
            Many people today believe that farmers just farm because they chose that as a profession. In many cases that is not true, some farmers have other jobs but they farm because they know that that is the only way that we will be able to feed the world in the future. To put in into perspective, when you go to work you work from 8am to 5pm and then you go home and take the night off. Well a farmer does not have a day off, If you are a farmer “you punch in at 5, and never punch out.” They never leave the farm or take a break because there is always something to do and people to feed.
        </h3>

    </div>
</div>

<div class="jumbotron mb-0 bg-transparent" id="secondaryJumbotron2" data-parallax="scroll" data-speed="0.7" data-image-src="img/3.jpg">
    <div class="container">
        <div class="row">
            <div class="col-5">

            </div>
            <div class="col-7 text-white">
                <h3 class="font-weight-bold text-info">Wants...</h3>
                <h5>
                    “It’s not about how bad you want it….it’s about how hard you are willing to work for it.”
                </h5>
                <h3 class="mt-4 font-weight-bold text-info">“Some of us grew up playing with tractors, the lucky ones still do”</h3>
                <h5>
                    Living on a regular farm or even just a hobby farm is never easy. No everything will go your way, especially the weather. You can’t just sit in the house and wish you had the perfect field or the perfect yield, you have to go out into the field and work with it. My dad always told me, “you get out of it what you put into it.” If you are not willing to put the time and money into your crop, when fall comes around and it’s time to harvest, you are not going to have the bumper crop that you wished for. Farmers work harder than anybody I know because they take pride in the land that they have and the people that they are going to feed from their bushels of corn or soybeans.
                </h5>
            </div>

        </div>
    </div>
</div>

<div class="jumbotron bg-dark mb-0" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-2">
                <div class="footer-logo">
                    <img src="img/logo.png" alt="LOGO" height="90">
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <h3 class="text-info">Company</h3>
                <ul class="text-white pl-3">
                    <li class="mb-3"><a href="#" class="text-white" >About</a> </li>
                    <li class="mb-3"><a href="#" class="text-white" >Jobs </a></li>
                    <li class="mb-3"><a href="#" class="text-white" >Press</a></li>
                    <li class="mb-3"><a href="#" class="text-white" >News</a></li>
                </ul>
            </div>

            <div class="col-xs-6 col-sm-4 col-md-2">
                <h3 class="text-info">Communities</h3>
                <ul class="text-white pl-3">
                    <li class="mb-3" > <a href="#" class="text-white">For Artists</a> </li>
                    <li class="mb-3" > <a href="#" class="text-white">Developers</a> </li>
                    <li class="mb-3" > <a href="#" class="text-white">Brands</a> </li>
                    <li class="mb-3" > <a href="#" class="text-white">Investors</a> </li>
                </ul>
            </div>

            <div class="col-xs-6 col-sm-4 col-md-2">
                <h3 class="text-info">Useful links</h3>
                <ul class="text-white pl-3">
                    <li class="mb-3"> <a href="#" class="text-white"> Help </a> </li>
                    <li class="mb-3"> <a href="#" class="text-white"> Gift </a> </li>
                    <li class="hidden-xs ">
                        <a href="#" class="text-white"> Web Player </a>
                    </li>
                </ul>
            </div>

            <div class="col-xs-12 col-md-4">
                <h3 class="text-info">Social Network</h3>
                <ul class="text-white pl-3">
                    <li class="mb-3"> <a href="#" class="text-white"> Facebook </a> </li>
                    <li class="mb-3"> <a href="#" class="text-white"> Twitter </a> </li>
                    <li class="mb-3"> <a href="#" class="text-white"> Intagram </a> </li>

                </ul>
            </div>
        </div>
    </div>
</div>

<!-- START MODAL FOR ERROR LOGIN -->

<!--Modal Attachments-->
<div class="modal fade" id="errorLogin" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <form action="#" method="POST" class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title text-white">Sign in</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
	            <?php
                $flashCard = FlashCard::hasFlashCard();
	            if ($flashCard){
                    switch (FlashCard::getFlashCard()) {
                        case 'errorUsername':
                            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                            echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                            echo "        <span aria-hidden='true'>&times;</span>";
                            echo "    </button>";
                            echo "    <b>Error!</b> Username does not exist.";
                            echo "</div>";
                            break;
                        case 'errorPassword':
                            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
                            echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                            echo "        <span aria-hidden='true'>&times;</span>";
                            echo "    </button>";
                            echo "    <b>Opps..</b> Password Mismatch.";
                            echo "</div>";
                            break;
                        case 'codeUsed':
                            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
                            echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                            echo "        <span aria-hidden='true'>&times;</span>";
                            echo "    </button>";
                            echo "    <b>Opps..</b> Code already used, try logging-in instead.";
                            echo "</div>";
                            break;
                        case 'codeWrong':
                            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
                            echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                            echo "        <span aria-hidden='true'>&times;</span>";
                            echo "    </button>";
                            echo "    <b>Opps..</b> You entered an invalid code, try logging-in instead.";
                            echo "</div>";
                            break;
                    }
                }
	            ?>

                <label for="username" class="font-weight-bold">Username:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                <label for="password" class="font-weight-bold">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
            </div>
            <div class="modal-footer">
                <input type="submit" value="Sign in" class="btn btn-primary">
                <button type="button" class="btn btn-outline-info" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
<?php
    if ($flashCard){
        echo '$("#errorLogin").modal("show");';
    }
?>
</script>
</body>
</html>