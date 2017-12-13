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
    
    <link rel="stylesheet" href="../../../css/popup.css" type="text/css">
    <link rel="stylesheet" href="../../../css/table.css" type="text/css">
   
    
   
    
    <meta charset="utf-8">
    

    
    
    
    <link rel="icon" href="../../../layui/images/face/65.gif" type="image/gif" >
</head>

<body>
        <header class="site-header">
            <div class="container site-header-inner">
                <!-- logo -->
                <div class="site-logo">ROSTER TRACKER</div>
                <!-- navigation bar -->
                <nav class="nav site-menu">
                   
                    <a href="../courseManage/Management.php" class="nav-link present" id="coursemLink">Manage Courses</a>
                    <a href="../attendance/TotalRatio.php" class="nav-link" id="AttendanceLink">Attendance</a>
                    <!--<a href="#" id=""></a>-->
                </nav>

                <div class="logout">
                    <a href="../../../includes/logout.inc.php" id="logOut">Log out</a>
                </div>
            </div>
        </header>
    
    <div class="site-main">
            <div class="container site-main-inner">
                <div class="sidebar">
                    
                    <a href="#"  id="myBtn">ADD COURSE</a>
                    <a href="#"  id="deletecourse">DELETE COURSE</a>
                    <a href="#"  id="addbtn">ADD STUDNETS</a>
                    
                    
                </div>
                
                <!-- Content shows here -->
                <div  class="content">
                    <div class="content-inner">
                        
                        <div  class="CourseManagement" >
                            
                             <form id= "radio">
                                 
                                 <?php    
   
                                include '../../../includes/dbh.inc.php';

   
                                $sql = "SELECT * FROM courses WHERE user_name= '".$_SESSION['u_id']."'";
   
                                $myresult = mysqli_query($conn, $sql);
   
                                $courseInfo = array();
                                 
    
                                while ($row_user = mysqli_fetch_assoc($myresult)){
       
                                    $userInfo[]=$row_user;
  
                                }
    

                                ?>
                                 
                                <?php
                                 
                                 if(isset($userInfo)){
                            
                                foreach($userInfo as $user) { ?>
                                 
                                 <input type="radio" name="CourseList" onclick="showTable(this.value);" value="<?= $user['course_id'] ?>"><?= $user['course_name'] ?><br>
                            
                             
                       
                                <?php
                        
                                                            }} ?>
                            
                                   
                                </form>
                        
                            
                                
                        </div>
                        
                        <div   class="Rostermanagement">
                            <p > <input onkeyup="filter('attendances', 'item', this.value)" placeholder="Seach..."><br/>
                            <button onclick="deleteStudents()">Delete Selected Students</button><br/>
                               </p>
                            
                            <table class = "table" id="attendances"></table>
                        </div>
                        
                        
                        <!-- The create course Modal -->
                        <div id="myModal" class="modal">

                            <!-- Modal content -->
                            <div class="modal-content">
                            <div class="modal-header">
                                <span id="close1" class="close">&times;</span>
                                <h2>Add Course</h2>
                            </div>
                                <div class="modal-body">
                                    <form method='post' action="../../../includes/createCourse.php">
                                    <input type='text' placeholder='Course ID' name='CourseId' required/>
                                    <input type='text' placeholder='Course Name' name='Coursename' required/>
                                    <input type='text' placeholder='Start Time' name='starttime' required/>
                                     <input type='text' placeholder='End Time' name='endtime' required/>
                                
                                    <input type='submit' name="submit" value='Create' />
                                </form>
    </div>
    <div class="modal-footer">
      <h3></h3>
    </div>
  </div>

</div>
                        
                                        <!-- The roster Modal -->
                        <div id="RosterModal" class="modal">

                            <!-- Modal content -->
                            <div class="modal-content">
                            <div class="modal-header">
                                <span class = "close" id="close2">&times;</span>
                                <h2>Add Students</h2>
                            </div>
                                <div class="modal-body">
                                     
                                    <form  enctype="multipart/form-data" method='post' action="../../../includes/Addstudents.php">
                                        <p>Select a Course:</p>
                                        <select class="styled-select" name="course" id="per1" >
                                            <option value = "">Courses</option>
                        
                        <?php
                            foreach($userInfo as $user) { ?>
                            <option value="<?= $user['course_id'] ?>"><?= $user['course_name'] ?></option>
                        <?php
                        } ?>
                        
                    </select>
                                       <p>upload roster file</p>
                                        <input name = "file" type="file"/>
                                        
                                        <hr/>
                                
                                    <input type='submit' name="submit" value='Add' />
                                </form>
    </div>
    <div class="modal-footer">
      <h3></h3>
    </div>
  </div>

</div>
                       
                        
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

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementById("close1");

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// Get the modal
var modal2 = document.getElementById('RosterModal');

// Get the button that opens the modal
var btn2 = document.getElementById("addbtn");

// Get the <span> element that closes the modal
var span2 = document.getElementById("close2");

// When the user clicks the button, open the modal 
btn2.onclick = function() {
    modal2.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
    modal2.style.display = "none";
}


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        modal2.style.display = "none";
    }
}
</script>

<?php
    
    if(isset($_GET['q'])){
    $q = $_GET['q'];
    }else{
        
        $q = '0';
    }
?>

<script type="text/javascript">
    
    var radio = document.getElementById("radio");
    
    var selectedIndex = parseInt("<?php echo $q; ?>");
    
    for(var i =0; i<radio.length; i++){
        if(i == selectedIndex -1){
            radio[i].checked = true;
            
            showTable(radio[i].value);
        }
        
    }
        
    
 function showTable(str) {
     
     
        
        
        
        var courseId;
        
        var radio = document.getElementById("radio");
      
        for(var i =0; i< radio.length; i++){
            if(radio[i].checked){
                var value = i+1;
                 document.getElementById('AttendanceLink').setAttribute('href', "../attendance/StudentTable.php?q="+ value);
     
                courseId= radio[i].value;
            }
        }
        
     
     document.getElementById('deletecourse').setAttribute('href', "../../../includes/deleteCourse.php?q="+ courseId);
     
 
     
    
    
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
				 '<th onclick="sortTable(3)">ID</th>';
                 
        
		s += th+"</tr></thead><tbody>";
		//table body
		for(var i=0; i<students.length; i++){
			var student = students[i];
            
			var tr = '<tr class="item"><td><input type="checkbox" name="name1" /></td><td >'+student.last_name+'</td>'+
					 "<td align='center'>"+student.first_name+"</td>"+
					 "<td align='center'>"+student.student_id+"</td>";
					 
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

<script>
    
     function deleteStudents(){
         
        var courseId="";
        
        var radio = document.getElementById("radio");
        
        for(var i =0; i< radio.length; i++){
            if(radio[i].checked){
                
                courseId= radio[i].value;
            }
            
        }
       var table = document.getElementById("attendances");
        
        var recievers = "";
        for (var i = 1, row; row = table.rows[i]; i++) {
            
            var checkbox = row.getElementsByTagName("input")[0].checked;
        
            if(checkbox){
                
                var delstudentId = row.cells[3].innerHTML ;
                
                if(delstudentId !=""){
                    
                    
                    recievers += delstudentId +";"
                }
               
            
            }
            
        }
        
        if(recievers == ""){
            
            alert("please select at least one student.");
            return;
            
        }
        
        document.location.href = "../../../includes/deleteStudents.php?q="+recievers+"&c="+courseId;
        
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
        
        
       if(!(n == 4|| n==5)) {
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

<script type="text/javascript">
    var elems = document.getElementById('deletecourse');
    var confirmIt = function (e) {
        
        var courseId= "";
        
        var radio = document.getElementById("radio");
        
        for(var i =0; i< radio.length; i++){
            if(radio[i].checked){
                
                courseId= radio[i].value;
            }
            
        }
        var coursename;
       if(courseId == null) {
           alert("Please select a course.");
           
           return;
       }
       
        if (!confirm('Are you sure?/n' + "Delete Course: " + courseId)) e.preventDefault();
    };
    
        elems.addEventListener('click', confirmIt, false);
   
</script>
