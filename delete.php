<?php
require_once "pdo.php";
if(isset($_POST['cancel'])){
    header("Location: view.php");
    return;
}
if(isset($_POST['delete']) && isset($_GET['passhold_id'])){
    $stmt=$pdo->prepare("DELETE FROM passhold WHERE passhold_id= :pi");
    $stmt->execute(array(':pi' => $_GET['passhold_id']));
    $_SESSION['success']="Entry Successfully Deleted";
    header("Location: view.php");
    return;
}
if(!isset($_GET['passhold_id'])){
    $_SESSION['error']="passhold_id is missing";
    header('Location: view.php');
    return;
}
$stmt=$pdo->prepare("SELECT * FROM passhold WHERE passhold_id= :pi");
$stmt->execute(array(':pi' => $_GET['passhold_id']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false){
    $_SESSION['error']="wrong passhold_id";
    header("Location: view.php");
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
<h1>Welcome to Password Manager</h1>
<p>Are you sure you want to delete this entry ?</p>
<form method='post'>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>