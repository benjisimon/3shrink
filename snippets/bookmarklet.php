<?php
/*
 * A PHP file for generating a URL shrinking bookmarklet
 */
$code = <<<EOF
(function(){
  window.open('http://{$_SERVER['SERVER_NAME']}/shrink?i=' + encodeURIComponent($src));
})()
EOF;
$code = esc_attr($code);
?>
<a href="javascript:<?= $code ?>">3shrink <?= $label ?></a>



