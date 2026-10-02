<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Xendit API Configuration
| -------------------------------------------------------------------------
| Enter your Xendit Secret API Key and Callback Verification Token here.
| You can obtain these from your Xendit Dashboard (https://dashboard.xendit.co).
|
*/

// Xendit Secret API Key (e.g. xnd_development_... or xnd_production_...)
$config['xendit_secret_key'] = 'xnd_development_YOUR_SECRET_KEY_HERE';

// Xendit Webhook/Callback Verification Token (from Xendit Dashboard -> Settings -> Webhooks)
$config['xendit_callback_token'] = 'YOUR_CALLBACK_VERIFICATION_TOKEN_HERE';

// Xendit API Base URL
$config['xendit_api_url'] = 'https://api.xendit.co';
