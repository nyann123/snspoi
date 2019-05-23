<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="utf-8">
    <title><?php echo $site_title ?></title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/common.css">
    <?php if (isset($file_title)): ?>
    <link rel="stylesheet" href="css/<?php echo $file_title?>.css">
    <?php endif ?>
  </head>
