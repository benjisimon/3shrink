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
<p>
  <input onclick="this.select()" type="text" value="<?= $item['abbreviation'] ?>"/>
</p>

<p>
  <input onclick="this.select()" type="text" value="<?= item_url($item) ?>"/>
</p>


<?= end_snippet(); ?>
