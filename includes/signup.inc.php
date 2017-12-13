<?php


//php file to create a new user

if (isset($_POST['submit'])) {
	
	include_once 'dbh.inc.php';

	$first = mysqli_real_escape_string($conn, $_POST['first']);
	$last = mysqli_real_escape_string($conn, $_POST['last']);
	
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	//Error handlers
	//Check for empty fields
	if (empty($first) || empty($last)  || empty($uid) || empty($pwd)) {
		header("Location: ../index.php?signup=empty");
		exit();
	} else {
		//Check if input characters are valid
		if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)) {
			header("Location: ../index.php?signup=invalid");
			exit();
		} else {
			//Check if email is valid
			
		
				$sql = "SELECT * FROM accounts WHERE user_name='$uid'";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);

				if ($resultCheck > 0) {
					header("Location: ../index.php?signup=usertaken");
					exit();
				} else {
					//Hashing the password
					$hashedPwd = strtoupper(md5($pwd));
					//Insert the user into the database
					$sql = "INSERT INTO accounts (first_name, last_name, user_name, passwd) VALUES ('$first', '$last', '$uid', '$hashedPwd');";
					$result = mysqli_query($conn, $sql);
                    if($result == FALSE){
                        header("Location: ../index.php?signup=failed");
                    }else{
					   header("Location: ../index.php?signup=success");
                    }
					exit();
				}
			
		}
	}

} else {
	header("Location: ../index.php?");
	exit();
}