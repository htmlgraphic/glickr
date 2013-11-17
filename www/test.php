<pre>
<?php

# error reporting
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

require_once 'PHPUnit/Autoload.php';


ini_set('error_log', '');
ini_set('display_errors', 'Off');
 
$fixture = array();
assertTrue(count($fixture) == 0);
 
$fixture[] = 'element';
assertTrue(count($fixture) == 1);
 
function assertTrue($condition)
{
    if (!$condition) {
        throw new Exception('Assertion failed.');
    }
}
?>