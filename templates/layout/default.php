<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakeGOV: Get your cake and eat it too!';
?>
<!DOCTYPE html>
<html>
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KXKX1KLHTY"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-KXKX1KLHTY');
    </script>

    <title>Treasury Tracker - <?= $title ?></title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <meta name="csrfToken" content="<?= $this->request->getAttribute('csrfToken') ?>">
    <?= $this->Html->css('default.css') ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

    <!-- temp icon set -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">

    <?= $this->Html->css('style.css') ?>
</head>
<body>
    <?= $this->element('header') ?>
    <main>
        <?= $this->fetch('content') ?>
    </main>
    <?= $this->element('footer') ?>
    <script src="/js/main.js"></script>
</body>
</html>
