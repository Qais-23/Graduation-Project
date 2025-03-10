<?php
session_start();
require 'database.php';  // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['customerID']) && !isset($_SESSION['emp_id'])) {
    echo "User not logged in.";
    exit;
}

// Get the ticket ID from the AJAX request
$ticketID = $_GET['ticket_id'];

// Prepare SQL to fetch messages for the ticket
$stmt = $pdo->prepare("
    SELECT t.message, t.message_date, e.Employee_Name AS employee_name, c.Name AS customer_name
    FROM support_tickets t
    LEFT JOIN employee e ON t.employee_id = e.Employee_Id
    LEFT JOIN customer c ON t.customer_id = c.customerID
    WHERE t.ticket_id = :ticket_id
    ORDER BY t.message_date DESC
");
$stmt->bindParam(':ticket_id', $ticketID);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through the messages and output them
foreach ($messages as $message) {
    echo "<div class='message'>";
    if ($message['employee_name']) {
        echo "<strong>Support (" . $message['employee_name'] . "):</strong><br>";
    } else {
        echo "<strong>You:</strong><br>";
    }
    echo nl2br(htmlspecialchars($message['message'])) . "<br>";
    echo "<small>" . $message['message_date'] . "</small>";
    echo "</div><hr>";
}
