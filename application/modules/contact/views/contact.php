<?php defined('SYSTEM') or exit('No direct script access allowed');?>
<?= Form::errors(); ?> 
<?= Form::open(); ?>
<?= Form::label('Title', 'title2', TRUE); ?>
<?= Form::text('title2')?>
<?= Form::label('Summary', 'summary2'); ?>
<?= Form::textarea('summary2'); ?>
<?= Form::label('Content', 'content2', TRUE); ?>
<?= Form::textarea('content2'); ?>
<?= Form::submit('submit', 'Submit'); ?>
<?= Form::close();?>