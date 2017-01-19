<?php
/*
 * A PHP file for rendering the code needed to install a web
 * based protocol handler.
 */
$domain = item_domain();
$code = <<<EOF
(function() {
  var proto = prompt("Protocol? (ex: grow)");
  navigator.registerProtocolHandler(proto, 
                                    "http://$domain/grow?i=%s",
                                    "$domain Handler");
})()
EOF;

$code = esc_attr($code);
?>
<a href="javascript:<?= $code ?>">Install Firefox Protocol Handler</a>
(<a href="https://developer.mozilla.org/en-US/docs/Web-based_protocol_handlers">Huh?</a>)

