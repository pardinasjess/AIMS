<?php
include_once "class/EnlistExam.php";
include_once "class/AccountHandler.php";

if(!AccountHandler::isLogin()){
	header("location: /");
	exit;
}else{
    if (AccountHandler::getAccountType() !== "code"){
	    header("location: /");
	    exit;
    }
}

@ $post = $_POST;

function checkRequired(){
	global $post;

	return (isset($post["fName"]) && isset($post["mName"]) && isset($post["lName"]) &&
	    isset($post["address"]) && isset($post["contact"]));
}

if (checkRequired()){
	// Temporarily Saved the data for later use
    EnlistExam::prepare($post);
}

if (EnlistExam::isPrepared()){
	header("location: examination.php");
	exit;
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Enlistment | Applicant</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/enlistment.css">

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.formatter.js"></script>
    <script src="js/jquery.formatter.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="/js/fontawesome-all.js"></script>
    <script src="/js/parallax.min.js"></script>
</head>

<body>
<nav class="navbar navbar-expand-lg fixed-top text-white justify-content-center">
    <img src="img/logo.png" alt="LOGO" height="90">
    <ul class="navbar-nav ml-auto">
        <li class="mt-1 ml-2">
            <label for=""></label>
            <a href="logout.php" class="btn btn-outline-danger">Cancel Enlistment</a>
        </li>
    </ul>
</nav>

<div class="jumbotron mb-0 bg-transparent" data-parallax="scroll" data-image-src="img/4.jpg">
    <div class="container">
        <div id="mainRow" class="row">
            <div class="col-6">
                <div id="introduction" class="text-white mt-3">
                    <h1 class="text-primary">AIMS Agri Ventures, Inc.</h1>
                    <h5 class="mt-4">
                        A crucial part of any business plan is spelling out your company history, business background
                        and telling your origin story. The main objective in sharing your history and the story of how you got
                        started is to show potential teammates and investors how you landed on this business idea, and explain
                        why you're uniquely qualified to pursue it.

                        Sharing your business background goes far beyond simply telling a clever story of how you
                        triumphed over adversity, to launch your new business.
                    </h5>
                </div>
            </div>

            <div class="col-6 card p-5">
                <h2 class="font-weight-bold">Applicant Enlistment Form</h2>
                <form action="#" method="POST" class="form-group mb-0">
                    <label for="" class="mb-0 mt-1 font-w">Enter your first name:</label>
                    <input class="form-control" placeholder="Enter First Name" type="text" name="fName" size="30" required/>
                    <label for="" class="mb-0 mt-1 font-w">Enter your middle name:</label>
                    <input class="form-control"  placeholder="Enter Middle Name" type="text" name="mName" size="30" required/>
                    <label for="" class="mb-0 mt-1 font-w">Enter your last name:</label>
                    <input class="form-control" placeholder="Enter Last Name" type="text" name="lName" size="30" required/>
                    <label for="" class="mb-0 mt-1 font-w">Enter full address:</label>
                    <input class="form-control" placeholder="Enter Address" type="text" name="address" size="30" required>
                    <label for="" class="mb-0 mt-1 font-w">Enter contact number:</label>
                    <input class="form-control" placeholder="Enter Contact Number" type="text" name="contact" size="30" required>
                    <input type="submit" class=" mt-2 btn btn-outline-info float-right" value="Proceed to Examination"/>
                </form>
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
<script type="text/javascript">
    $(document).ready(function () {
        
        $("input[name='contact']").on({
            "keydown": function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl/cmd+A
                    (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: Ctrl/cmd+C
                    (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: Ctrl/cmd+X
                    (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                        // let it happen, don't do anything
                        return;
                }

                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            },
            "keyup change": function(){
                $contact = $(this);
                $maxLength = 11;

                if ($contact.val().length > $maxLength){
                    $contact.val($contact.val().substring(0, $maxLength))
                }
            }
        });
    })
</script>
</body>
</html>