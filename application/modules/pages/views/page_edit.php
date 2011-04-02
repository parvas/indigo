<?php defined('SYSTEM') or exit('No direct script access allowed');?>
<div id="errors">
<? if (isset($errors))
    echo $errors;?> 
</div>
<?= Form::open(); ?>
<?= Form::label('Title', 'title', TRUE); ?>
<?= Form::text('title', array('value' => $title))?>
<?= Form::label('Summary', 'summary'); ?>
<?= Form::textarea('summary', array(), $summary); ?>
<?= Form::label('Content', 'content', TRUE); ?>
<?= Form::textarea('content', array(), $content); ?>
<?= Form::submit('submit', 'Submit'); ?>
<?= Form::close();?>