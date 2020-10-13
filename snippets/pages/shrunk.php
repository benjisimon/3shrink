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
<div class="output">
  <input class="w100" onclick="this.select()" type="text" value="<?= $item['abbreviation'] ?>"/>
  <input class="w100" onclick="this.select()" type="text" value="<?= item_url($item) ?>"/>
</div>


<?= end_snippet(); ?>
