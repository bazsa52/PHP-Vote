<?php
    session_start();

    if(isset($_SESSION["name"])){
        header("location: main.php");
    }
    
    if(!empty($_POST)){
        if(isset($_POST["login"])){
            require 'db.func.php';
            $sql = "SELECT * FROM users WHERE `name` = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                session_reset();
                unset($_POST);
                header("location: index.php?error=stmtfailed");
                exit();
            }
            mysqli_stmt_bind_param($stmt, "s", $_POST["uname"]);

            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);

            if(!password_verify($_POST["pwd"], $row["password"])){
                session_reset();
                unset($_POST);
                header("location: index.php?error=wronglogin");
                exit();
            }

            session_reset();
            unset($_POST);
            $_SESSION["name"] = $row["name"];
            $_SESSION["id"] = $row["id"];
            header("location: main.php");
            exit();

        }
    }

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Bejelentkezés a szavazáshoz</title>
</head>
<body>
<form action="index.php" method="post">
        <span>Felhasználónév: </span>
        <input type="text" name="uname">
        <span>Jelszó: </span>
        <input type="password" name="pwd">
        <input type="submit" name="login" value="login">
    </form>
</body>
</html>