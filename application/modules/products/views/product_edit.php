<?php defined('SYSTEM') or exit('No direct script access allowed'); ?>

<?php echo Form::errors(); ?>
<?php echo Form::open(); ?>
<?php echo Form::label(_NAME_, 'category_name', TRUE); ?>
<?php echo Form::text('category_name', array('value' => $name))?>
<?php echo Form::label(_DESCRIPTION_, 'category_description'); ?>
<?php echo Form::textarea('category_description', array(), $description); ?>
<?php echo Form::label('Ανήκει στην κατηγορία', 'parent_category'); ?>
<?php echo Form::select('parent_category', $categories, $parent); ?>
<?php echo Form::submit(_SUBMIT_); ?>
<?php echo Form::close();?>