<?php
require 'vendor/autoload.php';
require "Functions.php";

use Graze\GuzzleHttp\JsonRpc\Client as RPCCLient;

//start the session so that the logged in user can be retrieved
if (!session_id()) {
    session_start();
}

//remove a dynamic field from a link
function removeqsvar($url, $varname)
{
    [$urlpart, $qspart] = array_pad(explode("?", $url), 2, "");
    parse_str($qspart, $qsvars);
    unset($qsvars[$varname]);
    $newqs = http_build_query($qsvars);
    return $urlpart . "?" . $newqs;
}

function profielAdresOphalen()
{
    global $wp;
    global $auth;

    $validatedUserData = validateAndSetPost([
        "post" => ["string", true],
        "nummer" => ["string", true]
    ]);

    try {
        $filter = $auth->getAllActivities($validatedUserData[0][0], $validatedUserData[1][0]);
    } catch (\GuzzleHttp\Exception\BadResponseException $e) {
        $nothing = "<p>excuses voor het ongemak de activiteiten zijn op het moment niet beschikbaar</p>";
    }
}

function showFilterActivities()
{
    global $wp;
    global $activity;

    $tagText = $_POST["tagText"];
    $dayId = $_POST["dagID"];
    $dayText = $_POST["dagText"];
    $groeperingID = $_POST["groeperingID"];
    $groeperingText = $_POST["groeperingText"];
    $vrijkenmerk06ID = $_POST["vrijkenmerk06ID"];
    $vrijkenmerk06Text = $_POST["vrijkenmerk06Text"];
    $locatieID = $_POST["locatieID"];
    $locatieText = $_POST["locatieText"];

    try {
        $filter = $activity->getAllActivities([
            // "tag" => [
            //     "$tagText" => 1
            // ],
            "dag" => [
                "$dayText" => $dayId
            ],
            "groepering" => [
                "$groeperingText" => $groeperingID
            ],
            "vrijkenmerk06" => [
                "$vrijkenmerk06Text" => $vrijkenmerk06ID
            ],
            "locatie" => [
                "$locatieText" => $locatieID
            ]
        ]);
    } catch (\GuzzleHttp\Exception\BadResponseException $e) {
        $nothing = "<p>excuses voor het ongemak de activiteiten zijn op het moment niet beschikbaar</p>";
    }

    $link = home_url($wp->request);

    if ($filter != null || count($filter) != 0) { ?>
        <?php foreach ($filter as $activity) { ?>
            <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card mb-3 w-100">
                    <div class="card-body">
                        <h5 class="card-title"> <?php echo $activity->omschrijving ?></h5>
                        <p class="card-text activityText"> <?php echo wp_trim_words($activity->omschrijvingUitgebreid, $num_words = 55, $more = null) ?></p>
                        <div class="popUpBox" style="display: none">
                            <div class="metaInfoHolder">
                                <p class="card-text"><?php echo $activity->omschrijvingUitgebreid ?></p>
                                <div class="tableHolder">
                                    <table>
                                        <tbody>
                                            <?php if ($activity->trefwoorden != null) { ?>
                                                <tr>
                                                    <td style="padding-right: 15px">
                                                        <b>Trefwoorden</b>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $activity->trefwoorden;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Wanneer</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->interval != null && $activity->dag != null && $activity->startDatum != null && $activity->eindDatum != null) {
                                                        echo array_shift(array_values(get_object_vars($activity->interval))) . ", " . array_shift(array_values(get_object_vars($activity->dag))) . " " . date("d F", strtotime($activity->startDatum)) . "<b> T/M </b> " . date("d F", strtotime($activity->eindDatum));
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Tijd</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->startTijd != null && $activity->eindTijd != null) {
                                                        echo $activity->startTijd . " - " . $activity->eindTijd;
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Url</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->url != null) {
                                                        echo $activity->url;
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Locatie</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->locatie != null) {
                                                        echo array_shift(array_values(get_object_vars($activity->locatie)));
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Ruimte</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->ruimte != null) {
                                                        echo array_shift(array_values(get_object_vars($activity->ruimte)));
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Plaats</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->inschrijvingenMaximum != null) {
                                                        echo "Maximum Inschrijvingen: " . $activity->inschrijvingenMaximum . "<br>";
                                                    }
                                                    $num = $activity->inschrijvingenMaximum - $activity->inschrijvingen;
                                                    if (is_int($num)) {
                                                        echo "Aantal vrije plekken: " . $num;
                                                    } else {
                                                        echo "Aantal vrije plekken: 0";
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Kosten</b>
                                                </td>
                                                <td>
                                                    €
                                                    <?php
                                                    if ($activity->prijs != null) {
                                                        echo $activity->prijs;
                                                    } else {
                                                        echo 0;
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 15px">
                                                    <b>Doelgroep</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->groepering != null) {
                                                        $data = [];
                                                        foreach ($activity->groepering as $key => $value) {
                                                            [$a, $b] = explode(" jaar ", $value);
                                                            $data[] = $b;
                                                        }
                                                        echo join(", ", $data);
                                                    } ?>
                                                </td>
                                            </tr>
											<tr>
                                                <td style="padding-right: 15px">
                                                    <b>Interesse</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($activity->activiteittype != null) {
                                                        echo join(", ", array_values(get_object_vars($activity->activiteittype)));
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if ($activity->inschrijven === 1) { ?>
                                    <a class="btn btn-orange float-right" href="<?php echo home_url($wp) ?>/inschrijven/?activityID=<?php echo $activity->activiteitID ?>">Inschrijven</a>
                                <?php } ?>
                            </div>
                        </div>
                        <a class="btn btn-orange vertoon">Toon meer</a>
                    </div>
                </div>
            </div>
        <?php }
    } else {
        ?>
        <div class="col sb-text-center">
            <div id="aanbodHolder">
                <div style="margin-top: 25px;" class="alert alert-info w-100">
                    Er zijn geen activiteiten gevonden met de opgegeven parameters.
                </div>
            </div>
        </div>
    <?php
    }
    die();
}

add_action("wp_ajax_showFilterActivities", "showFilterActivities");
add_action("wp_ajax_nopriv_showFilterActivities", "showFilterActivities");


function validateAdress()
{
    if (isset($_REQUEST["zipcode"]) && isset($_REQUEST["houseNumber"])) {
        global $wp;
        global $auth;

        try {
            $adress = $auth->profielAdresControle($_REQUEST["zipcode"], $_REQUEST["houseNumber"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            echo json_encode(["error" => "error"]);
            die();
            return;
        }

        if (!isset($adress->result) || $adress->result !== "OK") {
            echo json_encode(["error" => false]);
            die();
            return;
        }

        try {
            $adress = $auth->profielAdresOphalen($_REQUEST["zipcode"], $_REQUEST["houseNumber"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            echo json_encode(["error" => "error"]);
            die();
            return;
        }

        if (isset($adress->straat) && isset($adress->nummer) && isset($adress->postcode) && isset($adress->plaats)) {
            echo json_encode($adress);
        } else {
            echo json_encode(["error" => false]);
        }
    }
    die();
}

add_action("wp_ajax_validateAdress", "validateAdress");
add_action("wp_ajax_nopriv_validateAdress", "validateAdress");

//log the user into the regicare API.
add_filter("init", "regicare_login");

function regicare_login()
{
    if (isset($_POST["regicare_email"]) && isset($_POST["regicare_password"])) {
        global $auth;
        global $wp;

        $link = home_url($wp);
        $authentication = $auth->login($_POST["regicare_email"], $_POST["regicare_password"]);

        if (is_string($authentication)) {
            wp_redirect("$link/login/?error=true");
        } else {
            if (isset($_SESSION["redirect_url"])) {
                $redirect_url = $_SESSION["redirect_url"];
                unset($_SESSION["redirect_url"]);
                wp_redirect($redirect_url);
            } else {
                wp_redirect($link);
            }
        }
        die();
    }
}

add_filter("init", "regicare_forgot_password");

function regicare_forgot_password()
{
    if (isset($_POST["regicare_email"])) {
        global $auth;
        global $wp;
        $link = home_url($wp);

        $client = RPCCLient::factory(get_option("regicare_domain"), [
            "timeout" => 100,
            "verify" => false
        ]);

        $result = $client->send($client->request(1, "profielWachtwoordAanvraag", [
            "gebruikersnaam" => $_POST["regicare_email"],
            "apiKey"         => get_option("regicare_key"),
        ]));

        // $res = $auth->forgotPassword($_POST["regicare_email"]);
        wp_redirect("$link/wachtwoord-vergeten?error=false");
        die();
    }
}

add_filter("init", "regicare_logout");

function regicare_logout()
{
    global $auth;
    global $wp;

    $link = home_url($wp);
    $logout = false;

    if (isset($_REQUEST["logout"])) {
        $logout = ($_REQUEST["logout"]);
    }

    if ($logout == "true") {
        $oldLink = home_url($wp) . $_SERVER["REQUEST_URI"];
        $_SESSION["redirect_url"] = $oldLink;
        $authentication = $auth->logout();
        if (is_string($authentication)) {
            wp_redirect($link);
        } else {
            unset($_SESSION["redirect_url"]);
            unset($_SESSION["user"]);
            wp_redirect(home_url($wp));
        }
        die();
    }
}

add_filter("init", "regicare_register_activity");

function regicare_register_activity()
{
    global $wp;
    global $activity;

    $link = home_url($wp->request);

    if (isset($_POST["activityID"])) {
        if ($_POST["loginKey"] == null) {
            $oldLink = home_url($wp) . $_SERVER["REQUEST_URI"];
            $_SESSION["redirect_url"] = $oldLink;
            wp_redirect(home_url($wp) . "/login");
            die();
        } else {
            if (isset($_POST["persoonID"])) {
                foreach ($_POST["persoonID"] as $persoonID) {
                    $persoonID = intval($persoonID);
                    $register = $activity->registeringOnActivity($_POST["activityID"], $persoonID, $_POST["loginKey"]);
                }
                wp_redirect(home_url($wp) . "/bedankt");
                die();
            } else {
                wp_redirect("$link/inschrijven/?activityID=" . $_POST["activityID"] . "&error=NOCHILD");
                die();
            }
        }
    }
}

add_filter("init", "regicare_register");

function regicare_register()
{
    if (isset($_SESSION["form"]) && $_SESSION["form"] === "register" && isset($_POST["emailadres"]) && isset($_POST["wachtwoord"])) {
        global $wp;
        global $auth;

        $baseUrl = home_url($wp->request);
        $validatedUserData = validateAndSetPost(
            [
                "regicare_roepnaam" => ["name", true],
                "voorletters" => ["name", true],
                "tussenvoegsel" => ["name", false],
                "achternaam" => ["name", true],
                "geslacht" => ["gender", true],
                "geboortedatum" => ["date", true],
                "toevoeging" => ["string", false],
                "postcode" => ["post", true],
                "nummer" => ["string", true],
                "telefoonVast" => ["phone", false],
                "telefoonMobiel" => ["phone", false],
                "emailadres" => ["mail", true],
                "iban" => ["string", false],
                "autoincasso" => ["string", false],
                "wachtwoord" => ["string", true]
            ]
        );
        foreach ($validatedUserData as $col => $val) {
            if (isset($val[1])) {
                $validatedUserData["code"] = "FORMDATAMISSING";
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/registreren/");
                die();
            }
        }

        try {
            $adress = $auth->profielAdresControle($_REQUEST["postcode"], $_REQUEST["nummer"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $validatedUserData["code"] = $e;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/registreren/");
            die();
        }

        if (!isset($adress->result) || $adress->result !== "OK") {
            $validatedUserData["code"] = $adress;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/registreren/");
            die();
        }

        try {
            $adress = $auth->profielAdresOphalen($_REQUEST["postcode"], $_REQUEST["nummer"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $validatedUserData["code"] = $e;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/registreren/");
            die();
        }

        if (!isset($adress->straat) || !isset($adress->nummer) || !isset($adress->postcode) || !isset($adress->plaats)) {
            $validatedUserData["code"] = $adress;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/registreren/");
            die();
        }

        if ($_REQUEST["iban"] !== "") {
            try {
                $iban = $auth->profielIbanControle($_REQUEST["iban"]);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $validatedUserData["code"] = $e;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/registreren/");
                die();
            }
            if (!isset($iban->result) || $iban->result === "OK") {
                $validatedUserData["code"] = $iban;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/registreren/");
                die();
            }
        }

        $params = array(
            "gegevens" => array(
                "roepnaam" => $_POST["regicare_roepnaam"],
                "voorletters" => $_POST["voorletters"],
                "tussenvoegsel" => $_POST["tussenvoegsel"],
                "achternaam" => $_POST["achternaam"],
                "geslacht" => $_POST["geslacht"],
                "geboortedatum" => date("Y-m-d", strtotime($_POST["geboortedatum"])),
                "postcode" => $adress->postcode,
                "nummer" => $_POST["nummer"],
                "toevoeging" => $_POST["toevoeging"],
                "straat" => $adress->straat,
                "plaats" => $adress->plaats,
                "land" => "Nederland",
                "telefoonVast" => $_POST["telefoonVast"],
                "telefoonMobiel" => $_POST["telefoonMobiel"],
                "emailadres" => $_POST["emailadres"],
                "iban" => ($iban ?? ""),
                "autoincasso" => $_POST["autoincasso"],
                "wachtwoord" => $_POST["wachtwoord"]
            )
        );

        $authentication = $auth->profielAanmelden($params["gegevens"]);

        if (is_string($authentication)) {
            $validatedUserData["code"] = $authentication;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/registreren/");
            exit();
            die();
        } else {
            $auth->login($_POST["emailadres"], $_POST["wachtwoord"]);
            wp_redirect($baseUrl . "/kind-registreren/");
        }
        exit();
        die();
    }
}

add_action('loop_start', 'check_login', 1, 0);
add_action('template_redirect', 'check_login', 1, 0);
function check_login()
{
    global $wp;
    global $auth;
    global $wp_query;

    $page = $wp_query->post->post_name;

    $pages = ["account", "account-bijwerken", "kind-aanpassen", "kind-registreren", "kinderen-account"];
    if (in_array($page, $pages)) {
        if (!$auth->authenticate()) {
            unset($_SESSION["user"]);
            $oldLink = home_url($wp) . $_SERVER["REQUEST_URI"];
            $_SESSION["redirect_url"] = $oldLink;
            wp_redirect(home_url($wp) . "/login");
            exit();
            die();
        }
    }

    $logout = ["registreren", "login"];
    if (in_array($page, $logout)) {
        if ($auth->authenticate()) {
            wp_redirect(home_url($wp));
            exit();
            die();
        }
    }
}

add_action('loop_start', 'check_child', 1, 0);
add_action('template_redirect', 'check_child', 1, 0);
function check_child()
{
    global $wp;
    global $auth;
    global $wp_query;

    if ($wp_query->post->post_name === "kind-aanpassen") {
        $baseUrl = home_url($wp->request);
        if (!isset($_SESSION["childID"])) {
            wp_redirect($baseUrl . "/kinderen-account/");
        } else {
            if (isset($_SESSION["childID"]) && $_SESSION["childID"] === "") {
                $_SESSION["error"] = array();
                $_SESSION["error"]["code"] = "PERSOON_ID_ONGELDIG";
                wp_redirect($baseUrl . "/kinderen-account/");
                exit();
                die();
            }

            try {
                $childID = $auth->profielPersoonGegevens($_SESSION["childID"]);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $_SESSION["error"] = array();
                $_SESSION["error"]["code"] = $e;
                wp_redirect($baseUrl . "/kinderen-account/");
                exit();
                die();
            }

            if ($childID === "PERSOON_ID_ONGELDIG") {
                $_SESSION["error"] = array();
                $_SESSION["error"]["code"] = "PERSOON_ID_ONGELDIG";
                wp_redirect($baseUrl . "/kinderen-account/");
                exit();
                die();
            }
        }
    }
}

add_filter("init", "regicare_register_child");

function regicare_register_child()
{
    if (isset($_SESSION["form"]) && $_SESSION["form"] === "registerChild" && isset($_POST["form"]) && $_POST["form"] === "childRegister") {
        global $auth;
        global $wp;

        $baseUrl = home_url($wp->request);
        $validatedUserData = validateAndSetPost(
            [
                "regicare_roepnaam" => ["name", true],
                "voorletters" => ["name", true],
                "tussenvoegsel" => ["name", false],
                "achternaam" => ["name", true],
                "geslacht" => ["gender", true],
                "geboortedatum" => ["date", true],
                "telefoonVast" => ["phone", false],
                "telefoonMobiel" => ["phone", false],
                "emailadres" => ["mail", true],
            ]
        );

        if (isset($_POST["house"]) && $_POST["house"] === "true") {
            $validatedUserAddress = validateAndSetPost(
                [
                    "toevoeging" => ["string", false],
                    "postcode" => ["post", true],
                    "nummer" => ["string", true]
                ]
            );
            $validatedUserData = array_merge($validatedUserData, $validatedUserAddress);
        }

        if (!isset($_POST["house"]) && $_POST["house"] !== "true" && $_POST["house"] !== "false") {
            $validatedUserData["house"] = [$_POST["house"], false];
        } else {
            $validatedUserData["house"] = [$_POST["house"]];
        }

        foreach ($validatedUserData as $col => $val) {
            if (isset($val[1])) {
                $validatedUserData["code"] = "FORMDATAMISSING";
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/kind-registreren/");
                die();
            }
        }

        if ($_POST["house"] === "true") {
            try {
                $adress = $auth->profielAdresControle($_POST["postcode"], $_POST["nummer"]);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $validatedUserData["code"] = $e;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/kind-registreren/");
                die();
            }

            if (!isset($adress->result) || $adress->result !== "OK") {
                $validatedUserData["code"] = $adress;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/kind-registreren/");
                die();
            }

            try {
                $adress = $auth->profielAdresOphalen($_POST["postcode"], $_POST["nummer"]);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $validatedUserData["code"] = $e;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/kind-registreren/");
                die();
            }

            if (!isset($adress->straat) || !isset($adress->nummer) || !isset($adress->postcode) || !isset($adress->plaats)) {
                $validatedUserData["code"] = $adress;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/kind-registreren/");
                die();
            }
        }

        if ($_POST["house"] === "false") {
            if ($_SESSION["user"]["loginKey"] == null) {
                $oldLink = home_url($wp) . $_SERVER["REQUEST_URI"];
                $_SESSION["redirect_url"] = $oldLink;
                wp_redirect(home_url($wp) . "/login");
                die();
            } else {
                $parentUser = $auth->profielGegevens();
            }
        }


        $params = array(
            "gegevens" => array(
                "roepnaam"      => $_POST["regicare_roepnaam"],
                "voorletters"   => $_POST["voorletters"],
                "tussenvoegsel" => $_POST["tussenvoegsel"],
                "achternaam"    => $_POST["achternaam"],
                "geslacht"      => $_POST["geslacht"],
                "geboortedatum" => date("Y-m-d", strtotime($_POST["geboortedatum"])),
                "telefoonVast" => $_POST["telefoonVast"],
                "telefoonMobiel" => $_POST["telefoonMobiel"],
                "emailadres"    => $_POST["emailadres"],
                "postcode"      => ($_POST["house"] === "true" ? $adress->postcode : $parentUser->postcode),
                "nummer"        => ($_POST["house"] === "true" ? $adress->nummer : $parentUser->nummer),
                "toevoeging"    => ($_POST["house"] === "true" ? $_POST["toevoeging"] : $parentUser->toevoeging),
                "straat"        => ($_POST["house"] === "true" ? $adress->straat : $parentUser->straat),
                "plaats"        => ($_POST["house"] === "true" ? $adress->plaats : $parentUser->plaats),
                "land"          => ($_POST["house"] === "true" ? "Nederland" : $parentUser->land)
            )
        );

        $authentication = $auth->profielPersoonToevoegen($params["gegevens"]);

        if (is_string($authentication)) {
            $validatedUserData["code"] = $authentication;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kind-registreren/");
        } else {
            if ($_SESSION["user"]["loginKey"] == null) {
                $oldLink = home_url($wp) . $_SERVER["REQUEST_URI"];
                $_SESSION["redirect_url"] = $oldLink;
                wp_redirect(home_url($wp) . "/login");
                die();
            } else {
                wp_redirect($baseUrl . "/nieuw-kind/");
                die();
            }
        }
        die();
    }
}

add_filter("init", "regicare_update_profile");

function regicare_update_profile()
{

    if (isset($_POST["form"]) && $_POST["form"] === "accountUpdate" && isset($_SESSION["form"]) && $_SESSION["form"] === "updateAccount") {
        global $auth;
        global $wp;

        $baseUrl = home_url($wp->request);

        $validatedUserData = validateAndSetPost(
            [
                "regicare_roepnaam" => ["name", true],
                "voorletters" => ["name", true],
                "tussenvoegsel" => ["name", false],
                "achternaam" => ["name", true],
                "geslacht" => ["gender", true],
                "geboortedatum" => ["date", true],
                "toevoeging" => ["string", false],
                "postcode" => ["post", true],
                "nummer" => ["string", true],
                "telefoonVast" => ["phone", false],
                "telefoonMobiel" => ["phone", false],
                "emailadres" => ["mail", true],
                "iban" => ["string", true],
                "autoincasso" => ["string", false]
            ]
        );

        foreach ($validatedUserData as $col => $val) {
            if (isset($val[1])) {
                $validatedUserData["code"] = "FORMDATAMISSING";
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/account-bijwerken/");
                die();
            }
        }

        try {
            $adress = $auth->profielAdresControle($_REQUEST["postcode"], $_REQUEST["nummer"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $validatedUserData["code"] = $e;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/account-bijwerken/");
            die();
        }

        if (!isset($adress->result) || $adress->result !== "OK") {
            $validatedUserData["code"] = $adress;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/account-bijwerken/");
            die();
        }

        try {
            $adress = $auth->profielAdresOphalen($_REQUEST["postcode"], $_REQUEST["nummer"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $validatedUserData["code"] = $e;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/account-bijwerken/");
            die();
        }

        if (!isset($adress->straat) || !isset($adress->nummer) || !isset($adress->postcode) || !isset($adress->plaats)) {
            $validatedUserData["code"] = $adress;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/account-bijwerken/");
            die();
        }

        if ($_REQUEST["iban"] !== "") {
            try {
                $iban = $auth->profielIbanControle($_REQUEST["iban"]);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $validatedUserData["code"] = $e;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/account-bijwerken/");
                die();
            }

            if (!isset($iban->result) || $iban->result === "OK") {
                $validatedUserData["code"] = $iban;
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/account-bijwerken/");
                die();
            }
        }

        $params = array(
            "gegevens" => array(
                "roepnaam" => $_POST["regicare_roepnaam"],
                "voorletters" => $_POST["voorletters"],
                "tussenvoegsel" => $_POST["tussenvoegsel"],
                "achternaam" => $_POST["achternaam"],
                "geslacht" => $_POST["geslacht"],
                "geboortedatum" => date("Y-m-d", strtotime($_POST["geboortedatum"])),
                "postcode" => $adress->postcode,
                "nummer" => $_POST["nummer"],
                "toevoeging" => $_POST["toevoeging"],
                "straat" => $adress->straat,
                "plaats" => $adress->plaats,
                "land" => "Nederland",
                "telefoonVast" => $_POST["telefoonVast"],
                "telefoonMobiel" => $_POST["telefoonMobiel"],
                "emailadres" => $_POST["emailadres"],
                "iban" => ($iban ?? ""),
                "autoincasso" => $_POST["autoincasso"]
            )
        );

        $result = $auth->profielOpslaan($params["gegevens"]);

        if ($result->result == "OK") {
            wp_redirect(home_url($wp) . "/account/");
            exit();
        } else {
            $validatedUserData["code"] = $result;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/account-bijwerken/");
            exit();
            die();
        }
    }
}

add_filter("init", "regicare_redirect_update_child");

function regicare_redirect_update_child()
{
    if (isset($_SESSION["form"]) && $_SESSION["form"] === "accountChildRedirect" && isset($_POST["childID"])) {
        global $wp;
        global $auth;

        $baseUrl = home_url($wp->request);

        if (isset($_POST["childID"]) && $_POST["childID"] === "") {
            $_SESSION["error"] = array();
            $_SESSION["error"]["code"] = "PERSOON_ID_ONGELDIG";
            wp_redirect($baseUrl . "/kinderen-account/");
            exit();
            die();
        }

        try {
            $childID = $auth->profielPersoonGegevens($_POST["childID"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $_SESSION["error"] = array();
            $_SESSION["error"]["code"] = $e;
            wp_redirect($baseUrl . "/kinderen-account/");
            exit();
            die();
        }

        if ($childID === "PERSOON_ID_ONGELDIG") {
            $_SESSION["error"] = array();
            $_SESSION["error"]["code"] = "PERSOON_ID_ONGELDIG";
            wp_redirect($baseUrl . "/kinderen-account/");
            exit();
            die();
        }

        $_SESSION["childID"] = $_POST["childID"];

        wp_redirect($baseUrl . "/kind-aanpassen/");
        exit();
        die();
    }
}

add_filter("init", "regicare_update_child_profile");

function regicare_update_child_profile()
{
    if (isset($_POST["form"]) && $_POST["form"] === "accountChildUpdate" && isset($_SESSION["form"]) && $_SESSION["form"] === "updateChildAccount") {
        global $wp;
        global $auth;

        $baseUrl = home_url($wp->request);

        $validatedUserData = validateAndSetPost(
            [
                "regicare_roepnaam" => ["name", true],
                "voorletters" => ["name", true],
                "tussenvoegsel" => ["name", false],
                "achternaam" => ["name", true],
                "geslacht" => ["gender", true],
                "geboortedatum" => ["date", true],
                "toevoeging" => ["string", false],
                "postcode" => ["post", true],
                "nummer" => ["string", true],
                "telefoonVast" => ["phone", false],
                "telefoonMobiel" => ["phone", false],
                "emailadres" => ["mail", true]
            ]
        );

        foreach ($validatedUserData as $col => $val) {
            if (isset($val[1])) {
                $validatedUserData["code"] = "FORMDATAMISSING";
                $_SESSION["error"] = $validatedUserData;
                wp_redirect($baseUrl . "/kind-aanpassen/");
                exit();
                die();
            }
        }

        if (isset($_POST["childID"]) && $_POST["childID"] === "") {
            $validatedUserData["code"] = "PERSOON_ID_ONGELDIG";
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kinderen-account/");
            exit();
            die();
        }

        try {
            $childID = $auth->profielPersoonGegevens($_POST["childID"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $validatedUserData["code"] = $e;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kind-aanpassen/");
            exit();
            die();
        }

        if ($childID === "PERSOON_ID_ONGELDIG") {
            $validatedUserData["code"] = "PERSOON_ID_ONGELDIG";
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kinderen-account/");
            exit();
            die();
        }

        try {
            $adress = $auth->profielAdresControle($_REQUEST["postcode"], $_REQUEST["nummer"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $validatedUserData["code"] = $e;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kind-aanpassen/");
            exit();
            die();
        }

        if (!isset($adress->result) || $adress->result !== "OK") {
            $validatedUserData["code"] = $adress;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kind-aanpassen/");
            exit();
            die();
        }

        try {
            $adress = $auth->profielAdresOphalen($_REQUEST["postcode"], $_REQUEST["nummer"]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $validatedUserData["code"] = $e;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kind-aanpassen/");
            exit();
            die();
        }

        if (!isset($adress->straat) || !isset($adress->nummer) || !isset($adress->postcode) || !isset($adress->plaats)) {
            $validatedUserData["code"] = $adress;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kind-aanpassen/");
            exit();
            die();
        }

        $params = array(
            "gegevens" => array(
                "roepnaam" => $_POST["regicare_roepnaam"],
                "voorletters" => $_POST["voorletters"],
                "tussenvoegsel" => $_POST["tussenvoegsel"],
                "achternaam" => $_POST["achternaam"],
                "geslacht" => $_POST["geslacht"],
                "geboortedatum" => date("Y-m-d", strtotime($_POST["geboortedatum"])),
                "postcode" => $adress->postcode,
                "nummer" => $_POST["nummer"],
                "toevoeging" => $_POST["toevoeging"],
                "straat" => $adress->straat,
                "plaats" => $adress->plaats,
                "land" => "Nederland",
                "telefoonVast" => $_POST["telefoonVast"],
                "telefoonMobiel" => $_POST["telefoonMobiel"],
                "emailadres" => $_POST["emailadres"],
                "iban" => ($iban ?? ""),
                "autoincasso" => $_POST["autoincasso"]
            )
        );

        $result = $auth->profielPersoonBewerken($params["gegevens"], $_POST["childID"]);

        if ($result->result == "OK") {
            wp_redirect(home_url($wp) . "/kinderen-account/");
            exit();
            die();
        } else {
            $validatedUserData["code"] = $result;
            $_SESSION["error"] = $validatedUserData;
            wp_redirect($baseUrl . "/kind-aanpassen/");
            exit();
            die();
        }
    }
}


/**
 * Adds account navigation item to the navigation.
 */
add_filter("wp_nav_menu_main-menu_items", "display_loggedin_username", 20, 2);

function display_loggedin_username($items, $args)
{
        global $wp;

        $link = home_url($wp->request);
        $userName = $_SESSION['user']['naam'] ?? null;

        if (isset($_SESSION['user'])) {
            $items .= '<li class="menu-item menu-item-has-children">
                <a href="#" class="menu-link elementor-item">' . substr($userName, 0,10)  . '</a>
                <ul class="sub-menu elementor-nav-menu--dropdown sm-nowrap">
                    <li class="menu-item">
                        <a class="menu-link elementor-sub-item" href="' . $link . '/account">Mijn account</a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link elementor-sub-item" href="' . $link . '/?logout=true">Afmelden</a>
                    </li>
                </ul>
            </li>';
        } else {
            $items .= '<li class="menu-item"><a class="menu-link elementor-item" href="'
                . $link
                . '/login">Inloggen</a></li>';
        }
        return $items;
}

add_action("admin_menu", "regicare_plugin_settings");

function enqueue_custom_stylesheets()
{
    global $directory;
    wp_localize_script("mytheme-scripts-jquery", "theme", array("ajax_url" => admin_url("https://cbwedamvolendam.nl/admin-ajax.php")));
    wp_enqueue_style("mytheme-custom", $directory . "/assets/css/regicare.css");
    wp_enqueue_script("mytheme-jquery-script", "https://code.jquery.com/jquery-3.4.1.min.js", NULL, true);
    wp_enqueue_script("mytheme-script", $directory . "/assets/js/custom/script.js", NULL, true);
}

add_action("wp_enqueue_scripts", "enqueue_custom_stylesheets", 11);

/* Voeg de pagina toe en voeg de hook toe voor het registreren van settings */
function regicare_plugin_settings()
{
    add_menu_page("RegiCare Settings", "RegiCare", "manage_options", "regicare-settings-mail", "regicare_plugin_options");
    add_action("admin_init", "register_regicare_settings");
}

/* Registreer de settings */
function register_regicare_settings()
{
    register_setting("regicare_settings", "regicare_key");
    register_setting("regicare_settings", "regicare_domain");
}

/* Laat de settings form zien */
function regicare_plugin_options()
{
    if (!current_user_can("manage_options")) {
        wp_die(__("You do not have sufficient permissions to access this page."));
    }
    ?>
    <div class="wrap">
        <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/southaxis.svg'; ?>" alt="SouthAxis" style="width: 13%; height: auto;">
        <h1>RegiCare plugin settings.</h1>
        <h2>Hier kan je alle RegiCare instellingen aanpassen.</h2>
        <form id="regicare_settings" action="options.php" method="post">
            <?php settings_fields("regicare_settings"); ?>
            <?php do_settings_sections("regicare_settings"); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">API key</th>
                    <td><input type="text" id="regicare_key" name="regicare_key" value="<?php echo esc_attr(get_option('regicare_key')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">API Endpoint</th>
                    <td><input type="text" id="regicare_domain" name="regicare_domain" value="<?php echo esc_attr(get_option('regicare_domain')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

add_filter("wp_nav_menu_items", "do_shortcode");
