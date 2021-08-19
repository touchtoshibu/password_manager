<?php
session_start();
require_once "pdo.php";
if(isset($_POST['cancel'])){
    header("Location: view.php");
    return;
}

if(isset($_POST['association']) && isset($_POST['login_id']) && isset($_POST['password'])){
    $_SESSION['association']=$_POST['association'];
    $_SESSION['login_id']=$_POST['login_id'];
    $_SESSION['password']=$_POST['password'];
    if(strlen($_POST['association'])<1){
        $_SESSION['error']="Association is required";
        header("Location: add.php");
        return;
    }
    else if ( strlen($_POST['login_id']) < 1 || strlen($_POST['password']) < 1 ) {
        $_SESSION['error'] = "login_id and password are required";
        header("Location: add.php");
        return;
    }
    else{
        $stmt = $pdo->prepare("UPDATE passhold SET
        user_id=:ur, association=:ass, login_id=:lg, log_password=:ps WHERE passhold_id=:psi");
        $stmt->execute(array(
        ':psi' => $_GET['passhold_id'],
        ':ur' => $_SESSION['user_id'],
        ':ass' => $_POST['association'],
        ':lg' => $_POST['login_id'],
        ':ps' => $_POST['password'])
        );
        $_SESSION['success'] = "Record Updated";
        header("Location: view.php");
        return;
    }
}

$stmt=$pdo->prepare("SELECT * FROM passhold WHERE passhold_id=:pi");
$stmt->execute(array(':pi'=>$_GET['passhold_id']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false){
    $_SESSION['error']="Bad passhold_id";
    header('Location: view.php');
    return;
}
$ac=$row['association'];
$li=$row['login_id'];
$pw=$row['log_password'];

?>

<!DOCTYPE html>
<html>
<head>
<title>Shibu kumar's Password Manger</title>
<?php require_once "bootstrap.php"; ?>

</head>
<body>
<div class="container">
<h1>Welcome to Password Manager :<?php 
if(isset($_SESSION['name'])){
    echo htmlentities($_SESSION['name'])."</h1>";
}
if( isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>

<form method="POST">
<p>Association:
<input type="text" name="association" value="<?=$ac?>"/></p>
<p>Login ID:
<input type="text" name="login_id" value="<?=$li?>"/></p>
<p>Password:
<input type="text" name="password" value="<?=$pw?>"/></p>
<input type="submit" value="Update">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>