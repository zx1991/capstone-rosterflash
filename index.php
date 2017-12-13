<?php
	session_start();

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/normalize.css" type="text/css">
    
    <script src="js/Chart.min.js"></script>
    <!-- http://www.layui.com/doc -->
    <script src="./layui/layui.js"></script>
    <link rel="stylesheet" href="./layui/css/layui.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/login.css" type="text/css">
    
    
    <link rel="icon" href="layui/images/face/65.gif" type="image/gif" >
    
    
</head>
<body>
    <header class="site-header">
        <div class="site-header-inner container">
            <div class="site-logo">ROSTER TRACKER</div>
        </div>
    </header>

    <div class="site-main">
        <div class="site-main-inner">
            <div class="login-container">
                <div class="layui-tab layui-tab-card">
                    <ul class="layui-tab-title">
                        <li class="layui-this">I am a teacher</li>
                        <li>I am a student</li>
                        <li>Register</li>
                    </ul>
                    <div class="layui-tab-content">
                        
                        <?php  if(isset($_GET['slogin'])){
                                                    echo '<div class="layui-tab-item">';
    
                                                }
                                        else{
                                            
                                            if(isset($_GET['signup'])){
                                                $q = $_GET['signup'];
                                                    if($q != "success"){
                                                        
                                                        echo '<div class="layui-tab-item">';
                                            }else{
                                            
                                             echo '<div class="layui-tab-item layui-show">';
                                                    }
                                        }else{
                                                echo '<div class="layui-tab-item layui-show">';
                                            }}?>
                            <div class='login'>
                                <form method='post' action="includes/login.inc.php" >
                                    <input type='text' placeholder='User' name='uid' required/>
                                    <input type='password' placeholder='Password' name='pwd' required/>
                                    <!-- put the error message here -->
                                    <div class="error_log"><?php  if(isset($_GET['login'])){
                                                    echo '<p style="color:red"> Login Failed. Please try again. </p>';
    
                                                }
                                        if(isset($_GET['signup'])){
                                                $singup = $_GET['signup'];
                                            
                                            if($signup == "success")
                                                    echo '<p style="color:blue"> Register succeed! please login. </p>';
    
                                                }?>
                                    </div>
                                    <input type='submit' name="submit" value='Log in' />
                                </form>
                            </div>
                        </div>
                        
                        <?php  if(isset($_GET['slogin'])){
                                                    echo '<div class="layui-tab-item layui-show">';
    
                                                }
                                        else{
                                            echo '<div class="layui-tab-item">';
                                        }?>
                            <div class='login'>
                                <form method='post' action="includes/student.login.inc.php">
                                    <input type='text' placeholder='Student ID' name='studentId' required/>
                                    <input type='text' placeholder='First Name' name='firstName' required/>
                                    <input type='text' placeholder='Last Name' name='lastName' required/>
                                    <!-- put the error message here -->
                                    <div class="error_log"> 
                                        
                                        <?php  if(isset($_GET['slogin'])){
                                                    echo '<p style="color:red"> Login Failed. Please try again. </p>';
    
                                                }
                                        ?>
        
    
    
                                    </div>
                                    <input type='submit' name="submit" value='Check' />
                                </form>
                            </div>
                        </div>
                      <?php if(isset($_GET['signup'])){
                
                                                    $q = $_GET['signup'];
                                                    if($q != "success"){
                                                    echo '<div class="layui-tab-item layui-show">';
    
                                                }else{echo '<div class="layui-tab-item">';}}
                                        else{
                                            echo '<div class="layui-tab-item">';
                                        }?>
                            <div class='login'>
                                <form method='post' action="includes/signup.inc.php" >
                                    <input type='text' placeholder='Username' name='uid' required/>
                                    <input type='password' placeholder='Password' name='pwd' required/>
                                    <input type='text' placeholder='First Name' name='first' required/>
                                    <input type='text' placeholder='Last name' name='last' required/>
                                    <!-- put the error message here -->
                                    <div class="error_log"><?php   if(isset($_GET['signup'])){
                                                 $q = $_GET['signup'];
                                                    if($q == "usertaken"){
                        
                                                    echo '<p style="color:red"> Register failed! (Username  has already been taken) </p>';
                                                    }
                                    }?>
                                    </div>
                                    <input type='submit' name="submit" value='Register' />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="site-footer-inner container">
           
        </div>
    </footer>

    <script>
        layui.use('element', function(){
            var element = layui.element;
            // Some listener here
            element.on('tab(demo)', function(data){
//            console.log(data);
            });
        });
    </script>
</body>
</html>