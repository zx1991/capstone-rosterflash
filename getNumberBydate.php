<?php

include 'includes/dbh.inc.php';


$q = $_GET['q'];


$course_id = mysqli_real_escape_string($conn, $q);



$sql="SELECT date, COUNT(date) AS Occurences from attendances WHERE course_id = '$course_id' AND attendance = 1 GROUP BY date ORDER by date";
    
$result = mysqli_query($conn,$sql);

print("[");
	$start = true;
	while($row = mysqli_fetch_array($result)){
		if($start){
			$start = false;
		}else{
			print(",");
		}
		print('{');
		print('"date":"'.$row['date'].'",');
		
		print('"Occurences":"'.$row['Occurences'].'"');
		print('}');
	}
	print("]");



?>