<?php

//php file to create a new course

session_start();
if (isset($_POST['submit'])) {
	
	include_once 'dbh.inc.php';
    
    
    $i = mysqli_real_escape_string($conn,$_SESSION['u_id']);

	$CourseId = mysqli_real_escape_string($conn, $_POST['CourseId']);
	$Coursename = mysqli_real_escape_string($conn, $_POST['Coursename']);
	
	$starttime = mysqli_real_escape_string($conn, $_POST['starttime']);
	$endtime = mysqli_real_escape_string($conn, $_POST['endtime']);
    
    $sql = "INSERT INTO courses (user_name, course_id, course_name, start_time, end_time) VALUES ('$i', '$CourseId', '$Coursename', '$starttime','$endtime');";
    $result = mysqli_query($conn, $sql);
    if($result == FALSE){
                        header("Location: ../pages/teacher/courseManage/Management.php?course=createerror");
                    }else{
					   header("Location: ../pages/teacher/courseManage/Management.php?course=createsuccess");
                    }
					exit();
    
}else{
    
    header("Location: ../pages/teacher/courseManage/Management.php?course=createerror");
}