<?php
include_once "class/EnlistExam.php";
include_once "class/MySqlLeaf.php";
include_once "class/AccountHandler.php";

if(!AccountHandler::isLogin()){
	header("location: /");
	exit;
}else{
	if (AccountHandler::getAccountType() !== "admin"){
		header("location: /");
		exit;
	}
}

if (isset($_POST["code"])){
	$generatedCode = md5(time());
	$sql = "INSERT INTO `codes`(`code`) VALUES ('$generatedCode')";
	mysqli_query(MySqlLeaf::getCon(), $sql);
	header("location: /code.php");
	exit;
}
@ $getList = $_POST["getList"];

if (isset($getList)){
	$requestData = $_POST;

	if ($getList == "code"){
		// datatable column index  => database column name
		$columns = array(
			0 => 'id',
			1 => 'code',
			2 => 'status'
		);

		// getting total number records without any search
		$query=mysqli_query(MySqlLeaf::getCon(),
			"SELECT `id` FROM `codes` WHERE 1"
		);
		$totalData = mysqli_num_rows($query);

		$sql = "SELECT `id`, `code`, `status` FROM `codes` WHERE 1";

		// Getting records as per search parameters
		if( !empty($requestData['search']['value']) )
			$sql.=" AND code LIKE '".$requestData['search']['value']."%'";

		$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
		$sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
		$query = mysqli_query(MySqlLeaf::getCon(), $sql);

		$data = array();

		while( $row=mysqli_fetch_array($query) ) {  // preparing an array
			$nestedData=array();

			$nestedData[] = $row["id"];
			$nestedData[] = $row["code"];
			$nestedData[] = $row["status"];
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

// Trigger Delete
if (isset($_POST["delete"])){
	$id = $_POST["id"];
	$sql = "DELETE FROM `codes` WHERE `id`='$id'";
	mysqli_query(MySqlLeaf::getCon(), $sql);

	header("location: /code.php");
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Administrator | Examination Codes</title>

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
			<li class="nav-item ">
				<a class="nav-link" href="admin.php">View List<span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item active">
				<a class="nav-link" href="#">Examination Codes<span class="sr-only">(current)</span></a>
			</li>
			<li class="float-right">
				<a href="logout.php" class="btn  btn-outline-danger">Sign out</a>
			</li>
		</ul>
	</div>
</nav>

<div class="card container-fluid p-3">
	<h3>List of Codes</h3>
	<table class="table" id="tableCode">
		<thead  class="thead-default">
		<tr>
			<th>#</th>
			<th>Code</th>
			<th>Status</th>
			<th style="width: 110px"></th>
		</tr>
		</thead>
		<tbody></tbody>
	</table>

	<form action="#" method="post">
		<input type="submit" name="code" class="btn btn-info ml-2 mb-2" value="Generate New Code" />
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
				Are you sure to delete this code?
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
        // Initialize the dataTable functionality
        $('#tableCode').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url :"#",
                type: "POST",
                data: {
                    "getList": "code"
                }
            },
            "columnDefs": [{
                "orderable":false,
                "targets": -1,
                "createdCell": function(td, cellData, rowData, row, col){
                    $(td).html(
                        "<button data-id=\"" +cellData+ "\" data-toggle='modal' data-target='#deleteModal' type=\"button\" class=\"deleteBtn btn btn-danger\"  data-toggle='modal' data-target='#deleteModal'>Delete Code</button>"
                    );
                }
            },{
                "orderable":false,
                "targets": -2,
                "createdCell": function(td, cellData, rowData, row, col){
                    switch (cellData){
	                    case "0":
                            $(td).html("Unused");
                            break;
	                    case "1":
                            $(td).html("Used");
                            break;
                    }
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
        const   searchBar = $("#tableCode_filter input"),
            lengthBar = $("#tableCode_length select");
        searchBar.attr("placeholder", "Search by Code");
        searchBar.addClass("form-control");

        lengthBar.closest("div").css({
            "float": "left"
        });
        lengthBar.addClass("form-control");

        // When the delete btn is clicked
        $("#tableCode").on("click", ".deleteBtn", function () {
            const $elID = $(this).attr("data-id");
            $("#deleteID").val($elID);
        })
    })
</script>
</body>
</html>