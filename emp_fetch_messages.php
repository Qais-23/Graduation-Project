<?php
require 'database.php';

if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    // Fetch messages for the specific ticket
    $stmt = $pdo->prepare("SELECT message, last_response FROM support_tickets WHERE ticket_id = :ticket_id");
    $stmt->bindParam(':ticket_id', $ticket_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the messages
    foreach ($messages as $message) {
        echo "<div>" . nl2br(htmlspecialchars($message['message'])) . " <small>at " . $message['last_response'] . "</small></div>";
    }
}
