<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ListPriorityExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('list_priority', [$this, 'priority']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('list_priority', [$this, 'priority']),
        ];
    }

    public function priority($value): string
    {
        $priorities =array('Nie ważne','Mało ważne','Średnio ważne','Ważne','Bardzo ważne');
        return $priorities[$value-1];
    }
}
