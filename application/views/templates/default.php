<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" name="Indigo Labs">
<meta name="generator" name="Indigo PHP Framework">
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>">
<meta name="keywords" content="<?php echo $keywords; ?>">
<link rel="stylesheet" type="text/css" media="all" href="<?=CSS?>styles.css">
<?php echo $head; ?>
</head>
<body>
    <div id="container">
        <?php echo "<h1>{$title}</h1>"; ?>
        <?php echo $body; ?>
    </div>
    <div id="footer">
    Powered by <span id="indigo">indigo</span>
    </div>
</body>
</html>