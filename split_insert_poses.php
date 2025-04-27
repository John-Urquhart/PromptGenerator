<?php
// split_insert_poses.php

require_once 'db_connect.php'; // Reuse your existing database connection

// Define your big comma-separated list here
$poseList = "Downward Dog, Tree Pose, Warrior Pose, Handstand, Lotus Pose, Cobra Stretch, Seated Twist";

// Split the list into an array
$poses = explode(',', $poseList);

$successCount = 0;
$errorCount = 0;

// Insert each pose individually
foreach ($poses as $pose) {
    $pose = trim($pose); // Remove leading/trailing spaces
    if ($pose !== '') {
        $stmt = $conn->prepare("INSERT INTO poses (pose_name) VALUES (?)");
        $stmt->bind_param("s", $pose);

        if ($stmt->execute()) {
            $successCount++;
        } else {
            $errorCount++;
        }

        $stmt->close();
    }
}

$conn->close();

echo "✅ Successfully inserted {$successCount} poses.<br>";
if ($errorCount > 0) {
    echo "⚠️ {$errorCount} inserts failed.<br>";
}
?>
