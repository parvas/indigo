<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>

<?php echo Form::open(array('enctype' => 'multipart/form-data')); ?>
<?php echo Form::errors(); ?>
<?php echo Form::label(_NAME_, 'product_name', true); ?>
<?php echo Form::text('product_name'); ?>
<?php echo Form::label(_DESCRIPTION_, 'product_description'); ?>
<?php echo Form::text('product_description'); ?>
<?php echo Form::label('Ανήκει στην κατηγορία', 'product_parent'); ?>
<?php echo Form::select('product_parent', $categories) ?>
<?php echo Form::label('Εικόνα', 'product_picture'); ?>
<?php echo Form::file('product_picture'); ?>
<?php echo Form::submit(_SUBMIT_); ?>
<?php echo Form::close(); ?>
