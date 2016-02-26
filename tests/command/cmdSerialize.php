<?php

require 'ObjectSerializable.php';

foreach ($argv as $arg) {
    if (strpos($arg, '--objSerialize=') !== false
        && unserialize(str_replace('--objSerialize=', '', $arg)) instanceof ObjectSerializable
    ) {
        echo 'true';
        exit(0);
    }
}
echo 'false';
exit(0);