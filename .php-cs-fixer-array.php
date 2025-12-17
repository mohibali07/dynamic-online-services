<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/includes')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
