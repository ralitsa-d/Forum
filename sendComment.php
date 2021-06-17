<?php
    session_start();
    include_once 'db.php';

    if(!$_SESSION["topicId"]){
        header("Location: dashboard.php");
    }

    $errors = [];
    $conn = Db::getConnection();

    if($_POST){
        $text = isset($_POST["text"]) ? $_POST["text"] : "";
        $username = $_SESSION["username"];
        $topicId = $_SESSION["topicId"];
        $date = date("Y-m-d");

        if(!$text){
            $errors[] = "Моля, въведете Вашия коментар!";
        }
        if($text){
            $sql = "INSERT INTO comments (topicId, username, date, text) VALUES (:topicid, :username, :date, :text)";
            $preparedStmt = DB::getConnection()->prepare($sql);
            try{
                $preparedStmt->execute(["topicid" => $topicId, "username" => $username, "date" => $date, "text" => $text]);
            }catch(PDOException $e){
                $errors[] = $e->getMessage();
            }

        }

        if($errors) {
            foreach ($errors as $error) {
                echo $error;
                echo "<br/>";
            }
            echo "<a href='topicPage.php'>Кликни тук, за да се върнеш към коментарите</a>";
        } else {
            echo "Коментарът е добавен успешно!";
            echo "<br>";
            echo "<a href='topicPage.php'>Кликни тук, за да се върнеш към формата</a>";
            header("Location: topicPage.php?topicId=$topicId");
        }

    }
    else{
        echo "ERROR. This is not a POST request!";
    }

?>