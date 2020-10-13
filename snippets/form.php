<?php
/*
 * A PHP file for rendering our action forms
 */
?>
<form method="GET" action="/<?= $action ?>">
  <input class="flex" name="i" type="text"/>
  <input type="submit" value="<?= $label ?>">
</form>
