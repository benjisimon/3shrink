<?php
/*
 * A PHP file for showing expanded content
 */
maybe_geek_output($content);
?>
<?= start_snippet('shell'); ?>
<pre><?= $content ?></pre>
<?= end_snippet(); ?>
