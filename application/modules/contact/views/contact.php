<?php defined('SYSTEM') or exit('No direct script access allowed');?>
<?= Form::errors(); ?> 
<?= Form::open(); ?>
<?= Form::label(_TITLE_, 'title2', TRUE); ?>
<?= Form::text('title2')?>
<?= Form::label(_SUMMARY_, 'summary2'); ?>
<?= Form::textarea('summary2'); ?>
<?= Form::label(_CONTENT_, 'content2', TRUE); ?>
<?= Form::textarea('content2'); ?>
<?= Form::submit(_SUBMIT_); ?>
<?= Form::reset(_RESET_); ?>
<?= Form::close();?>