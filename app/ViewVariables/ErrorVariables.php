<?php

namespace App\ViewVariables;

class ErrorVariables
{
    public function getName(): string
    {
        return 'error';
    }

    public function getValues(): array
    {
        return $_SESSION['error'] ?? [];
    }
}