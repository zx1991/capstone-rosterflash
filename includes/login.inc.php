<?php

// php file to perform a teacher login

session_start();

if (isset($_POST['submit'])) {
	
	include 'dbh.inc.php';

	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	//Error handlers
	//Check if inputs are empty
	if (empty($uid) || empty($pwd)) {
		header("Location: ../index.php?login=empty1");
		exit();
	} else {
		$sql = "SELECT * FROM accounts WHERE user_name='$uid' ";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			header("Location: ../index.php?login=error1");
			exit();
		} else {
			if ($row = mysqli_fetch_assoc($result)) {
				//De-hashing the password
                
				$hashedPwdCheck = (strtoupper(md5($pwd)) === $row['passwd']);
				if ($hashedPwdCheck == false) {
					header("Location: ../index.php?login=error2");
					exit();
				} elseif ($hashedPwdCheck == true) {
					//Log in the user here
					$_SESSION['u_id'] = $row['user_name'];
					
					header("Location: ../pages/teacher/attendance/StudentTable.php");
					exit();
				}
			}
		}
	}
} else {
	header("Location: ../index.php?login=error3");
	exit();
}