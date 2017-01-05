<?php
/*
 * A PHP file for rendering our home page
 */
?>
<? start_snippet('shell'); ?>

<h1>3shrink</h1>

<div class="cols">


<div class="col w50 ac">
<?= snippet('form', array('label' => "Shrink Me", 'action' => 'shrink')) ?>
</div>

<div class="col w50 ac">
<?= snippet('form', array('label' => "Grow Me", 'action' => 'grow')) ?>
</div>


<div class="clear"></div>

</div>

<?= end_snippet(); ?>
