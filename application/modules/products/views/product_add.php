<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>

<?php echo Form::open(array('enctype' => 'multipart/form-data')); ?>
<?php echo Form::errors(); ?>
<?php echo Form::label(_NAME_, 'name', true); ?>
<?php echo Form::text('name'); ?>
<?php echo Form::label(_DESCRIPTION_, 'description'); ?>
<?php echo Form::text('description'); ?>
<?php echo Form::label('Ανήκει στην κατηγορία', 'parent'); ?>
<?php echo Form::select('parent', $categories) ?>
<?php echo Form::label('Εικόνα', 'picture'); ?>
<?php echo Form::file('picture'); ?>
<?php echo Form::submit(_SUBMIT_); ?>
<?php echo Form::close(); ?>
