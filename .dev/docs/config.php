<?php

declare(strict_types=1);

use Castor\Docs\Documentation;

return static function (Documentation $docs): void {
    $docs->addComposerJsonPath(__DIR__ . '/../../composer.json');
    $docs->section('Introduction', [
        'index.md',
        'getting-started.md',
    ]);
    $docs->section('Guides', [
        'guides/versions.md',
        'guides/parsing-and-formatting.md',
        'guides/bytes-and-time.md',
        'guides/system-services.md',
        'guides/errors-and-compatibility.md',
    ]);
};
