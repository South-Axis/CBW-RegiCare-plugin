<?php

namespace Southaxis\RegiCare;

require_once 'Client.php';

use Southaxis\RegiCare\Client;
use Graze\GuzzleHttp\JsonRpc\Exception\ClientException;

class Activiteiten extends Client
{
    public function __construct($token, $domain)
    {
        $this->setDomain($domain);
        $this->setToken($token);
        parent::__construct($domain, $token);
    }

    /**
     * This will get all the activities from the database.
     *
     * @param array $filter
     * @return mixed
     */
    public function getAllActivities($filter = array())
    {
        $return = $this->client->send($this->client->request(1, 'activiteitOverzicht', [
            'loginKey' => null,
            'apiKey' => $this->getToken(),
            'filter' => $filter
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    /**
     * This wil get all the filters possible that can be put on a activity
     * @return mixed
     */
    public function getAllFilters()
    {
        $response = $this->client->send($this->client->request(1, "activiteitFilter", [
            'apiKey' => $this->getToken(),
            'volledig' => false,
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }
    /**
     * This will return the data from one specific activity
     * @param $activiteitID
     * @return mixed
     */
    public function getSpecificActivity($activiteitID)
    {
        try {
            $response = $this->client->send($this->client->request(1, "activiteitInformatie", [
                "activiteitID" => $activiteitID,
                'apiKey' => $this->getToken(),
            ]));
        } catch (ClientException $e) {
            var_dump($e->getResponse()->getBody()->getContents());
        }
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     * The amount of available activities
     * @param array $filter
     * @return mixed
     */
    public function activiteitOverzichtAantal($filter = array())
    {
        $return = $this->client->send($this->client->request(1, 'activiteitOverzichtAantal', [
            'loginKey' => null,
            'apiKey' => $this->getToken(),
            'filter' => $filter
        ]));
        $res = json_decode($return->getBody()->getContents());
        if (isset($res->result->error)) {
            return $res->result->error;
        }
        return $res->result;
    }

    /**
     *get the information about a specific meeting
     * @param $bijeenkomstID
     * @return mixed
     */
    public function activitySpecificMeetingInformation($bijeenkomstID)
    {
        $response = $this->client->send($this->client->request(1, "activiteitBijeenkomstInformatie", [
            "bijeenkomstID" => $bijeenkomstID,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     *get the available meetings
     * @param array $filter
     * @return mixed
     */
    public function activityMeetingInformation($filter = array())
    {
        $response = $this->client->send($this->client->request(1, "activiteitBijeenkomstOverzicht", [
            "filter" => $filter,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     *get the amount of available meetings
     * @param array $filter
     * @return mixed
     */
    public function amountOfActivityMeetings($filter = array())
    {
        $response = $this->client->send($this->client->request(1, "activiteitBijeenkomstOverzichtAantal", [
            "filter" => $filter,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     * get information about a specific location
     * @param $locatieID
     * @return mixed
     */
    public function activitySpecifcLocation($locatieID)
    {
        $response = $this->client->send($this->client->request(1, "activiteitLocatieInformatie", [
            "locatieID" => $locatieID,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     *get all the available locations
     * @param array $filter
     * @return mixed
     */
    public function availableLocations($filter = array())
    {
        $response = $this->client->send($this->client->request(1, "activiteitLocatieOverzicht", [
            "filter" => $filter,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     * get the amount of available locations
     * @param array $filter
     * @return mixed
     */
    public function amountOfAvailableLocations($filter = array())
    {
        $response = $this->client->send($this->client->request(1, "activiteitLocatieOverzichtAantal", [
            "filter" => $filter,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }

    /**
     * get all the registrations on a activity
     * @param array $filter
     * @return string
     */
    public function summaryRegisteredPeopleActivity($filter = array())
    {
        if (Auth::isLoggedIn()) {
            $return = $this->client->send($this->client->request(1, 'activiteitIngeschreven', [
                'loginKey' => $_SESSION['user']['loginKey'],
                'apiKey' => $this->getToken(),
                'filter' => $filter
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
     * register someone on a activity
     * @param $activiteitID
     * @param array $filter
     * @return string
     */
    public function registeringOnActivity($activiteitID, $persoonID, $filter = array())
    {
        global $wp;
        if (Auth::isLoggedIn()) {
            $return = $this->client->send($this->client->request(1, 'activiteitInschrijvingPlaatsen', [
                'loginKey' => $_SESSION['user']['loginKey'],
                'apiKey' => $this->getToken(),
                'activiteitID' => $activiteitID,
                'gegevens' => ["persoonID" => $persoonID],
                'filter' => $filter
            ]));
            $res = json_decode($return->getBody()->getContents());
            if (isset($res->result->error)) {
                return $res->result->error;
            }
            return  wp_redirect(home_url($wp));
            //            return $res->result;
        } else {
            return "NIET_INGELOGD";
        }
    }

    /**
     * cancel a registration on a activity
     * @param $inschrijvingID
     * @return string
     */
    public function cancelRegistration($inschrijvingID)
    {
        if (Auth::isLoggedIn()) {
            $return = $this->client->send($this->client->request(1, 'activiteitInschrijvingAnnuleren', [
                'loginKey' => $_SESSION['user']['loginKey'],
                'apiKey' => $this->getToken(),
                'inschrijvingID' => $inschrijvingID
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
     * register anonymous on a activity
     * @param $activiteitID
     * @param array $gegevens
     * @return mixed
     */
    public function registerAnonymous($activiteitID, $gegevens = array())
    {
        $response = $this->client->send($this->client->request(1, "activiteitInschrijvingAnoniem", [
            "gegevens" => $gegevens,
            'activiteitID' => $activiteitID,
            'apiKey' => $this->getToken(),
        ]));
        $result = json_decode($response->getBody()->getContents());
        if (isset($result->result->error)) {
            return $result->result->error;
        }
        return $result->result;
    }
}
