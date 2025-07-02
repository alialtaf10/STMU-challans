<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DOMPDF Options
    |--------------------------------------------------------------------------
    |
    | Allowed options and their defaults.
    | For more information see:
    | https://github.com/dompdf/dompdf/blob/master/src/Options.php
    |
    */

    'show_warnings' => false,   // Set to true for debugging
    'defaultPaperSize' => 'a4',
    'defaultFont' => 'dejavu sans',
    'dpi' => 96,
    'isPhpEnabled' => true,
    'isRemoteEnabled' => true,
    'isHtml5ParserEnabled' => true,
    'isFontSubsettingEnabled' => true,
    
    // Additional options for better PDF rendering
    'debugPng' => false,
    'debugKeepTemp' => false,
    'debugCss' => false,
    'debugLayout' => false,
    'debugLayoutLines' => false,
    'debugLayoutBlocks' => false,
    'debugLayoutInline' => false,
    'debugLayoutPaddingBox' => false,
    
    // Temp directory
    'tempDir' => storage_path('app/dompdf'),
    
    // Font directory
    'fontDir' => storage_path('fonts/'),
    
    // Font cache directory
    'fontCache' => storage_path('fonts/'),
    
    // Log output file
    'logOutputFile' => storage_path('logs/dompdf.html'),
];