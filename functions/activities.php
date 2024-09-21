<?php
require_once '../config/db.php';

function getActivitiesByUser($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM activities WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function registerActivity($userId, $activity, $date, $hour) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO activities (user_id, activity, date, hour) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $activity, $date, $hour]);
}
?>
