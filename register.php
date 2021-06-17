<?php
    include_once 'db.php';
    include_once 'user.php';

    function testInput($input){
        $input = trim($input);
        $input = htmlspecialchars($input);
        $input = stripslashes($input);
        return $input;
    }
    $errors = [];
    $result;

    $conn = Db::getConnection();

    if($_POST){
        $email = isset($_POST["email"]) ? testInput($_POST["email"]) : "";
        $username = isset($_POST["username"]) ? $_POST["username"] : "";
        $password = isset($_POST["password"]) ? testInput($_POST["password"]) : "";
        $confirmPass = isset($_POST["confirmPass"]) ? testInput($_POST["confirmPass"]) : "";

        $regex = '/[A-Za-z1-9._-]+@[a-z]+\.[a-z]+/';
        if(!$email){
            $errors[] = "Моля, въведете имейл!";
        }
        else{    
            if(!preg_match($regex, $email)){
                $errors[] = "Имейлът е невалиден!";
            }
        }

        $validUsername;
        if(!$username){
            $errors[] = "Моля, въведете потребителско име!";
        }
        //още тук да проверя дали вече няма използвано това потребителско име
        $sql = "SELECT * FROM users WHERE username=:username";
        $preparedStmt = DB::getConnection()->prepare($sql); // improvizirah
        try{
            $preparedStmt->execute(["username" => $username]);
        }catch(PDOException $e){
            $errMsg = $e->getMessage();
            echo $errMsg;
            //$query = ["sucessfullyExecuted" => false, "errMessage" => $errMsg];
            //return $query;
        }
        $user_assoc = $preparedStmt->fetch(PDO::FETCH_ASSOC);
        if($user_assoc){
            $validUsername = false;
            $errors[] = "Въведеното потребителско име вече съществува. Моля, въведете ново потребителско име!";
            //$query = ["sucessfullyExecuted" => true, "userExists" => true];
            //return $query;
        }
        else{
            $validUsername = true;
            ///$query = ["sucessfullyExecuted" => true, "userExists" => false];
            //return $query;
        }



        if(!$password){
            $errors[] = "Моля въведете парола!";
        }
        if(!$confirmPass){
            $errors[] = "Моля, потвърдете паролата!";
        }
        if($password != $confirmPass){
            $errors[] = "Паролите не съвпадат!";
        }


        if($email && $username && $password && $confirmPass && $password == $confirmPass 
                && preg_match($regex, $email) && $validUsername){
            $user = new User($email, $username, $password);
            $exists = $user->exists();
            if(!$exists["successfullyExecuted"]){
                $errors[] = $exists["errMessage"];
            }
            if($exists["successfullyExecuted"] && $exists["userExists"]){
                $errors[] = "Потребител с този имейл вече съществува!";
            }
            if($exists["successfullyExecuted"] && !$exists["userExists"]){
                $sql = "INSERT INTO users (email, username, password) VALUES (:email, :username, :password)";
                $preparedStmt = DB::getConnection()->prepare($sql);
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                try{
                    $preparedStmt->execute(["email" => $email, "username" => $username, "password" => $passwordHash]);
                }catch(PDOException $e){
                    $errMessage = $e->getMessage();
                }
            }
        }

        if($errors) {
            foreach ($errors as $error) {
                echo $error;
                echo "<br/>";
            }
            echo "<a href='register.html'>Кликни тук, за да се върнеш към формата</a>";
        } else {
            echo "Потребителят е добавен успешно!";
            echo "<br>";
            echo "<a href='register.html'>Кликни тук, за да се върнеш към формата</a>";
            header("Location: login.html");
        }

    }
    else{
        echo "ERROR. This is not a POST request!";
    }

?>