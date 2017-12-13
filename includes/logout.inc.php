<?php

    // php file to perform a log out

	session_start();
	session_unset();
	session_destroy();
	header("Location: ../index.php");
	exit();
