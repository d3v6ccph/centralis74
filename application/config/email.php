<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $config = array(
        'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
        'smtp_host' => 'smtp.googlemail.com',
        'smtp_port' => 587, // 465
        'smtp_user' => 'gccphtest@gmail.com',
        'smtp_pass' => 'developer',
        'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
        'mailtype' => 'html', //plaintext 'text' mails or 'html'
        'charset' => 'iso-8859-1', // iso-8859-1
        'wordwrap' => TRUE
    );