<?php defined('SYSTEM') or exit('No direct script access allowed');?>
<div id="errors">
<?= Form::errors(); ?> 
</div>
<?= Form::open(); ?>
<?= Form::label('Title', 'title', TRUE); ?>
<?= Form::text('title')?>
<?= Form::label('Summary', 'summary'); ?>
<?= Form::textarea('summary'); ?>
<?= Form::label('Content', 'content', TRUE); ?>
<?= Form::textarea('content'); ?>
<?= Form::submit('submit', 'Submit'); ?>
<?= Form::close();?>