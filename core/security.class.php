<?php
namespace Core;

class Security
{

    function escString($value)
    {
        $newValue = htmlspecialchars($value, ENT_QUOTES);
        $newValue = html_entity_decode($newValue);
        return $newValue;
    }

    function getToken()
    {
        if (isset($_SESSION["_ftoken"]) && $_SESSION["_ftoken"] != "")
        {
            $token = $_SESSION["_ftoken"];
        }
        else
        {
            $token = $_SESSION["_ftoken"] = bin2hex(crypt(20));
        }
        return $token;
    }

    function checkToken($token)
    {
        if (!isset($_SESSION["_ftoken"]) or ($token != $_SESSION["_ftoken"]))
        {
            $check = "false";
        }
        else
        {
            $check = "true";
        }
        return $check;
    }

    function filterInput($meth, $name, $type)
    {
        switch ($type)
        {
            case 'int':
                $inputvalue = filter_input($meth == "post" ? INPUT_POST : INPUT_GET, $name, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'float':
                $inputvalue = filter_input($meth == "post" ? INPUT_POST : INPUT_GET, $name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                break;
            case 'url':
                $inputvalue = filter_input($meth == "post" ? INPUT_POST : INPUT_GET, $name, FILTER_SANITIZE_URL);
                break;
            case 'email':
                $inputvalue = filter_input($meth == "post" ? INPUT_POST : INPUT_GET, $name, FILTER_SANITIZE_EMAIL);
                break;
            case 'html':
                $inputvalue = filter_input($meth == "post" ? INPUT_POST : INPUT_GET, $name, FILTER_SANITIZE_SPECIAL_CHARS);
                break;
            case 'string':
                $inputvalue = filter_input($meth == "post" ? INPUT_POST : INPUT_GET, $name, FILTER_SANITIZE_STRING);
                break;
            default:
                $inputvalue = filter_input($meth == "post" ? INPUT_POST : INPUT_GET, $name, FILTER_SANITIZE_STRING);
                break;
        }
        return trim($inputvalue);
    }

    function filterInputArray($meth, $name, $type)
    {
        $arrayType = array(
            "int" => FILTER_SANITIZE_NUMBER_INT,
            "float" => FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION,
            "string" => FILTER_SANITIZE_STRING,
            "url" => FILTER_SANITIZE_URL,
            "email" => FILTER_SANITIZE_EMAIL,
            "html" => FILTER_SANITIZE_SPECIAL_CHARS
        );
        switch ($meth)
        {

            case 'get':
                $inputvalue = filter_var_array($_GET[$name], $arrayType[$type]);
                break;
            case 'post':
                $inputvalue = filter_var_array((array)$_POST[$name], $arrayType[$type]);
                break;
            default:
                break;
        }
        return $inputvalue;
    }

    function recapcha_check($captcha)
    {
        $captcha;
        if (!$captcha)
        {
            return FALSE;
        }

        $secreatKey = "6Le05LsUAAAAALoKp0euPxOtdaP_lXgUuiGDCAJk";
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secreatKey . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  //to suppress the curl output
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result);
        if ($data->success == TRUE &&  $data->score >= 0.5)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}

?>