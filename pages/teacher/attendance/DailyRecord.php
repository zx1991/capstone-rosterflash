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
    
    <link rel="icon" href="../../../layui/images/face/65.gif" type="image/gif" >
    <script src="../../../js/Chart.min.js"></script>
    
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
                    <a href=""  id="RateLink">Total Ratio</a>
                    <a href="" class="present" id="DateLink">Daily record</a>
                    <a href="" id="TableLink">Detailed Table</a>
                </div>
                
                <!-- Content shows here -->                   
                <div class="content">
                    <div class="content-inner">
                        
                        <h3 id= "course title" align = "center"></h3>
                        <hr/>
                        <canvas id="myChart" height="400" ></canvas>
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
        
        $q = '';
    }
?>

<script type="text/javascript">
    var NumberofAttendance = 0;
    var NumberofAbsence = 0;
    
    
    var ctx ;
    var myChart;
    var config;
    
    window.onload = function() {
        
       
        ctx = document.getElementById("myChart").getContext('2d');                           
                                          
        config = {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{ 
                            data: [],
                            label: "",
                            borderColor: "#3e95cd",
                            fill: false
                        },{ 
        data:   [],
        label: "",
        borderColor: "#FF0000",
        fill: false
      }

                                  ]
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
                var response = JSON.parse(this.responseText);
               
               
                var newData =[] ;
                var newlables = [];
                
                
                
                   
                var sum = 0;
                for(var i =0; i< response.length; i++){
                    
                    newlables.push(response[i].date);
                   
                    newData.push(parseInt(response[i].Occurences));
                    sum += parseInt(response[i].Occurences);
                    
                }
                var avg = (sum/response.length).toPrecision(4);
                
                var newData1 = Array(response.length).fill(avg);
               
                
                config = {
  type: 'line',
  data: {
    labels: newlables,
    datasets: [{ 
        data:   newData,
        label: "Attentance by date",
        borderColor: "#3e95cd",
        fill: false
      },{ 
        data:   newData1,
        label: "Average",
        borderColor: "#FF0000",
        fill: false
      }
    ]
  },
    options:{
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
};
                
                //myChart.data.labels = newlables;
               // window.alert(response.length);
                //myChart.data.datasets[0].data = newdata;
               // window.alert(response.length);
               // myChart.destroy();
                myChart = new Chart(ctx, config);
                
                
                
             
  
            }
        }
        xmlhttp.open("GET", "../../../getNumberBydate.php?q="+str,true);
        xmlhttp.send();
         
        
}
};
  
 

</script>