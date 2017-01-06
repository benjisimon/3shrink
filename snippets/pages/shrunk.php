<?php
/*
 * A PHP file for showing the outcome of the shrinking process
 */

maybe_geek_output($item['abbreviation']);
?>
<? start_snippet('shell') ?>
<h1><a href='/<?= $item['abbreviation'] ?>'><?= $item['abbreviation'] ?></a>
<?= end_snippet(); ?>
