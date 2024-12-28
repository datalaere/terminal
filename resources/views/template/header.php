<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TeleTerm</title>
        <link rel="icon" type="image/x-icon" href="<?php base_url() ?>/img/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php base_url() ?>/css/app.min.css?v=<?php echo($_SESSION['hash']) ?>">
        <link id="theme-color" rel="stylesheet" href="<?php base_url() ?>/css/green-crt.css?v=<?php echo($_SESSION['hash']) ?>">
    <style>
    @font-face {
        font-family: "RIT-V300";
        src: url('<?php base_url() ?>/fonts/RIT-V300.ttf') format('truetype');
    }

    @font-face {
        font-family: "RX-9000";
        src: url('<?php base_url() ?>/fonts/RX-9000.woff2') format( 'woff2' ),
             url('<?php base_url() ?>/fonts/RX-9000.woff') format( 'woff' );
    }
    </style>
    </head>
        <body class="rit-v300" id="page">