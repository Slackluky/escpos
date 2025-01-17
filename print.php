<?php
require 'vendor/autoload.php'; // Load escpos-php library

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

header('Content-Type: application/json');

try {
    // Check if required query parameters are provided
    if (! isset($_GET['text']) || ! isset($_GET['printer']) || ! isset($_GET['type']) || ! isset($_GET['redirect'])) {
        throw new Exception("Missing required query parameters: 'text', 'printer', 'type', or 'redirect'.");
    }

    // Get query parameters
    $text           = $_GET['text'];
    $printerAddress = $_GET['printer'];
    $printerType    = strtolower($_GET['type']); // Windows or network
    $redirectUrl    = $_GET['redirect'];

    // Printer setup based on type
    if ($printerType === 'windows') {
        $connector = new WindowsPrintConnector($printerAddress);
    } elseif ($printerType === 'network') {
        $connector = new NetworkPrintConnector($printerAddress, 9100); // Default port for network printers
    } else {
        throw new Exception("Invalid printer type. Supported types are 'Windows' and 'network'.");
    }

    // Initialize the printer
    $printer = new Printer($connector);

    // Print the received text
    $printer->text($text . "\n");
    $printer->cut();
    $printer->feed();

    // Close the printer connection
    $printer->close();

    // Redirect to the specified URL
    header("Location: " . $redirectUrl);
    exit;
} catch (Exception $e) {
    // Handle errors
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}