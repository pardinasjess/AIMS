
<?php

include_once "class/MySqlLeaf.php";

@$id =  $_GET['id'];

if (isset($id)){
    $sql = "SELECT * FROM `evaldata` WHERE `eval_id`='$id' LIMIT 1";
    $query = mysqli_query(MySqlLeaf::getCon(), $sql);
    $arr = mysqli_fetch_array($query);
}else{
    header("location: /");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Printable Evaluation</title>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.formatter.js"></script>
    <script src="js/jquery.formatter.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script defer src="/js/fontawesome-all.js"></script>
</head>
<body>

<div class="card container" style="background-color:white;">
    <div class="card-header mt-3">
        <h5 align="center" >EMPLOYEE EVALUATION FORM</h5>
    </div>
    <div id="printable">
        <div class="card container">
            <div class="row">
                <div class="col-6 mt-4">
                    <h6>Present Employee Status:</h6>
                    <input type="radio" value="trainee" name="pes" id="trainee"  class="mr-2" required /><label for="trainee">Trainee</label>
                    <input type="radio" value="fix_period" name="pes" id="fixperiod" class="mr-2" required/><label for="fixperiod">Fix Period</label>
                    <input type="radio" value="probationary" name="pes" id="probationary" class="mr-2" required /><label for="probationary">Probationary</label>
                    <input type="radio" value="regular" name="pes" id="regular" class="mr-2" required/><label for="regular">Regular</label>
                </div>

                <div class="col-6 mt-4 mb-3">
                    <h6>Period of present evaluation:</h6>
                    <input type="radio" value="Q1" id="1stquarter" name="Quarter" class="mr-2" required/><label for="1stquarter">1st Quarter</label>
                    <input type="radio" value="Q2" id="2ndquarter" name="Quarter" class="mr-2" required/><label for="2ndquarter">2nd Quarter</label>
                    <input type="radio" value="Q3" id="3rdquarter" name="Quarter" class="mr-2" required/><label for="3rdquarter">3rd Quarter</label>
                    <input type="radio" value="Q4" id="4thquarter" name="Quarter" class="mr-2" required/><label for="4thquarter">4th Quarter</label>
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
                    <td><input type="radio" name="a1" id="a11" value="1" title="Choose 1" checked required/></td>
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
                    <td><input type="radio" name="a2" id="a21" value="1" title="Choose 1" checked required/></td>
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
                    <td><input type="radio" name="a3" id="a31" value="1" title="Choose 1" checked required/></td>
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
                    <td><input type="radio" name="a4" id="a41" value="1" title="Choose 1" checked required/></td>
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
                    <td><input type="radio" name="a5" id="a51" value="1" title="Choose 1" checked required/></td>
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
                        <input type="radio" name="a6" id="a61" value="1" title="Choose 1" checked required/><br>
                        <input type="radio" name="b6" id="b611" value="1" title="Choose 1" checked required/><br>
                        <input type="radio" name="c6" id="c6111" value="1" title="Choose 1" checked required/><br>
                        <input type="radio" name="d6" id="d61111" value="1" title="Choose 1" checked required/>
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

            </div>
        </div>
    </div>
    <div>
        <button class="btn btn-success" onclick="printData();">Print</button>
    </div>
</div>

<script type="text/javascript">

    function printData(){
        const body = $("body");
        const backup = body.html();

        body.html($("#printable").html());
        body.css("background", "#fff !important");

        print();
        body.html(backup);
    }

    $(document).ready(function () {

       $("input[name='pes'][value='<?php echo $arr['emp_status']; ?>']").attr("checked", "checked");
       $("input[name='Quarter'][value='<?php echo $arr['eval_period']; ?>']").attr("checked", "checked");
       $("textarea[name='comment']").val('<?php echo $arr['comment']; ?>');
       $("input[name='evaluator']").val('<?php echo $arr['evaluator']; ?>');

       $("input[name='a1'][value='<?php echo $arr['1a']; ?>']").attr("checked", "checked");
       $("input[name='a2'][value='<?php echo $arr['2a']; ?>']").attr("checked", "checked");
       $("input[name='a3'][value='<?php echo $arr['3a']; ?>']").attr("checked", "checked");
       $("input[name='a4'][value='<?php echo $arr['4a']; ?>']").attr("checked", "checked");
       $("input[name='a5'][value='<?php echo $arr['5a']; ?>']").attr("checked", "checked");
       $("input[name='a6'][value='<?php echo $arr['6a']; ?>']").attr("checked", "checked");
       $("input[name='b6'][value='<?php echo $arr['6b']; ?>']").attr("checked", "checked");
       $("input[name='c6'][value='<?php echo $arr['6c']; ?>']").attr("checked", "checked");
       $("input[name='d6'][value='<?php echo $arr['6d']; ?>']").attr("checked", "checked");
    });

</script>
</body>
</html>