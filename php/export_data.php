<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="export_users.csv"');

$conn = new mysqli('localhost', 'root', '', 'emargement');
if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM users");

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Username', 'Role']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
?>
