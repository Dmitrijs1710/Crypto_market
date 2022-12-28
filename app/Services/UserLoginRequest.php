<?php

namespace App\Services;

use App\Models\User;

class UserLoginRequest
{
    private string $password;
    private ?string $email;
    private ?string $id;

    public function __construct(?string $email, string $password, ?string $id = null)
    {
        $this->password = $password;
        $this->email = $email;
        $this->id = $id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function checkRequest(User $user): bool
    {
        return password_verify($this->password, $user->getPassword()) && $this->email === $user->getEMail();
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}