<?php

namespace app\models;

use app\models\KeywordFunctions;

/**
 * Class KeywordCleaner
 */
class KeywordCleaner extends KeywordFunctions
{
    private $phrases;
    private $length;
    private $count;
    private $negative;


    /**
     * @return string
     */
    public function getPhrases()
    {
        return $this->phrases;
    }

    /**
     * @param $phrases
     */
    public function setPhrases($phrases)
    {
        $this->phrases = $phrases;
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getNegative()
    {
        return $this->negative;
    }

    /**
     * @param $negative
     */
    public function setNegative($negative)
    {
        $this->negative = $negative;
    }


    /**
     * @return json
     */
    public function getData()
    {
        $errors = $this->getErrorMessage($this->phrases, $this->length);

        if (!empty($errors)) {
            $data['success'] = false;
            $data['errors'] = $errors;
        } else {
            $data['success'] = true;

            $filter = $this->filter();
            $negative = $this->negativeWords($this->negative, $filter);

            $data['result'] = $negative;
        }

        return json_encode($data);
    }

    /**
     * @param $negative
     * @param $filter
     * @return array
     *
     * Remove negative words from our array
     *
     */
    protected function negativeWords($negative, $filter)
    {
        $negative = $this->removeElements($negative);

        $arrayNegative = explode(" ", $negative);
        sort($arrayNegative);

        foreach ($arrayNegative as $value) {
            unset($filter[$value]);
        }

        return $filter;
    }


    /**
     * @return array
     */
    protected function filter()
    {
        $data = array();
        $length = intval($this->length);


        $phrases = $this->removeElements($this->phrases);

        $arrayPhrases = explode(" ", $phrases);
        sort($arrayPhrases);

        $result = array_count_values($arrayPhrases);

        foreach ($result as $value => $itemCount) {
            $lengthWord = strlen($value);
            if ($lengthWord >= $length && $itemCount >= $this->count) {
                $data[$value] = $itemCount;
            }
        }

        return $data;
    }
}