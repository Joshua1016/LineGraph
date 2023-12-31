<?php
require_once '../libraries/database.php';
require_once '../models/VarietyModel.php';

$dbObj = new Database();
$dbConnection = $dbObj->openConnection();
$userObj = new Variety($dbConnection);

$userList = $userObj->fetchAll();
$fetchtotal = $userObj->fetchTotal();

require_once('../layout/header.php');
// loader
require_once('../layout/loader.php');
?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

<div class="container-fluid">
    <h1 style="text-align: center; font-family: cursive">
        Variety Graph Details <br>
    </h1>
    <div class="row">
        <div class="col-md-5">
            <div class="table-responsive table-container">
                <table id="example" class="table table-striped custom-table" width="100%">
                    <!-- ... table content ... -->
                    <thead class="responsive" style="text-align: center;">
                        <th></th>
                        <th>Week</th>
                        <th>Gross Cane</th>
                        <th>Net Cane</th>
                    </thead>
                    <tbody>
                        <?php
                        $transactionDates = [];
                        $grsCaneDailyTotal = [];
                        $netCaneDailyTotal = [];

                        foreach ($userList as $item) {
                            $transactionDates[] = $item['week'];
                            $grsCaneDailyTotal[] = floatval(str_replace(',', '', $item['total_grscane']));
                            $netCaneDailyTotal[] = floatval(str_replace(',', '', $item['netcane']));

                            echo "<tr>";
                            echo '<td><a href="variety_details.php?TargetWeek=' . $item['week'] . '">' . "view" . '</a></td>';
                            echo '<td>' . $item['week'] . '</td>';
                            echo '<td>' . $item['total_grscane'] . '</td>';
                            echo '<td>' . $item['netcane'] . '</td>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-7">
            <br>
            <canvas id="myLineChart" width="800" height="400"></canvas>
        </div>
    </div>

    <script>
        var transactionDates = <?php echo json_encode($transactionDates); ?>;
        var grsCaneDailyTotal = <?php echo json_encode($grsCaneDailyTotal); ?>;
        var netCaneDailyTotal = <?php echo json_encode($netCaneDailyTotal); ?>;

        console.log('Transaction Dates:', transactionDates);
        console.log('Gross Cane Data:', grsCaneDailyTotal);
        console.log('Net Cane Data:', netCaneDailyTotal);

        var myLineChart = new Chart(document.getElementById('myLineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: transactionDates,
                datasets: [{
                        label: 'Gross Cane',
                        borderColor: 'rgb(75, 192, 192)',
                        data: grsCaneDailyTotal,
                        fill: false
                    },
                    {
                        label: 'Net Cane',
                        borderColor: 'rgb(255, 99, 132)',
                        data: netCaneDailyTotal,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        ticks: {
                            callback: function (value, index, values) {
                                return new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
                            }
                        }
                    }
                },
                onHover: function (event, chartElement) {
                    console.log(event, chartElement);
                },
                onClick: function (event, chartElement) {
                    console.log(event, chartElement);
                },
               
            }
        });
    </script>

    <?php require_once('../layout/footer.php'); ?>
