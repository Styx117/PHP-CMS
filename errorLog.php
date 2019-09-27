<?php
//Set the error engien to true
error_reporting(E_ALL); 
//Make sure repeated errors aren't recorded
ini_set('ignore_repeated_errors', TRUE);
//Since this project will not be hosted to the worl and this is onyl for testing purposes, always display the errors
ini_set('display_errors', TRUE); 
//Set the error logging engine to true
ini_set('log_errors', TRUE); 
//Specify file path, where to store the errors
ini_set('error_log', 'logs\errorLog.txt');
//Make the maximum size of that file to 1024
ini_set('log_errors_max_len', 1024); // Logging file size
?>