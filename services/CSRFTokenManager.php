<?php

class CSRFTokenManager
{
    public function generateCSRFToken(): string
    {
        $token = bin2hex(random_bytes(32));

        return $token;
    }

    public function validateCSRFToken($token): bool
    {

        if (isset($_SESSION['csrfToken']) && hash_equals($_SESSION['csrfToken'], $token)) {
            return true;
        } else {
            return false;
        }
    }
}
