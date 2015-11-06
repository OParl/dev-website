<?php

include __DIR__ . '/../bootstrap/autoload.php';

$spec = 'tests/assets/spec.git';

if (!file_exists($spec)) {
    exec('mkdir -p tests/assets/');
    exec('git clone -q --depth=10 https://github.com/OParl/spec.git tests/assets/spec.git', $output);
}
