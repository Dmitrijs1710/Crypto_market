<?php

namespace App;

class Validate
{
    public static function passwordMatch(string $password1, string $password2): void
    {
        if ($password1 !== $password2) {
            $_SESSION['error']['passwordRepeat'] = 'Passwords do not match';
        }
    }

    public static function passwordChecker(string $password): void
    {
        $passwordPattern = '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/';
        if (!preg_match($passwordPattern, $password)) {
            $_SESSION['error']['password'] = 'Incorrect password format';
        }
    }

    public static function emailChecker(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error']['email'] = 'incorrect email format';
        }
    }

    public static function nameChecker(string $name): void
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $_SESSION['error']['name'] = 'incorrect name format';
        }
    }

    public static function balanceChecker(int $balance, float $amount): void
    {

        if ($balance < $amount) {
            $_SESSION['error']['buy'] = 'Not enough balance';
        }
    }

    public static function balanceInputChecker(float $amount): void
    {
        if ($amount <= 0) {
            $_SESSION['error']['buy'] = 'Incorrect amount';
        }
    }

    public static function userCoinChecker(float $coinAmount, float $requiredAmount, int $id): void
    {
        if ($coinAmount < $requiredAmount) {
            $_SESSION['error'][$id] = 'Not enough coins';
        }

    }

    public static function userCoinInputChecker(float $requiredAmount, ?int $id = null)
    {
        if ($id !== null) {
            if ($requiredAmount <= 0) {
                $_SESSION['error']['buy'] = 'Incorrect amount';
            }
        } else if ($requiredAmount <= 0) {
            $_SESSION['error'][$id] = 'Input less than zero';
        }
    }
}