<?php defined('SYSTEM') or exit('No direct script access allowed'); ?>

<?php echo Form::errors(); ?>
<?php echo Form::open(); ?>
<?php echo Form::label(_NAME_, 'name', TRUE); ?>
<?php echo Form::text('name', Arr::item($data, 'name')); ?>
<?php echo Form::label(_DESCRIPTION_, 'description'); ?>
<?php echo Form::textarea('description', Arr::item($data, 'description')); ?>
<?php echo Form::label('Ανήκει στην κατηγορία', 'parent'); ?>
<?php echo Form::select('parent', Arr::item($data, 'categories'), Arr::item($data, 'parent')); ?>
<?php echo Form::submit(_SUBMIT_); ?>
<?php echo Form::close();?>