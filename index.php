<?php
$excelFiles = glob('files/*.xlsx');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Export Form</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Export Data to Excel</h3>
                    </div>
                    <div class="card-body">
                        <form id="exportForm" action="export_excel.php" method="post">
                            <div class="form-group mb-3">
                                <label for="export_type">Select Data Type:</label>
                                <select class="form-control" id="export_type" name="export_type">
                                    <option value="deposits">Deposits</option>
                                    <option value="users">Users</option>
                                </select>
                            </div>
                            <div id="depositsFields"> <!-- Fields for exporting deposits -->
                                <div class="form-group mb-3">
                                    <label for="status">Status (for Deposits):</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="0">Failed</option>
                                        <option value="1">Successful</option>
                                        <option value="2">Pending</option>
                                        <option value="3">Cancelled</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="date_range">Date Range (for Deposits):</label>
                                    <select class="form-control" id="date_range" name="date_range">
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="past_week">Past Week</option>
                                        <option value="past_month">Past Month</option>
                                        <option value="all_time">All Time</option>
                                    </select>
                                </div>
                            </div>
                            <div id="usersFields"> <!-- Fields for exporting users -->
                                <div class="form-group mb-3">
                                    <label for="date_range_users">Date Range (for Users):</label>
                                    <select class="form-control" id="date_range_users" name="date_range_users">
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="past_week">Past Week</option>
                                        <option value="past_month">Past Month</option>
                                        <option value="all_time">All Time</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <button type="submit" name="export" class="btn btn-primary w-100">Export to Excel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Download Files</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($excelFiles as $file) : ?>
                                <li class="list-group-item">
                                    <a href="<?php echo $file; ?>" download><?php echo basename($file); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        // JavaScript to toggle form fields based on selected data type
        $(document).ready(function() {
            const depositsFields = $('#depositsFields');
            const usersFields = $('#usersFields');

            // Initially, hide users fields
            usersFields.hide();

            $('#export_type').change(function() {
                if ($(this).val() === 'deposits') {
                    depositsFields.show();
                    usersFields.hide();
                } else if ($(this).val() === 'users') {
                    depositsFields.hide();
                    usersFields.show();
                }
            });
        });
    </script>
</body>
</html>
