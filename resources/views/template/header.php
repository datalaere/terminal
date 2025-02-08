<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TeleTerm (TTY)</title>
        <link rel="icon" type="image/x-icon" href="<?php base_url() ?>/img/favicon.ico">
        <link rel="stylesheet" href="<?php base_url() ?>/css/app.min.css?v=<?php echo($_SESSION['hash']) ?>">
    <style>
    @font-face {
        font-family: "DEC-VT100";
        src: url('<?php base_url() ?>/fonts/DEC-VT100.ttf') format('truetype');
    }

    @font-face {
        font-family: "IBM-3270";
        src: url('<?php base_url() ?>/fonts/IBM-3270.woff2') format( 'woff2' ),
             url('<?php base_url() ?>/fonts/IBM-3270.woff') format( 'woff' );
    }
    </style>
    </head>
        <body class="rit-v300" id="page">