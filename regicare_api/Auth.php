<?php
namespace Southaxis\RegiCare;

require_once 'Client.php';

use Southaxis\RegiCare\Client;

class Auth extends Client
{
    private $user;

    public function __construct($token, $domain)
    {
        $this->setDomain($domain);
        $this->setToken($token);
        parent::__construct($domain, $token);
    }

    /**
     * @return bool
     */
    public static function isLoggedIn()
    {
        if (isset($_SESSION['user']['loginKey'])) {
            return true;
        }
        return false;
    }

    /**
     * Authenticates using session
     *
     * @param $loginKey
     * @return bool
     */
    public function authenticate()
    {
        if (isset($_SESSION['user']['loginKey'])) {
            $response = $this->client->send($this->client->request(1, 'authenticate', [
                "loginKey" => $_SESSION['user']['loginKey'],
                'apiKey' => $this->getToken(),
            ]));
            $result = json_decode($response->getBody()->getContents());
            if (isset($result->result->error)) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function login($gebruikersnaam, $wachtwoord)
    {
        $return = $this->client->send($this->client->request(1, 'login', [
            "gebruikersnaam" => $gebruikersnaam,
            "wachtwoord" => $wachtwoord,
            'apiKey' => $this->getToken(),
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        $_SESSION['user'] = array(
            "naam" => $res->result->naam,
            "loginKey" => $res->result->loginKey,
            "rol" => $res->result->rol
        );
        return $res->result;
    }

    public function logout()
    {
        if ($this::isLoggedIn()) {
            $return = $this->client->send($this->client->request(1, 'logout', [
                "loginKey" => $_SESSION['user']['loginKey'],
                'apiKey' => $this->getToken(),
            ]));
            $res = json_decode($return->getBody()->getContents());
            if (isset($res->result->error)) {
                return $res->result->error;
            }
            session_start();
            session_destroy();
            return true;
        } else {
            return "NIET_INGELOGD";
        }
    }

    public function profielAanmelden($gegevens = array())
    {
        $return = $this->client->send($this->client->request(1, 'profielAanmelden', [
            "gegevens" => $gegevens,
            "apiKey" => $this->getToken(),
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielPersoonToevoegen($gegevens = array())
    {
        $return = $this->client->send($this->client->request(1, 'profielPersoonToevoegen', [
            "apiKey" => $this->getToken(),
            "loginKey" => $_SESSION['user']['loginKey'],
            "gegevens" => $gegevens,
        ]));

        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }


    public function profielPersoonBewerken($gegevens = array(), $persoonID = "") {
        $return = $this->client->send($this->client->request(1, 'profielPersoonBewerken', [
            "apiKey" => $this->getToken(),
            "loginKey" => $_SESSION['user']['loginKey'],
            "persoonID" => $persoonID,
            "gegevens" => $gegevens,
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielPersoonGekoppeld()
    {
        $return = $this->client->send($this->client->request(1, 'profielPersoonGekoppeld', [
            "loginKey" => $_SESSION['user']['loginKey'],
            "apiKey" => $this->getToken(),
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielPersoonGegevens($persoonID)
    {
        $return = $this->client->send($this->client->request(1, 'profielPersoonGegevens', [
            "loginKey" => $_SESSION['user']['loginKey'],
            "apiKey" => $this->getToken(),
            "persoonID" => $persoonID,
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielGegevens() 
    {
        $return = $this->client->send($this->client->request(1, 'profielGegevens', [
            "loginKey" => $_SESSION['user']['loginKey'],
            'apiKey' => $this->getToken(),
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielOpslaan($gegevens = array())
    {
        $return = $this->client->send($this->client->request(1, 'profielOpslaan', [
            "loginKey" => $_SESSION['user']['loginKey'],
            'apiKey' => $this->getToken(),
            "gegevens" => $gegevens,
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielAdresControle($zipcode = "", $nummer = "")
    {
        $return = $this->client->send($this->client->request(1, 'profielAdresControle', [
            "apiKey" => $this->getToken(),
            "postcode" => $zipcode,
            "nummer" => $nummer
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielAdresOphalen($zipcode = "", $nummer = "")
    {
        $return = $this->client->send($this->client->request(1, 'profielAdresOphalen', [
            "apiKey" => $this->getToken(),
            "postcode" => $zipcode,
            "nummer" => $nummer
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    public function profielIbanControle($iban = "") {
        $return = $this->client->send($this->client->request(1, 'profielIbanControle', [
            "apiKey" => $this->getToken(),
            "iban" => $iban
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }
}