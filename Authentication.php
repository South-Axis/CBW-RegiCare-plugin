<?php

/**
 * Handles Authentication
 */

if (!defined('ABSPATH')) {
    exit;
}
require 'vendor/autoload.php';

if (!class_exists('Authentication')) {
    class Authentication
    {
        const LOGIN_ERROR = 'GEBRUIKERSACCOUNT_ONBEKEND';

        public function login_regicare_show()
        {
            global $wp;

            $link = home_url($wp->request);
            $error = isset($_REQUEST['error']) ? ($_REQUEST['error']) : null;

            ob_start();

?>
            <form method="post" enctype="multipart/form-data" id="loginForm">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" name="regicare_email" class="form-control" id="regicare_email" aria-describedby="email" placeholder="email adres" required>
                </div>
                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" name="regicare_password" class="form-control" id="regicare_password" placeholder="Wachtwoord" required>
                </div>
                <?php
                if ($error != null) {
                ?>
                    <p class="sb-text-center sb-login-warning">Het wachtwoord of de gebruikersnaam is incorrect</p>
                <?php }
                ?>
                <button type="submit" class="sb-filter-button btn-orange sb-filter-button-text float-right">Inloggen
                </button>
            </form>
            <p class="float-right mt-3"> Hebt u nog geen account <a href="<?php $link ?>/registreren">registreer</a>
                hier of ga naar <a href="<?php $link ?>/wachtwoord-vergeten">wachtwoord vergeten</a>.
            </p>
            <?php
            return ob_get_clean();
        }

        public function forgot_password_show()
        {

            global $wp;

            $link = home_url($wp->request);

            ob_start();

            if (isset($_REQUEST['error']) && $_REQUEST['error'] === "false") {
            ?>
                <p class="sb-text-center sb-login-warning">Als dit emailadres bij ons bekend is heeft u een email met instructies ontvangen.</p>
            <?php
            } else {
            ?>
                <form method="post" enctype="multipart/form-data" id="forgotPasswordForm">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="regicare_email" class="form-control" id="regicare_email" aria-describedby="email" placeholder="email adres" required>
                    </div>
                    <button type="submit" class="sb-filter-button btn-orange sb-filter-button-text float-right">Verstuur
                    </button>
                </form>
            <?php
            }
            return ob_get_clean();
        }

        public function register_regicare_show()
        {
            global $wp;

            $_SESSION["form"] = "register";
            $baseUrl = home_url($wp->request);

            if (isset($_SESSION["error"]["code"])) {
                $error = $_SESSION["error"];
                unset($_SESSION["error"]);

                switch ($error["code"]) {
                    case "FORMDATAMISSING":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_BEZET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven e-mailadres bestaat al.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_INCOMPLEET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "ADRES_ONBEKEND":
                        echo '<p class="sb-text-center sb-login-warning">Het opgegeven adress voor het opslaan van het gebruikersaccount is incorrect.</p>';
                        break;
                    default:
                        echo '<p class="sb-text-center sb-login-warning">Er ging iets fout met het aanmaken van het gebruikersaccount probeer het later nog eens.</p>';
                        break;
                }
            }

            ob_start();
            ?>

            <form method="post" enctype="multipart/form-data" id="registerForm">
                <div class="form-group">
                    <label class="requiredField" for="regicare_roepnaam">Roepnaam</label>
                    <input type="text" name="regicare_roepnaam" class="form-control <?php echo (isset($error["regicare_roepnaam"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["regicare_roepnaam"][0]) ? 'value="' . $error["regicare_roepnaam"][0] . '"' : null) ?> id="regicare_roepnaam" aria-describedby="roepnaam" placeholder="Roepnaam" required>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="voorletters">Voorletters</label>
                    <input type="text" name="voorletters" class="form-control <?php echo (isset($error["voorletters"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["voorletters"][0]) ? 'value="' . $error["voorletters"][0] . '"' : null) ?> id="voorletters" aria-describedby="voorletters" placeholder="Voorletters" required>
                </div>
                <div class="form-group">
                    <label for="tussenvoegsel">Tussenvoegsel</label>
                    <input type="text" name="tussenvoegsel" class="form-control <?php echo (isset($error["tussenvoegsel"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["tussenvoegsel"][0]) ? 'value="' . $error["tussenvoegsel"][0] . '"' : null) ?> id="tussenvoegsel" aria-describedby="tussenvoegsel" placeholder="Tussenvoegsel">
                </div>
                <div class="form-group">
                    <label class="requiredField" for="achternaam">Achternaam</label>
                    <input type="text" name="achternaam" class="form-control <?php echo (isset($error["achternaam"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["achternaam"][0]) ? 'value="' . $error["achternaam"][0] . '"' : null) ?> id="achternaam" aria-describedby="achternaam" placeholder="Achternaam" required>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="geslacht">Geslacht</label>
                    <select class='form-control <?php echo (isset($error["geslacht"][1]) ? "border-danger" : null) ?>' id='geslacht' name='geslacht' required>
                        <option <?php echo (isset($error["geslacht"][0]) && $error["geslacht"][0] === "1" ? "selected" : null); ?> value='1'>Man</option>
                        <option <?php echo (isset($error["geslacht"][0]) && $error["geslacht"][0] === "2" ? "selected" : null); ?> value='2'>Vrouw</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="geboortedatum">Geboortedatum</label>
                    <input type="date" name="geboortedatum" class="form-control <?php echo (isset($error["geboortedatum"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["geboortedatum"][0]) ? 'value="' . $error["geboortedatum"][0] . '"' : null) ?> id="geboortedatum" aria-describedby="geboortedatum" placeholder="Geboortedatum" required>
                </div>
                <div>
                    <div class="form-group">
                        <label class="requiredField" for="postcode">Postcode</label>
                        <input type="text" name="postcode" id="zipcode" class="form-control <?php echo (isset($error["postcode"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["postcode"][0]) ? 'value="' . $error["postcode"][0] . '"' : null) ?> id="postcode" aria-describedby="postcode" placeholder="Postcode" required>
                    </div>
                    <div class="form-group">
                        <label class="requiredField" for="nummer">Huisnummer</label>
                        <input type="text" name="nummer" id="housenumber" class="form-control <?php echo (isset($error["nummer"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["nummer"][0]) ? 'value="' . $error["nummer"][0] . '"' : null) ?> id="nummer" aria-describedby="nummer" placeholder="Huisnummer" required>
                    </div>
                    <div class="form-group">
                        <label for="toevoeging">Toevoeging</label>
                        <input type="text" name="toevoeging" class="form-control <?php echo (isset($error["toevoeging"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["toevoeging"][0]) ? 'value="' . $error["toevoeging"][0] . '"' : null) ?> id="toevoeging" aria-describedby="toevoeging" placeholder="Toevoeging">
                    </div>
                    <div class="form-group">
                        <label class="requiredField" for="straat">Straat</label>
                        <input type="text" name="straat" id="street" class="form-control <?php echo (isset($error["straat"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["straat"][0]) ? 'value="' . $error["straat"][0] . '"' : null) ?> id="straat" aria-describedby="straat" placeholder="Straatnaam" required disabled>
                    </div>
                    <div class="form-group">
                        <label class="requiredField" for="plaats">Plaats</label>
                        <input type="text" name="plaats" id="place" class="form-control <?php echo (isset($error["plaats"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["plaats"][0]) ? 'value="' . $error["plaats"][0] . '"' : null) ?> id="plaats" aria-describedby="plaats" placeholder="Plaats" required disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label for="telefoonVast">Telefoon (Vast)</label>
                    <input type="text" name="telefoonVast" class="form-control <?php echo (isset($error["telefoonVast"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["telefoonVast"][0]) ? 'value="' . $error["telefoonVast"][0] . '"' : null) ?> id="telefoonVast" aria-describedby="telefoon" placeholder="Telefoonnummer">
                </div>
                <div class="form-group">
                    <label for="telefoonMobiel">Telefoon (Mobiel)</label>
                    <input type="text" name="telefoonMobiel" class="form-control <?php echo (isset($error["telefoonMobiel"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["telefoonMobiel"][0]) ? 'value="' . $error["telefoonMobiel"][0] . '"' : null) ?> id="telefoonMobiel" aria-describedby="telefoon" placeholder="Telefoonnummer">
                </div>
                <div class="form-group">
                    <label for="iban">IBAN-Rekeningnummer</label>
                    <input type="text" name="iban" class="form-control <?php echo (isset($error["iban"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["iban"][0]) ? 'value="' . $error["iban"][0] . '"' : null) ?> id="iban" aria-describedby="iban" placeholder="IBAN-Rekeningnummer">
                </div>
                <div class="form-group form-check">
                    <label for="autoincasso">Auto incasso</label>
                    <select class='form-control <?php echo (isset($error["autoincasso"][1]) ? "border-danger" : null) ?>' id='autoincasso' name='autoincasso' aria-describedby="auto incasso">
                        <option <?php echo (isset($error["autoincasso"][0]) && $error["autoincasso"][0] === "1" ? "selected" : null); ?> value='1'>Ja</option>
                        <option <?php echo (isset($error["autoincasso"][0]) && $error["autoincasso"][0] === "0" ? "selected" : null); ?> value='0'>Nee</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="emailadres">E-mailadres</label>
                    <input type="email" name="emailadres" class="form-control <?php echo (isset($error["emailadres"][1]) || $error["code"] === "GEBRUIKERSACCOUNT_BEZET" ? "border-danger" : null) ?>" <?php echo (isset($error["emailadres"][0]) ? 'value="' . $error["emailadres"][0] . '"' : null) ?> id="emailadres" aria-describedby="emailadres" placeholder="Emailadres" required>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="wachtwoord">Wachtwoord</label>
                    <input type="password" name="wachtwoord" class="form-control <?php echo (isset($error["wachtwoord"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["wachtwoord"][0]) ? 'value="' . $error["wachtwoord"][0] . '"' : null) ?> id="wachtwoord" aria-describedby="wachtwoord" placeholder="Wachtwoord" required>
                </div>
                <button type="submit" class="sb-filter-button btn-orange sb-filter-button-text float-right sb-registerButton">
                    Registreer
                </button>
            </form>
            <p class="float-right mt-3"> Hebt u al een account log dan hier in, <a href="<?php echo $baseUrl . "/login" ?>">inloggen</a> hier. </p>
        <?php
            return ob_get_clean();
        }

        public function register_child_regicare_show()
        {
            global $wp;

            $_SESSION["form"] = "registerChild";
            $baseUrl = home_url($wp->request);

            if (isset($_SESSION["error"]["code"])) {
                $error = $_SESSION["error"];
                unset($_SESSION["error"]);

                switch ($error["code"]) {
                    case "FORMDATAMISSING":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_BEZET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven e-mailadres bestaat al.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_INCOMPLEET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "ADRES_ONBEKEND":
                        echo '<p class="sb-text-center sb-login-warning">Het opgegeven adress voor het opslaan van het gebruikersaccount is incorrect.</p>';
                        break;
                    default:
                        echo '<p class="sb-text-center sb-login-warning">Er ging iets fout met het aanmaken van het gebruikersaccount probeer het later nog eens.</p>';
                        break;
                }
            }

            ob_start();
        ?>
            <h3 class="sb-text-center">Voeg uw kind toe</h3>
            <form method="post" enctype="multipart/form-data" id="registerForm">
                <input type="text" name="form" value="childRegister" hidden>
                <div class="form-group">
                    <label class="requiredField" for="regicare_roepnaam">Roepnaam</label>
                    <input type="text" name="regicare_roepnaam" class="form-control <?php echo (isset($error["regicare_roepnaam"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["regicare_roepnaam"][0]) ? 'value="' . $error["regicare_roepnaam"][0] . '"' : null) ?> id="regicare_roepnaam" aria-describedby="roepnaam" placeholder="Roepnaam" required>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="voorletters">Voorletters</label>
                    <input type="text" name="voorletters" class="form-control <?php echo (isset($error["voorletters"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["voorletters"][0]) ? 'value="' . $error["voorletters"][0] . '"' : null) ?> id="voorletters" aria-describedby="voorletters" placeholder="Voorletters" required>
                </div>
                <div class="form-group">
                    <label for="tussenvoegsel">Tussenvoegsel</label>
                    <input type="text" name="tussenvoegsel" class="form-control <?php echo (isset($error["tussenvoegsel"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["tussenvoegsel"][0]) ? 'value="' . $error["tussenvoegsel"][0] . '"' : null) ?> id="tussenvoegsel" aria-describedby="tussenvoegsel" placeholder="Tussenvoegsel">
                </div>
                <div class="form-group">
                    <label class="requiredField" for="achternaam">Achternaam</label>
                    <input type="text" name="achternaam" class="form-control <?php echo (isset($error["achternaam"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["achternaam"][0]) ? 'value="' . $error["achternaam"][0] . '"' : null) ?> id="achternaam" aria-describedby="achternaam" placeholder="Achternaam" required>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="geslacht">Geslacht</label>
                    <select class='form-control <?php echo (isset($error["geslacht"][1]) ? "border-danger" : null) ?>' id='geslacht' name='geslacht' required>
                        <option <?php echo (isset($error["geslacht"][0]) && $error["geslacht"][0] === "1" ? "selected" : null); ?> value='1'>Man</option>
                        <option <?php echo (isset($error["geslacht"][0]) && $error["geslacht"][0] === "2" ? "selected" : null); ?> value='2'>Vrouw</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="geboortedatum">Geboortedatum</label>
                    <input type="date" name="geboortedatum" class="form-control <?php echo (isset($error["geboortedatum"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["geboortedatum"][0]) ? 'value="' . $error["geboortedatum"][0] . '"' : null) ?> id="geboortedatum" aria-describedby="geboortedatum" placeholder="Geboortedatum" required>
                </div>
                <div class="form-group">
                    <label for="telefoonVast">Telefoon (Vast)</label>
                    <input type="text" name="telefoonVast" class="form-control <?php echo (isset($error["telefoonVast"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["telefoonVast"][0]) ? 'value="' . $error["telefoonVast"][0] . '"' : null) ?> id="telefoonVast" aria-describedby="telefoon" placeholder="Telefoonnummer">
                </div>
                <div class="form-group">
                    <label for="telefoonMobiel">Telefoon (Mobiel)</label>
                    <input type="text" name="telefoonMobiel" class="form-control <?php echo (isset($error["telefoonMobiel"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["telefoonMobiel"][0]) ? 'value="' . $error["telefoonMobiel"][0] . '"' : null) ?> id="telefoonMobiel" aria-describedby="telefoon" placeholder="Telefoonnummer">
                </div>
                <div class="form-group">
                    <label class="requiredField" for="emailadres">E-mailadres</label>
                    <input type="email" name="emailadres" class="form-control <?php echo (isset($error["emailadres"][1]) || $error["code"] === "GEBRUIKERSACCOUNT_BEZET" ? "border-danger" : null) ?>" <?php echo (isset($error["emailadres"][0]) ? 'value="' . $error["emailadres"][0] . '"' : null) ?> id="emailadres" aria-describedby="emailadres" placeholder="Emailadres" required>
                </div>
                <div class="form-group">
                    <?php echo (isset($error["house"][0]) ? $error["house"][0] : null) ?>
                    <label class="requiredField" for="name">Woont uw kind op een andere locatie dan u?</label>
                    <br>
                    <input type="radio" name="house" class="<?php echo (isset($error["house"][1]) ? "border-danger" : null) ?>" value="true" id="ja" onclick="javascript:addAdress(true);" <?php echo (isset($error["house"][0]) && $error["house"][0] === "true" ? "checked" : null) ?> required>
                    <label for="ja">Ja</label>
                    <input type="radio" name="house" class="<?php echo (isset($error["house"][1]) ? "border-danger" : null) ?>" value="false" id="nee" onclick="javascript:addAdress(false);" <?php echo (isset($error["house"][0]) && $error["house"][0] === "false" ? "checked" : null) ?>>
                    <label for="nee">Nee</label>
                </div>
                <div id="showAlternateAdress">
                </div>
                <button type="submit" class="sb-filter-button btn-orange sb-filter-button-text float-right sb-registerButton">
                    Voeg een kind toe aan uw account
                </button>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (document.getElementById("ja").checked === true) {
                        addAdress(true);
                    }
                }, false);

                function addAdress(x) {
                    const e = document.getElementById("showAlternateAdress");
                    if (x === true) {
                        e.innerHTML = `<div>
                        <div class="form-group">
                            <label class="requiredField" for="postcode">Postcode</label>
                            <input type="text" name="postcode" id="zipcode" class="form-control <?php echo (isset($error["postcode"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["postcode"][0]) ? 'value="' . $error["postcode"][0] . '"' : null) ?> id="postcode" aria-describedby="postcode" placeholder="Postcode" required>
                        </div>
                        <div class="form-group">
                            <label class="requiredField" for="nummer">Huisnummer</label>
                            <input type="text" name="nummer" id="housenumber" class="form-control <?php echo (isset($error["nummer"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["nummer"][0]) ? 'value="' . $error["nummer"][0] . '"' : null) ?> id="nummer" aria-describedby="nummer" placeholder="Huisnummer" required>
                        </div>
                        <div class="form-group">
                            <label for="toevoeging">Toevoeging</label>
                            <input type="text" name="toevoeging" class="form-control <?php echo (isset($error["toevoeging"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["toevoeging"][0]) ? 'value="' . $error["toevoeging"][0] . '"' : null) ?> id="toevoeging" aria-describedby="toevoeging" placeholder="Toevoeging">
                        </div>
                        <div class="form-group">
                            <label class="requiredField" for="straat">Straat</label>
                            <input type="text" name="straat" id="street" class="form-control <?php echo (isset($error["straat"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["straat"][0]) ? 'value="' . $error["straat"][0] . '"' : null) ?> id="straat" aria-describedby="straat" placeholder="Straatnaam" required disabled>
                        </div>
                        <div class="form-group">
                            <label class="requiredField" for="plaats">Plaats</label>
                            <input type="text" name="plaats" id="place" class="form-control <?php echo (isset($error["plaats"][1]) ? "border-danger" : null) ?>" <?php echo (isset($error["plaats"][0]) ? 'value="' . $error["plaats"][0] . '"' : null) ?> id="plaats" aria-describedby="plaats" placeholder="Plaats" required disabled>
                        </div>
                    </div>`;
                    } else {
                        e.innerHTML = "";
                    }
                }
            </script>
        <?php
            return ob_get_clean();
        }

        public function other_child_short_show()
        {
            global $wp;

            if (isset($_SESSION['redirect_url'])) {
                $link = $_SESSION['redirect_url'];
            } else {
                $link = home_url($wp);
            }
            ob_start();
        ?>
            <div class="inline-button-container">
                <a class="inline-button sb-filter-button-text" href="<?php echo $link ?>">Nee</a>
                <a class="inline-button sb-filter-button-text" href="<?php echo home_url($wp) . "/kind-registreren/" ?>">Ja</a>
            </div>
        <?php
            return ob_get_clean();
        }

        public function account_short_show()
        {
            global $wp;
            global $auth;

            $link = home_url($wp->request);
            $error = false;

            if (isset($_REQUEST['error'])) {
                $error = ($_REQUEST['error']);
            }

            $user = $auth->profielGegevens();
            ob_start();

        ?>
            <div class="container">
                <h3 class="sb-color-orange">Mijn profiel</h3>
                <div class="inline-button-container">
                    <a href="<?php echo home_url($wp) . '/account-bijwerken/'; ?>" class="btn btn-orange sb-align-bottom" style="margin-right: 10px;">
                        Profiel bijwerken
                    </a>
                    <a href="<?php echo home_url($wp) . '/kinderen-account/'; ?>" class="btn btn-orange sb-align-bottom">
                        Mijn kinderen
                    </a>
                </div>
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">E-mailadres</strong>
                <input class="form-control" value="<?php echo $user->gebruikersnaam ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Roepnaam</strong>
                <input class="form-control" value="<?php echo $user->roepnaam ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Volledige naam</strong>
                <input class="form-control" value="<?php echo $user->voorletters . (isset($user->tussenvoegsel) && $user->tussenvoegsel !== '' ? ' ' . $user->tussenvoegsel . ' ' : ' ') . $user->achternaam ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Geslacht</strong>
                <input class="form-control" value="<?php echo ($user->geslacht === 1 ? 'Man' : 'Vrouw') ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Geboortedatum</strong>
                <input class="form-control" value="<?php echo $user->geboortedatum ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Telefoon (Vast)</strong>
                <input class="form-control" value="<?php echo $user->telefoonVast ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Telefoon (Mobiel)</strong>
                <input class="form-control" value="<?php echo $user->telefoonMobiel ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">IBAN-Rekeningnummer</strong>
                <input class="form-control" value="<?php echo $user->iban ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Auto incasso</strong>
                <input class="form-control" value="<?php echo ($user->autoincasso === 0 ? 'Nee' : 'Ja') ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Postcode</strong>
                <input class="form-control" value="<?php echo $user->postcode ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Huisnummer</strong>
                <input class="form-control" value="<?php echo $user->nummer ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Toevoeging</strong>
                <input class="form-control" value="<?php echo $user->toevoeging ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Plaats</strong>
                <input class="form-control" value="<?php echo $user->plaats ?>" disabled />
            </div>
            <div class="form-group">
                <strong class="sb-color-orange">Straat</strong>
                <input class="form-control" value="<?php echo $user->straat ?>" disabled />
            </div>
        <?php
            return ob_get_clean();
        }

        public function account_update_short_show()
        {
            global $wp;
            global $auth;

            $_SESSION["form"] = "updateAccount";

            if (isset($_SESSION["error"]["code"])) {
                $error = $_SESSION["error"];
                unset($_SESSION["error"]);

                switch ($error["code"]) {
                    case "FORMDATAMISSING":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_BEZET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven e-mailadres bestaat al.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_INCOMPLEET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "ADRES_ONBEKEND":
                        echo '<p class="sb-text-center sb-login-warning">Het opgegeven adress voor het opslaan van het gebruikersaccount is incorrect.</p>';
                        break;
                    default:
                        echo '<p class="sb-text-center sb-login-warning">Er ging iets fout met het aanmaken van het gebruikersaccount probeer het later nog eens.</p>';
                        break;
                }
            }

            $user = $auth->profielGegevens();
            ob_start();
        ?>

            <div class="container">
                <h3 class="sb-color-orange">Mijn profiel bijwerken</h3>
                <div class="inline-button-container">
                    <a href="<?php echo home_url($wp) . '/account/'; ?>" class="btn btn-orange sb-align-bottom" style="margin-right: 10px;">
                        Terug
                    </a>
                </div>
            </div>
            <form method="post" enctype="multipart/form-data" id="registerForm">
                <div class="form-group">
                    <label class="requiredField" for="emailadres"><strong class="sb-color-orange">E-mailadres</strong></label>
                    <input type="email" name="emailadres" class="form-control <?php echo (isset($error["emailadres"][1]) ? "border-danger" : null) ?>" id="emailadres" aria-describedby="emailadres" placeholder="Emailadres" value="<?php echo (isset($error["emailadres"][0]) ? $error["emailadres"][0] : $user->gebruikersnaam) ?>" required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="regicare_roepnaam"><strong class="sb-color-orange">Roepnaam</strong></label>
                    <input type="text" name="regicare_roepnaam" id="regicare_roepnaam" class="form-control <?php echo (isset($error["regicare_roepnaam"][1]) ? "border-danger" : null) ?>" aria-describedby="roepnaam" placeholder="Roepnaam" value="<?php echo (isset($error["regicare_roepnaam"][0]) ? $error["regicare_roepnaam"][0] : $user->roepnaam) ?>" required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="voorletters"><strong class="sb-color-orange">Voorletters</strong></label>
                    <input type="text" name="voorletters" class="form-control <?php echo (isset($error["voorletters"][1]) ? "border-danger" : null) ?>" id="voorletters" aria-describedby="voorletters" placeholder="Voorletters" value="<?php echo (isset($error["voorletters"][0]) ? $error["voorletters"][0] : $user->voorletters) ?>" required />
                </div>
                <div class="form-group">
                    <label for="tussenvoegsel"><strong class="sb-color-orange">Tussenvoegsel</strong></label>
                    <input type="text" name="tussenvoegsel" class="form-control <?php echo (isset($error["tussenvoegsel"][1]) ? "border-danger" : null) ?>" id="tussenvoegsel" aria-describedby="tussenvoegsel" placeholder="Tussenvoegsel" value="<?php echo (isset($error["tussenvoegsel"][0]) ? $error["tussenvoegsel"][0] : $user->tussenvoegsel) ?>" />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="achternaam"><strong class="sb-color-orange">Achternaam</strong></label>
                    <input type="text" name="achternaam" class="form-control <?php echo (isset($error["achternaam"][1]) ? "border-danger" : null) ?>" id="achternaam" placeholder="Achternaam" aria-describedby="achternaam" value="<?php echo (isset($error["achternaam"][0]) ? $error["achternaam"][0] : $user->achternaam) ?>" required />
                </div>
                <div class="form-group">
                    <label for="geslacht"><strong class="sb-color-orange">Geslacht</strong></label>
                    <select class='form-control <?php echo (isset($error["geslacht"][1]) ? "border-danger" : null) ?>' id='geslacht' name='geslacht' aria-describedby="geslacht" required>
                        <option <?php echo ((isset($error["geslacht"][0]) ? $error["geslacht"][0] : $user->geslacht) === 1 ? 'selected' : null) ?> value='1'>Man</option>
                        <option <?php echo ((isset($error["geslacht"][0]) ? $error["geslacht"][0] : $user->geslacht) === 2 ? 'selected' : null) ?> value='2'>Vrouw</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="geboortedatum"><strong class="sb-color-orange">Geboortedatum</strong></label>
                    <input type="date" name="geboortedatum" class="form-control <?php echo (isset($error["geboortedatum"][1]) ? "border-danger" : null) ?>" id="geboortedatum" placeholder="Geboortedatum" aria-describedby="geboortedatum" value="<?php echo (isset($error["geboortedatum"][0]) ? $error["geboortedatum"][0] : $user->geboortedatum) ?>" required />
                </div>
                <div class="form-group">
                    <label for="telefoonVast"><strong class="sb-color-orange">Telefoon (vast)</strong></label>
                    <input type="text" name="telefoonVast" class="form-control <?php echo (isset($error["telefoonVast"][1]) ? "border-danger" : null) ?>" id="telefoonVast" aria-describedby="telefoon vast" placeholder="Telefoonnummer vast" value="<?php echo (isset($error["telefoonVast"][0]) ? $error["telefoonVast"][0] : $user->telefoonVast) ?>" />
                </div>
                <div class="form-group">
                    <label for="telefoonMobiel"><strong class="sb-color-orange">Telefoon (Mobiel)</strong></label>
                    <input type="text" name="telefoonMobiel" class="form-control <?php echo (isset($error["telefoonMobiel"][1]) ? "border-danger" : null) ?>" id="telefoonMobiel" aria-describedby="telefoon mobiel" placeholder="Telefoonnummer mobiel" value="<?php echo (isset($error["telefoonMobiel"][0]) ? $error["telefoonMobiel"][0] : $user->telefoonMobiel) ?>" />
                </div>
                <div class="form-group">
                    <strong class="sb-color-orange">IBAN-Rekeningnummer</strong>
                    <input type="text" name="iban" class="form-control <?php echo (isset($error["iban"][1]) ? "border-danger" : null) ?>" id="iban" aria-describedby="iban" placeholder="IBAN-Rekeningnummer" value="<?php echo ($error["iban"][0] ?? $user->iban) ?>" />
                </div>
                <div class="form-group">
                    <label for="autoincasso"><strong class="sb-color-orange">Auto incasso</strong></label>
                    <select class='form-control <?php echo (isset($error["autoincasso"][1]) ? "border-danger" : null) ?>' id='autoincasso' name='autoincasso' aria-describedby="auto incasso">
                      <option <?php echo (($error["autoincasso"][0] ?? $user->autoincasso) === 1 ? 'selected' : null) ?> value='1'>Ja</option>
                     <option <?php echo (($error["autoincasso"][0] ?? $user->autoincasso) === 0 ? 'selected' : null) ?> value='0'>Nee</option>
                   </select>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="postcode"><strong class="sb-color-orange">Postcode</strong></label>
                    <input type="text" name="postcode" class="form-control <?php echo (isset($error["postcode"][1]) ? "border-danger" : null) ?>" id="zipcode" aria-describedby="postcode" placeholder="Postcode" value="<?php echo (isset($error["postcode"][0]) ? $error["postcode"][0] : $user->postcode) ?>" required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="nummer"><strong class="sb-color-orange">Huisnummer</strong></label>
                    <input type="text" name="nummer" class="form-control <?php echo (isset($error["nummer"][1]) ? "border-danger" : null) ?>" id="housenumber" aria-describedby="nummer" placeholder="Huisnummer" value="<?php echo (isset($error["nummer"][0]) ? $error["nummer"][0] : $user->nummer) ?>" required />
                </div>
                <div class="form-group">
                    <label for="toevoeging"><strong class="sb-color-orange">Toevoeging</strong></label>
                    <input type="text" name="toevoeging" class="form-control <?php echo (isset($error["toevoeging"][1]) ? "border-danger" : null) ?>" id="toevoeging" aria-describedby="toevoeging" placeholder="Toevoeging" value="<?php echo (isset($error["toevoeging"][0]) ? $error["toevoeging"][0] : $user->toevoeging) ?>" />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="plaats"><strong class="sb-color-orange">Plaats</strong></label>
                    <input type="text" name="plaats" class="form-control <?php echo (isset($error["plaats"][1]) ? "border-danger" : null) ?>" id="place" aria-describedby="plaats" placeholder="Plaats" value="<?php echo (isset($error["plaats"][0]) ? $error["plaats"][0] : $user->plaats) ?>" disabled required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="straat"><strong class="sb-color-orange">Straat</strong></label>
                    <input type="text" name="straat" class="form-control <?php echo (isset($error["straat"][1]) ? "border-danger" : null) ?>" id="street" aria-describedby="straat" placeholder="Straatnaam" value="<?php echo (isset($error["straat"][0]) ? $error["straat"][0] : $user->straat) ?>" disabled required />
                </div>
                <input type="text" hidden name="form" value="accountUpdate" required>
                <button type="submit" class="sb-filter-button btn-orange sb-filter-button-text float-right sb-registerButton">
                    Profiel bijwerken
                </button>
            </form>
        <?php
            return ob_get_clean();
        }

        public function account_child_short_show()
        {
            global $wp;
            global $auth;

            $_SESSION["form"] = "accountChildRedirect";

            if (isset($_SESSION["error"]["code"])) {
                $error = $_SESSION["error"];
                unset($_SESSION["error"]);

                switch ($error["code"]) {
                    case "FORMDATAMISSING":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_BEZET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven e-mailadres bestaat al.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_INCOMPLEET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "ADRES_ONBEKEND":
                        echo '<p class="sb-text-center sb-login-warning">Het opgegeven adress voor het opslaan van het gebruikersaccount is incorrect.</p>';
                        break;
                    case "PERSOON_ID_ONGELDIG":
                        echo '<p class="sb-text-center sb-login-warning">De persoon die u probeert bij te werken blijkt niet te bestaan probeer het later nog eens.</p>';
                        break;
                    default:
                        echo '<p class="sb-text-center sb-login-warning">Er ging iets fout met het aanmaken van het gebruikersaccount probeer het later nog eens.</p>';
                        break;
                }
            }

            $childeren = $auth->profielPersoonGekoppeld();

            ob_start();
        ?>
            <div class="container">
                <h3 class="sb-color-orange">Uw kinderen</h3>
                <div class="inline-button-container">
                    <a href="<?php echo home_url($wp) . '/account/'; ?>" class="btn btn-orange sb-align-bottom">
                        Terug
                    </a>
                </div>
            </div>
            <?php
            if (!empty($childeren)) {
                foreach ($childeren as $child) {
            ?>
                <div class="col-lg-4 d-flex align-items-stretch">
                    <div class="card mb-3 w-100">
                        <div class="card-body">
                            <div class="child-flex-inline">
                                <h5 class="account-title"><?php echo $child->roepnaam . ($child->tussenvoegsel != "" ? " " . $child->tussenvoegsel . " " : " ") . $child->achternaam ?></h5>
                                <a class="btn btn-orange vertoon">Toon meer</a>
                            </div>
                            <div class="popUpBox" style="display: none">
                                <div class="form-group">
                                    <strong class="sb-color-orange">E-mailadres</strong>
                                    <input class="form-control" value="<?php echo $child->emailadres ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Roepnaam</strong>
                                    <input class="form-control" value="<?php echo $child->roepnaam ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Volledige naam</strong>
                                    <input class="form-control" value="<?php echo $child->voorletters . (isset($child->tussenvoegsel) && $child->tussenvoegsel !== '' ? ' ' . $child->tussenvoegsel . ' ' : ' ') . $child->achternaam ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Geslacht</strong>
                                    <input class="form-control" value="<?php echo ($child->geslacht === 1 ? 'Man' : 'Vrouw') ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Geboortedatum</strong>
                                    <input class="form-control" value="<?php echo $child->geboortedatum ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Telefoon (Vast)</strong>
                                    <input class="form-control" value="<?php echo $child->telefoonVast ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Telefoon (Mobiel)</strong>
                                    <input class="form-control" value="<?php echo $child->telefoonMobiel ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Postcode</strong>
                                    <input class="form-control" value="<?php echo $child->postcode ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Huisnummer</strong>
                                    <input class="form-control" value="<?php echo $child->nummer ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Toevoeging</strong>
                                    <input class="form-control" value="<?php echo $child->toevoeging ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Plaats</strong>
                                    <input class="form-control" value="<?php echo $child->plaats ?>" disabled />
                                </div>
                                <div class="form-group">
                                    <strong class="sb-color-orange">Straat</strong>
                                    <input class="form-control" value="<?php echo $child->straat ?>" disabled />
                                </div>
                                <form method="post" enctype="multipart/form-data" id="">
                                    <input type="text" hidden name="childID" value="<?php echo  $child->persoonID ?>" required>
                                    <button type="submit" class="sb-filter-button btn-orange sb-filter-button-text float-right sb-registerButton">
                                        Gegeven aanpassen
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                ?>
                <div class="container">
                    <b>Wij konden geen kinderen vinden!</b>
                    <p>Wilt u een kind toevoegen <a class="sb-color-orange" href="<?php echo home_url($wp) . "/kind-registreren/" ?>">klik dan hier</a></p>
                </div>
                <?php
            }
        }

        public function account_child_update_short_show()
        {
            global $wp;
            global $auth;

            $_SESSION["form"] = "updateChildAccount";

            if (isset($_SESSION["error"]["code"])) {
                $error = $_SESSION["error"];
                unset($_SESSION["error"]);

                switch ($error["code"]) {
                    case "FORMDATAMISSING":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_BEZET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven e-mailadres bestaat al.</p>';
                        break;
                    case "GEBRUIKERSACCOUNT_INCOMPLEET":
                        echo '<p class="sb-text-center sb-login-warning">De opgegeven data voor het opslaan van het gebruikersaccount is incompleet.</p>';
                        break;
                    case "ADRES_ONBEKEND":
                        echo '<p class="sb-text-center sb-login-warning">Het opgegeven adress voor het opslaan van het gebruikersaccount is incorrect.</p>';
                        break;
                    case "PERSOON_ID_ONGELDIG":
                        echo '<p class="sb-text-center sb-login-warning">De persoon die u probeert bij te werken blijkt niet te bestaan probeer het later nog eens.</p>';
                        break;
                    default:
                        echo '<p class="sb-text-center sb-login-warning">Er ging iets fout met het aanmaken van het gebruikersaccount probeer het later nog eens.</p>';
                        break;
                }
            }

            $childID = $_SESSION["childID"];

            $child = $auth->profielPersoonGegevens($childID);

            ob_start();

            ?>
            <div class="container">
                <h3 class="sb-color-orange">Mijn kind bijwerken</h3>
                <div class="inline-button-container">
                    <a href="<?php echo home_url($wp) . '/kinderen-account/'; ?>" class="btn btn-orange sb-align-bottom" style="margin-right: 10px;">
                        Terug
                    </a>
                </div>
            </div>
            <form method="post" enctype="multipart/form-data" id="registerForm">
                <div class="form-group">
                    <label class="requiredField" for="emailadres"><strong class="sb-color-orange">E-mailadres</strong></label>
                    <input type="email" name="emailadres" class="form-control <?php echo (isset($error["emailadres"][1]) ? "border-danger" : null) ?>" id="emailadres" aria-describedby="emailadres" placeholder="Emailadres" value="<?php echo (isset($error["emailadres"][0]) ? $error["emailadres"][0] : $child->emailadres) ?>" required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="regicare_roepnaam"><strong class="sb-color-orange">Roepnaam</strong></label>
                    <input type="text" name="regicare_roepnaam" id="regicare_roepnaam" class="form-control <?php echo (isset($error["regicare_roepnaam"][1]) ? "border-danger" : null) ?>" aria-describedby="roepnaam" placeholder="Roepnaam" value="<?php echo (isset($error["regicare_roepnaam"][0]) ? $error["regicare_roepnaam"][0] : $child->roepnaam) ?>" required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="voorletters"><strong class="sb-color-orange">Voorletters</strong></label>
                    <input type="text" name="voorletters" class="form-control <?php echo (isset($error["voorletters"][1]) ? "border-danger" : null) ?>" id="voorletters" aria-describedby="voorletters" placeholder="Voorletters" value="<?php echo (isset($error["voorletters"][0]) ? $error["voorletters"][0] : $child->voorletters) ?>" required />
                </div>
                <div class="form-group">
                    <label for="tussenvoegsel"><strong class="sb-color-orange">Tussenvoegsel</strong></label>
                    <input type="text" name="tussenvoegsel" class="form-control <?php echo (isset($error["tussenvoegsel"][1]) ? "border-danger" : null) ?>" id="tussenvoegsel" aria-describedby="tussenvoegsel" placeholder="Tussenvoegsel" value="<?php echo (isset($error["tussenvoegsel"][0]) ? $error["tussenvoegsel"][0] : $child->tussenvoegsel) ?>" />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="achternaam"><strong class="sb-color-orange">Achternaam</strong></label>
                    <input type="text" name="achternaam" class="form-control <?php echo (isset($error["achternaam"][1]) ? "border-danger" : null) ?>" id="achternaam" placeholder="Achternaam" aria-describedby="achternaam" value="<?php echo (isset($error["achternaam"][0]) ? $error["achternaam"][0] : $child->achternaam) ?>" required />
                </div>
                <div class="form-group">
                    <label for="geslacht"><strong class="sb-color-orange">Geslacht</strong></label>
                    <select class='form-control <?php echo (isset($error["geslacht"][1]) ? "border-danger" : null) ?>' id='geslacht' name='geslacht' aria-describedby="geslacht" required>
                        <option <?php echo ((isset($error["geslacht"][0]) ? $error["geslacht"][0] : $child->geslacht) === 1 ? 'selected' : null) ?> value='1'>Man</option>
                        <option <?php echo ((isset($error["geslacht"][0]) ? $error["geslacht"][0] : $child->geslacht) === 2 ? 'selected' : null) ?> value='2'>Vrouw</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredField" for="geboortedatum"><strong class="sb-color-orange">Geboortedatum</strong></label>
                    <input type="date" name="geboortedatum" class="form-control <?php echo (isset($error["geboortedatum"][1]) ? "border-danger" : null) ?>" id="geboortedatum" placeholder="Geboortedatum" aria-describedby="geboortedatum" value="<?php echo (isset($error["geboortedatum"][0]) ? $error["geboortedatum"][0] : $child->geboortedatum) ?>" required />
                </div>
                <div class="form-group">
                    <label for="telefoonVast"><strong class="sb-color-orange">Telefoon (vast)</strong></label>
                    <input type="text" name="telefoonVast" class="form-control <?php echo (isset($error["telefoonVast"][1]) ? "border-danger" : null) ?>" id="telefoonVast" aria-describedby="telefoon vast" placeholder="Telefoonnummer vast" value="<?php echo (isset($error["telefoonVast"][0]) ? $error["telefoonVast"][0] : $child->telefoonVast) ?>" />
                </div>
                <div class="form-group">
                    <label for="telefoonMobiel"><strong class="sb-color-orange">Telefoon (Mobiel)</strong></label>
                    <input type="text" name="telefoonMobiel" class="form-control <?php echo (isset($error["telefoonMobiel"][1]) ? "border-danger" : null) ?>" id="telefoonMobiel" aria-describedby="telefoon mobiel" placeholder="Telefoonnummer mobiel" value="<?php echo (isset($error["telefoonMobiel"][0]) ? $error["telefoonMobiel"][0] : $child->telefoonMobiel) ?>" />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="postcode"><strong class="sb-color-orange">Postcode</strong></label>
                    <input type="text" name="postcode" class="form-control <?php echo (isset($error["postcode"][1]) ? "border-danger" : null) ?>" id="zipcode" aria-describedby="postcode" placeholder="Postcode" value="<?php echo (isset($error["postcode"][0]) ? $error["postcode"][0] : $child->postcode) ?>" required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="nummer"><strong class="sb-color-orange">Huisnummer</strong></label>
                    <input type="text" name="nummer" class="form-control <?php echo (isset($error["nummer"][1]) ? "border-danger" : null) ?>" id="housenumber" aria-describedby="nummer" placeholder="Huisnummer" value="<?php echo (isset($error["nummer"][0]) ? $error["nummer"][0] : $child->nummer) ?>" required />
                </div>
                <div class="form-group">
                    <label for="toevoeging"><strong class="sb-color-orange">Toevoeging</strong></label>
                    <input type="text" name="toevoeging" class="form-control <?php echo (isset($error["toevoeging"][1]) ? "border-danger" : null) ?>" id="toevoeging" aria-describedby="toevoeging" placeholder="Toevoeging" value="<?php echo (isset($error["toevoeging"][0]) ? $error["toevoeging"][0] : $child->toevoeging) ?>" />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="plaats"><strong class="sb-color-orange">Plaats</strong></label>
                    <input type="text" name="plaats" class="form-control <?php echo (isset($error["plaats"][1]) ? "border-danger" : null) ?>" id="place" aria-describedby="plaats" placeholder="Plaats" value="<?php echo (isset($error["plaats"][0]) ? $error["plaats"][0] : $child->plaats) ?>" disabled required />
                </div>
                <div class="form-group">
                    <label class="requiredField" for="straat"><strong class="sb-color-orange">Straat</strong></label>
                    <input type="text" name="straat" class="form-control <?php echo (isset($error["straat"][1]) ? "border-danger" : null) ?>" id="street" aria-describedby="straat" placeholder="Straatnaam" value="<?php echo (isset($error["straat"][0]) ? $error["straat"][0] : $child->straat) ?>" disabled required />
                </div>
                <input type="text" hidden name="form" value="accountChildUpdate" required>
                <input type="text" hidden name="childID" value="<?php echo $childID ?>" required>
                <button type="submit" class="sb-filter-button btn-orange sb-filter-button-text float-right sb-registerButton">
                    Profiel bijwerken
                </button>
            </form>
<?php
            return ob_get_clean();
        }
    }
}
