<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <!-- TODO -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <!-- Bootstrap core CSS -->
        <link href="/css/bootstrap.min.css" rel="stylesheet"/>

        <!-- Custom styles for this template -->
        <link href="signin.css" rel="stylesheet">

        <!-- ??? -->
        <link href="/css/bootstrap-theme.min.css" rel="stylesheet"/>

        <!-- TODO CS50 CSS
        <link href="/css/styles.css" rel="stylesheet"/> -->

        <?php if (isset($title)): ?>
            <title>Edinote: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>Edinote</title>
        <?php endif ?>

        <!-- TODO
        <script src="/js/jquery-1.11.1.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/scripts.js"></script> -->

    </head>

    <body>

        <div class="container">

            <div id="top">
                <a href="/"><p>Edinote</p></a>
            </div>

            <div id="middle">
