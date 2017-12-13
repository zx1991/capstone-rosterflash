<?php

session_start();


include 'includes/dbh.inc.php';

$q = mysqli_real_escape_string($conn,$_GET['q']);

$i = (int)$_SESSION['student_id'];



$sql="SELECT * FROM attendances inner join roster  on (attendances.course_id = roster.course_id) and (attendances.student_id = roster.student_id) WHERE roster.course_id =  '$q' and roster.student_id = '$i'  ORDER BY roster.last_name, roster.student_id, date";
$result = mysqli_query($conn,$sql);

print("[");
	$start = true;
	while($row = mysqli_fetch_array($result)){
		if($start){
			$start = false;
		}else{
			print(",");
		}
		print('{"last_name":"'.$row['last_name'].'",');
		print('"first_name":"'.$row['first_name'].'",');
		print('"student_id":"'.$row['student_id'].'",');
		print('"date":"'.$row['date'].'",');
		print('"time":"'.$row['time'].'",');
		print('"attendance":"'.$row['attendance'].'"');
		print('}');
	}
	print("]");



?>