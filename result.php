<?php
header("Content-Type: text/html");

include 'db.php';

// Fetch all survey answers
$sql = "SELECT * FROM survey";
$result = mysqli_query($dbhandle, $sql);

// Fetch average rating
$sql_avg = "SELECT AVG(rating) as avg_rating FROM survey";
$result_avg = mysqli_query($dbhandle, $sql_avg);
$avg_rating = mysqli_fetch_assoc($result_avg)['avg_rating'];

// Fetch count/sum of each relation
$sql_relation = "SELECT relation, COUNT(*) as count FROM survey GROUP BY relation";
$result_relation = mysqli_query($dbhandle, $sql_relation);

// Prepare data for pie chart
$relation_counts = [];
$total_count = 0;
while ($row = mysqli_fetch_assoc($result_relation)) {
    $relation_counts[] = $row;
    $total_count += $row['count'];
}

echo "<h2>Average Rating: " . number_format($avg_rating, 2) . "</h2>";

echo "<h2>Relation Counts</h2>";
echo "<table border='1'>
<tr>
<th>Relation</th>
<th>Count</th>
</tr>";

foreach ($relation_counts as $row) {
    echo "<tr>";
    echo "<td>" . $row['relation'] . "</td>";
    echo "<td>" . $row['count'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Generate SVG pie chart
function getColor($index) {
    $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
    return $colors[$index % count($colors)];
}

$cx = 250;
$cy = 150;
$r = 100;
$startAngle = 0;

echo '<svg width="500" height="300" viewBox="0 0 300 300">';
foreach ($relation_counts as $index => $row) {
    $endAngle = $startAngle + ($row['count'] / $total_count) * 360;
    $x1 = $cx + $r * cos(deg2rad($startAngle));
    $y1 = $cy + $r * sin(deg2rad($startAngle));
    $x2 = $cx + $r * cos(deg2rad($endAngle));
    $y2 = $cy + $r * sin(deg2rad($endAngle));
    $largeArcFlag = ($endAngle - $startAngle) > 180 ? 1 : 0;

    echo '<path d="M' . $cx . ',' . $cy . ' L' . $x1 . ',' . $y1 . ' A' . $r . ',' . $r . ' 0 ' . $largeArcFlag . ',1 ' . $x2 . ',' . $y2 . ' Z" fill="' . getColor($index) . '"></path>';

    // Calculate label position
    $midAngle = ($startAngle + $endAngle) / 2;
    $labelX = $cx + ($r + 20) * cos(deg2rad($midAngle));
    $labelY = $cy + ($r + 20) * sin(deg2rad($midAngle));
    $textAnchor = $midAngle > 180 ? 'end' : 'start';

    echo '<text x="' . $labelX . '" y="' . $labelY . '" text-anchor="' . $textAnchor . '" fill="black">' . $row['relation'] . ' (' . $row['count'] . ')</text>';

    $startAngle = $endAngle;
}
echo '</svg>';

echo "<h1>Survey Answers</h1>";
echo "<table border='1'>
<tr>
<th>Timestamp</th>
<th>Rating</th>
<th>Relation</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['timestamp'] . "</td>";
    echo "<td>" . $row['rating'] . "</td>";
    echo "<td>" . $row['relation'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($dbhandle);
?>