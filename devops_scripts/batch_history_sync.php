<?php
ini_set('memory_limit', '-1');

$startDate = new DateTime('2023-06-20');
$endDate = new DateTime('2024-06-01'); // We know recent ones are there, so up to mid 2024 is plenty to cover the old era.

$interval = new DateInterval('P30D'); 
$period = new DatePeriod($startDate, $interval, $endDate);

$fullSqlFile = 'D:\NTT_WEBSITE\history_dump.sql';
file_put_contents($fullSqlFile, ""); // Clear previous

echo "Starting chunked history download...\n";

foreach ($period as $dt) {
    $startStr = $dt->format('Y-m-d');
    $endDt = clone $dt;
    $endDt->add(new DateInterval('P30D'));
    if ($endDt > $endDate) $endDt = $endDate;
    $endStr = $endDt->format('Y-m-d');

    echo "Fetching $startStr to $endStr...\n";
    
    $url = "https://newsthetruth.com/backup_direct.php?start=$startStr&end=$endStr";
    $content = file_get_contents($url);
    if ($content) {
        file_put_contents($fullSqlFile, $content . "\n", FILE_APPEND);
        echo " -> Allowed Size: " . strlen($content) . " bytes\n";
    }
}

echo "Done fetching chunks. File built at: $fullSqlFile\n";
?>
