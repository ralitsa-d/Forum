<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="addTopic.css">
    <title>Find Your Solution - Add Topic</title>
</head>
<body>
    <?php
        require_once "db.php";
        session_start(); 
        if(!$_SESSION["username"]){
            header("Location: login.html");
        } 
    ?>
    <h1>Добави тема за обсъждане</h1>
    <hr>
    <form action="addTopicToDB.php" method="POST">
        <label for="title">Заглавие</label><br>
        <input type="text" id="title" name="title"><br>

        <label for="text">Текст</label><br>
        <textarea type="text" id="text" name="text" rows=5></textarea><br>

        <input type="submit" value="Добави">
    </form>
</body>
</html>