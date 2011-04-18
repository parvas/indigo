<?php defined('SYSTEM') or exit('No direct script access allowed');?>
<?= Form::errors(); ?> 
<?= Form::open(); ?>
<?= Form::label(_TITLE_, 'title', TRUE); ?>
<?= Form::text('title')?>
<?= Form::label(_SUMMARY_, 'summary'); ?>
<?= Form::textarea('summary'); ?>
<?= Form::label(_CONTENT_, 'content', TRUE); ?>
<?= Form::textarea('content'); ?>
<?= Form::label(_KEYWORDS_, 'keywords'); ?>
<?= Form::textarea('keywords'); ?>
<?= Form::label(_DESCRIPTION_, 'description'); ?>
<?= Form::textarea('description'); ?>
<?= Form::submit(_SUBMIT_); ?>
<?= Form::close();?>