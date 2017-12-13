<?php
	session_start();
    if (!isset($_SESSION['student_id'])) {
        
        header("Location: ../../../index.php");
			exit();	
    }
?>

<html>
<head>
    <title>Student Roster Tracker</title>
    <link rel="stylesheet" href="../../css/normalize.css" type="text/css">
    <link rel="stylesheet" href="../../css/style.css" type="text/css">
    <link rel="stylesheet" href="../../css/table.css" type="text/css">
    <script src="../../../js/Chart.min.js"></script>
    
    
    <link rel="icon" href="../../../layui/images/face/65.gif" type="image/gif" >
   
    
    <meta charset="utf-8">
    
    
    
    
</head>

<body>
        <header class="site-header">
            <div class="container site-header-inner">
                <!-- logo -->
                <div class="site-logo">ROSTER TRACKER</div>
                <!-- navigation bar -->
                <nav class="nav site-menu">
                    
                    <!--<a href="#" id=""></a>-->
                </nav>

                <div class="logout">
                    <a href="../../includes/logout.inc.php" id="logOut">Log out</a>
                </div>
            </div>
        </header>
    
    
    
    <?php

    
    include '../../includes/dbh.inc.php';

    $sql = "SELECT * FROM roster inner join courses on roster.course_id = courses.course_id WHERE student_id= '".$_SESSION['student_id']."'";
    $myresult = mysqli_query($conn, $sql);
    $courseInfo = array();
    while ($row_user = mysqli_fetch_assoc($myresult)){
        $userInfo[]=$row_user;
    }
    
?>
         <div class="site-main">
            <div class="container site-main-inner">
                <div class="sidebar">
                    <select class="styled-select" name="per1" id="per1" onchange="showTable(this.value)">
                        
                        <option>Courses</option>
                        <?php
                            foreach($userInfo as $user) { ?>
                            <option value="<?= $user['course_id'] ?>"><?= $user['course_name'] ?></option>
                        <?php
                        } ?>
                        
                    </select>
                    
                </div>
                
                <!-- Content shows here -->                   
               
                        
                <div class="content">
                    <div class="content-inner">
                        <h3 id= "course title" align = "center"></h3>
                        <hr/>
                        <table class="table" id="mytable" ></table>
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


<script type="text/javascript">
    var NumberofAttendance = 0;
    var NumberofAbsence = 0;
    
      
   
    
 function showTable(str) {
     
     
    
     //var coursetitle = document.getElementById("course title");
     
     
     
     var link = document.getElementById('per1');
     var value = link.selectedIndex;
     
     var newTitle = link[value].innerHTML;
     if(newTitle != "Courses"){
       // coursetitle.innerHTML = link[value].innerHTML;
    }else{
        
       // coursetitle.innerHTML = "Please select a course.";
    }
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
                
                parseAttendances(response);
                
              
               
                
  
            }
        }
        
        var url="../../getStudentRecord.php";
        xmlhttp.open("GET", url+ "?q="+str,true);
        xmlhttp.send();
         
        
}
};
    
    function parseAttendances(attendances_str){
        
       
	var attendances = JSON.parse(attendances_str);
	var dateArray = new Array();
	var students = trimAttendances(attendances, dateArray);
	var table = document.getElementById("mytable");
        
        
        
	if(students == 0){
		table.innerHTML = "";
		return;
	}else{
		// refresh
		table.innerHTML = '';
        
        
        var s = '';
		// table header
		var th = '<thead><tr><th>Last Name<span class="sorting" style="display: block;"></span></th>'+
				 "<th>First Name</th>"+
				 "<th>ID</th>"+
                 "<th>Total Absence</th>"+
                 "<th>Total Attentance</th>";
        
		for(var i=0; i<dateArray.length; i++){
			th += "<th>"+dateArray[i]+"</th>";
		}
        
        
		s += th+"</tr></thead><tbody>";
		//table body
		for(var i=0; i<students.length; i++){
			var student = students[i];
            
			var tr = "<tr><td align='center'>"+student.last_name+"</td>"+
					 "<td align='center'>"+student.first_name+"</td>"+
					 "<td align='center'>"+student.student_id+"</td>"+
					 "<td align='center'>"+student.noa+"</td>"+
					 "<td align='center'>"+student.noat+"</td>";
            
			for(var j=0; j<dateArray.length; j++){
				var date = dateArray[j];
				if(student[date]){
					tr += "<td align='center'>"+student[date]+"</td>";
				}else{
					tr += "<td align='center'>&nbsp;</td>";
				}
			}
			tr += "</tr>";
			s += tr;
		}
        
        s += "</tbody>";
   
        table.innerHTML= s;
	}
}
    
    function trimAttendances(attendances, dateArray){
	var numOfDates = 0;
        
	if(attendances.length == 0){
        
		return 0;
	}else{
        
		var id = attendances[0].student_id;
		dateArray[0] = attendances[0].date;
		for(var i=1; i<attendances.length; i++){
			if(attendances[i].student_id !== id){
				break;
			}else{
				dateArray[i] = attendances[i].date;
			}
		}
		numOfDates = i;
	}
        
	var students = new Array();
	var counter = 0;
	for(var i=0; i<attendances.length; i+=numOfDates){
		var student = {};
        var absencecount = 0;
        var attendancecount =0;
		student.last_name = attendances[i].last_name;
		student.first_name = attendances[i].first_name;
		student.student_id = attendances[i].student_id;
        
		for(var j=0; j<numOfDates; j++){
			var date = dateArray[j];
			if(attendances[i+j].attendance === "1"){
				var time = attendances[i+j].time.substr(0,5);
				student[date] = time;
                attendancecount++;
                
			}else{
                absencecount++;
				student[date] = 0;
			}
		}
        
        student.noa = absencecount;
        student.noat = attendancecount;
       
		students[counter++] = student;
        
	}
        
	return students;
}

    

</script>