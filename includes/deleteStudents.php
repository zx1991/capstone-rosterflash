<?php

//php file to delete students

session_start();

if(!isset($_GET['q'])){
    
    header("Location: ../pages/teacher/courseManage/Management.php?roster=deleteerror");
}

$q = $_GET['q'];
$studentsList = explode(';', $q);

if(!isset($_GET['c'])){
    
    header("Location: ../pages/teacher/courseManage/Management.php?roster=deleteerror");
}

if(!isset($_SESSION['u_id'])){
    
    header("Location: ../pages/teacher/courseManage/Management.php?roster=deleteerror");
}

$c = $_GET['c'];

include 'dbh.inc.php';

$course_id = mysqli_real_escape_string($conn, $c);

$user_name =mysqli_real_escape_string($conn, $_SESSION['u_id']);


$isFirst = true;
$sql;
foreach($studentsList as $student){
    
    if($student != ""){
        
        if($isFirst){
            
            $sql = "DELETE FROM roster WHERE user_name= '$user_name' and course_id = '$course_id' and student_id = '$student';";
            $isFirst = false;
        }else{
            
             $sql .= "DELETE FROM roster WHERE user_name= '$user_name' and course_id = '$course_id' and student_id = '$student';";
            
        }
        
        
    }
    
}

if(mysqli_multi_query($conn,$sql)){
               
               do{
                  
                   
                   
               }while(mysqli_next_result($conn));
               
               
 
               
           } header("Location: ../pages/teacher/courseManage/Management.php?roster=deletesuccess");

