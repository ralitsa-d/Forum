<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="topicPage.css">
    <title>Find Your Solution</title>
</head>
<body>
    <?php
        session_start();
        if(!$_SESSION["topicId"]){
            header("Location: dashboard.php");
        }
        require_once "db.php";
    ?>
    <?php
        $sql = "SELECT * FROM topics WHERE id=:topicid";
        $preparedStmt = DB::getConnection()->prepare($sql);
        try{
            $preparedStmt->execute(["topicid" => $_GET["topicId"]]);
            $_SESSION["topicId"] = $_GET["topicId"];
        }catch(PDOException $e){ 
            $errMsg = $e->getMessage();
        }
        $topic_assoc = $preparedStmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div>
        <?php if($topic_assoc): ?>
            <div class="theQuestion">
                <h1><?php echo $topic_assoc["title"]; ?></h1>
                <p class="small"><?php echo "Зададен от: " . $topic_assoc["username"]; ?></p>
                <p class="small"><?php echo "на " . $topic_assoc["date"]; ?></p>
                <p class="text"><?php echo $topic_assoc["text"]; ?></p>
            </div>
        <?php endif; ?>
    </div>        

    <?php
        $sql = "SELECT * FROM comments WHERE topicId=:topicid";
        $preparedStmt = DB::getConnection()->prepare($sql);
        try{
            $preparedStmt->execute(["topicid" => $_GET["topicId"]]);
        }catch(PDOException $e){
            $errMsg = $e->getMessage();
        }
        $comments_assoc = $preparedStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <?php if(!$comments_assoc): ?>
            <h2 id="noComments">Все още няма коментари</h2>
        <?php endif; ?>

    <div>
        <?php if($comments_assoc) : ?>
            <?php foreach($comments_assoc as $comment) : ?>
                <li class="comment">
                    <h3><?php echo $comment["username"] . " каза: " ?></h3>
                    <p class="text"><?php echo $comment["text"]; ?></p>
                    <p class="small"><?php echo "На: " . $comment["date"]; ?></p>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="commentSection">
        <h2>Добави коментар:</h2>
        <form action="sendComment.php" method="POST">
            <label for="text"></label>
            <textarea name="text" id="text" cols="30" rows="10"></textarea>
            <input type="submit" value="Публикувай">
        </form>
    </div>
    

</body>
</html>