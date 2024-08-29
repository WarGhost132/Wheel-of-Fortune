<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang'] ?? 'en') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($PAGE_CONTENT['title'] ?? 'Default Title') ?></title>
    <meta
        name="description"
        content="<?php echo htmlspecialchars($PAGE_CONTENT['description'] ?? 'Default description') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#ffffff">

    <?php include_top_cdns(); ?>

    <!-- You can put metrics here, like Google Ads, Yandex Metrics, etc. -->
</head>

<body>
    <?php require 'includes/components/header.php'; ?>

    <?php echo $PAGE_CONTENT['body'] ?? '' ?>

    <?php require 'includes/components/footer.php'; ?>

    <?php include_bottom_cdns(); ?>
</body>

</html>