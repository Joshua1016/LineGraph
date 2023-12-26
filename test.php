<?php
require_once '../libraries/database.php';
require_once '..//models/SugarModel.php';

$dbObj = new Database();
$dbConnection = $dbObj->openConnection();
$userObj = new SugarModel($dbConnection);
$userList = $userObj->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Graph</title>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <!-- <table>
        <thead>
            <th>week</th>
            <th>netcane</th>
            <th>grosscane</th>
        </thead>
        <tbody> -->
    <?php
    foreach ($userList as $obj) {
        // echo "<tr>";
        // echo '<td style="font-size: 20px;">' . $obj['week'] . '</td>';
        // echo '<td style="font-size: 20px;">' . $obj['netcane'] . '</td>';
        // echo '<td style="font-size: 20px;">' . $obj['grosscane'] . '</td>';
        // echo "</tr>";
    }
    ?>
    <!-- </tbody>
    </table> -->

    <canvas id="myLineChart" width="400" height="400"></canvas>

    <script>
        // Extracting data from PHP to JavaScript
        var weekData = <?php echo json_encode(array_map(function ($week) {
                            return "Week " . $week;
                        }, array_column($userList, 'week'))); ?>;
        var netcaneData = <?php echo json_encode(array_column($userList, 'netcane')); ?>;
        var grosscaneData = <?php echo json_encode(array_column($userList, 'grosscane')); ?>;

        var ctx = document.getElementById('myLineChart').getContext('2d');

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: weekData,
                datasets: [{
                    label: 'Netcane',
                    data: netcaneData,
                    borderColor: 'blue',
                    borderWidth: 2,
                    fill: false
                }, {
                    label: 'Grosscane',
                    data: grosscaneData,
                    borderColor: 'green',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: false, // Set to false to control the size
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>