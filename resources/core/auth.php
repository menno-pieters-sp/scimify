<?php

class Authentication
{

    function __construct()
    {
        $username = NULL;
        $password = NULL;

        $authenticated = false;

        if (ENABLE_BASIC_AUTH !== false && ENABLE_BEARER_AUTH !== true) {
            $$authenticated = true;
        }

        if (ENABLE_BASIC_AUTH === true) {
            if (isset($_SERVER['PHP_AUTH_USER'])) {
                $username = $_SERVER['PHP_AUTH_USER'];
            }
            if (isset($_SERVER['PHP_AUTH_PW'])) {
                $password = $_SERVER['PHP_AUTH_PW'];
            }
            if (isset($username) && isset($password) && !is_null($username) && !is_null($password)) {
                $authenticated = $this->basicAuthentication($username, $password);
            }
        }
        
        // TODO: Bearer authentication
        
        if ($authenticated !== true && ENABLE_BASIC_AUTH === true) {
            $this->requireBasicAuthentication();
        }
    }

    protected function basicAuthentication($username, $password)
    {
        if (BASIC_AUTH_USER && BASIC_AUTH_PASS) {
            $success = false;
            if (BASIC_AUTH_USER === $username) {                
                if ($this->isSHA256Password(BASIC_AUTH_PASS)) {
                    $success = $this->authSHA256Password(BASIC_AUTH_PASS, $password);
                } else {
                    $success = $this->authPlainPassword(BASIC_AUTH_PASS, $password);
                }
            }
            return $success;
        } else {
            header('HTTP/1.0 500 Internal Server Error');
            echo "Basic authentication user name and password not configured";
            exit();
        }
    }

    protected function requireBasicAuthentication()
    {
        header('WWW-Authenticate: Basic realm="Scimify"');
        header('HTTP/1.0 401 Unauthorized');
        echo "You must enter a valid login name and password";
        exit();
    }

    protected function isSHA256Password($hashedPass)
    {
        $PREFIX = '{SSHA256}';
        return ($hashedPass && substr($hashedPass, 0, strlen($PREFIX)) === $PREFIX);
    }

    protected function createSHA256Password($salt, $pass)
    {
        $PREFIX = '{SSHA256}';
        $hash = hash("sha256", $pass . $salt);
        return sprintf("%s%s$%s", $PREFIX, $salt, $hash);
    }

    protected function getSalt($len = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        if ($len < 1 || $len > 8) {
            $len = 8;
        }
        for ($i = 0; $i < $len; $i ++) {
            $randstring = $randstring . $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    protected function authSHA256Password($hashedPass, $pass)
    {
        $PREFIX = '{SSHA256}';
        $result = false;
        if ($hashedPass && $pass) {
            if ($this->isSHA256Password($hashedPass)) {
                $hashedPassWithoutPrefix = substr($hashedPass, strlen($PREFIX));
                $p = strpos($hashedPassWithoutPrefix, '$');
                if ($p > 0) {
                    $salt = substr($hashedPassWithoutPrefix, 0, $p);
                    $hashResult = $this->createSHA256Password($salt, $pass);
                    $result = ($hashedPass === $hashResult);
                }
            }
        }
        return $result;
    }

    protected function authPlainPassword($plainPass, $pass)
    {
        return ($plainPass && $pass && $plainPass === $pass);
    }
}

?>
