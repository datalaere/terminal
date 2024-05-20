<?php

// Function to display Message of the Day
function motd() {
    if (!isset($_SESSION['welcomed'])) {
        include('sys/var/boot.txt');
        $_SESSION['welcomed'] = true; // Set the welcomed flag
        require('sys/lib/welcome.php');
        exit;
    } else {
        require('sys/lib/welcome.php');
        exit;
    }
}

// Function to get help information for commands
function getHelpInfo($command) {
    $helpInfo = include 'sys/lib/help.php';

    $command = strtoupper($command);
    
    if (!empty($command)) {
        return isset($helpInfo[$command]) ? $helpInfo[$command] : "Command not found.";
    }
    $helpText = "HELP:\n";
    foreach ($helpInfo as $cmd => $description) {
        $helpText .= " $cmd $description\n";
    }
    return $helpText;
}