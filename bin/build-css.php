<?php

declare(strict_types=1);

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

$rootDir = dirname(__DIR__);

require $rootDir . '/vendor/autoload.php';

$compiler = new Compiler();
$compiler->setImportPaths($rootDir . '/scss');
$compiler->setOutputStyle(OutputStyle::COMPRESSED);

$css = $compiler->compileString(file_get_contents($rootDir . '/scss/app.scss'))->getCss();
file_put_contents($rootDir . '/public/assets/css/app.css', $css);

echo 'CSS собран: public/assets/css/app.css' . PHP_EOL;
