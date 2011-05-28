<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>

<?php echo Form::errors(); ?>
<?php echo Form::open(); ?>
<?php echo Form::label('Title', 'title', TRUE); ?>
<?php echo Form::text('title', Arr::item($data, 'title')); ?>
<?php echo Form::label('Description', 'description', TRUE); ?>
<?php echo Form::textarea('description', Arr::item($data, 'description')); ?>
<?php echo Form::submit(Arr::item($data, 'action')); ?>
<?php echo Form::close(); ?>