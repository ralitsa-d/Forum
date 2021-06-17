<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Find Your Solution</title>
</head>
<body>
    <?php
        require_once "db.php";
        session_start(); 
        if(!$_SESSION["username"]){
            header("Location: login.html");
        } 
    ?>
    <div id="header">
        <h1><?= "Здравейте, " . $_SESSION["username"] . "!"?></h1>
        <div id="buttons">
            <button id="add" onclick="window.location='addTopic.php';">Добави тема</button>
            <form action="logout.php" method="POST">
                <input type="submit" value="Изход">
            </form>
        </div>
    </div>
    <div id="topics">
        <h2>Теми</h2>
        <?php
            require_once "db.php";
            $sql = "SELECT * FROM topics";
            $preparedStmt = DB::getConnection()->prepare($sql);
            try{
                $preparedStmt->execute();
            }catch(PDOException $e){
                $errMsg = $e->getMessage();
                echo $errMsg;
            }
            $topics_assoc = $preparedStmt->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($topics_assoc);
            //var_dump(date_default_timezone_get());
            //var_dump(date("d.m.Y"));
            if(!$topics_assoc):?>
                <h3>Все още няма теми.</h3>
            <?php endif; ?>
            <?php if($topics_assoc) : ?>
                <?php foreach($topics_assoc as $topic): ?>
                    <li class="topics">
                        <?php $_SESSION["topicId"] = $topic["id"]; ?>
                        <a href="topicPage.php?topicId=<?= $topic["id"] ?>">
                            <?= $topic["title"] . " създадена от " . $topic["username"] . " на " . $topic["date"];?> </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
    </div>
</body>
</html>

