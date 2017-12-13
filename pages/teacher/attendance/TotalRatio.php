<?php
	session_start();
    if (!isset($_SESSION['u_id'])) {
        
        header("Location: ../../../index.php");
			exit();	
    }
?>

<html>
<head>
    <title>Student Roster Tracker</title>
    <link rel="stylesheet" href="../../../css/normalize.css" type="text/css">
    <link rel="stylesheet" href="../../../css/style.css" type="text/css">
    <script src="../../../js/Chart.min.js"></script>
    
    <link rel="icon" href="../../../layui/images/face/65.gif" type="image/gif" >
    
</head>

<body>
        <header class="site-header">
            <div class="container site-header-inner">
                <!-- logo -->
                <div class="site-logo">ROSTER TRACKER</div>
                <!-- navigation bar -->
                <nav class="nav site-menu">
              
                    <a href="../courseManage/Management.php" class="nav-link" id="coursemLink">Manage Courses</a>
                    <a href="TotalRatio.php" class="nav-link present" id="AttendanceLink">Attendance</a>
                    <!--<a href="#" id=""></a>-->
                    <!--<a href="#" id=""></a>-->
                </nav>

                <div class="logout">
                    <a href="../../../includes/logout.inc.php" id="logOut">Log out</a>
                </div>
            </div>
        </header>

    
    <?php

    
    include '../../../includes/dbh.inc.php';

    $sql = "SELECT * FROM courses WHERE user_name= '".$_SESSION['u_id']."'";
    $myresult = mysqli_query($conn, $sql);
    $courseInfo = array();
    while ($row_user = mysqli_fetch_assoc($myresult)){
        $userInfo[]=$row_user;
    }
    
?>
    
        <div class="site-main">
            <div class="container site-main-inner">
                <div class="sidebar">
                    <select class="styled-select" name="per1" id="per1" onchange="showChart(this.value)">
                        
                        <option>Courses</option>
                        <?php
                            foreach($userInfo as $user) { ?>
                            <option value="<?= $user['course_id'] ?>"><?= $user['course_name'] ?></option>
                        <?php
                        } ?>
                        
                    </select>
                    <a href="" class="present" id="RateLink">Total Ratio</a>
                    <a href="" id="DateLink">Daily record</a>
                    <a href="" id="TableLink">Detailed Table</a>
                </div>
                <!-- Content shows here -->
                <div class="content">
                    <div class="content-inner">
                        
                        <h3 id= "course title" align = "center"></h3>
                        <hr/>
                        <canvas id="myChart" ></canvas>
                    </div>
                </div>
            </div>
        </div>

        <footer class="site-footer">
            <div class="container site-footer-inner">

            </div>
        </footer>
</body>
</html>

<?php
    
    if(isset($_GET['q'])){
    $q = $_GET['q'];
    }else{
        
        $q = '0';
    }
?>

<!-- Script to load chart-->
<script type="text/javascript">
    var NumberofAttendance = 0;
    var NumberofAbsence = 0;
    
    
    var ctx ;
    var myChart;
    var config;
    
    window.onload = function() {
        
       
        ctx = document.getElementById("myChart").getContext('2d');
        
        config = {
            type: 'pie',
            data: {
                labels: ["Attendance", "Absence"],
                datasets: [{
                    backgroundColor: [
                        "#2ecc71",
                        "#3498db",
        
                    ],
                    data: [NumberofAttendance, NumberofAbsence]
                }]
            }
        };
        myChart = new Chart(ctx, config);
    };
    
   
    document.getElementById('per1').selectedIndex = parseInt("<?php echo $q; ?>");
    
    
    showChart(document.getElementById('per1').options[parseInt("<?php echo $q; ?>")].value);
 
    
 function showChart(str) {
     
     
     var coursetitle = document.getElementById("course title");
     
     
     
     var link = document.getElementById('per1');
     var value = link.selectedIndex;
     
     var newTitle = link[value].innerHTML;
     if(newTitle != "Courses"){
        coursetitle.innerHTML = link[value].innerHTML;
    }else{
        
        coursetitle.innerHTML = "Please select a course.";
    }
     var link = document.getElementById('per1');
     var value = link.selectedIndex;
     
     document.getElementById('RateLink').setAttribute('href', "TotalRatio.php?q="+ value);
     document.getElementById('DateLink').setAttribute('href', "DailyRecord.php?q="+ value);
     document.getElementById('TableLink').setAttribute('href', "StudentTable.php?q="+ value);
    document.getElementById('coursemLink').setAttribute('href', "../courseManage/Management.php?q="+ value);
     
     /*
     
     var link = document.getElementById('per1');
     var value = link.options[link.selectedIndex].value;
     
     if((value == 'Null')||("<?php echo $q; ?>" != '')){
         document.getElementById('RateLink').setAttribute('href', "info.php?q="+ "<?php echo $q; ?>");
     }else{
         document.getElementById('RateLink').setAttribute('href', "info.php?q="+ value);
     }
     
      var link = document.getElementById('per1');
     var value = link.options[link.selectedIndex].value;
     
    if((value == 'Null')||("<?php echo $q; ?>" != '')){
         document.getElementById('DateLink').setAttribute('href', "ReportBydate.php?q="+ "<?php echo $q; ?>");
     }else{
         document.getElementById('DateLink').setAttribute('href', "ReportBydate.php?q="+ value);
     }
     
     
      var link = document.getElementById('per1');
     var value = link.options[link.selectedIndex].value;
     
   if((value == 'Null')||("<?php echo $q; ?>" != '')){
         document.getElementById('TableLink').setAttribute('href', "StudentTable.php?q="+ "<?php echo $q; ?>");
     }else{
         document.getElementById('TableLink').setAttribute('href', "StudentTable.php?q="+ value);
     }
     
     */
	if (str == "") {
        
        return;} 
     else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText;
               
                var Numberlist = response.split(',');    
                NumberofAttendance = parseInt(Numberlist[0]);
                NumberofAbsence = parseInt(Numberlist[1]);
                myChart.data.datasets[0].data[0] = NumberofAttendance;
                myChart.data.datasets[0].data[1] = NumberofAbsence;
                myChart.update();
                
  
            }
        }
        xmlhttp.open("GET", "../../../getTotalNumber.php?q="+str,true);
        xmlhttp.send();
         
        
     }
};
    

	</script>