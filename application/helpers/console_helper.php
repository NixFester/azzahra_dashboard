<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Console Helper
 * Provides easy logging to browser console for debugging
 */

/**
 * Log to Browser Console
 * 
 * Logs variables to the browser's developer tools console
 * 
 * @param mixed $data - The data to log (string, array, object, etc.)
 * @param string $label - Optional label for the logged data
 * @param string $level - Console level: 'log', 'warn', 'error', 'info', 'debug'
 * 
 * @example
 * log_to_console($user_data);
 * log_to_console($error, 'Error Details', 'error');
 * log_to_console($config_array, 'Configuration', 'debug');
 */
function log_to_console($data, $label = null, $level = 'log')
{
    // Sanitize level to prevent injection
    $allowed_levels = array('log', 'warn', 'error', 'info', 'debug');
    $level = in_array($level, $allowed_levels) ? $level : 'log';
    
    // Convert data to JSON for safe console output
    $json_data = json_encode($data);
    
    if (empty($json_data)) {
        $json_data = 'null';
    }
    
    // Build the console statement
    if ($label !== null && !empty($label)) {
        $label = addslashes($label);
        $script = "console.{$level}('{$label}:', {$json_data});";
    } else {
        $script = "console.{$level}({$json_data});";
    }
    
    // Output script tag
    echo "<script>" . $script . "</script>";
}

/**
 * Log Table to Browser Console
 * 
 * Logs data as a table in the browser's console
 * 
 * @param array $data - Array of data to display as table
 * @param string $label - Optional label for the table
 * 
 * @example
 * log_table_to_console($users_array, 'User List');
 */
function log_table_to_console($data, $label = null)
{
    $json_data = json_encode($data);
    
    if (empty($json_data)) {
        $json_data = '[]';
    }
    
    if ($label !== null && !empty($label)) {
        $label = addslashes($label);
        $script = "console.log('{$label}:'); console.table({$json_data});";
    } else {
        $script = "console.table({$json_data});";
    }
    
    echo "<script>" . $script . "</script>";
}
