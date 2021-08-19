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
        $stmt = $pdo->prepare('INSERT INTO passhold
        (user_id, association, login_id, log_password) VALUES ( :ur, :as, :lg, :ps)');
        $stmt->execute(array(
        ':ur' => $_SESSION['user_id'],
        ':as' => $_POST['association'],
        ':lg' => $_POST['login_id'],
        ':ps' => $_POST['password'])
        );
        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Shibu kumar's Automobile Tracker</title>
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
<input type="text" name="association" size="60"/></p>
<p>Login ID:
<input type="text" name="login_id"/></p>
<p>Password:
<input type="text" name="password"/></p>
<input type="submit" value="Add New">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>