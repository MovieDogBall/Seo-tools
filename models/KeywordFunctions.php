<?php

namespace app\models;

use yii\base\Model;

/**
 * Class KeywordCleaner
 */
class KeywordFunctions extends Model
{
    /**
     * @param $phrases
     * @return string
     */
    protected function removeElements($phrases)
    {
        $phrases = strtolower($phrases);
        $arraySymb = array("\n", "\r", "<br />", "<br>", "</br>", "&nbsp;", "&#32;");

        $str = str_replace($arraySymb, " ", $phrases);
        $result = preg_replace("/[^a-zA-ZА-Яа-я\s]/", " ", $str);

        $result = rtrim($result);
        $result = ltrim($result);

        return $result;
    }

    /**
     * @param $phrases
     * @param $length
     * @return array
     */
    protected function getErrorMessage($phrases, $length)
    {
        $errors = array();

        if (empty($phrases))
            $errors['phrases'] = 'Empty textarea.';

        if (empty($length))
            $errors['length'] = 'Empty data';

        return $errors;
    }
}