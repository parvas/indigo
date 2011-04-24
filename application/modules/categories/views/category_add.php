<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>

<?= Form::open(); ?>
<?= Form::errors(); ?>
<?= Form::label(_NAME_, 'category_name', true); ?>
<?= Form::text('category_name'); ?>
<?= Form::label(_DESCRIPTION_, 'category_description'); ?>
<?= Form::text('category_description'); ?>
<?= Form::label('Ανήκει στην κατηγορία', 'parent_category'); ?>
<?= Form::select('parent_category', $categories) ?>
<?= Form::submit(_SUBMIT_); ?>
<?= Form::close(); ?>
