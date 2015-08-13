<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- TODO CS50 CSS -->
    <link href="/css/styles.css" rel="stylesheet"/>

    <!-- Custom styles for templates -->
    <link rel="stylesheet" href="/css/signin.css">
    <link rel="stylesheet" href="/css/dashboard.css">

    <?php if (isset($title)): ?>
        <title>Edinote: <?= htmlspecialchars($title) ?></title>
    <?php else: ?>
        <title>Edinote</title>
    <?php endif ?>

  </head>

  <body>
