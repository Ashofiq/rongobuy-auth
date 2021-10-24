<?php 
namespace App\Helper;
use Hash;

class Helper{

    const VALIDATOR_FAIL_MESSAGE = 'Validate fails';

    // remove unNessary or danger attact
    public static function removeDanger($str){
        return htmlspecialchars($str);
    }

    public static function removeDangerMultiple($data){
        $dataObj = new \stdClass();
        foreach ((array)$data as $key => $value) {
            $dataObj->$key = self::removeDanger($value);
        }
        return  $dataObj;
    }

    // Make Hash
    public static function makeHash($str){
        return Hash::make($str);
    }

    // get validate error message 
    public static function validateErrorMsg($messages){
        $messages = $messages->all();
        $data = [];
        foreach ($messages as $message) {
            $data[] = $message;
        }
        return $data;
    }

    // Unique Random Numbers Within Range
    public static function randomNumber($length) {
        $result = '';
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }
}