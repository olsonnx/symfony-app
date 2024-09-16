<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
    ->exclude('migrations')
    ->exclude('var');


return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
