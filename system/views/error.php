<html>
<head></head>
<body>
<style>
    .indigo_error {
        font: 13px Arial;
        margin: 20px;
        border: 1px dotted #888;
    }
    
    .error_header {
        background-color: #911;
        color: #fff;
        padding: 10px 20px;
        font-size: 13px;
    }
    
    .error_details, .trace {
        background-color: #ddd;
        padding: 5px 20px;
        font-family: arial;
    }
    
    .trace {
        font-size: 12px;
    }
    
    pre {
        font-family: arial;
    }
</style>
<div class="indigo_error">
    <div class="error_header"><?php echo $type; ?> &bull; <?php echo $message; ?></div>
    <div class="error_details">
        <?php echo $file; ?> [<?php echo $line; ?>]
    </div>
    <div class="trace">
        <?php echo $trace?>
    </div>
</div>

</body>
</html>