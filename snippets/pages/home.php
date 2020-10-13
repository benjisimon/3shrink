<?php
/*
 * A PHP file for rendering our home page
 */
?>
<? start_snippet('shell'); ?>

<?= snippet('form', array('label' => "Shrink Me", 'action' => 'shrink')) ?>
<?= snippet('form', array('label' => "Grow Me", 'action' => 'grow')) ?>

<?= end_snippet(); ?>
