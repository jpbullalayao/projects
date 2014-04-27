<?php
    require_once("functions.php");

    $username = $_GET['username'];
    $password = $_GET['password'];
    
    if ($username == "jourdan" && $password == "pw") {
        echo "signed in successfully";
        redirect_to("index.html");
    } else {
        redirect_to("login.php?username={$_GET['username']}&error=true");
    }
?>