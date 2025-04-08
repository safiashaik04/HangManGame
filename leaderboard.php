<?php
session_start();


$leaderboard = [];

function addScoreToLeaderboard($username, $score, &$leaderboard) {
    $leaderboard[$username] = $score;
    arsort($leaderboard);
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

    
    addScoreToLeaderboard($username, $score, $leaderboard);
}


displayLeaderboard($leaderboard);
?>
