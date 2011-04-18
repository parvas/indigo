<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" name="Paraschos Vasiliadis">
<meta name="generator" name="Indigo PHP Framework">
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>">
<meta name="keywords" content="<?php echo $keywords; ?>">
<link rel="stylesheet" href="<?=CSS?>styles.css">
<?php echo $head; ?>
</head>
<body>
    <header>
        indigo
    </header>    
    <nav>
        <ul>
            <li><?php echo HTML::a(WEB . 'pages/add', _ADD_PAGE_); ?></li>
        </ul>
    </nav>
    <div id="container">
        <div id="langs">
            <?php echo I18n::instance()->locale_box(); ?>
        </div>
        <?php echo "<h1>{$title}</h1>"; ?>
        <?php echo $body; ?>
    </div>
    <footer>
    Powered by <a id="indigo" target="_blank" href="https://github.com/parvas/indigo">indigo</a>
    </footer>
</body>
</html>