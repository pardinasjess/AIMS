<?php
include_once "class/EnlistExam.php";
include_once "class/MySqlLeaf.php";
include_once "class/AccountHandler.php";
include_once "class/FlashCard.php";

if(!AccountHandler::isLogin()){
	header("location: /");
	exit;
}else{
	if (AccountHandler::getAccountType() !== "admin"){
		header("location: /");
		exit;
	}
}

@ $fname = $_POST["fName"];
@ $mname = $_POST["mName"];
@ $lname = $_POST["lName"];
@ $address = $_POST["address"];
@ $contact = $_POST["ConNum"];
@ $birthdate = $_POST["DoBirth"];
@ $position = $_POST["Pos"];
@ $custom_position = $_POST["_otherPos"];
@ $empstatus = $_POST["EmpStatus"];
@ $custom_empstatus = $_POST["_otherEmpStatus"];
@ $username = $_POST["username"];
@ $password = $_POST["password"];
@ $hiredDate = $_POST["Dhired"];
@ $fatherName = $_POST["FatName"];
@ $motherName = $_POST["MotName"];
@ $sssNum = $_POST["sssnum"];
@ $philNum = $_POST["philnum"];
@ $tinNum = $_POST["tinnum"];
@ $pagibigNum = $_POST["Pagibignum"];
@ $id = $_POST["id"];

// TriggerCreate and Update Employee Account
if(isset($fname) && isset($mname) && isset($lname) && isset($address) && isset($contact) &&
    isset($birthdate) && isset($empstatus) && isset($username) && isset($password) && isset($hiredDate) &&
    isset($fatherName) && isset($motherName) && isset($sssNum) && isset($philNum) && isset($tinNum) &&
    isset($pagibigNum) && isset($position) && isset($id))
{
    // If the user chooses the other in selection use the input instead.
    if ($empstatus == "_other"){
        $empstatus = $custom_empstatus;
    }

    if ($position == "_other"){
        $position = $custom_position;
    }
    
    if ($id == ""){
        

	    $sql = "INSERT INTO `accounts`
            (`username`, `password`, `fname`, `mname`, `lname`, `address`, `contact_num`, `birthdate`, `position`, `emp_type`, `date_hired`, `father_name`, `mother_name`, `sss_no`, `ph_no`, `pagibig`, `tin`, `status`)
             VALUES
            ('$username','$password','$fname','$mname','$lname','$address','$contact','$birthdate','$position','$empstatus','$hiredDate','$fatherName','$motherName','$sssNum','$philNum','$pagibigNum','$tinNum','active')";
	    mysqli_query(MySqlLeaf::getCon(), $sql);

        FlashCard::setFlashCard("accountCreated");
    }else{
        
        $sql = "UPDATE `accounts`
                SET `username`='$username',`password`='$password',`fname`='$fname',`mname`='$mname',
                    `lname`='$lname',`address`='$address',`contact_num`='$contact',`birthdate`='$birthdate',
                    `position`='$position',`emp_type`='$empstatus',`date_hired`='$hiredDate',`father_name`='$fatherName',
                    `mother_name`='$motherName',`sss_no`='$sssNum',`ph_no`='$philNum',`pagibig`='$pagibigNum',
                    `tin`='$tinNum'
                WHERE `id`='$id'";
        mysqli_query(MySqlLeaf::getCon(), $sql);

        FlashCard::setFlashCard("accountUpdated");
    }
    header("location: /admin.php");
    exit;
}


// GEt the List of Accounts.
@ $getList = $_POST["getList"];
if (isset($getList)){
    $requestData = $_POST;

    if ($getList == "active"){
	    // datatable column index  => database column name
	    $columns = array(
		    0 => 'id',
		    1 => 'fname',
		    2 => 'lname',
		    3 => 'position',
		    4 => 'emp_type'
	    );

	    // getting total number records without any search
	    $query=mysqli_query(MySqlLeaf::getCon(),
		    "SELECT id FROM `accounts` WHERE `status`='active' AND `acct_type`='employee'"
	    );
	    $totalData = mysqli_num_rows($query);

	    $sql = "SELECT id, fname, lname, emp_type, `position` FROM `accounts` WHERE `status`='active' AND `acct_type`='employee'";

	    // Getting records as per search parameters
	    if( !empty($requestData['search']['value']) )
		    $sql.=" AND (fname LIKE '".$requestData['search']['value']."%' OR lname LIKE '".$requestData['search']['value']."%') ";

	    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	    $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	    $query = mysqli_query(MySqlLeaf::getCon(), $sql);

	    $data = array();

	    while( $row=mysqli_fetch_array($query) ) {  // preparing an array
		    $nestedData=array();

		    $nestedData[] = $row["id"];
		    $nestedData[] = ucfirst($row["fname"]);
		    $nestedData[] = ucfirst($row["lname"]);
		    $nestedData[] = $row["position"];
		    $nestedData[] = ucfirst($row["emp_type"]);
		    $nestedData[] = $row["id"];

		    $data[] = $nestedData;
	    }

	    $json_data = array(
		    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		    "recordsTotal"    => intval( $totalData ),  // total number of records
		    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		    "data"            => $data   // total data array
	    );
	    mysqli_free_result($query);

    }else if ($getList == "pending"){
	    // datatable column index  => database column name
	    $columns = array(
		    0 => 'id',
		    1 => 'fname',
		    2 => 'lname',
		    3 => 'score'
	    );

	    // getting total number records without any search
	    $query=mysqli_query(MySqlLeaf::getCon(),
		    "SELECT id FROM `accounts` WHERE status='pending'"
	    );
	    $totalData = mysqli_num_rows($query);

	    $sql = "SELECT id, fname, lname, score FROM `accounts` WHERE status='pending'";

	    // Getting records as per search parameters
	    if( !empty($requestData['search']['value']) )
		    $sql.=" AND (fname LIKE '".$requestData['search']['value']."%' OR lname LIKE '".$requestData['search']['value']."%') ";

	    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	    $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	    $query = mysqli_query(MySqlLeaf::getCon(), $sql);

	    $data = array();

	    while( $row=mysqli_fetch_array($query) ) {  // preparing an array
		    $nestedData=array();

		    $nestedData[] = $row["id"];
		    $nestedData[] = $row["fname"];
		    $nestedData[] = $row["lname"];
		    $nestedData[] = $row["score"];
		    $nestedData[] = $row["id"];

		    $data[] = $nestedData;
	    }

	    $json_data = array(
		    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		    "recordsTotal"    => intval( $totalData ),  // total number of records
		    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		    "data"            => $data   // total data array
	    );
	    mysqli_free_result($query);
    }elseif($getList == "attachments"){
        $id = $_POST["id"];

	    // datatable column index  => database column name
	    $columns = array(
		    0 => 'id',
		    1 => 'description',
		    2 => 'date_uploaded'
	    );

	    // getting total number records without any search
	    $query=mysqli_query(MySqlLeaf::getCon(),
		    "SELECT id FROM `attachments` WHERE `account_id`='$id '"
	    );
	    $totalData = mysqli_num_rows($query);

	    $sql = "SELECT * FROM `attachments` WHERE `account_id`='$id '";

	    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	    $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	    $query = mysqli_query(MySqlLeaf::getCon(), $sql);

	    $data = array();

	    while( $row=mysqli_fetch_array($query) ) {  // preparing an array
		    $nestedData=array();

		    $nestedData[] = $row["id"];
		    $nestedData[] = $row["description"];
		    $nestedData[] = $row["date_uploaded"];
		    $nestedData[] = $row["photo"];

		    $data[] = $nestedData;
	    }

	    $json_data = array(
		    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		    "recordsTotal"    => intval( $totalData ),  // total number of records
		    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		    "data"            => $data   // total data array
	    );
	    mysqli_free_result($query);
    }elseif($getList == "evaluation"){
	    $id = $_POST["id"];

	    // datatable column index  => database column name
	    $columns = array(
		    0 => 'id',
		    1 => 'date',
		    2 => 'rating'
	    );

	    // getting total number records without any search
	    $query=mysqli_query(MySqlLeaf::getCon(),
		    "SELECT id FROM `eval` WHERE `acct_id`='$id'"
	    );
	    $totalData = mysqli_num_rows($query);

	    $sql = "SELECT * FROM `eval` WHERE `acct_id`='$id'";

	    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	    $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	    $query = mysqli_query(MySqlLeaf::getCon(), $sql);

	    $data = array();

	    while( $row=mysqli_fetch_array($query) ) {  // preparing an array
		    $nestedData=array();

		    $nestedData[] = $row["id"];
		    $nestedData[] = $row["date"];
		    $nestedData[] = $row["rating"];
		    $nestedData[] = $row["id"];

		    $data[] = $nestedData;
	    }

	    $json_data = array(
		    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		    "recordsTotal"    => intval( $totalData ),  // total number of records
		    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		    "data"            => $data   // total data array
	    );
	    mysqli_free_result($query);
    }else{
        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval(0),  // total number of records
            "recordsFiltered" => intval(0), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => array()   // total data array
        );
    }

	echo json_encode($json_data);
	exit;

}

@ $action = $_POST["action"];
if (isset($action) && isset($id)){
    if ($action == "getExam"){
	    $sql = "SELECT * FROM `exam` WHERE `account_id`='$id' LIMIT 1;";
	    $query = mysqli_query(MySqlLeaf::getCon(), $sql);
	    $arr = mysqli_fetch_array($query);
	    $arr["C14"] = unserialize($arr["C14"]);
	    $arr["C15"] = unserialize($arr["C15"]);
	    echo json_encode($arr);
	    exit;
    }

    if ($action == "getAccount"){
	    $sql = "SELECT `username`, `password`, `fname`, `mname`, `lname`, `address`, `contact_num`,
                        `birthdate`, `position`, `emp_type`, `date_hired`, `father_name`, `mother_name`,
                         `sss_no`, `ph_no`, `pagibig`, `tin` FROM `accounts` WHERE `id`='$id'";
	    $query = mysqli_query(MySqlLeaf::getCon(), $sql);
	    $arr = mysqli_fetch_array($query);
	    echo json_encode($arr);
	    exit;
    }
}


// Trigger Delete
if (isset($_POST["delete"])){
	$id = $_POST["id"];
	$sql = "
            DELETE FROM `exam` WHERE `account_id`='$id'; 
            DELETE FROM `attachments` WHERE `account_id`='$id';
            DELETE FROM `accounts` WHERE `id`='$id'; 
            ";
    mysqli_multi_query(MySqlLeaf::getCon(), $sql);
    
    FlashCard::setFlashCard("deleteAccount");
	header("location: /admin.php");
	exit;
}

// Trigger Decline
if (isset($_POST["decline"])){
	$id = $_POST["id"];
	$sql = "DELETE FROM `exam` WHERE `account_id`='$id'; 
            DELETE FROM `attachments` WHERE `account_id`='$id';
            DELETE FROM `accounts` WHERE `id`='$id'; ";
    mysqli_multi_query(MySqlLeaf::getCon(), $sql);
    
    FlashCard::setFlashCard("declineApplicant");
	header("location: /admin.php");
	exit;
}

// Trigger Approave
if (isset($_POST["approve"])){
	$id = $_POST["id"];
	$username = $_POST["approveUsername"];
	$password = $_POST["approvePassword"];
	$sql = "UPDATE `accounts`
                SET `username`='$username',`password`='$password', `status`='active'
                WHERE `id`='$id'";
    $query = mysqli_multi_query(MySqlLeaf::getCon(), $sql);
    
    if ($query === true){
        FlashCard::setFlashCard("approvedApplicant");
    }else{
        FlashCard::setFlashCard("duplicateUser");
    }
    header("location: /admin.php");        
	exit;
}

// Trigger Evaluate
if (isset($_POST['evaluate'])){
	$id = $_POST['id'];

	$pes = $_POST['pes'];
	$quarter =  $_POST['Quarter'];

	$a1 = $_POST['a1'];
	$a2 = $_POST['a2'];
	$a3 = $_POST['a3'];
	$a4 = $_POST['a4'];
	$a5 = $_POST['a5'];
	$a6 = $_POST['a6'];
	$b6 = $_POST['b6'];
	$c6 = $_POST['c6'];
	$d6 = $_POST['d6'];
	$comment = $_POST['comment'];
	$evaluator = $_POST['evaluator'];
	$average = round(($a1 + $a2 + $a3 + $a4 + $a5 + $a6 + $b6 + $c6 + $d6)/9, 2);

	$sql = "
      INSERT INTO `eval`(`acct_id`, `rating`, `date`) VALUES ('$id','$average',now());
      INSERT INTO `evaldata`
      (`eval_id`, `emp_status`, `eval_period`, `comment`, `evaluator`, `1a`, `2a`, `3a`, `4a`, `5a`, `6a`, `6b`, `6c`, `6d`) 
      VALUES
       (LAST_INSERT_ID(),'$pes','$quarter','$comment','$evaluator','$a1','$a2','$a3','$a4','$a5','$a6','$b6','$c6','$d6');
      ";

	mysqli_multi_query(MySqlLeaf::getCon(), $sql);

    FlashCard::setFlashCard("successEvaluation");
	header("location: /admin.php");
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Administrator | List</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/admin.css">

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.formatter.js"></script>
    <script src="js/jquery.formatter.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script defer src="/js/fontawesome-all.js"></script>
    <script defer src="/js/jquery.dataTables.min.js"></script>
</head>
<body>

<nav class="navbar p-3 mb-5 navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">View List<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="code.php">Examination Codes<span class="sr-only">(current)</span></a>
            </li>
            <li class="float-right">
                <a href="logout.php" class="btn  btn-outline-danger">Sign out</a>
            </li>
        </ul>
    </div>
</nav>

<div class="card container-fluid p-3">
    <?php  
    $flashCard = FlashCard::hasFlashCard();
    if ($flashCard){
        switch (FlashCard::getFlashCard()) {
            case 'accountCreated':
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                echo "        <span aria-hidden='true'>&times;</span>";
                echo "    </button>";
                echo "    <b>Success!</b> A new employee account information has been added";
                echo "</div>";
                break;
            case 'accountUpdated':
                echo "<div class='alert alert-info alert-dismissible fade show' role='alert'>";
                echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                echo "        <span aria-hidden='true'>&times;</span>";
                echo "    </button>";
                echo "    <b>Information:</b> Employee Information has been updated.";
                echo "</div>";
                break;
            case 'deleteAccount':
                echo "<div class='alert alert-info alert-dismissible fade show' role='alert'>";
                echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                echo "        <span aria-hidden='true'>&times;</span>";
                echo "    </button>";
                echo "    <b>Information:</b> Employee Information has been removed";
                echo "</div>";
                break;
            case 'declineApplicant':
                echo "<div class='alert alert-info alert-dismissible fade show' role='alert'>";
                echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                echo "        <span aria-hidden='true'>&times;</span>";
                echo "    </button>";
                echo "    <b>Information:</b> An applicant has been decline.";
                echo "</div>";
                break;
            case 'approvedApplicant':
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                echo "        <span aria-hidden='true'>&times;</span>";
                echo "    </button>";
                echo "    <b>Information:</b> An applicant has been approved.";
                echo "</div>";
                break;
            case 'duplicateUser':
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                echo "        <span aria-hidden='true'>&times;</span>";
                echo "    </button>";
                echo "    <b>Warning:</b> Possible username duplication has been detected.";
                echo "</div>";
                break;
            case 'successEvaluation':
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                echo "    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                echo "        <span aria-hidden='true'>&times;</span>";
                echo "    </button>";
                echo "    <b>Congratulations:</b> an Evaluation was successfully added.";
                echo "</div>";
                break;
        }
    }
    ?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="listofemp-tab" data-toggle="tab" href="#listofemp" role="tab" aria-controls="perprof" aria-selected="true">List of Employees</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="listofapp-tab" data-toggle="tab" href="#listofapp" role="tab" aria-controls="idnumb" aria-selected="false">List of Applicants</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active mt-3" id="listofemp" role="tab">
            <table class="table" id="tableEmployee">
                <thead  class="thead-default">
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Position</th>
                    <th>Employment Type</th>
                    <th style="width: 365px"></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button type="button" class="btn btn-info ml-2 mb-2"  data-toggle='modal' data-target='#addModal'>Add Employee Account</button>
        </div>
        
        <div class="tab-pane fade mt-3 pb-4" id="listofapp" role="tab">
            <table class="table" id="tablePending">
                <thead  class="thead-default">
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Score (out of 25)</th>
                    <th style="width: 330px"></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!--Modal Adding Employee-->
<div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog" id="dia" role="document">
        <!-- Modal content-->
        <form action="#" method ="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="text-white modal-title ">Add Employee Account</h4>
                <button class="text-secondary close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" name="id" id="EditID">
                <div class="row p-3">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="fName">First Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="fName" id="fName" placeholder="Enter First Name"  required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="mName">Middle Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="mName" id="mName" placeholder="Enter Middle Name" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="lName">Last Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="lName" id="lName" placeholder="Enter Last Name" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="address">Address</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="address" id="address" placeholder="Enter Full Address" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="ConNum">Contact No.</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="ConNum" name="ConNum" placeholder="Enter Contact Number" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="DoBirth">Date of Birth</label>
                            </div>
                            <div class="col-8">
                                <input type="Date" class="form-control" id="DoBirth" name="DoBirth" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="Pos">Position</label>
                            </div>
                            <div class="col-8 input-group">
                                <select id="Pos" class="custom-select" name="Pos" required>
                                    <option value="" selected disabled>== SELECT POSITION == </option>
                                    <option value="Accounting Head">Accounting Head</option>
                                    <option value="Accounting Staff">Accounting Staff</option>
                                    <option value="Executive Assistant">Executive Assistant</option>
                                    <option value="Office Staff">Office Staff</option>
                                    <option value="Senior Sales Supervisor">Senior Sales Supervisor</option>
                                    <option value="_other">Other (Define Custom)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-4"> </div>
                            <div class="col-8 input-group">
                                <input type="text" class="form-control" style="display: none" placeholder="Custom Employment Position" name="_otherPos" id="_otherPos">                                
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="EmpStatus">Employment Type</label>
                            </div>
                            <div class="col-8 input-group">
                                <select id="EmpStatus" class="custom-select" name="EmpStatus" required>
                                    <option value="" selected disabled>== SELECT EMPLOYMENT TYPE ==</option>
                                    <option value="Trainee">Trainee</option>
                                    <option value="probationary">Probationary</option>
                                    <option value="Fixed Period">Fixed Period</option>
                                    <option value="Regular">Regular</option>
                                    <option value="_other">Other (Define Custom)</option>
                                </select>

                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4"> </div>
                            <div class="col-8 input-group">
                                <input type="text" class="form-control" style="display: none" placeholder="Custom Employment Type" name="_otherEmpStatus" id="_otherEmpStatus">                                
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label for="username">Username</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="username">Password</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="Dhired">Date Hired</label>
                            </div>
                            <div class="col-8">
                                <input type="date" class="form-control" name="Dhired" id="Dhired" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="Dhired">Hired Duration</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="hired_duration" id="hired_duration" disabled>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="FatName">Father's Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="FatName" id="FatName" placeholder="Enter Father's Name" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="MotName">Mother's Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="MotName" placeholder="Enter Mother's Name" name="MotName" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="sss">SSS No.</label>
                            </div>
                            <div class="col-8">
                                <input  type="text" class="form-control"  id="sss" name="sssnum" placeholder="Enter SSS No.">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="philnum">Philhealth No.</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="phil" name="philnum" placeholder="Enter Philhealth No.">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="tinnum">TIN</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="tinum" name="tinnum" placeholder="Enter TIN">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="Pagibignum">Pag-ibig No.</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="pagibig" name="Pagibignum" placeholder="Enter PagIbig No.">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Finalize">
            </div>
        </form>

    </div>
</div>

<div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <form action="#" method="post" class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="text-white modal-title ">Confirmation</h4>
                <button class="close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input id="deleteID" type="hidden" name="id" value="">
                Are you sure to delete this employee and all of its data?
            </div>
            <div class="modal-footer">
                <input type="submit" name="delete" class="btn btn-info mt-2 float-right" value="Delete">
                <button data-dismiss="modal" class="btn btn-outline-info mt-2 float-right">Close</button>
            </div>
        </form>

    </div>
</div>

<!--Modal Attachments-->
<div class="modal fade" id="attachModal" role="dialog">
    <div class="modal-dialog clearfix">
        <!-- Modal content-->
        <div class="modal-content float-left" id="attachmentPreview">
            <div class="modal-body">
                <img id="previewImg" src="/img/7.jpg" alt="Preview Image" width="100%" height="100%" style="background: gray; ">
            </div>
        </div>
        <!-- Modal content-->
        <div class="modal-content float-left" id="attachmentList">
            <div class="modal-header">
                <h4 class="modal-title">List of Attachments</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="attachID">
                <table id="attachmentTable" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!--Modal Evaluate -->
<div class="modal fade" id="evaluateModal" role="dialog">
    <div class="modal-dialog clearfix">
        <!-- Modal content-->
        <div class="modal-content" id="evaluationPreview">
            <div class="modal-body">
                <div class="card-header mt-3">
                    <h5 align="center" >EMPLOYEE EVALUATION FORM</h5>
                </div>
                <form action="#" method="POST">
                    <input type="hidden" name="id" id="evaluateID">
                    <div class="card container">
                        <div class="row">
                            <div class="col-6 mt-4">
                                <h6>Present Employee Status:</h6>

                                <label for="trainee">
                                    <input type="radio" value="trainee" name="pes" id="trainee"  class="mr-2" required />
                                    Trainee
                                </label>
                                <label for="fixperiod">
                                    <input type="radio" value="fix_period" name="pes" id="fixperiod" class="mr-2" required/>
                                    Fix Period
                                </label>
                                <label for="probationary">
                                    <input type="radio" value="probationary" name="pes" id="probationary" class="mr-2" required />
                                    Probationary
                                </label>
                                <label for="regular">
                                    <input type="radio" value="regular" name="pes" id="regular" class="mr-2" required/>
                                    Regular
                                </label>
                            </div>

                            <div class="col-6 mt-4 mb-3">
                                <h6>Period of present evaluation:</h6>
                                <label for="1stquarter">
                                    <input type="radio" value="Q1" id="1stquarter" name="Quarter" class="mr-2" required/>
                                    1st Quarter
                                </label>
                                <label for="2ndquarter">
                                    <input type="radio" value="Q2" id="2ndquarter" name="Quarter" class="mr-2" required/>
                                    2nd Quarter
                                </label>
                                <label for="3rdquarter">
                                    <input type="radio" value="Q3" id="3rdquarter" name="Quarter" class="mr-2" required/>
                                    3rd Quarter
                                </label>
                                <label for="4thquarter">
                                    <input type="radio" value="Q4" id="4thquarter" name="Quarter" class="mr-2" required/>
                                    4th Quarter
                                </label>
                            </div>

                            <div class="col-6 mt-4">
                                <h6>Direction: Choose the number that corresponds to your rating.</h6>
                                <h6 class="ml-5 mt-3">Rating Scale:</h6>
                                <h6 class="ml-5">1 - Excellent</h6>
                                <h6 class="ml-5">2 - Satisfactory</h6>
                                <h6 class="ml-5">3 - Fair</h6>
                                <h6 class="ml-5">4 - Needs Improvement</h6>
                                <h6 class="ml-5">5 - Poor Performance</h6>
                            </div>

                        </div>
                        <h6 align="center">NOTE: PLEASE DO NOT LEAVE BLANK RATE.</h6>
                    </div>

                    <div class="card container">
                        <table class="table table-bordered mt-2">
                            <thead>
                            <tr>
                                <th>1. TIMELINESS</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>a. The extent to which the employee completes assigned task within the alloted time.</td>
                                <td><input type="radio" name="a1" id="a11" value="1" title="Choose 1"  required/></td>
                                <td><input type="radio" name="a1" id="a12" value="2" title="Choose 2"  required/></td>
                                <td><input type="radio" name="a1" id="a13" value="3" title="Choose 3"  required/></td>
                                <td><input type="radio" name="a1" id="a14" value="4" title="Choose 4"  required/></td>
                                <td><input type="radio" name="a1" id="a15" value="5" title="Choose 5"  required/></td>
                            </tr>

                            <tr>
                                <th scope="row">2. JUDGMENT</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                            </tr>


                            <tr>
                                <td>a. The extent to which the employee utilizes the job knowledge and sound reasoning to analyze situation, resolve prolbem and reach decision.</td>
                                <td><input type="radio" name="a2" id="a21" value="1" title="Choose 1"  required/></td>
                                <td><input type="radio" name="a2" id="a22" value="2" title="Choose 2"  required/></td>
                                <td><input type="radio" name="a2" id="a23" value="3" title="Choose 3"  required/></td>
                                <td><input type="radio" name="a2" id="a24" value="4" title="Choose 4"  required/></td>
                                <td><input type="radio" name="a2" id="a25" value="5" title="Choose 5"  required/></td>
                            </tr>

                            <tr>
                                <th scope="row">3. INITIATIVE</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                            </tr>

                            <tr>
                                <td>a. The extent to which the employee is self-motivated; takes appropraite action on work related issues without being prompted.</td>
                                <td><input type="radio" name="a3" id="a31" value="1" title="Choose 1"  required/></td>
                                <td><input type="radio" name="a3" id="a32" value="2" title="Choose 2"  required/></td>
                                <td><input type="radio" name="a3" id="a33" value="3" title="Choose 3"  required/></td>
                                <td><input type="radio" name="a3" id="a34" value="4" title="Choose 4"  required /></td>
                                <td><input type="radio" name="a3" id="a35" value="5" title="Choose 5"  required/></td>
                            </tr>

                            <tr>
                                <th scope="row">4. COMMUNICATION</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                            </tr>

                            <tr>
                                <td>a. The extent to which the employee communicates effectively and accurately with peers, supervisors and other business contacts. Considers writtend and verbal communication.</td>
                                <td><input type="radio" name="a4" id="a41" value="1" title="Choose 1"  required/></td>
                                <td><input type="radio" name="a4" id="a42" value="2" title="Choose 2"  required/></td>
                                <td><input type="radio" name="a4" id="a43" value="3" title="Choose 3"  required/></td>
                                <td><input type="radio" name="a4" id="a44" value="4" title="Choose 4"  required/></td>
                                <td><input type="radio" name="a4" id="a45" value="5" title="Choose 5"  required/></td>
                            </tr>

                            <tr>
                                <th scope="row">5. COOPERATION</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                            </tr>

                            <tr>
                                <td>a. The extent to which the employee cooperates and work harmoniously with peers, supervisors and other business contacts.</td>
                                <td><input type="radio" name="a5" id="a51" value="1" title="Choose 1"  required/></td>
                                <td><input type="radio" name="a5" id="a52" value="2" title="Choose 2"  required/></td>
                                <td><input type="radio" name="a5" id="a53" value="3" title="Choose 3"  required/></td>
                                <td><input type="radio" name="a5" id="a54" value="4" title="Choose 4"  required/></td>
                                <td><input type="radio" name="a5" id="a55" value="5" title="Choose 5"  required/></td>
                            </tr>

                            <tr>
                                <th scope="row">6. PUNCTUALITY AND ATTENDANCE</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                            </tr>

                            <tr>
                                <td>
                                    a.  At work on time.<br>
                                    b. Start and finishes according to approved schedule (punctual).<br>
                                    c. Calls to explain absence.<br>
                                    d. Observes generally agreed work break/meal periods.

                                </td>
                                <td>
                                    <input type="radio" name="a6" id="a61" value="1" title="Choose 1"  required/><br>
                                    <input type="radio" name="b6" id="b611" value="1" title="Choose 1"  required/><br>
                                    <input type="radio" name="c6" id="c6111" value="1" title="Choose 1"  required/><br>
                                    <input type="radio" name="d6" id="d61111" value="1" title="Choose 1"  required/>
                                </td>
                                <td>
                                    <input type="radio" name="a6" id="a52" value="2" title="Choose 2"  required/><br>
                                    <input type="radio" name="b6" id="b522" value="2" title="Choose 2"  required/><br>
                                    <input type="radio" name="c6" id="c5222" value="2" title="Choose 2"  required/><br>
                                    <input type="radio" name="d6" id="d52222" value="2" title="Choose 2"  required/>
                                </td>
                                <td>
                                    <input type="radio" name="a6" id="a53" value="3" title="Choose 3"  required/><br>
                                    <input type="radio" name="b6" id="b533" value="3" title="Choose 3"  required/><br>
                                    <input type="radio" name="c6" id="c5333" value="3" title="Choose 3"  required/><br>
                                    <input type="radio" name="d6" id="d53333" value="3" title="Choose 3"  required/>
                                </td>
                                <td>
                                    <input type="radio" name="a6" id="a54" value="4" title="Choose 4" required/><br>
                                    <input type="radio" name="b6" id="b544" value="4" title="Choose 4" required/><br>
                                    <input type="radio" name="c6" id="c5444" value="4" title="Choose 4" required/><br>
                                    <input type="radio" name="d6" id="d54444" value="4" title="Choose 4" required>
                                </td>
                                <td>
                                    <input type="radio" name="a6" id="a55" title="Choose 5" value="5" required/><br>
                                    <input type="radio" name="b6" id="b555" title="Choose 5" value="5" required/><br>
                                    <input type="radio" name="c6" id="c5555" title="Choose 5" value="5" required/><br>
                                    <input type="radio" name="d6" id="d55555" title="Choose 5" value="5" required />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h6 class="mt-2">Mandatory Comment/Recommendations: (Do not leave it blank)</h6>
                        <textarea rows="4" cols="50" class="mb-4 form-control" name="comment" placeholder="Enter Comment" required></textarea>

                        <div class="col-6">
                            <span>Evaluated by:</span>
                            <input class="float-right ml-2 mb-3 form-control" type="text" name="evaluator" placeholder="Enter Evaluators Fullname" size="35" required><br>
                            <input type="submit" name="evaluate" class="ml-2 mb-3 mt- btn btn-success" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal content-->
        <div class="modal-content" id="evaluationList">
            <div class="modal-header">
                <h4 class="modal-title">Evaluation History</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table id="EvaluationTable" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Rating</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>2018-7-19</td>
                        <td>2</td>
                        <td>
                            <a href="/imguploads/Koala.jpg" download class="btn btn-primary">Open</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- START APPLICANTS -->

<!-- Approval -->
<div class="modal fade" id="approvalModal" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <form method="POST" action="#" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 id="modal-title">Approval</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Provide username and password to proceed.</p>
                <label for="approveUsername" class="font-weight-bold">Username:</label>
                <input type="hidden" name="id" id="approveID">
                <input type="text" class="form-control" name="approveUsername" id="approveUsername" placeholder="Enter Username" required>
                <label for="approvePassword" class="font-weight-bold">Password:</label>
                <input type="text" class="form-control" name="approvePassword" id="approvePassword" placeholder="Enter Password" required>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success mt-2 float-right" value="Approve" name="approve">
                <button data-dismiss="modal" class="btn btn-outline-success mt-2 float-right">Close</button>
            </div>
        </form>

    </div>
</div>

<!-- Examination Modal -->
<div class="modal fade" id="examModal" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header font-weight-bold text-center">
                <h4 class="modal-title">Examination</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="pointer-events: none" >

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
            </div>
            <div class="modal-footer">
                <button onclick="printData('#examModal .modal-body')" class="btn btn-success mt-2 float-right">Print</button>
                <button data-dismiss="modal" class="btn btn-outline-danger mt-2 float-right">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <form action="#" method="post" class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="text-white modal-title ">Confirmation</h4>
                <button class="close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input id="declineID" type="hidden" name="id" value="">
                Are you sure to decline and remove the data of this person?
            </div>
            <div class="modal-footer">
                <input type="submit" name="delete" class="btn btn-info mt-2 float-right" value="Decline">
                <button data-dismiss="modal" class="btn btn-outline-info mt-2 float-right">Close</button>
            </div>
        </form>

    </div>
</div>

<script type="text/javascript">
    function printData($this){
        const $body = $("body");
        $body.css("background", "#fff");
        $body.html($($this).html());
        $(".modal").modal('hide');

        print();
        document.location.href = "admin.php";
    }

    function previewAttach(url){
        $('#previewImg').attr("src", "/imguploads/"+url);
    }
    $(document).ready(function () {
        // Initialize the dataTable functionality
        $('#tableEmployee').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url :"#",
                type: "POST",
                data: {
                    "getList": "active"
                }
            },
            "columnDefs": [{
                "orderable":false,
                "targets": -1,
                "createdCell": function(td, cellData, rowData, row, col){
                    $(td).html(
                        " <button data-id=\"" +cellData+ "\" type=\"button\" class=\"editBtn btn btn-outline-primary\"  data-toggle='modal' data-target='#addModal'>Edit</button>\n" +
                        "<button data-id=\"" +cellData+ "\" type=\"button\" class=\"evaluateBtn btn btn-outline-success\"  data-toggle='modal' data-target='#evaluateModal'>Evaluate</button>\n" +
                        "<button data-id=\"" +cellData+ "\" type=\"button\" class=\"attachBtn btn btn-info\"  data-toggle='modal' data-target='#attachModal'>Attachments</button>\n" +
                        "<button data-id=\"" +cellData+ "\" type=\"button\" class=\"deleteBtn btn btn-danger\"  data-toggle='modal' data-target='#deleteModal'>Delete</button>"
                    );
                }
            }],
            language: {
                paginate: {
                    previous: "&#171;",
                    next: "&#187;"
                }
            }
        });

        // Design the search bar and length selection bar
        const   searchBar = $("#tableEmployee_filter input"),
            lengthBar = $("#tableEmployee_length select");
        searchBar.attr("placeholder", "Search by Name");
        searchBar.addClass("form-control");

        lengthBar.closest("div").css({
            "float": "left"
        });
        lengthBar.addClass("form-control");

        $('#tablePending').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url :"#",
                type: "POST",
                data: {
                    "getList": "pending"
                }
            },
            "columnDefs": [{
                "orderable":false,
                "targets": -1,
                "createdCell": function(td, cellData, rowData, row, col){
                    $(td).html(
                        "<button data-id=\"" +cellData+ "\" type=\"button\" class=\"examBtn btn btn-success\" data-toggle='modal' data-target='#examModal'>View Examination</button> " +
                        "<button data-id=\"" +cellData+ "\" type=\"button\" class=\"approveBtn btn btn-primary\"  data-toggle='modal' data-target='#approvalModal'>Approve</button> " +
                        "<button data-id=\"" +cellData+ "\" type=\"button\" class=\"declineBtn btn btn-outline-primary\"  data-toggle='modal' data-target='#declineModal'>Decline</button> "
                    );
                }
            }],
            language: {
                paginate: {
                    previous: "&#171;",
                    next: "&#187;"
                }
            }
        });


        // Design the search bar and length selection bar
        const   searchBar2 = $("#tablePending_filter input"),
            lengthBar2 = $("#tablePending_length select");
        searchBar2.attr("placeholder", "Search by Name");
        searchBar2.addClass("form-control");

        lengthBar2.closest("div").css({
            "float": "left"
        });
        lengthBar2.addClass("form-control");

        // Event Handlers
        $("#tableEmployee").on("click", ".deleteBtn", function () {
            const $elID = $(this).attr("data-id");
            $("#deleteID").val($elID);
        });

        // Event Handlers
        $("#tableEmployee").on("click", ".evaluateBtn", function () {
            const $elID = $(this).attr("data-id");
            $("#evaluateID").val($elID);

            $("#EvaluationTable").DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "searching": false,
                "ajax": {
                    url :"#",
                    type: "POST",
                    data: {
                        "getList": "evaluation",
                        "id": $elID
                    }
                },
                "columnDefs": [{
                    "orderable":false,
                    "targets": -1,
                    "createdCell": function(td, cellData, rowData, row, col){
                        $(td).html("<a href='/evaluation.php?id=" +cellData+ "' target='_blank' class='btn btn-primary'>Open</a> ");
                    }
                }],
                language: {
                    paginate: {
                        previous: "&#171;",
                        next: "&#187;"
                    }
                }
            });

        });

        // Event Handlers

        $("#attachModal").on("hidden.bs.modal", function () {
            $("#previewImg").attr("src", "/img/7.jpg");
        });

        $("#tableEmployee").on("click", ".attachBtn", function () {
            const $elID = $(this).attr("data-id");
            $("#attachID").val($elID);

            $("#attachmentTable").DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "searching": false,
                "ajax": {
                    url :"#",
                    type: "POST",
                    data: {
                        "getList": "attachments",
                        "id": $elID
                    }
                },
                "columnDefs": [{
                    "orderable":false,
                    "targets": -1,
                    "createdCell": function(td, cellData, rowData, row, col){
                        $(td).html(
                            "<a href='/imguploads/" +cellData+ "' download class='btn btn-primary'>Download</a> " +
                            "<button onclick=\"previewAttach('" +cellData+ "')\" class='btn btn-success'>Preview</button>"
                        );
                    }
                }],
                language: {
                    paginate: {
                        previous: "&#171;",
                        next: "&#187;"
                    }
                }
            });
        });

        // Event Handlers
        $("#tablePending").on("click", ".declineBtn", function () {
            const $elID = $(this).attr("data-id");
            $("#declineID").val($elID);
        });

        // Event Handlers
        $("#tablePending").on("click", ".approveBtn", function () {
            const $elID = $(this).attr("data-id");
            $("#approveID").val($elID);
        });

        $("#addModal").on("hidden.bs.modal", function () {
            $(this).find(".modal-title").html("Add Employee Account:");

            $("#hired_duration").val("");
            $("#_otherEmpStatus").val("").hide();
            $("#_otherPos").val("").hide();

            $("input#EditID").val("");

            $("input#fName").val("");
            $("input#mName").val("");
            $("input#lName").val("");
            $("input#address").val("");
            $("input#ConNum").val("");
            $("input#DoBirth").val("");
            $("select#Pos").val("");
            $("select#EmpStatus").val("");

            $("input#username").val("");
            $("input#password").val("");
            $("input#Dhired").val("");
            $("input#FatName").val("");
            $("input#MotName").val("");
            $("input#sss").val("");
            $("input#phil").val("");
            $("input#tinum").val("");
            $("input#pagibig").val("");
        });

        $("#tableEmployee").on("click", ".editBtn", function () {
            const $elID = $(this).attr("data-id");
            const $addModal = $("#addModal");
            $addModal.find(".modal-title").html("Loading...");

            $("input#EditID").val($elID);

            // Get Branch Info Dynamically
            $.post( "#", {
                action: "getAccount",
                id: $elID
            })
            .done(function(data) {
                $addModal.find(".modal-title").html("Edit Employee: " + $elID);
                const accountInfo = JSON.parse(data);

                $("input#EditID").val($elID);

                $("input#fName").val(accountInfo["fname"]);
                $("input#mName").val(accountInfo["mname"]);
                $("input#lName").val(accountInfo["lname"]);
                $("input#address").val(accountInfo["address"]);
                $("input#ConNum").val(accountInfo["contact_num"]);
                $("input#DoBirth").val(accountInfo["birthdate"]);
               

                var exists = false;
                $('select#EmpStatus option').each(function(){
                    if (this.value == accountInfo["emp_type"]) {
                        exists = true;
                        return false;
                    }
                });
                if (!exists){
                    $("select#EmpStatus").val("_other");
                    $("#_otherEmpStatus").val(accountInfo['emp_type']).show();
                }else{
                    $("select#EmpStatus").val(accountInfo["emp_type"]);
                }

                var exists = false;
                $('select#Pos option').each(function(){
                    if (this.value == accountInfo["position"]) {
                        exists = true;
                        return false;
                    }
                });

                if (!exists){
                    $("select#Pos").val("_other");
                    $("#_otherPos").val(accountInfo["position"]).show();
                }else{  
                    $("select#Pos").val(accountInfo["position"]);
                }

                $("input#username").val(accountInfo["username"]);
                $("input#password").val(accountInfo["password"]);
                $("input#Dhired").val(accountInfo["date_hired"]);
                $("input#FatName").val(accountInfo["father_name"]);
                $("input#MotName").val(accountInfo["mother_name"]);
                $("input#sss").val(accountInfo["sss_no"]);
                $("input#phil").val(accountInfo["ph_no"]);
                $("input#tinum").val(accountInfo["tin"]);
                $("input#pagibig").val(accountInfo["pagibig"]);

                // Calculate the Hired Duration
                calculateHiredDuration($("#Dhired"));
            })
            .fail(function() {
                $addModal.find(".modal-title").html("Error");
                alert( "Error Retrieving Branch Data" );
            })

        });

        $("#tablePending").on("click", ".examBtn", function(){
            const $elID= $(this).attr("data-id");
            const $examModal = $("#examModal");
            $examModal.find(".modal-title").html("Loading...");

            // Get Branch Info Dynamically
            $.post( "#", {
                action: "getExam",
                id: $elID
            })
            .done(function(data) {
                $examModal.find(".modal-title").html("Examination");
                const examInfo = JSON.parse(data);

                // CheckBox
                for(var i=0; i < examInfo["C14"].length; i++){
                    $("input[name='C14[]'][value='" +examInfo["C14"][i]+ "']").attr("checked", "checked");
                }
                for(var e=0; e < examInfo["C15"].length; e++){
                    $("input[name='C15[]'][value='" +examInfo["C15"][e]+ "']").attr("checked", "checked");
                }

                // Radio Buttons
                $("input[name='C1'][value='" +examInfo["C1"]+ "']").attr("checked", "checked");
                $("input[name='C2'][value='" +examInfo["C2"]+ "']").attr("checked", "checked");
                $("input[name='C3'][value='" +examInfo["C3"]+ "']").attr("checked", "checked");
                $("input[name='C4'][value='" +examInfo["C4"]+ "']").attr("checked", "checked");
                $("input[name='C5'][value='" +examInfo["C5"]+ "']").attr("checked", "checked");
                $("input[name='C6'][value='" +examInfo["C6"]+ "']").attr("checked", "checked");
                $("input[name='C7'][value='" +examInfo["C7"]+ "']").attr("checked", "checked");
                $("input[name='C8'][value='" +examInfo["C8"]+ "']").attr("checked", "checked");
                $("input[name='C9'][value='" +examInfo["C9"]+ "']").attr("checked", "checked");
                $("input[name='C10'][value='" +examInfo["C10"]+ "']").attr("checked", "checked");
                $("input[name='C11'][value='" +examInfo["C11"]+ "']").attr("checked", "checked");
                $("input[name='C12'][value='" +examInfo["C12"]+ "']").attr("checked", "checked");
                $("input[name='C13'][value='" +examInfo["C13"]+ "']").attr("checked", "checked");
                $("input[name='C16'][value='" +examInfo["C16"]+ "']").attr("checked", "checked");
                $("input[name='C17'][value='" +examInfo["C17"]+ "']").attr("checked", "checked");
                $("input[name='C18'][value='" +examInfo["C18"]+ "']").attr("checked", "checked");
                $("input[name='C19a'][value='" +examInfo["C19a"]+ "']").attr("checked", "checked");
                $("input[name='C19b'][value='" +examInfo["C19b"]+ "']").attr("checked", "checked");
                $("input[name='C19c'][value='" +examInfo["C19c"]+ "']").attr("checked", "checked");
                $("input[name='C19d'][value='" +examInfo["C19d"]+ "']").attr("checked", "checked");
                $("input[name='C20a'][value='" +examInfo["C20a"]+ "']").attr("checked", "checked");
                $("input[name='C20b'][value='" +examInfo["C20b"]+ "']").attr("checked", "checked");
                $("input[name='C20c'][value='" +examInfo["C20c"]+ "']").attr("checked", "checked");

            })
            .fail(function() {
                $examModal.find(".modal-title").html("Error");
                alert( "Error Retrieving Branch Data" );
            })
        });

        //ADD EMP RESTRICTIONS
        function restriction(restrict){
            $("input[name="+restrict+"]").on({
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
        }
        restriction('ConNum');
        restriction('sssnum');
        restriction('philnum');
        restriction('tinnum');
        restriction('Pagibignum');


        // Custom Employee Type Value
        $("#EmpStatus").on("change", function(){
            if ($(this).val() == "_other"){
                $("#_otherEmpStatus").show();
            }else{
                $("#_otherEmpStatus").hide();
            }
        })
         // Custom Employee Position Value
        $("#Pos").on("change", function(){
            if ($(this).val() == "_other"){
                $("#_otherPos").show();
            }else{
                $("#_otherPos").hide();
            }
        })

        function calculateHiredDuration(selector){
            var $Dhired = new Date(selector.val());
            var curDay = new Date();                // Current Day
            
            // The date input should be valid.
            if ( !!$Dhired.valueOf() ) { 
                var $totalYears = (curDay.getFullYear() - $Dhired.getFullYear());
                var $totalMonths = (curDay.getMonth() - $Dhired.getMonth());

                $("#hired_duration").val($totalMonths+ " Month(s) and "+$totalYears+ " Year(s).");
            } else {
                 /* Invalid date */
                 selector.val("having problem to compute");
            }
        }

        // Autocompute Hired
        $("#Dhired").change(function(){
            calculateHiredDuration($(this))
        })

    });
</script>
</body>
</html>