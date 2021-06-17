<?php
    session_start();
    include_once 'db.php';

    function testInput($input){
        $input = trim($input);
        $input = htmlspecialchars($input);
        $input = stripslashes($input);
        return $input;
    }
    $errors = [];
    $result;

    $conn = DB::getConnection();

    if($_POST){
        $title = isset($_POST["title"]) ? testInput($_POST["title"]) : "";
        $text = isset($_POST["text"]) ? testInput($_POST["text"]) : "";
        $username = $_SESSION["username"];

        if (!$title) {
            $errors[] = "Моля, въведете заглавие на темата!";
        }

        if (!$text) {
            $errors[] = "Моля, въведете своя въпрос!";
        }

        if($title && $text){
            $sql = "INSERT INTO topics (title, username, date, text) VALUES (:title, :username, :date, :text)";
            $preparedStmt = DB::getConnection()->prepare($sql);
            try{
                $preparedStmt->execute(["title" => $title, "username" => $username, "date" => date("Y.m.d"), "text" => $text]);
            }catch(PDOException $e){
                $errors[] = $e->getMessage();
            }
        }
        if($errors) {
            foreach ($errors as $error) {
                echo $error;
                echo "<br/>";
            }
            echo "<a href='dashboard.php'>Кликни тук, за да се върнеш към основната страница</a>";
        } else {
            echo "Успешно дибавяне на тема!";
            echo "<br>";
            echo "<a href='dashboard.php'>Кликни тук, за да се върнеш към основната страница</a>";
            header("Location: dashboard.php");
        }

    }
    else{
        echo "ERROR. This is not a POST request!";
    }
?>