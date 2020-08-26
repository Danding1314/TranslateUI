<?php

namespace phuongaz\Translate;

use DetectLanguage\DetectLanguage;

Class Translate{

    private static $key = '3c12f9b3628bf3048f46483ee1227e7e'; //http://detectlanguage.com

    private static function setDetectKey(string $key){
        self::$key = $key;
    }

    public static function detect(string $text)
    {
        if(self::$key !== null)
        {
            DetectLanguage::setApiKey(self::$key);
            $source = DetectLanguage::detect($text);
            $k = json_decode(json_encode($source), true);
            return $k[0]["language"];
        }
        return null;
    }

    public static function translate($source, $target, $text)
    {
        $response = self::requestTranslation($source, $target, $text);
        $translation = self::getSentencesFromJSON($response);

        return $translation;
    }

    public static function requestTranslation($source, $target, $text)
    {
        $url = "https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e";

        $fields = array(
            'sl' => urlencode($source),
            'tl' => urlencode($target),
            'q' => urlencode($text)
        );

        if(strlen($fields['q'])>=5000)
            throw new \Exception("Maximum number of characters exceeded: 5000");
        
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        rtrim($fields_string, '&');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    protected static function getSentencesFromJSON($json)
    {
        $sentencesArray = json_decode($json, true);
        $sentences = $sentencesArray["sentences"][0]["trans"];

        return $sentences;
    }
}