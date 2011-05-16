<?php defined('SYSTEM') or exit('No direct script access allowed'); ?>

<?php echo Form::errors(); ?>
<?php echo Form::open(); ?>
<?php echo Form::label(_TITLE_, 'title', TRUE); ?>
<?php echo Form::text('title', Arr::item($data, 'title')); ?>
<?php echo Form::label(_SUMMARY_, 'summary'); ?>
<?php echo Form::textarea('summary', Arr::item($data, 'summary')); ?>
<?php echo Form::label(_CONTENT_, 'content', TRUE); ?>
<?php echo Form::textarea('content', Arr::item($data, 'content')); ?>
<?php echo Form::label(_KEYWORDS_, 'keywords'); ?>
<?php echo Form::textarea('keywords', Arr::item($data, 'keywords')); ?>
<?php echo Form::label(_DESCRIPTION_, 'description'); ?>
<?php echo Form::textarea('description', Arr::item($data, 'description')); ?>
<?php echo Form::submit(_SUBMIT_); ?>
<?php echo Form::close();?>