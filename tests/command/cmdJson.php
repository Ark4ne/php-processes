<?php

foreach ($argv as $arg) {
	if (strpos($arg, '--objJson=') !== false && json_decode(str_replace('--objJson=', '', $arg))) {
		echo 'true';
		exit(0);
	}
}
echo 'false';
exit(0);