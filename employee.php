<?php
include_once "class/EnlistExam.php";
include_once "class/MySqlLeaf.php";
include_once "class/AccountHandler.php";

if(!AccountHandler::isLogin()){
	header("location: /");
	exit;
}else{
	if (AccountHandler::getAccountType() !== "employee"){
		header("location: /");
		exit;
	}
}

if (isset($_POST["upload"])){
    if (isset($_FILES['image'])){
	    // Get image name
	    $image = $_FILES['image']['name'];
	    $newName = time().$image;
	    // Get text
	    $image_text = mysqli_real_escape_string(MySqlLeaf::getCon(), $_POST['description']);

	    // image file directory
	    $target = "imguploads/".basename($newName);

	    $sql = "INSERT INTO `attachments`(`account_id`, `description`, `photo`, `date_uploaded`) 
                              VALUES ('" .AccountHandler::getAccountId(). "','$image_text','$newName',now())";

	    // execute query
	    mysqli_query(MySqlLeaf::getCon(), $sql);

	    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    if (isset($_FILES["profile"])){
	    // Get image name
	    $image = $_FILES['profile']['name'];
	    $newName = time().$image;

	    // image file directory
	    $target = "imguploads/".basename($newName);

	    $sql = "UPDATE `accounts` SET `photo`='$newName' WHERE `id`='" .AccountHandler::getAccountId(). "'";
	    // execute query
	    mysqli_query(MySqlLeaf::getCon(), $sql);

	    move_uploaded_file($_FILES['profile']['tmp_name'], $target);
    }

	header("location: /employee.php");
    exit;
}

// Trigger Delete
if (isset($_POST["delete"])){
    $id = $_POST["id"];
    $sql = "DELETE FROM `attachments` WHERE `id`='$id'";
    mysqli_query(MySqlLeaf::getCon(), $sql);

    header("location: /employee.php");
	exit;
}

// Get Profile Information
$profile = mysqli_query(MySqlLeaf::getCon(),
    "SELECT * FROM `accounts` WHERE `id`='" .AccountHandler::getAccountId(). "'");
$profileInfo = mysqli_fetch_array($profile);

$images = mysqli_query(MySqlLeaf::getCon(),
    "SELECT `id`,`description`, `photo`, `date_uploaded` FROM `attachments` WHERE  `account_id`='" .AccountHandler::getAccountId(). "'  ORDER BY `id` DESC");

?>
<!DOCTYPE html>
<html>
<head>
    <title> <?php echo AccountHandler::getUsername() ?> | Profile</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/employee.css">

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.formatter.js"></script>
    <script src="js/jquery.formatter.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="/js/fontawesome-all.js"></script>
    <script src="/js/parallax.min.js"></script>
</head>
<body>

<nav class="navbar p-3 navbar-expand-lg navbar-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ">
            <li class="nav-item active">
                <a class="nav-link" href="#">Profile<span class="sr-only">(current)</span></a>
            </li>
            <li class="float-right">
                <a class="btn btn-outline-danger" href="logout.php">Signout</a>
            </li>
        </ul>
    </div>
</nav>

<!--Employee detailes-->
<div class="jumbotron container mb-0 bg-transparent" data-parallax="scroll" data-image-src="img/bg.jpg" style="height: 450px;"></div>

<div class="jumbotron container p-0 pt-3" style="border-top-left-radius: 0; border-top-right-radius: 0; height: 90px;">
    <div class="card project container float-left pl-0 pr-0 ml-3" style="width: 200px; height: 200px; margin-top: -130px;">
        <div class="project__card">
            <span class="helper"></span>
            <img src="<?php echo empty($profileInfo["photo"])? "/img/13.png": "/imguploads/".$profileInfo["photo"]; ?>" height="100%" width="100%" alt="PROFILE PICTURE">
        </div>

        <div class="project__detail">
            <h4 class="project__category text-white">
                <a href="javascript: void(0)" data-toggle="modal" data-target="#uploadModal">Upload Profile</a>
            </h4>
        </div>
    </div>
    <h3 class="ml-3 mt-3" style="display: inline">
		<?php echo ucwords($profileInfo["fname"] ." ". $profileInfo["mname"] ." ". $profileInfo["lname"]) ?>
    </h3>
</div>

<div class="container">
    <div class="row">
        <div class="col-5">
            <div class="mt-3  mb-3 card container">
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Address</span>
					<?php echo ucfirst($profileInfo["address"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Contact Number</span>
					<?php echo ucfirst($profileInfo["contact_num"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Date of Birth</span>
					<?php echo ucfirst($profileInfo["birthdate"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Position</span>
					<?php echo ucfirst($profileInfo["position"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Employment Type</span>
					<?php echo ucfirst($profileInfo["emp_type"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Date Hired</span>
					<?php echo ucfirst($profileInfo["date_hired"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Fathers Name</span>
					<?php echo ucfirst($profileInfo["father_name"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Mothers Name</span>
					<?php echo ucfirst($profileInfo["mother_name"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">SSS number</span>
					<?php echo ucfirst($profileInfo["sss_no"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Philhealth number</span>
					<?php echo ucfirst($profileInfo["ph_no"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">TIN</span>
					<?php echo ucfirst($profileInfo["tin"]); ?>
                </h6>
                <h6 class="mt-3 mb-3">
                    <span class="text-secondary">Pag-ibig number</span>
					<?php echo ucfirst($profileInfo["pagibig"]); ?>
                </h6>
            </div>
        </div>
        <!--upload buttons-->
        <div class="col-7">
            <form action="#" method="post" enctype="multipart/form-data" class="mb-3 mt-3 p-3 card container" >
                <h4 class="text-secondary mt-1 mb-0 font-weight-bold" align="center">UPLOAD SCANNED IMAGE</h4>
                <div class="row mb-1 mt-4">
                    <div class="col-3 font-weight-bold"> Description </div>
                    <div class="col-9">
                        <input class="form-control" type="text" name="description" placeholder="Enter Short Description" required>
                    </div>
                </div>
                <input type="file" name="image" accept="image/*" class="form-control-file" required/>
                <div class="mt-1">
                    <input type="submit" name="upload" class="btn btn-success" value="Start Image Upload"/>
                </div>
            </form>
	        <?php
	        while ($row = mysqli_fetch_array($images)) {
	            echo "<div class='card mb-3 project'>";
	            echo "    <div class='project__card'>";
		        echo "        <a href='#' class='project__image'>";
		        echo "          <img src='/imguploads/".$row['photo']."' >";
		        echo "        </a>";
		        echo "    </div>";
		        echo "    <div class='project__detail'>";
		        echo "        <h3 class='project__title text-white'>".$row['description']."</h3>";
		        echo "        <small class='project__category mt-3'>";
		        echo "            <a href='/imguploads/".$row['photo']."' download class='btn btn-sm btn-outline-primary mr-1'>Download</a>";
		        echo "            <button data-id='".$row['id']."' data-toggle='modal' data-target='#deleteModal' class='deleteBtn btn btn-sm btn-outline-danger'>Delete</button>";
		        echo "        </small>";
		        echo "    </div>";
		        echo "</div>";
	        }
	        ?>
        </div>
    </div>
</div>

<!--Modal yes or no-->
<div class="modal fade" id="uploadModal" role="dialog">
    <form action="#" enctype="multipart/form-data"  method="post" class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header font-weight-bold text-center">
                Upload Profile Picture
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="file" accept="image/*" name="profile" required>
            </div>
            <div class="modal-footer">
                <input type="submit" name="upload" class="btn btn-success mt-2 float-right" value="Upload Image"/>
            </div>
        </div>

    </form>
</div>

<!--Modal yes or no-->
<div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog" role="document">

        <!-- Modal content-->
        <form action="#" method="post" class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="text-white">Confirmation</h4>
                <button class="close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input id="deleteID" type="hidden" name="id" value="">
                Are you sure to delete this image?
            </div>
            <div class="modal-footer">
                <input type="submit" name="delete" class="btn btn-info mt-2 float-right" value="Delete">
                <button data-dismiss="modal" class="btn btn-outline-info mt-2 float-right">Close</button>
            </div>
        </form>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        // When the delete btn is clicked
        $(".deleteBtn").on("click", function () {
            const $elID = $(this).attr("data-id");
            $("#deleteID").val($elID);
        })
    })
</script>
</body>
</html>