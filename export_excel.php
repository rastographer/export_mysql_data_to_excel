<?php
require 'database.php'; // Include the database connection code
require 'vendor/autoload.php'; // Include PhpSpreadsheet autoload

function sanitizeKenyanPhoneNumber($phone) {
    // Remove all non-digit characters
    $phone = preg_replace('/\D/', '', $phone);

    // Check if the number starts with '0' or '+254' and adjust accordingly
    if (strlen($phone) == 9) {
        // If it's a 9-digit number, prepend '254'
        $phone = '254' . $phone;
    } elseif (substr($phone, 0, 4) == '2540') {
        // If it starts with '254', remove any '+' sign
        $phone = '254' . substr($phone, 4);
    }

    // Ensure the final format is (254xxxxxxxxx)
    $phone = '254' . substr($phone, -9);

    return $phone;
}

$excelDirectory = 'files/';

if (isset($_POST['export'])) {
    $exportType = isset($_POST['export_type']) ? $_POST['export_type'] : '';
    $dateRange = isset($_POST['date_range_users']) ? $_POST['date_range_users'] : 'all_time';

    if ($exportType === 'users') {
        // Determine the date range based on the selected option
        switch ($dateRange) {
            case 'today':
                $startDate = date('Y-m-d');
                $endDate = date('Y-m-d');
                break;
            case 'yesterday':
                $startDate = date('Y-m-d', strtotime('-1 day'));
                $endDate = date('Y-m-d', strtotime('-1 day'));
                break;
            case 'past_week':
                $startDate = date('Y-m-d', strtotime('-7 days'));
                $endDate = date('Y-m-d');
                break;
            case 'past_month':
                $startDate = date('Y-m-d', strtotime('-1 month'));
                $endDate = date('Y-m-d');
                break;
            default:
                $startDate = '2023-10-01'; // A date that covers your user data from the beginning
                $endDate = date('Y-m-d');
                break;
        }

    // Create a new Spreadsheet object
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();

        // Add headers to the Excel file
    $worksheet->setCellValue('A1', 'Mobile');
    $worksheet->setCellValue('B1', 'Username');
    $worksheet->setCellValue('C1', 'Email');
    $worksheet->setCellValue('D1', 'ReferrerEmail');
    $worksheet->setCellValue('E1', 'CreatedAt');

    // SQL Query to retrieve user data including referrer's email
    $query = "SELECT u1.mobile AS mobile, u1.username AS username, u1.email AS email, 
                     u2.email AS referrer_email, u1.created_at AS created_at
              FROM users AS u1
              LEFT JOIN users AS u2 ON u1.ref_by = u2.id
              WHERE u1.created_at >= ? AND u1.created_at <= ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging: Print the SQL query
    // echo "SQL Query: " . $query . "<br>";

    // Loop through the result and populate the Excel file
    $row = 2;
    while ($row_data = $result->fetch_assoc()) {
        // Debugging: Print the results
        // echo "Email: " . $row_data['email'] . "<br>";

        $mobile = sanitizeKenyanPhoneNumber($row_data['mobile']);

        $worksheet->setCellValue('A' . $row, $mobile);        
        $worksheet->setCellValue('B' . $row, $row_data['username']);
        $worksheet->setCellValue('C' . $row, $row_data['email']);

        if($row_data['referrer_email'] > 0){
            $ref_by = $row_data['referrer_email'];
        } else {
            $ref_by = 'You were Referred by No One';
        }

        $worksheet->setCellValue('D' . $row, $ref_by);
    
        // Format the "Created At" date using the specified format
        $createdAt = new DateTime($row_data['created_at']);
        $formattedCreatedAt = $createdAt->format('l jS \o\f F Y h:i:s A');
        $worksheet->setCellValue('F' . $row, $formattedCreatedAt);
        
        $row++;
    }

        // Naming and saving the file
        $t = time();
        $rand = "ACU" . rand(1000, 9999) . "_";
        $filename = $rand . "_Users_" . date("Y-m-d", $t) . ".xlsx";

        // Set the content type and header for the Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');

        // Save the Excel file to the output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $excelFilePath = $excelDirectory . $filename;
        $writer->save('php://output');
        exit; // Terminate the script to prevent further output
    } else {
        // Handle deposits export here (existing code for deposits export)
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0; // Sanitize the input

        // Date range parameters (e.g., 'today', 'yesterday', 'past_week', 'past_month', 'all_time')
        $dateRange = isset($_POST['date_range']) ? $_POST['date_range'] : 'all_time';
    
        // Determine the date range based on the selected option
        switch ($dateRange) {
            case 'today':
                $startDate = date('Y-m-d');
                $endDate = date('Y-m-d');
                break;
            case 'yesterday':
                $startDate = date('Y-m-d', strtotime('-1 day'));
                $endDate = date('Y-m-d', strtotime('-1 day'));
                break;
            case 'past_week':
                $startDate = date('Y-m-d', strtotime('-7 days'));
                $endDate = date('Y-m-d');
                break;
            case 'past_month':
                $startDate = date('Y-m-d', strtotime('-1 month'));
                $endDate = date('Y-m-d');
                break;
            default:
                $startDate = '2023-10-01'; // A date that covers your data from the beginning
                $endDate = date('Y-m-d');
                break;
        }
    
        // Create a new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
    
        // Add headers to the Excel file
        $worksheet->setCellValue('A1', 'Phone');
        $worksheet->setCellValue('B1', 'Username');
        $worksheet->setCellValue('C1', 'Email');
        $worksheet->setCellValue('D1', 'Plan Name');
        $worksheet->setCellValue('E1', 'Amount');
        $worksheet->setCellValue('F1', 'CreatedAt');
    
        // Query to retrieve data with date range filtering using prepared statements
        $query = "SELECT users.mobile, users.username, users.email, plans.name, deposits.amount, deposits.created_at
                  FROM deposits
                  JOIN users ON deposits.user_id = users.id
                  JOIN plans ON deposits.plan_id = plans.id
                  WHERE deposits.status = ? AND deposits.created_at >= ? AND deposits.created_at <= ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $status, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Loop through the result and populate the Excel file
        $row = 2;
        while ($row_data = $result->fetch_assoc()) {
            $mobile = sanitizeKenyanPhoneNumber($row_data['mobile']);
        
            $worksheet->setCellValue('A' . $row, $mobile);        
            $worksheet->setCellValue('B' . $row, $row_data['username']);
            $worksheet->setCellValue('C' . $row, $row_data['email']);
            $worksheet->setCellValue('D' . $row, $row_data['name']);
            $worksheet->setCellValue('E' . $row, $row_data['amount']);
        
            // Format the "Created At" date using the specified format
            $createdAt = new DateTime($row_data['created_at']);
            $formattedCreatedAt = $createdAt->format('l jS \o\f F Y h:i:s A');
            $worksheet->setCellValue('F' . $row, $formattedCreatedAt);
        
            $row++;
        }
        
    
        // Naming and saving the file
        $t = time();
        $rand = "ACR" . rand(1000, 9999) . "_";
        $statusLabels = ["Failed", "Successful", "Pending", "Cancelled"];
        $filename = $rand . $statusLabels[$status] . "_Deposits_" . date("Y-m-d", $t) . ".xlsx";
    
        // Set the content type and header for the Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
    
        // Save the Excel file to the output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $excelFilePath = $excelDirectory . $filename;
        $writer->save('php://output');
        exit; // Terminate the script to prevent further output
    }
}
?>
