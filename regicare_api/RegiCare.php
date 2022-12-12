<?php

namespace Southaxis\RegiCare;

require 'Vacatures.php';
require 'Auth.php';
require 'Activiteiten.php';

use Southaxis\RegiCare\Vacatures;
use Southaxis\RegiCare\Auth;
use Southaxis\RegiCare\Activiteiten;

abstract class RegiCare
{
    /**
     * Creates instance of Vacatures class and returns it.
     *
     * @param string $token
     * @param string $domain
     * @return \Southaxis\RegiCare\Vacatures
     */
    public static function vacatures($token, $domain)
    {
        return new Vacatures($token, $domain);
    }

    /**
     * Creates instance of Activities class and returns it.
     * @param string $token
     * @param string $domain
     */
    public static function activities($token, $domain)
    {
        return new Activiteiten($token, $domain);
    }

    /**
     * Creates instance of Auth class and returns it.
     *
     * @param string $token
     * @param string $domain
     * @return \Southaxis\RegiCare\Auth
     */
    public static function auth($token, $domain)
    {
        return new Auth($token, $domain);
    }
}
