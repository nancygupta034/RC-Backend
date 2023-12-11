<?php
$appVersion = "1.0.0";
$pagename = strtolower(basename($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
    <title>Responsive Centers CMS</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="shortcut icon" type="image/png" href="{{url('assets/images/favicon.png')}}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400&family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500&display=swap" rel="stylesheet">

    <!-- <link rel="stylesheet" href="{{url('assets/css/app.css?v=<?php echo $appVersion; ?>')}}"> -->

    @vite(['resources/js/app.js'])
</head>
<body style="background-image: url({{url('assets/images/page-bg.webp')}});">
    <div id="app"></div>
</body>
</html>