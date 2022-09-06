<?php
// Redirect to parent directory.
$url = '../';
header( 'Request-URI: '.$url );
header( 'Content-Location: '.$url );
header( 'Location: '.$url );
exit();
