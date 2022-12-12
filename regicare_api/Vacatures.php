<?php

namespace Southaxis\RegiCare;

require_once 'Client.php';

use Psr\Http\Message\ResponseInterface;
use Southaxis\RegiCare\Client;

class Vacatures extends Client
{
    public function __construct($token, $domain)
    {
        $this->setDomain($domain);
        $this->setToken($token);
        parent::__construct($domain, $token);
    }

    /**
     * Haalt alle vacatures op met een eventuele filter.
     *
     * @param array $filter
     * @param $werksoortID
     * @return  $vacatures
     */
    public function vacatureOverzicht($werksoortID, $filter = array())
    {
        $return = $this->client->send($this->client->request(1, 'vacaturebankVacatureOverzicht', [
            "werksoortID" => $werksoortID,
            'apiKey' => $this->getToken(),
            "filter" => $filter,
            "loginKey" => isset($_SESSION['user']['loginKey']) ? $_SESSION['user']['loginKey'] : "",
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    /**
     * @param array $filter
     * @param $werksoortID
     * @return ResponseInterface
     */
    public function vacatureOvezichtAantal($werksoortID, $filter = array())
    {
        $return = $this->client->send($this->client->request(1, 'vacaturebankVacatureOverzichtAantal', [
            "werksoortID" => $werksoortID,
            'apiKey' => $this->getToken(),
            "filter" => $filter,
            "loginKey" => isset($_SESSION['user']['loginKey']) ? $_SESSION['user']['loginKey'] : "",
        ]));
        $res = json_decode($return->getBody()->getContents());
        return $res;
    }

    /**
     * @param $werksoortID
     * @return ResponseInterface
     */
    public function vacatureFilter($werksoortID)
    {
        $return = $this->client->send($this->client->request(1, 'vacaturebankVacatureFilter', [
            "werksoortID" => $werksoortID,
            'apiKey' => $this->getToken(),
        ]));
        $res = json_decode($return->getBody()->getContents());
        return $res;
    }

    /**
     * @param array $filter
     * @param $werksoortID
     * @return ResponseInterface
     */
    public function vacatureInschrijvingen($werksoortID, $filter = array())
    {
        if (Auth::isLoggedIn()) {
            $return = $this->client->send($this->client->request(1, 'vacaturebankVacatureIngeschreven', [
                "werksoortID" => $werksoortID,
                'apiKey' => $this->getToken(),
                "filter" => $filter,
                'loginKey' => $_SESSION['user']['loginKey'],
            ]));
            $res = json_decode($return->getBody()->getContents());
            if (isset($res->result->error)) {
                return $res->result->error;
            }
            return $res->result;
        } else {
            return "NIET_INGELOGD";
        }
    }

    /**
     * Hier moet loginKey een rol spelen.
     *
     * @param $werksoortID
     * @param $vacatureID
     * @param array $gegevens
     * @return ResponseInterface
     */
    public function vacatureInschrijvingPlaatsen($werksoortID, $vacatureID, $gegevens)
    {
        if (Auth::isLoggedIn()) {
            $response = $this->client->send($this->client->request(1, 'vacaturebankVacatureInschrijvingPlaatsen', [
                "werksoortID" => $werksoortID,
                'apiKey' => $this->getToken(),
                "vacatureID" => $vacatureID,
                "gegevens" => $gegevens,
                'loginKey' => $_SESSION['user']['loginKey'],
            ]));
            $result = json_decode($response->getBody()->getContents());
            if (isset($result->result->error)) {
                return $result->result->error;
            }
            return $result->result;
        } else {
            return "NIET_INGELOGD";
        }
    }
    /**
     * @param $werksoortID
     * @param $inschrijvingID
     * @return ResponseInterface
     */
    public function vacatureInschrivingAnnuleren($werksoortID, $inschrijvingID)
    {
        if (Auth::isLoggedIn()) {
            $response = $this->client->send($this->client->request(1, "vacaturebankVacatureInschrijvingAnnuleren", [
                "werksoortID" => $werksoortID,
                'apiKey' => $this->getToken(),
                "inschrijvingID" => $inschrijvingID,
                "loginKey" => $_SESSION['user']['loginKey'],
            ]));
            $result = json_decode($response->getBody()->getContents());
            if (isset($result->result->error)) {
                return $result->result->error;
            }
            return $result->result;
        } else {
            return "NIET_INGELOGD";
        }
    }

    /**
     * @param $werksoortID
     * @return ResponseInterface
     */
    public function vacatureInschrijvingsFilter($werksoortID)
    {
        $response = $this->client->send($this->client->request(1, "vacaturebankVacatureInschrijvingFilter", [
            "werksoortID" => $werksoortID,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     * @param $werksoortID
     * @param array $filter
     * @return ResponseInterface
     */
    public function vacaturebankConfiguratie($werksoortID)
    {
        $response = $this->client->send($this->client->request(1, "vacaturebankConfiguratie", [
            "werksoortID" => $werksoortID,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    public function geplaatsteVacatures($werksoortID, $filter = array())
    {
        if (Auth::isLoggedIn()) {
            $response = $this->client->send($this->client->request(1, "vacaturebankVacatureGeplaatst", [
                "werksoortID" => $werksoortID,
                'apiKey' => $this->getToken(),
                "filter" => $filter,
                "loginKey" => $_SESSION['user']['loginKey'],
            ]));
            $result = json_decode($response->getBody()->getContents());
            if (isset($result->result->error)) {
                return $result->result->error;
            }
            return $result->result;
        } else {
            return "NIET_INGELOGD";
        }
    }

    /**
     * @param $vacatureID
     * @param $werksoortID
     * @param string $loginKey
     * @return ResponseInterface
     */
    public function vacatureGegevens($vacatureID, $werksoortID)
    {
        $response = $this->client->send($this->client->request(1, "vacaturebankVacatureGegevens", [
            "vacatureID" => $vacatureID,
            'apiKey' => $this->getToken(),
            "werksoortID" => $werksoortID,
            "loginKey" => $_SESSION['user']['loginKey'],
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    public function vacatureTemplate($werksoortID)
    {
        if (Auth::isLoggedIn()) {
            $response = $this->client->send($this->client->request(1, "vacaturebankVacatureTemplate", [
                "werksoortID" => $werksoortID,
                'apiKey' => $this->getToken(),
                "loginKey" => $_SESSION['user']['loginKey'],
            ]));
            $result = json_decode($response->getBody()->getContents());
            if (isset($result->result->error)) {
                return $result->result->error;
            }
            return $result->result;
        } else {
            return "NIET_INGELOGD";
        }
    }
}