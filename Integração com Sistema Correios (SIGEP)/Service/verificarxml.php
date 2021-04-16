<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 'E_ALL|E_STRICT');
error_reporting(E_ALL);

echo '<p>This is XML string content:</p>';
echo '<pre>';
echo htmlspecialchars($_SESSION['XML']);
echo '</pre>';

?>