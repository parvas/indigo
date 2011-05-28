<?php defined('SYSTEM') or exit('No direct script access allowed'); ?>
<p><?php echo Arr::item($data, 'summary'); ?></p>
<?php echo Arr::item($data, 'content'); ?>