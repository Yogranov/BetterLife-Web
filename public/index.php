<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
?>

<html lang="he">
<head>
    <meta charset="utf-8">

    <title>The HTML5 Herald</title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="system/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="system/aos/aos.css">

</head>

<body>
<div class="container">
    <h1 data-aos="fade-up">Test</h1>
</div>

<script src="system/bootstrap/js/bootstrap.js"></script>
<script src="system/aos/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>


