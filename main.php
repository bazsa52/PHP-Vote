<?php
    session_start();

    if(!isset($_SESSION["name"])){
        header("location: index.php");
    }

    if(!empty($_POST)){
        if(isset($_POST["voted"])){
            require_once 'db.func.php';
            $sql = "INSERT INTO answer(qid, answer) VALUES(\"".$_POST["id"]."\", \"".$_POST["xx"]."\")";

            mysqli_query($conn, $sql);

            $id = mysqli_insert_id($conn);

            $sql = "INSERT INTO voted(uid, ip, date, qid, aid) VALUES(?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: main.php?error=stmtfailed");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "sssss", $_SESSION["id"], $_SERVER["REMOTE_ADDR"], date("Y/m/d"), $_POST["id"], $id);

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Szavazat leadása</title>
</head>
<body>

    <?php

        error_reporting(E_ERROR | E_PARSE);
        error_reporting(0);

        require_once 'db.func.php';
        $sql = "SELECT * FROM questions";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: main.php?error=stmtfailed");
            exit();
        }
        
        mysqli_stmt_execute($stmt);

        $res = mysqli_stmt_get_result($stmt);


        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()){
                if($row["finished"] == false){

                    $vsql = 'SELECT voted.ip FROM voted WHERE voted.uid = '.$_SESSION["id"].' AND voted.qid = '.$row["id"].';';
                    $vstmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($vstmt, $vsql)){
                        header("location: main.php?error=stmtfailed");
                        exit();
                    }
                    
                    mysqli_stmt_execute($vstmt);
            
                    $vres = mysqli_stmt_get_result($vstmt);
                    mysqli_stmt_close($vstmt);

                    if($vres->num_rows == 0){
                        $anss = explode(";", $row["answers"]);

                        echo '<form action="main.php" method="post"><h1>'.$row["question"].'</h1>';

                        foreach($anss as $e){
                            if(strlen($e) > 0){
                                echo '<span>'.$e.'</span>';
                                echo '<input type="radio" name="xx" value="'.$e.'">';
                            }
                        }
                        echo '<input type="text" name="id" value="'.$row["id"].'" hidden><input type="submit" value="Szavazás" name="voted"></form>';

                    }else{


                        require_once 'db.func.php';

                        $rsql = 'SELECT answer.answer FROM answer WHERE answer.qid = ' .$row["id"]. '';
                        $rstmt = mysqli_stmt_init($conn);

                        if(!mysqli_stmt_prepare($rstmt, $rsql)){
                            header("location: main.php?error=stmtfailed");
                            exit();
                        }

                        mysqli_stmt_execute($rstmt);

                        $rres = mysqli_stmt_get_result($rstmt);

                        $arres = [];

                        if($rres->num_rows > 0){
                            while($hh = $rres->fetch_assoc()){
                                $arres[] = $hh;
                            }
                        }
                        $anss = [];
                        foreach($arres as $f){
                            $anss[$f["answer"]] = 0;

                        }

                        foreach($arres as $f){
                            $anss[$f["answer"]]++;
                        }

                        echo '<h1>'.$row["question"].'</h1>';

                        foreach($anss as $k => $v){

                            echo $k.": ".$v."\t";

                        }

                    }

                }else{

                    require_once 'db.func.php';

                        $rsql = 'SELECT answer.answer, questions.question FROM answer, questions WHERE answer.qid = '.$row["id"].'';
                        $rstmt = mysqli_stmt_init($conn);

                        if(!mysqli_stmt_prepare($rstmt, $rsql)){
                            header("location: main.php?error=stmtfailed");
                            exit();
                        }

                        mysqli_stmt_execute($rstmt);

                        $rres = mysqli_stmt_get_result($rstmt);

                        $arres = [];

                        if($rres->num_rows > 0){
                            while($hh = $rres->fetch_assoc()){
                                $arres[] = $hh;
                            }
                        }
                        $anss = [];
                        foreach($arres as $f){
                            $anss[$f["answer"]] = 0;

                        }

                        foreach($arres as $f){
                            $anss[$f["answer"]]++;
                        }

                        echo '<h1>'.$row["question"].'</h1>';

                        foreach($anss as $k => $v){

                            echo $k.": ".$v."\t";

                        }

                }
            }
        }


    ?>
    <br>
    <br>
    <br>
    <br>
    <form action="logout.php" method="get">
        <input type="submit" value="Kilépés">
    </form>
    <br>
</body>
</html>