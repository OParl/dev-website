<?php

include __DIR__.'/../vendor/autoload.php';

function remove_test_repo() {
    // remove test repo
    $cmd = sprintf('rm -rf %s', __DIR__.'/../storage/app/hub_sync/test');
    exec($cmd);
}

remove_test_repo();
