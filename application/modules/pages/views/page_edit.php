<?php defined('SYSTEM') or exit('No direct script access allowed'); ?>
<div id="errors">
<? if (isset($errors))
    echo $errors;?> 
</div>
<?= Form::open(); ?>
<?= Form::label(_TITLE_, 'title', TRUE); ?>
<?= Form::text('title', array('value' => $title))?>
<?= Form::label(_SUMMARY_, 'summary'); ?>
<?= Form::textarea('summary', array(), $summary); ?>
<?= Form::label(_CONTENT_, 'content', TRUE); ?>
<?= Form::textarea('content', array(), $content); ?>
<?= Form::submit(_SUBMIT_); ?>
<?= Form::close();?>