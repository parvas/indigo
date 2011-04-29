<style>
    .indigo_error {
        margin: 20px;
        font: 13px Arial;
        border: 1px dotted #888;
    }
    
    .error_header {
        background-color: #911;
        color: #fff;
        padding: 5px 20px;
        font-size: 13px;
    }
    
    .error_details {
        background-color: #ddd;
        padding: 5px 20px;
    }
</style>

<div class="indigo_error">
    <div class="error_header"><?php echo $type; ?> &bull; <?php echo $message; ?></div>
    <div class="error_details">
        <?php echo $file; ?> [<?php echo $line; ?>]
    </div>
</div>