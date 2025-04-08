<?php
    ob_start();
    session_start();

    function userExists($username, $users) {
        return array_key_exists($username, $users);
    }

    function registerUser($username, $password, &$users) {
        $users[$username] = $password;
        $_SESSION['users'] = $users;
    }

    
    $users = isset($_SESSION['users']) ? $_SESSION['users'] : [];

    $msg = '';

    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (userExists($username, $users)) {
            if ($users[$username] == $password) {
                $_SESSION['valid'] = true;
                $_SESSION['timeout'] = time();
                $_SESSION['username'] = $username;
                header("Location: level_selection.php"); 
                exit();
            } 
            
            else {
                $msg = "Wrong password. Please try again.";
            }
        } 
        
        else {
            $msg = "User information not found. Please register to play.";
        }
    } 
    
    elseif (isset($_POST['register']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (!userExists($username, $users)) {
            registerUser($username, $password, $users);
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $username;
            header("Location: level_selection.php");
            exit();
        } 
        
        else {
            $msg = "Username already being used. Please select a different username.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="hangman.css">
    <meta charset="UTF-8">
    <title>Hangman Game</title>
</head>
<body style="background-image: url(''); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container">
        <img src="hangmanlogo1.png" alt="">
        <img src="hang.gif" alt="" height="300px" width="300px">
        <p>Guess the Word, Save the Hangman – Can You Outwit Fate?</p>
        <br>
        <b>Login to continue</b>

        <h4 style="margin-left:10rem; color:red;"><?php echo $msg; ?></h4>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="name">
            </div>
            <br>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <br>
            <section style="margin-left:2rem;">
                <button style="background-color: #008CBA; color: white;" type="submit" name="login">LOGIN</button>
                <button style="background-color: #008CBA; color: white;"type="submit" name="register">REGISTER</button>
            </section>
        </form>
    </div>
</body>
</html>
