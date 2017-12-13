<?php




include 'includes/dbh.inc.php';

$q = mysqli_real_escape_string($conn,$_GET['q']);


$sql="SELECT * FROM attendances WHERE course_id = '$q' AND attendance = 1 ";
$result = mysqli_query($conn,$sql);

$noa = mysqli_num_rows($result);




echo "$noa,";

$sql="SELECT * FROM attendances WHERE course_id = '".$q."' AND attendance = 0 ";
$result = mysqli_query($conn,$sql);

$non = mysqli_num_rows($result);
echo "$non";





?>