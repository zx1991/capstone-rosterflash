<?php

// php file to delete a course

session_start();
	
$q = $_GET['q'];

include 'dbh.inc.php';

$course_id = mysqli_real_escape_string($conn, $q);

$i = mysqli_real_escape_string($conn,$_SESSION['u_id']);

$sql = "DELETE FROM courses where user_name = '$i' and course_id = '$course_id' ";

 $result = mysqli_query($conn, $sql);
    if($result == FALSE){
        
        $error = mysqli_error($conn);
                        header("Location: ../pages/teacher/courseManage/Management.php?course=deleteerror");
                    }else{
					   header("Location: ../pages/teacher/courseManage/Management.php?course=deletesuccess");
                    }
					exit();
    
