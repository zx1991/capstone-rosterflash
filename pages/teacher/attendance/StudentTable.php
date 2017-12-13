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
    <link rel="stylesheet" href="../../../css/table.css" type="text/css">
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
                    <select class="styled-select" name="per1" id="per1" onchange="showTable(this.value)">
                        
                        <option>Courses</option>
                        <?php
                            foreach($userInfo as $user) { ?>
                            <option value="<?= $user['course_id'] ?>"><?= $user['course_name'] ?></option>
                        <?php
                        } ?>
                        
                    </select>
                    <a href=""  id="RateLink">Total Ratio</a>
                    <a href="" id="DateLink">Daily record</a>
                    <a href="" class="present" id="TableLink">Detailed Table</a>
                </div>
                <!-- Content shows here -->
                <div class="content">
                    <div class="content-inner">
                        
                        
                        
                        
                        
                        
                        
                        
                        <h3 id= "course title" align = "center"></h3>
                        <hr/>
                        
                        <input onkeyup="filter('attendances', 'item', this.value)" placeholder="Seach...">
                        <button onclick="sendEmail()">Alert Selected Students</button>
                        <p></p>
                        <table  class = "table" id="attendances"></table>
                        
                        
                        
                        
                        
                        
                        
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

<script type="text/javascript">
    var NumberofAttendance = 0;
    var NumberofAbsence = 0;
    
      
   document.getElementById('per1').selectedIndex = parseInt("<?php echo $q; ?>");
    
    
   showTable(document.getElementById('per1').options[parseInt("<?php echo $q; ?>")].value);
    
    
 function showTable(str) {
     
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
                var response = this.responseText;
                
                parseAttendances(response);
                
              
               
                
  
            }
        }
        
        var url="../../../getStudentList.php";
        xmlhttp.open("GET", url+ "?q="+str,true);
        xmlhttp.send();
         
        
}
};
    
    function parseAttendances(attendances_str){
	var attendances = JSON.parse(attendances_str);
	var dateArray = new Array();
	var students = trimAttendances(attendances, dateArray);
	var table = document.getElementById("attendances");
        
	if(students == 0){
		table.innerHTML = "";
		return;
	}else{
		// refresh
		table.innerHTML = "";
		// table header
		      var s = '';
		// table header
		var th = '<thead><tr><th onclick="sortTable(0)">Select </th><th onclick="sortTable(1)">Last Name<span class="sorting" style="display: block;"></span></th>'+
				 '<th onclick="sortTable(2)">First Name</th>'+
				 '<th onclick="sortTable(3)">ID</th>'+
                 '<th onclick="sortTable(4)">Email</th>'+
                 '<th onclick="sortTable(5)">Total Absence</th>'+
                 '<th onclick="sortTable(6)">Total Attentance</th>';
        
		for(var i=0; i<dateArray.length; i++){
			th += '<th onclick="sortTable('+  (i+7).toString() +')">'+dateArray[i]+'</th>';
		}
        
        
		s += th+"</tr></thead><tbody>";
		//table body
		for(var i=0; i<students.length; i++){
			var student = students[i];
            
			var tr = '<tr class="item"><td><input type="checkbox" id="checkbox" /></td><td >'+student.last_name+'</td>'+
					 "<td align='center'>"+student.first_name+"</td>"+
					 "<td align='center'>"+student.student_id+"</td>"+
                    "<td align='center'><a href = 'mailto:"+student.email+"?Subject=Attendance%20Alert'>"+ student.email+"</td>"+
                
					 "<td align='center'> "+student.noa+"</td>"+
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
        student.email = attendances[i].email;
		for(var j=0; j<numOfDates; j++){
			var date = dateArray[j];
			if(attendances[i+j].attendance === "1"){
				var time = attendances[i+j].time.substr(0,5);
				student[date] = time;
                attendancecount++;
                
			}else if(attendances[i+j].attendance === "0"){
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

<script>
    
    function sendEmail(){
       var table = document.getElementById("attendances");
        
        var recievers = "";
        for (var i = 1, row; row = table.rows[i]; i++) {
            
            var checkbox = row.getElementsByTagName("input")[0].checked;
        
            if(checkbox){
                
                var mail = row.cells[4].getElementsByTagName("A")[0].innerHTML ;
                
                if(mail !=""){
                    
                    
                    recievers += mail +";"
                }
               
            
            }
            
        }
        
        if(recievers == ""){
            
            alert("please select at least one student.");
            return;
            
        }
        
        var coursetitle = document.getElementById("course title");
     
     
     
     var link = document.getElementById('per1');
     var value = link.selectedIndex;
     
     var CourseName = link[value].innerHTML;
        var CourseId = link[value].value;
        document.location.href = "mailto:" +recievers+"?subject=Attendance%20Alert"+"&body=Dear%20Student,%0A%0A%20%20%20%20This is a notification to inform you that it has showed that you have several absences in the class: "+ CourseId +" "+ CourseName +".%0A%0AThanks. ";
        
    }
    
    
    
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("attendances");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc"; 
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
        
        
       if(!(n == 5|| n==6)) {
        if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }}else{
      if (dir == "asc") {
        if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++; 
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
    
   function  filter(id, sel, filter) {
       
  console.log(filter);
  var table, hit;
  table = document.getElementById(id);
   for (var i = 1, row; row = table.rows[i]; i++) {
   //iterate through rows
   //rows would be accessed using the "row" variable assigned in the for loop
   for (var j = 0, col; col = row.cells[j]; j++) {
       
     if (col.innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
         
          row.style.display = "";
         break;
     }  
       
       row.style.display = "none";
   }  
   
      
    }
  
}
</script>