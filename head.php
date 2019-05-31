<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="utf-8">
    <title><?php echo $site_title ?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/common.css">
    <?php if (isset($css_file_title)): ?>
      <link rel="stylesheet" href="css/<?php echo $css_file_title?>.css">
    <?php endif ?>
  </head>
