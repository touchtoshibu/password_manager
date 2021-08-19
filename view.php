<?php
require_once "pdo.php";
session_start();
if(isset($_POST['logout'])){
    session_start();
    session_destroy();
    header("Location: index.php");
    return;
}
if(isset($_POST['add'])){
    header("Location: add.php");
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Shibu Kumar - Password Manager</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Welcome to Password Manager </h1>
<?php
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
  }
$count=0;
$stmt=$pdo->prepare("SELECT passhold_id, user_id, association, login_id, log_password FROM passhold WHERE user_id= :ui");
$stmt->execute(array(':ui'=> $_SESSION['user_id']));
echo '<pre><table border="1"><th>Association</th><th>User Id</th><th>PassWord</th><th>Action</th>'."\n";
while($row= $stmt->fetch(PDO::FETCH_ASSOC)){
    $count++;
    echo '<tr><td>';
    echo(htmlentities($row['association']));
    echo '</td><td>';
    echo(htmlentities($row['login_id']));
    echo '</td><td>';
    echo(htmlentities($row['log_password']));
    echo '</td><td>';
    echo '<a href="edit.php?passhold_id='.$row['passhold_id'].'"class="button">Edit</a>';
    echo ' ';
    echo '<a href="delete.php?passhold_id='.$row['passhold_id'].'">Delete</a>';
    echo '</td></tr>';
}
echo '</table></pre>';
if($count==0){
    echo '<p> Your entry is empty</p>';
}
?>
<form method='post'>
<input type="submit" name="add" value="Add New Entry">
<input type="submit" name="logout" value="Logout">
</form>
</div>
</body>
</html>