<?php
session_start();


include 'generateWord.php';

$leaderboard = loadLeaderboard();

if (!isset($_SESSION['word'])) {
    $difficulty = $_GET['difficulty'] ?? 'easy'; 
    $wordData = generateWord($difficulty);
    $_SESSION['word'] = $wordData['word'];
    $_SESSION['hint'] = $wordData['hint'];
    $_SESSION['guessedLetters'] = [];
    $_SESSION['misses'] = 0;
    $_SESSION['score'] = 0;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guess'])) {
    $guess = strtolower(trim($_POST['guess']));
    if (!in_array($guess, $_SESSION['guessedLetters'])) {
        $_SESSION['guessedLetters'][] = $guess;
        if (strpos($_SESSION['word'], $guess) === false) {
            $_SESSION['misses']++;
            $_SESSION['score'] -= 100; 
        } else {
            $_SESSION['score'] += 100;
        }
    }
}

$displayWord = '';
foreach (str_split($_SESSION['word']) as $letter) {
    $displayWord .= in_array($letter, $_SESSION['guessedLetters']) ? $letter : '_';
    $displayWord .= ' ';
}

$gameOver = $_SESSION['misses'] >= 6;
$win = !in_array('_', str_split($displayWord));

if ($gameOver) {
    $finalWord = $_SESSION['word']; 
    $hangmanImageSrc = "hang7.png";
    
} else {
    $hangmanImageNumber = min($_SESSION['misses'] + 1, 7);
    $hangmanImageSrc = "hang$hangmanImageNumber.png";
}


function loadLeaderboard() {
    $filePath = 'leaderboard.txt';
    $leaderboard = [];
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($username, $score) = explode(',', $line);
            $leaderboard[$username] = (int)$score;
        }
    }
    return $leaderboard;
}

function saveLeaderboard($leaderboard) {
    $filePath = 'leaderboard.txt';
    $lines = [];
    foreach ($leaderboard as $username => $score) {
        $lines[] = "$username,$score";
    }
    file_put_contents($filePath, implode("\n", $lines));
}

function addScoreToLeaderboard($username, $score, &$leaderboard) {
    $leaderboard[$username] = $score; 
    arsort($leaderboard);
    saveLeaderboard($leaderboard);
}

function displayLeaderboard($leaderboard) {
    echo "<h2>Leaderboard</h2>";
    echo "<table>";
    echo "<tr><th>Rank</th><th>Username</th><th>Score</th></tr>";
    $rank = 1;
    foreach ($leaderboard as $username => $score) {
        echo "<tr><td>$rank</td><td>$username</td><td>$score</td></tr>";
        $rank++;
    }
    echo "</table>";
}

if ($gameOver || $win) {
   
    $username = $_SESSION['username'];
    $score = $_SESSION['score'];
    $finalWord = $_SESSION['word'];

   
    addScoreToLeaderboard($username, $score, $leaderboard);
    displayLeaderboard($leaderboard);


    
    unset($_SESSION['word']);
    unset($_SESSION['guessedLetters']);
    unset($_SESSION['misses']);
    unset($_SESSION['score']);
    
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hangman Game</title>
    <link rel="stylesheet" href="hangman.css">
</head>
<body>
    <div class="game-container">
        <img class="logo" src="hangmanlogo1.png" alt="Hangman Logo">

        <div class="game-area">
            <div class="score-container">
                <img class="score" src="hangscore.png" alt="">
                <p>Score: <?php echo $_SESSION['score'] ?? '0'; ?></p>
            </div>
            <div class="hangman-play-area">
                <img class="hanglevels" src="<?php echo $hangmanImageSrc; ?>" alt="Hangman Status">
                <?php if (isset($gameOver) && $gameOver): ?>
                    <p>Game Over! The word was "<?php echo htmlspecialchars($finalWord); ?>".</p>
                    <a href="homepage.php">Go Home</a>
                    
                <?php elseif (isset($win) && $win): ?>
                    <p>Congratulations! You guessed the word "<?php echo htmlspecialchars($finalWord); ?>" correctly!</p>
                    <a href="homepage.php">Go Home</a>
                    
                <?php else: ?>
                    <p>Guess the word: <?php echo $displayWord; ?></p>
                    <form action="gamepage.php" method="post">
                        Guess a letter: <input type="text" name="guess" maxlength="1" required>
                        <br><br/>
                        <button type="submit">Submit</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="letters-container">
                <img class="letters" src="hangletters.png" alt="Letters Used">
                <p><?php echo implode(', ', $_SESSION['guessedLetters'] ?? []); ?></p>
            </div>
            
        </div>
        <div class="hint-container">
    <img src="hanghint.png" alt="hint" class="hint-image">
    <div class="overlay">
        <div class="text">
            <?php echo htmlspecialchars($_SESSION['hint'] ?? 'No hint available'); ?>
        </div>
    </div>
</div>
        
        <br>
        <a href="homepage.php">Home Page</a>
        <br>
        <a class="diffButton" href="level_selection.php">Change Difficulty?</a>
        <br>
    </div>


</body>


</html>

