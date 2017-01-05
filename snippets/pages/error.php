<?php
/*
 * A PHP file for rendering an error page
 */

http_response_code($code);
?>
<? start_snippet('shell'); ?>
<h1>D'oh.</h1>

<p class="ac">
  <?= $message ?>
</p>


<?= end_snippet() ?>
