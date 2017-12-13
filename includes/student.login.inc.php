<?php

// php file to perform a student login

session_start();

if (isset($_POST['submit'])) {
	
	include 'dbh.inc.php';

	$uid = mysqli_real_escape_string($conn, $_POST['studentId']);
	$first = mysqli_real_escape_string($conn, $_POST['firstName']);
    $last =mysqli_real_escape_string($conn, $_POST['lastName']);
	//Error handlers
	//Check if inputs are empty
	if (empty($uid) || empty($first)||empty($last)) {
		header("Location: ../index.php?slogin=empty1");
		exit();
	} else {
		$sql = "SELECT * FROM roster WHERE student_id='$uid' and first_name = '$first' and last_name = '$last' ";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			header("Location: ../index.php?slogin=error1");
			exit();
		} else {
			if ($row = mysqli_fetch_assoc($result)) {
				    $_SESSION['student_id'] = (int)$row['student_id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];  
					
					header("Location: ../pages/student/student.php");
                    
					exit();

			}
		}
	}
} else {
	header("Location: ../index.php?slogin=error3");
	exit();
}