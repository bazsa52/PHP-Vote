<?php
    session_start();

    if(!isset($_SESSION["name"])){
        header("location: index.php");
        exit();
    }

    session_destroy();
    header("location: index.php");
    exit();