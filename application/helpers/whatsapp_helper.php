<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('format_wa_number')) {
    function format_wa_number($hp) {
        // Clean non-numeric characters
        $hp_wa = preg_replace('/\D/', '', $hp);
        
        // Convert 0 prefix to 62
        if (substr($hp_wa, 0, 1) == '0') {
            $hp_wa = '62' . substr($hp_wa, 1);
        }
        
        return $hp_wa;
    }
}