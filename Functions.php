<?php

/**
 * Checks if the given post keys are set and not empty then makes an array out of it
 */
function validateAndSetPost($args)
{
    $form = [];
    foreach ($args as $param => $type) {
        if (isset($_POST[$param])) {
            if (($type[1] === true && $_POST[$param] !== "" && $_POST[$param] !== null) || ($type[1] === false)) {
                switch ($type[0]) {
                    case "string":
                        if (is_string($_POST[$param]) && isAscii($_POST[$param])) {
                            $form[$param] = [$_POST[$param]];
                        } else {
                            $form[$param] = [$_POST[$param], false];
                        }
                        break;
                    case "name":
                        if (is_string($_POST[$param]) && isAscii($_POST[$param])) {
                            if (preg_match('~[0-9]+~', $_POST[$param])) {
                                $form[$param] = [$_POST[$param], false];
                                $form["code"] = "INVALIDNAME";
                            } else {
                                $form[$param] = [$_POST[$param]];
                            }
                        } else {
                            $form[$param] = [$_POST[$param], false];
                            $form["code"] = "INVALIDNAME";
                        }
                        break;
                    case "date":
                        if (is_string($_POST[$param]) && isAscii($_POST[$param])) {
                            $form[$param] = [$_POST[$param]];
                        } else {
                            $form[$param] = [$_POST[$param], false];
                            $form["code"] = "INVALIDDATE";
                        }
                        break;
                    case "post":
                        if (is_string($_POST[$param]) && isAscii($_POST[$param])) {
                            $form[$param] = [$_POST[$param]];
                        } else {
                            $form[$param] = [$_POST[$param], false];
                            $form["code"] = "INVALIDZIPCODE";
                        }
                        break;
                    case "gender":
                        if ($_POST[$param] !== "1" && $_POST[$param] !== "2") {
                            $form[$param] = [$_POST[$param], false];
                            $form["code"] = "INVALIDGENDER";
                        } else {
                            $form[$param] = [$_POST[$param]];
                        }
                        break;
                    case "mail":
                        if (filter_var($_POST[$param], FILTER_VALIDATE_EMAIL) !== false) {
                            $form[$param] = [$_POST[$param]];
                        } else {
                            $form[$param] = [$_POST[$param], false];
                            $form["code"] = "INVALIDMAIL";
                        }
                        break;
                    case "phone":
                        if ($_POST[$param] !== "") {
                            $validPhone = filter_var($_POST[$param], FILTER_SANITIZE_NUMBER_INT);
                            if ($validPhone === false) {
                                $form[$param] = [$_POST[$param], false];
                                $form["code"] = "INVALIDPHONE";
                            } else {
                                $phone = str_replace(array("+", "-", " ", "."), "", $validPhone);
                                if ($param === "telefoonMobiel") {
                                    if ("06" === substr($phone, 0, 2) || "316" === substr($phone, 0, 3)) {
                                        $form[$param] = [$phone];
                                    } else {
                                        echo "test";
                                        $form[$param] = [$_POST[$param], false];
                                        $form["code"] = "INVALIDPHONE";
                                    }
                                } else {
                                    if (strlen($phone) < 10 || strlen($phone) > 14) {
                                        $form[$param] = [$_POST[$param], false];
                                        $form["code"] = "INVALIDPHONE";
                                    } else {
                                        if ("06" !== substr($_POST[$param], 0, 2) && "316" !== substr($_POST[$param], 0, 3)) {
                                            $form[$param] = [$phone];
                                        } else {
                                            $form[$param] = [$_POST[$param], false];
                                            $form["code"] = "INVALIDPHONE";
                                        }
                                    }
                                }
                            }
                        } else {
                            $form[$param] = [""];
                        }
                        break;
                    default:
                        $form[$param] = [$_POST[$param], false];
                        $form["code"] = "FORMDATAMISSING";
                        break;
                }
            } else {
                $form[$param] = [$_POST[$param], false];
                $form["code"] = "FORMDATAMISSING";
            }
        } else {
            $form[$param] = ["", false];
            $form["code"] = "FORMDATAMISSING";
        }
    }
    return !empty($form) ? $form : $form;
}

/**
 * Checks if given string is all ASCII characters
 */
function isAscii($str)
{
    return mb_check_encoding($str, 'ASCII');
}
