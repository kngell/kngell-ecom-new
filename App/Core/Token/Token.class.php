<?php

declare(strict_types=1);
class Token extends RandomStringGenerator
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function create(int $length = 8, string $frm = '', string $alphabet = '')
    {
        $identifiant = '';
        $this->setAlphabet($alphabet);
        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $identifiant .= $this->alphabet[$randomKey];
        }
        $time = time();
        $separator = !empty($frm) ? $frm : '|';
        $hash = hash_hmac('sha256', session_id() . $identifiant . $time . $frm ?? '', CSRF_TOKEN_SECRET, true);

        return $this->urlSafeEncode($hash . $separator . $identifiant . $separator . $time);
    }

    public function urlSafeEncode(string $str) : string
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    public function urlSafeDecode(string $str) : string
    {
        return base64_decode(strtr($str, '-_', '+/'));
    }

    public function validate(string $token = '', string $frm = '') : bool
    {
        $separator = !empty($frm) ? $frm : '|';
        $part = explode($separator, $this->urlSafeDecode($token));
        if (count($part) === 3) {
            $hash = hash_hmac('sha256', session_id() . $part[1] . $part[2] . $frm ?? '', CSRF_TOKEN_SECRET, true);
            if (hash_equals($hash, $part[0])) {
                return true;
            }
        }

        return false;
    }

    public function generate(int $length = 8, string $alphabet = '') : string
    {
        $token = '';
        $this->setAlphabet($alphabet);
        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }

        return $token;
    }

    public function check($formName, $token)
    {
        if ($this->session->exists(TOKEN_NAME) && $token === $this->session->get(TOKEN_NAME)) {
            $this->session->delete(TOKEN_NAME);

            return true;
        }
        $serverToken = hash_hmac('sha256', $formName, $this->session->exists(TOKEN_NAME) ? $this->session->get(TOKEN_NAME) : '');

        return hash_equals($serverToken, $token);
    }

    public function getHash() : string
    {
        return hash_hmac('sha256', $this->token, YamlFile::get('app')['settings']['secret_key']);
    }

    public function csrfInput(string $name, string $tokenString) : string
    {
        return '<input type="hidden" name="' . $name . '" value="' . $this->create(8, $tokenString) . '" />';
    }
}
