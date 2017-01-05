<?php
/*
 * We're the home page, and we offer folks a way to shrink/grow
 * a URL.
 */
require_once('lib/siteconfig.php');
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
