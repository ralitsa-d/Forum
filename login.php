<?php
    require_once "db.php";
    require_once "user.php";

    session_start();

    $errors = [];

    function testInput($input){
        $input = trim($input);
        $input = htmlspecialchars($input);
        $input = stripslashes($input);
        return $input;
    }

    function isValid($userName, $pass){
        $sql = "SELECT * FROM users WHERE username=:username";
        $preparedStmt = DB::getConnection()->prepare($sql);
        try{
            $preparedStmt->execute(["username" => $userName]);
        }catch(PDOException $e){
            $errMsg = $e->getMessage();
            $query = ["successfullyExecuted" => false, "errMessage" => $errMsg];
            return $query;
        }
        $user_assoc = $preparedStmt->fetch(PDO::FETCH_ASSOC);
        if($user_assoc){
            if (password_verify($pass, $user_assoc["password"])) {
                $query = ["successfullyExecuted" => true, "isValid" => true, 
                        "id" => $user_assoc["id"], "email" => $user_assoc["email"]];
                return $query;
            }
            else{
                $query = ["successfullyExecuted" => true, "isValid" => false, "errMsg" => "Сгрешена парола!"];
                return $query;
            }
        }
        else{
            $query = ["successfullyExecuted" => true, "isValid" => false, "errMsg" => "Сгрешено потребителско име!"];
            return $query;
        }
    }

    if ($_POST) {
        $username = isset($_POST["username"]) ? testInput($_POST["username"]) : "";
        $password = isset($_POST["password"]) ? testInput($_POST["password"]) : "";
        if (!$username) {
            $errors[] = "Моля, въведете потребителско име!";
        }

        if (!$password) {
            $errors[] = "Моля, въведете парола!";
        }

        if($username && $password){
            $valid = isValid($username, $password);
            if($valid["successfullyExecuted"] && $valid["isValid"]){
                $_SESSION["userId"] = $valid["id"];
                $_SESSION["email"] = $valid["email"];
                $_SESSION["username"] = $username;
                
                header("Location: dashboard.php");
            }
            else if($valid["successfullyExecuted"] && !$valid["isValid"]){
                $errors[] = $valid["errMsg"];
            }
            else if(!$valid["successfullyExecuted"]){
                $errors[] = $valid["errMessage"];
            }
        }

        if($errors) {
            foreach ($errors as $error) {
                echo $error;
                echo "<br/>";
            }
            echo "<a href='login.html'>Кликни тук, за да се върнеш към формата</a>";
        } else {
            echo "Потребителят се логна успешно!";
            echo "<br>";
            echo "<a href='login.html'>Кликни тук, за да се върнеш към формата</a>";
            //header("Location: dashboard.php");
        }
    }
    else{
        echo "ERROR. This is not a POST request!";
    }
?>