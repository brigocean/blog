<?php

declare(strict_types=1);

namespace App\Core;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(string $rootDir)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($rootDir . '/templates');
        $this->smarty->setCompileDir($rootDir . '/var/templates_c');
        $this->smarty->setCacheDir($rootDir . '/var/cache');
        $this->smarty->setEscapeHtml(true);
    }

    public function render(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        $this->smarty->assign($data);
        $this->smarty->display($template);
    }
}
