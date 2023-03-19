<?php
    error_reporting(E_ERROR | E_PARSE);
    // session_start();

    // if(!isset($_SESSION["name"])){
    //     header("location: index.php");
    //     exit();
    // }
    
    if(!empty($_POST)){
        if(isset($_POST["sub"])){
            if($_POST["vsp"] == "123"){
                $_SESSION["verified"] = true;
            }
        }elseif(isset($_POST["add"])){
            require_once 'db.func.php';
            $sql = "INSERT INTO users(name, password) VALUES(?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: admin.php?error=stmtfailed");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "ss", $_POST["name"], password_hash($_POST["pwd"], PASSWORD_DEFAULT));
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
        }elseif(isset($_POST["qq"])){
            require_once 'db.func.php';
            $sql = "INSERT INTO questions(question, answers, finished) VALUES(\"".$_POST["question"]."\",\"".$_POST["sum"]."\" , false);";

            mysqli_query($conn, $sql);

            $id = mysqli_insert_id($conn) - 1;

            $sql = "UPDATE questions SET finished=true WHERE id = $id";
            mysqli_query($conn, $sql);

        }
    }


?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="admin.js"></script>
    <link rel="stylesheet" href="main.css">
    <title>Adminisztráció</title>
</head>
<body>
    <?php
        if(!isset($_SESSION["verified"])){
            echo '<form action="admin.php" method="post">
            <span>Jelszó:</span>
            <input type="password" name="vsp">
            <input type="submit" name="sub" value="Ellenőrzés">
            </form>';
        }else{
            echo '<form action="admin.php" method="post">
            <h1>Felhasználó hozzáadása</h1>
            <span>Név:</span>
            <input type="text" name="name">
            <br>
            <span>Jelszó:</span>
            <input type="password" name="pwd">
            <br>
            <input type="submit" value="Hozzáad" name="add">
        </form>
        <form action="admin.php" method="post">
            <h1>Szavazás hozzáadása</h1>
            <span>Kérdés:</span>
            <input type="text" name="question">
            <br>
            <span>Válaszok száma:</span>
            <input type="number" id="qnum" min="2" max="10" onchange="nums()">
            <br>
            <span id="outq"></span>
            <input type="text" name="sum" id="su" hidden>
            <br>
            <input type="submit" value="Hozzáad" name="qq">
            </form>';
        }
    ?>
    
</body>
</html>