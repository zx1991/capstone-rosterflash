<?php
// php file for add students to class


session_start();

include_once 'dbh.inc.php';


$userid = mysqli_real_escape_string($conn,$_SESSION['u_id']);

if(isset($_POST['course'])){
    $CourseId = mysqli_real_escape_string($conn, $_POST['course']);
   
    
}else{
    
    header("Location: ../pages/teacher/courseManage/Management.php?roster=noclass");
    exit();
}



if(isset($_FILES['file'])){
    
    
    


    if ($_FILES['file']['error'] == UPLOAD_ERR_OK               //checks for errors
        && is_uploaded_file($_FILES['file']['tmp_name'])) { //checks that file is uploaded
        $handle = fopen($_FILES['file']['tmp_name'],"r");
        if($handle){
            
            $int =0;
            $isfirst = true;
            while(($line = fgets($handle))!= false){
                $sql;          
                $int ++;
                $comp = preg_split("/[\t]/", $line);
                
                if(count($comp)>2){
                    $first = mysqli_real_escape_string($conn,$comp[0]);
                    $last =  mysqli_real_escape_string($conn,$comp[1]);
                    $id =  mysqli_real_escape_string($conn,(int)$comp[2]);
                    if(count($comp)>3){
                        
                        $email = mysqli_real_escape_string($conn,$comp[3]);
                    }else{
                        $email = mysqli_real_escape_string($conn,'');
                    }
                    
                    
                        
                        $sql = "INSERT INTO roster (user_name, course_id,student_id,first_name,last_name,email) VALUES ('$userid', '$CourseId', $id, '$first','$last','$email');";
                       
                        $result = mysqli_query($conn, $sql);
                        
                    }
                    
                    
                }
                
                
                
               
            }
            
           
          
             header("Location: ../pages/teacher/courseManage/Management.php?roster=success");
               exit();
        }
     
    }
    
