<?php
/**
 * Plugin Name: RegiCare
 * Description: Adsysco RegiCare API plugin
 * Version: 3.1.1
 * Author: SouthAxis
 * Author URI: https://www.southaxis.com/
 * Text Domain: sb
 */

require 'vendor/autoload.php';
require 'ActivityShow.php';
require 'Authentication.php';
require 'regicare_api/RegiCare.php';

use Southaxis\RegiCare\RegiCare;

class RegiCarePlugin
{
    function __construct()
    {
        require_once("settings.php");
        $this->init();
    }

    function init()
    {
        global $directory;
        global $vacature;
        global $activity;
        global $auth;

        $directory = plugin_dir_url(__FILE__);
        $token = get_option("regicare_key", "");
        $domain = get_option("regicare_domain", "");
        $vacature = RegiCare::vacatures($token, $domain);
        $auth = RegiCare::auth($token, $domain);
        $activity = RegiCare::activities($token, $domain);

        add_shortcode('regicareActivitiesClusterLosseActiviteiten', 'regicare_activities_cluster_losse_activiteiten');
        add_shortcode('regicareActiviteiten', 'regicare_actvities_short');
        add_shortcode('login', 'login_short');
        add_shortcode('register', 'register_short');
        add_shortcode('registerChild', 'register_child_short');
        add_shortcode('otherChild', 'other_child_short');
        add_shortcode('registerActivity', 'register_activities_short');
        add_shortcode('forgot-password', 'forgot_password_short');
        add_shortcode('account', 'account_short');
        add_shortcode('account_update', 'account_update_short');
        add_shortcode('account_child', 'account_child_short');
        add_shortcode('account_child_update', 'account_child_update_short');
        add_shortcode('regicareActiviteitClusterPiano', 'regicare_activities_cluster_piano');
        add_shortcode('regicareActiviteitClusterPiano', 'regicare_activities_cluster_piano');
        add_shortcode('regicareActiviteitClusterBreakdance', 'regicare_activities_cluster_breakdance');
        add_shortcode('regicareActiviteitClusterLuchtacrobatiek', 'regicare_activities_cluster_luchtacrobatiek');
        add_shortcode('regicareActiviteitClusterPowerhour', 'regicare_activities_cluster_powerhour');
        add_shortcode('regicareActiviteitClusterDrumles', 'regicare_activities_cluster_drumles');
        add_shortcode('regicareActiviteitClusterGitaarles', 'regicare_activities_cluster_gitaarles');
        add_shortcode('regicareActiviteitClusterZangcoaching', 'regicare_activities_cluster_zangcoaching');
        add_shortcode('regicareActiviteitClusterKidsclub', 'regicare_activities_cluster_kidsclub');
        add_shortcode('regicareActiviteitClusterCreaclub', 'regicare_activities_cluster_creaclub');
        add_shortcode('regicareActiviteitClusterTimmerclub', 'regicare_activities_cluster_timmerclub');
        add_shortcode('regicareActiviteitClusterStreetdance', 'regicare_activities_cluster_streetdance');
        add_shortcode('regicareActiviteitClusterDanslesKinderen', 'regicare_activities_cluster_dansleskinderen');
        add_shortcode('regicareActiviteitClusterDanslesTraining', 'regicare_activities_cluster_danslestraining');
        add_shortcode('regicareActiviteitClusterPilates', 'regicare_activities_cluster_pilates');
        add_shortcode('regicareActiviteitClusterAvonturenMiddag', 'regicare_activities_cluster_avonturenmiddag');
        add_shortcode('regicareActiviteitClusterBuitenSpelen', 'regicare_activities_cluster_buitenspelen');
        add_shortcode('regicareActiviteitClusterMasterclassMarjan', 'regicare_activities_cluster_masterclassmarjan');
        add_shortcode('regicareActiviteitClusterPeuterplezier', 'regicare_activities_cluster_peuterplezier');
        add_shortcode('regicareActiviteitClusterYoga', 'regicare_activities_cluster_yoga');
        add_shortcode('regicareActiviteitClusterFilmhuis', 'regicare_activities_cluster_filmhuis');
        add_shortcode('regicareActiviteitClusterZumba', 'regicare_activities_cluster_zumba');
        add_shortcode('regicareActiviteitClusterSportlessen', 'regicare_activities_cluster_sportlessen');
        add_shortcode('regicareActiviteitClusterBewegen', 'regicare_activities_cluster_bewegen');
        add_shortcode('regicareActiviteitClusterKunstCultuur', 'regicare_activities_cluster_kunstcultuur');
        add_shortcode('regicareActiviteitClusterOntmoeten', 'regicare_activities_cluster_ontmoeten');
        add_shortcode('regicareActiviteitClusterSenioren', 'regicare_activities_cluster_senioren');
        add_shortcode('regicareActiviteitClusterSamenEten', 'regicare_activities_cluster_sameneten');

    }
}

function forgot_password_short()
{
    $authentication = new Authentication();
    return $authentication->forgot_password_show();
}

function regicare_actvities_short()
{
    $activityShow = new ActivityShow();
    return $activityShow->showAllActivitiesEmpty();
}

function login_short()
{
    $authentication = new Authentication();
    return $authentication->login_regicare_show();
}

function register_short()
{
    $authentication = new Authentication();
    return $authentication->register_regicare_show();
}

function register_child_short()
{
    $authentication = new Authentication();
    return $authentication->register_child_regicare_show();
}

function other_child_short()
{
    $authentication = new Authentication();
    return $authentication->other_child_short_show();
}

function account_short()
{
    $authentication = new Authentication();
    return $authentication->account_short_show();
}

function account_update_short()
{
    $authentication = new Authentication();
    return $authentication->account_update_short_show();
}

function account_child_short()
{
    $authentication = new Authentication();
    return $authentication->account_child_short_show();
}

function account_child_update_short()
{
    $authentication = new Authentication();
    return $authentication->account_child_update_short_show();
}

function register_activities_short()
{
    $activityShow = new ActivityShow();
    return $activityShow->registerActivity();
}

function regicare_activities_cluster_piano($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterPiano($attributes['activiteitID']);
}

function regicare_activities_cluster_losse_activiteiten($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterLosseActiviteiten($attributes['activiteitID']);
}

function regicare_activities_cluster_breakdance($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterBreakdance($attributes['activiteitID']);
}

function regicare_activities_cluster_luchtacrobatiek($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterLuchtacrobatiek($attributes['activiteitID']);
}

function regicare_activities_cluster_powerhour($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterPowerhour($attributes['activiteitID']);
}

function regicare_activities_cluster_drumles($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterDrumles($attributes['activiteitID']);
}

function regicare_activities_cluster_gitaarles($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterGitaarles($attributes['activiteitID']);
}

function regicare_activities_cluster_zangcoaching($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterZangcoaching($attributes['activiteitID']);
}

function regicare_activities_cluster_kidsclub($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterKidsclub($attributes['activiteitID']);
}

function regicare_activities_cluster_creaclub($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterCreaclub($attributes['activiteitID']);
}

function regicare_activities_cluster_timmerclub($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterTimmerclub($attributes['activiteitID']);
}

function regicare_activities_cluster_streetdance($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterStreetdance($attributes['activiteitID']);
}

function regicare_activities_cluster_dansleskinderen($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterDanslesKinderen($attributes['activiteitID']);
}

function regicare_activities_cluster_danslestraining($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterDanslesTraining($attributes['activiteitID']);
}

function regicare_activities_cluster_pilates($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterPilates($attributes['activiteitID']);
}

function regicare_activities_cluster_avonturenmiddag($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterAvonturenMiddag($attributes['activiteitID']);
}

function regicare_activities_cluster_buitenspelen($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterBuitenSpelen($attributes['activiteitID']);
}

function regicare_activities_cluster_masterclassmarjan($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterMasterclassMarjan($attributes['activiteitID']);
}

function regicare_activities_cluster_peuterplezier($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterPeuterPlezier($attributes['activiteitID']);
}


function regicare_activities_cluster_yoga($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterYoga($attributes['activiteitID']);
}

function regicare_activities_cluster_filmhuis($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterFilmhuis($attributes['activiteitID']);
}

function regicare_activities_cluster_zumba($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterZumba($attributes['activiteitID']);
}

function regicare_activities_cluster_sportlessen($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterSportlessen($attributes['activiteitID']);
}

function regicare_activities_cluster_bewegen($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterBewegen($attributes['activiteitID']);
}

function regicare_activities_cluster_kunstcultuur($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterKunstCultuur($attributes['activiteitID']);
}

function regicare_activities_cluster_ontmoeten($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterOntmoeten($attributes['activiteitID']);
}

function regicare_activities_cluster_senioren($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterSenioren($attributes['activiteitID']);
}

function regicare_activities_cluster_sameneten($atts) {
    // Parameter values send along with shortcode
    $attributes = shortcode_atts(['activiteitID' => -1], $atts);

    $activityShow = new ActivityShow();
    return $activityShow->regicareActivitiesClusterSamenEten($attributes['activiteitID']);
}

$regiPlugin = new RegiCarePlugin();

