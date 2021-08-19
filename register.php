<?php
session_start();
require_once "pdo.php";
if(isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}
$salt = 'XyZzy12*_';
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: register.php");
        return;
    }else if(strpos($_POST['email'],'@')===false){
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: register.php");
        return;
    } 
    else {
        $count=0;
        $stmt=$pdo->prepare("SELECT user_id FROM users WHERE email=:em");
        $stmt->execute(array(':em'=>$_POST['email']));   
        while($flag=$stmt->fetch(PDO::FETCH_ASSOC)){
            $count++;
        }
        if($count>0){
            $_SESSION['error'] = "Email already exist";
            header("Location: register.php");
            return;
        }
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('INSERT INTO users
        (email,password) VALUES ( :em, :ps)');
        $stmt->execute(array(
        ':em' => $_POST['email'],
        ':ps' => $check)
        );
        $_SESSION['success'] = "Succesfully Registered , please Log In";
        header("Location: login.php");
        return;
    }

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
<?php
if( isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="email">Email Id</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Register">
<input type="submit" name="cancel" value="Cancel">
</form>

</div>
</body>
</html>