<?php
include_once "class/EnlistExam.php";
include_once "class/MySqlLeaf.php";
include_once "class/AccountHandler.php";

// Check if not login
if(!AccountHandler::isLogin()){
	header("location: /");
	exit;
}else{
	if (AccountHandler::getAccountType() !== "code"){
		header("location: /");
		exit;
	}
}

// Check if not yet filled up the detail
if (!EnlistExam::isPrepared()) {
	header("location: enlistment.php");
	exit;
}

// Trigger for Back button
if(isset($_POST["back"])){
    EnlistExam::clearPrepared();
    header("location: enlistment.php");
    exit;
}

// Trigger for submit exam
if(isset($_POST['C1']) && isset($_POST['C2']) && isset($_POST['C3']) &&
    isset($_POST['C4']) && isset($_POST['C5']) && isset($_POST['C6']) &&
    isset($_POST['C7']) && isset($_POST['C8']) && isset($_POST['C9']) &&
    isset($_POST['C10']) && isset($_POST['C11']) && isset($_POST['C12'])&&
    isset($_POST['C13']) && isset($_POST['C15']) && isset($_POST['C14']) &&
    isset($_POST['C15']) && isset($_POST['C16'])&& isset($_POST['C17']) &&
    isset($_POST['C18']) && isset($_POST['C19a']) && isset($_POST['C19b']) &&
    isset($_POST['C19c']) && isset($_POST['C19d']) && isset($_POST['C20a']) &&
    isset($_POST['C20b']) && isset($_POST['C20c'])
){

    // START SAVING APPLICANT INFORMATION
    $prep = EnlistExam::getPrepared();
    $fName = $prep["fName"]; $lName = $prep["lName"]; $mName = $prep["mName"]; $address = $prep["address"];
    $contact = $prep["contact"];

    $postData = $_POST;

	$C1 = $postData['C1']; $C2 = $postData['C2']; $C3 = $postData['C3']; $C4 = $postData['C4'];
	$C5 = $postData['C5']; $C6 = $postData['C6']; $C7 = $postData['C7']; $C8 = $postData['C8'];
	$C9 = $postData['C9']; $C10 = $postData['C10']; $C11 = $postData['C11']; $C12 = $postData['C12'];
	$C13 = $postData['C13']; $C14 = serialize($postData['C14']); $C15 = serialize($postData['C15']);
	$C16 = $postData['C16']; $C17 = $postData['C17']; $C18 = $postData['C18']; $C19a = $postData['C19a'];
	$C19b = $postData['C19b']; $C19c = $postData['C19c']; $C19d = $postData['C19d']; $C20a = $postData['C20a'];
	$C20b = $postData['C20b']; $C20c = $postData['C20c'];

	$sql1 = "INSERT INTO `accounts`
            (`fname`, `mname`, `lname`, `address`, `contact_num`, `status`, `score`)
            VALUES 
            ('$fName', '$mName', '$lName', '$address', '$contact', 'pending', '" .EnlistExam::checkAnswer($postData). "');";

    // START SAVING EXAMINATION
    $sql2 = "INSERT INTO `exam`
          (`account_id`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`, `C9`, `C10`, `C11`, `C12`, `C13`, `C14`, `C15`, `C16`, `C17`, `C18`, `C19a`, `C19b`, `C19c`, `C19d`, `C20a`, `C20b`, `C20c`)
           VALUES
            (LAST_INSERT_ID(),'$C1','$C2','$C3','$C4','$C5','$C6','$C7','$C8','$C9','$C10','$C11','$C12','$C13','$C14','$C15','$C16','$C17','$C18','$C19a','$C19b','$C19c','$C19d','$C20a','$C20b','$C20c');";

    $sql3 = "UPDATE `codes` SET `status`='1' WHERE `code`='" .AccountHandler::getUsername(). "';";
    mysqli_multi_query(MySqlLeaf::getCon(), $sql1.$sql2.$sql3);

    EnlistExam::clearPrepared();
    header("location: /logout.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Examination | Applicant</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/examination.css">

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.formatter.js"></script>
    <script src="js/jquery.formatter.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script defer src="/js/fontawesome-all.js"></script>
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

<div class="card container mb-3 pb-3" id="mainContainer">
    <form action="#" method="POST" class="card-header mt-3">
        <h5>
            Kindly choose the right answer,
            <?php
                $prep = EnlistExam::getPrepared();
                echo ucfirst($prep["fName"] ." ". $prep["lName"]) .".";
            ?>
        </h5>
        <input type="submit" class="btn btn-primary" name="back" value="« Back to fill-up form">
    </form>
    <form action="#" method="POST">
        <!-- START QUESTION NUMBER 1 -->
        <h5 class="mt-4 ml-3">1.) 139 + 235 =</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C1" class="ml-5" id="a1" required/>
                <label for="a1">&nbsp;A.) 372</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C1" class="ml-5" id="b1" required />
                <label for="b1">&nbsp;B.) 374</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C1" class="ml-5" id="c1" required/>
                <label for="c1">&nbsp;C.) 376</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C1" class="ml-5" id="d1" required/>
                <label for="d1">&nbsp;D.) 437</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 2 -->
        <h5 class="ml-3 mt-3">2.) 139 - 235 =</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C2" class="ml-5" id="a2" required />
                <label for="a2">&nbsp;A.) -69</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C2" class="ml-5" id="b2" required />
                <label for="b2">&nbsp;B.) 96</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C2" class="ml-5" id="c2" required />
                <label for="c2">&nbsp;C.) 98</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C2" class="ml-5" id="d2" required />
                <label for="d2">&nbsp;D.) -96</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 3 -->
        <h5 class="ml-3 mt-3">3.) 5 x 16 =</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="1" name="C3" class="ml-5" id="a3"  required/>
                <label for="a3">&nbsp;A.) 80</label>
            </div>
            <div class="col-3">
                <input type="radio" value="a" name="C3" class="ml-5" id="b3" required/>
                <label for="b3">&nbsp;B.) 86</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C3" class="ml-5" id="c3"  required/>
                <label for="c3">&nbsp;C.) 88</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C3" class="ml-5" id="d3" required/>
                <label for="d3">&nbsp;D.) 78</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 4 -->
        <h5 class="ml-3 mt-3">4.) 45 / 9 =</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C4" class="ml-5" id="a4"  required/>
                <label for="a4">&nbsp;A.) 4.5</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C4" class="ml-5" id="b4" required/>
                <label for="b4">&nbsp;B.) 4</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C4" class="ml-5" id="c4"  required/>
                <label for="c4">&nbsp;C.) 5</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C4" class="ml-5" id="d4" required/>
                <label for="d4">&nbsp;D.) 6</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 5 -->
        <h5 class="ml-3 mt-3">5.) 15% of 300 =</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a"  name="C5" class="ml-5" id="a5" required/>
                <label for="a5">&nbsp;A.) 20</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C5" class="ml-5" id="b5" required/>
                <label for="b5">&nbsp;B.) 45</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C5" class="ml-5" id="c5" required/>
                <label for="c5">&nbsp;C.) 40</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C5" class="ml-5" id="d5" required/>
                <label for="d5">&nbsp;D.) 35</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 6 -->
        <h5 class="mt-3 ml-3">6.) 1/2 + 1/4 x 3/4 =</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C6" class="ml-5" id="a6" required />
                <label for="a6">&nbsp;A.) 3/8</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C6" class="ml-5" id="b6" required />
                <label for="b6">&nbsp;B.) 13/8</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C6" class="ml-5" id="c6" required />
                <label for="c6">&nbsp;C.) 9/16</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C6" class="ml-5" id="d6" required />
                <label for="d6">&nbsp;D.) 3/4</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 7 -->
        <h5 class="mt-3 ml-3">7.) Find the next number in the series (4&nbsp; &nbsp; &nbsp;8&nbsp; &nbsp; &nbsp;16&nbsp; &nbsp; &nbsp;32)</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C7" class="ml-5" id="a7" required />
                <label for="a7">&nbsp;A.) 48</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C7" class="ml-5" id="b7" required />
                <label for="b7">&nbsp;B.) 64</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C7" class="ml-5" id="c7" required />
                <label for="c7">&nbsp;C.) 40</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C7" class="ml-5" id="d7" required />
                <label for="d7">&nbsp;D.) 46</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 8 -->
        <h5 class="mt-3 ml-3">8.) Find the next number in the series (4&nbsp; &nbsp; &nbsp;8&nbsp; &nbsp; &nbsp;12&nbsp; &nbsp; &nbsp;20)</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="1" name="C8" class="ml-5" id="a8" required />
                <label for="a8">&nbsp;A.) 32</label>
            </div>
            <div class="col-3">
                <input type="radio" value="a" name="C8" class="ml-5" id="b8" required />
                <label for="b8">&nbsp;B.) 34</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C8" class="ml-5" id="c8" required />
                <label for="c8">&nbsp;C.) 36</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C8" class="ml-5" id="d8" required />
                <label for="d8">&nbsp;D.) 38</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 9 -->
        <h5 class="mt-3 ml-3">9.) Find the missing number in the series (54&nbsp; &nbsp; &nbsp;49&nbsp; &nbsp; &nbsp;___&nbsp; &nbsp; &nbsp;39&nbsp; &nbsp; &nbsp;34)</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C9" class="ml-5" id="a9" required />
                <label for="a9">&nbsp;A.) 47</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C9" class="ml-5" id="b9" required />
                <label for="b9">&nbsp;B.) 44</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C9" class="ml-5" id="c9" required />
                <label for="c9">&nbsp;C.) 45</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C9" class="ml-5" id="d9" required />
                <label for="d9">&nbsp;D.) 46</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 10 -->
        <h5 class="mt-3 ml-3">10.) Find the first number in the series ( ___&nbsp; &nbsp; &nbsp;19&nbsp; &nbsp; &nbsp;23&nbsp; &nbsp; &nbsp;29&nbsp; &nbsp; &nbsp;31)</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C10" class="ml-5" id="a10" required />
                <label for="a10">&nbsp;A.) 12</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C10" class="ml-5" id="b10"  required />
                <label for="b10">&nbsp;B.) 15</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C10" class="ml-5" id="c10" required />
                <label for="c10">&nbsp;C.) 16</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C10" class="ml-5" id="d10" required />
                <label for="d10">&nbsp;D.) 17</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 11 -->
        <h5 class="mt-3 ml-3">11.) Find the next number in the series (3&nbsp; &nbsp; &nbsp;6&nbsp; &nbsp; &nbsp;11&nbsp; &nbsp; &nbsp;18&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C11" class="ml-5" id="a11" required />
                <label for="a11">&nbsp;A.) 30</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C11" class="ml-5" id="b11" required />
                <label for="b11">&nbsp;B.) 22</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C11" class="ml-5" id="c11" required />
                <label for="c11">&nbsp;C.) 27</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C11" class="ml-5" id="d11" required />
                <label for="d11">&nbsp;D.) 29</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 12 -->
        <h5 class="mt-3 ml-3">12.) Find the next number in the series (48&nbsp; &nbsp; &nbsp;46&nbsp; &nbsp; &nbsp;42&nbsp; &nbsp; &nbsp;38&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C12" class="ml-5" id="a12" required />
                <label for="a12">&nbsp;A.) 32</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C12" class="ml-5" id="b12" required />
                <label for="b12">&nbsp;B.) 30</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C12" class="ml-5" id="c12" required />
                <label for="c12">&nbsp;C.) 33</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C12" class="ml-5" id="d12" required />
                <label for="d12">&nbsp;D.) 34</label>
            </div>
        </div>

        <!-- START QUESTION NUMBER 13 -->
        <h5 class="mt-3 ml-3">13.) Find the missing number in the series (4&nbsp; &nbsp; &nbsp;3&nbsp; &nbsp; &nbsp;5&nbsp; &nbsp; &nbsp;9&nbsp; &nbsp; &nbsp;12&nbsp; &nbsp; &nbsp;17&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a"  name="C13" class="ml-5" id="a13" required />
                <label for="a13">&nbsp;A.) 32</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C13" class="ml-5" id="b13" required />
                <label for="b13">&nbsp;B.) 30</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C13" class="ml-5" id="c13" required />
                <label for="c13">&nbsp;C.) 24</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C13" class="ml-5" id="d13" required />
                <label for="d13">&nbsp;D.) 26</label>
            </div>
        </div>

        <!-- START QUESTION NUMBER 14 -->
        <h5 class="mt-3 ml-3">14.) Find the missing number in the series (5&nbsp; &nbsp; &nbsp;6&nbsp; &nbsp; &nbsp;7&nbsp; &nbsp; &nbsp;8&nbsp; &nbsp; &nbsp;10&nbsp; &nbsp; &nbsp;11&nbsp; &nbsp; &nbsp;14&nbsp; &nbsp; &nbsp;___&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="checkbox" value="1" name="C14[]" class="ml-5" id="a14"  />
                <label for="a14">&nbsp;A.) 19</label>
            </div>
            <div class="col-3">
                <input type="checkbox" value="a" name="C14[]" class="ml-5" id="b14"  />
                <label for="b14">&nbsp;B.) 17</label>
            </div>
            <div class="col-3">
                <input type="checkbox" value="1" name="C14[]" class="ml-5" id="c14"  />
                <label for="c14">&nbsp;C.) 15</label>
            </div>
            <div class="col-3">
                <input type="checkbox" value="b" name="C14[]" class="ml-5" id="d14" />
                <label for="d14">&nbsp;D.) 16</label>
            </div>
        </div>

        <!-- START QUESTION NUMBER 15 -->
        <h5 class="mt-3 ml-3">15.) Find the missing number in the series (1&nbsp; &nbsp; &nbsp;___&nbsp; &nbsp; &nbsp;4&nbsp; &nbsp; &nbsp;7&nbsp; &nbsp; &nbsp;7&nbsp; &nbsp; &nbsp;8&nbsp; &nbsp; &nbsp;10&nbsp; &nbsp; &nbsp;9&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="checkbox" value="1" name="C15[]" class="ml-5" id="a15"   />
                <label for="a15">&nbsp;A.) 6</label>
            </div>
            <div class="col-3">
                <input type="checkbox" value="a" name="C15[]" class="ml-5" id="b15"   />
                <label for="b15">&nbsp;B.) 3</label>
            </div>
            <div class="col-3">
                <input type="checkbox" value="b" name="C15[]" class="ml-5" id="c15"   />
                <label for="c15">&nbsp;C.) 11</label>
            </div>
            <div class="col-3">
                <input type="checkbox" value="1" name="C15[]" class="ml-5" id="d15"  />
                <label for="d15">&nbsp;D.) 13</label>
            </div>
        </div>

        <!-- START QUESTION NUMBER 16 -->
        <h5 class="mt-3 ml-3">16.) Find the next letter in the series (B&nbsp; &nbsp; &nbsp;E&nbsp; &nbsp; &nbsp;H&nbsp; &nbsp; &nbsp;K&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C16" class="ml-5" id="a16" required />
                <label for="a16">&nbsp;i.) L</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C16" class="ml-5" id="b16" required />
                <label for="b16">&nbsp;ii.) M</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C16" class="ml-5" id="c16" required />
                <label for="c16">&nbsp;iii.) N</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C16" class="ml-5" id="d16" required />
                <label for="d16">&nbsp;iv.) O</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 17 -->
        <h5 class="mt-3 ml-3">17.) Find the next letter in the series (A&nbsp; &nbsp; &nbsp;Z&nbsp; &nbsp; &nbsp;B&nbsp; &nbsp; &nbsp;Y&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="1" name="C17" class="ml-5" id="a17" required />
                <label for="a17">&nbsp;i.) C</label>
            </div>
            <div class="col-3">
                <input type="radio" value="a" name="C17" class="ml-5" id="b17" required />
                <label for="b17">&nbsp;ii.) X</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C17" class="ml-5" id="c17" required />
                <label for="c17">&nbsp;iii.) D</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C17" class="ml-5" id="d17" required />
                <label for="d17">&nbsp;iv.) Y</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 18 -->
        <h5 class="mt-3 ml-3">18.) Find the next letter in the series (T&nbsp; &nbsp; &nbsp;V&nbsp; &nbsp; &nbsp;X&nbsp; &nbsp; &nbsp;Z&nbsp; &nbsp; &nbsp;___ )</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C18" class="ml-5" id="a18" required />
                <label for="a18">&nbsp;i.) Y</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C18" class="ml-5" id="b18" required />
                <label for="b18">&nbsp;ii.) B</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C18" class="ml-5" id="c18" required />
                <label for="c18">&nbsp;iii.) A</label>
            </div>
            <div class="col-3">
                <input type="radio" value="c" name="C18" class="ml-5" id="d18" required />
                <label for="d18">&nbsp;iv.) W</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 19 -->
        <h5 class="p-3 ">19.) Below are the sales figures for 3 different types of network server over 3 months.</h5>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered mt-3">
                    <thead>
                    <tr>
                        <th>Server</th>
                        <th colspan="2">January</th>
                        <th colspan="2">February</th>
                        <th colspan="2">March</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row"></th>
                        <td>Units</td>
                        <td>Values</td>
                        <td>Units</td>
                        <td>Values</td>
                        <td>Units</td>
                        <td>Values</td>
                    </tr>
                    <tr>
                        <th scope="row">ZXC43</th>
                        <td>32</td>
                        <td>480</td>
                        <td>40</td>
                        <td>600</td>
                        <td>45</td>
                        <td>720</td>
                    </tr>
                    <tr>
                        <th scope="row">ZXC53</th>
                        <td>45</td>
                        <td>585</td>
                        <td>45</td>
                        <td>585</td>
                        <td>48</td>
                        <td>585</td>
                    </tr>
                    <tr>
                        <th scope="row">ZXC63</th>
                        <td>12</td>
                        <td>240</td>
                        <td>14</td>
                        <td>280</td>
                        <td>18</td>
                        <td>340</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h5 class="mt-3 ml-3">19a.) In which month was the sales value highest?</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C19a" class="ml-5" id="a19a" required />
                <label for="a19a">&nbsp;A) January</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C19a" class="ml-5" id="b19a" required />
                <label for="b19a">&nbsp;B.) February</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C19a" class="ml-5" id="c19a" required />
                <label for="c19a">&nbsp;C.) March</label>
            </div>
        </div>
        <h5 class="mt-3 ml-3">19b.) What is the unit cost of server type ZXC53?</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C19b" class="ml-5" id="a19b" required />
                <label for="a19b">&nbsp;A) 12</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C19b" class="ml-5" id="b19b" required />
                <label for="b19b">&nbsp;B.) 13</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C19b" class="ml-5" id="c19b" required />
                <label for="c19b">&nbsp;C.) 14</label>
            </div>
        </div>
        <h5 class="mt-3 ml-3">19c.) What is the unit cost of server type ZXC43?</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="1" name="C19c" class="ml-5" id="a19c" required />
                <label for="a19c">&nbsp;A) 56</label>
            </div>
            <div class="col-3">
                <input type="radio" value="a" name="C19c" class="ml-5" id="b19c" required />
                <label for="b19c">&nbsp;B.) 58</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C19c" class="ml-5" id="c19c" required />
                <label for="c19c">&nbsp;C.) 60</label>
            </div>
        </div>
        <h5 class="mt-3 ml-3">19d.) What is the unit cost of server type ZXC43?</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C19d" class="ml-5" id="a19d" required />
                <label for="a19d">&nbsp;A) ZXC43</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C19d" class="ml-5" id="b19d" required />
                <label for="b19d">&nbsp;B.) ZXV53</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C19d" class="ml-5" id="c19d" required />
                <label for="c19d">&nbsp;C.) ZXC63</label>
            </div>
        </div>
        <!-- START QUESTION NUMBER 20 -->
        <h5 class="mt-3 ml-3">20.) Below are some figures for agricultural imports. Asnwer the following questions using the data provided. You may use a calculator for this question.</h5>
        <div class="row">
            <div class="12">
                <img id="graph" src="img/graph.jpg" align="center" width="50%" height="90%">
            </div>
        </div>
        <h5 class="mt-3 ml-3">20a.) Which month showed the largest total decrease in imports over the previous month?</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C20a" class="ml-5" id="a20a"  required />
                <label for="a20a">&nbsp;A) March</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C20a" class="ml-5" id="b20a" required />
                <label for="b20a">&nbsp;B.) April</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C20a" class="ml-5" id="c20a"  required />
                <label for="c20a">&nbsp;C.) May</label>
            </div>
        </div>
        <h5 class="mt-3 ml-3">20b.) Which month showed the largest total decrease in imports over the previous month?</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="a" name="C20b" class="ml-5" id="a20b"  required />
                <label for="a20b">&nbsp;A) 17%</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C20b" class="ml-5" id="b20b"  required />
                <label for="b20b">&nbsp;B.) 19%</label>
            </div>
            <div class="col-3">
                <input type="radio" value="1" name="C20b" class="ml-5" id="c20b"  required />
                <label for="c20b">&nbsp;C.) 21%</label>
            </div>
        </div>
        <h5 class="mt-3 ml-3">20c.) What was the total cost of wheat imports in the 5 months period?</h5>
        <div class="row">
            <div class="col-3">
                <input type="radio" value="1" name="C20c" class="ml-5" id="a20c"  required />
                <label for="a20c">&nbsp;A) 27,500</label>
            </div>
            <div class="col-3">
                <input type="radio" value="a" name="C20c" class="ml-5" id="b20c" />
                <label for="b20c">&nbsp;B.) 25,000</label>
            </div>
            <div class="col-3">
                <input type="radio" value="b" name="C20c" class="ml-5" id="c20c" />
                <label for="c20c">&nbsp;C.) 22,000</label>
            </div>
        </div>
        <hr>
        <div class="alert alert-warning">
            <h6>NOTE: Finalize your answer before clicking the submit button!</h6>
        </div>
        <input type="submit" class="btn btn-success" />
        <hr>
    </form>
</div>
</body>
</html>