<?php

namespace App\ViewVariables;

class PopupVariables
{
    public function getName(): string
    {
        return 'popup';
    }

    public function getValues(): string
    {
        return $_SESSION['popup'] ?? '';
    }
}