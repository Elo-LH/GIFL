<?php

use Twig\Extra\Intl\IntlExtension;

abstract class AbstractController
{
    private \Twig\Environment $twig;
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('cookie', $_COOKIE);

        $this->twig = $twig;
    }

    protected function render(string $template, array $data): void
    {
        echo $this->twig->render($template, $data);
    }
    protected function redirect(string $route): void
    {
        header("Location: $route");
    }
}
