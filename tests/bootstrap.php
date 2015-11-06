<?php

use OParl\Spec\LiveVersionUpdater;

include __DIR__ . '/../bootstrap/autoload.php';

if (!file_exists('tests/assets')) {
    mkdir('tests/assets', 0777, true);
}

if (!file_exists('tests/assets/spec.git')) {
    exec('git clone --depth 10 --bare https://github.com/OParl/spec.git tests/assets/spec.git');
}

if (!file_exists('storage/app/live_version')) {
    /* @var $updater LiveVersionUpdater */
    $updater = app(LiveVersionUpdater::class);
    $updater->updateRepository();
}
