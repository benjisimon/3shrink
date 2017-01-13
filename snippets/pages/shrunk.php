<?php
/*
 * A PHP file for showing the outcome of the shrinking process
 */
register_hook('shell_title', function($title) use($item) {
  return "{$item['abbreviation']} | $title";
});
maybe_geek_output($item['abbreviation']);
?>
<? start_snippet('shell') ?>
<h1><a href='/<?= $item['abbreviation'] ?>'><?= $item['abbreviation'] ?></a>
<?= end_snippet(); ?>
