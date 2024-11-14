<?php
/*
  SQL to create table:
    CREATE TABLE survey (
        timestamp DATETIME NOT NULL,
        rating INT NOT NULL,
        relation VARCHAR(50) NOT NULL
    );
*/
header("Content-Type: application/json");

include 'db.php';

$rating_fixed = intval($_GET["rating"] ?? null);
$relation = $_GET["relation"] ?? null;
$relation_fixed = mysqli_real_escape_string($dbhandle, $relation);
$sql = "INSERT INTO survey (timestamp, rating, relation) VALUES (UTC_TIMESTAMP(), " . $rating_fixed . ", '" . $relation_fixed . "')";
if (mysqli_query($dbhandle, $sql) === TRUE) {
    $json_response = (object)  ['message' => 'Survey answer stored', 'success' => true];
} else {
    $json_response = (object)  ['message' => 'Error storing survey answer', 'success' => false];
}
echo json_encode($json_response);
?>
