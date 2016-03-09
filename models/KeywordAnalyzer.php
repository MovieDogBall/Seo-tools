<?php

namespace app\models;

use app\models\KeywordFunctions;

/**
 * Class KeywordCleaner
 */
class KeywordAnalyzer extends KeywordFunctions
{
    private $sentences;
    private $keywordWords;


    /**
     * @return string
     */
    public function getKeywordWords()
    {
        return $this->keywordWords;
    }

    /**
     * @param $keywordWords
     */
    public function setKeywordWords($keywordWords)
    {
        $this->keywordWords = $keywordWords;
    }

    /**
     * @return string
     */
    public function getSentences()
    {
        return $this->sentences;
    }

    /**
     * @param $sentences
     */
    public function setSentences($sentences)
    {
        $this->sentences = $sentences;
    }


    /**
     * @return string
     *
     */
    public function getData()
    {
        $errors = $this->getErrorMessage($this->sentences, $this->keywordWords);

        if (!empty($errors)) {
            $data = [
                'success'   => false,
                'errors'    => $errors,
            ];
        } else {
            $splitText = $this->splitSentences($this->sentences);

            $positive = $this->positiveWords();
            $negative = $this->negativeWords();
            $analyze = $this->analyzeSentences($splitText);
            $aveMed = $this->averageAndMediana($analyze);
            $text = $this->fullText($splitText);

            $data = [
                'success'           => true,
                'resultPositive'    => $positive,
                'resultNegative'    => $negative,
                'analyzeSentences'  => $analyze,
                'averageAndMediana' => $aveMed,
                'fullText'          => $text,
            ];
        }

        return json_encode($data);
    }

    /**
     * @return array|bool
     */
    protected function positiveWords()
    {
        $sentences = $this->removeElements($this->sentences);
        $keywordWords = $this->removeElements($this->keywordWords);

        $arraySentences = explode(" ", $sentences);
        $arrayWords = explode(" ", $keywordWords);

        if(is_array($arraySentences) && is_array($arrayWords)){
            sort($arraySentences);
            sort($arrayWords);

            $positiveWords = array_count_values(array_intersect($arraySentences, $arrayWords));

            return $positiveWords;
        }

        return false;
    }

    /**
     * @return array|bool
     */
    protected function negativeWords()
    {
        $sentences = $this->removeElements($this->sentences);
        $keywordWords = $this->removeElements($this->keywordWords);

        $arraySentences = explode(" ", $sentences);
        $arrayWords = explode(" ", $keywordWords);
        if(is_array($arraySentences) && is_array($arrayWords)) {
            sort($arraySentences);
            sort($arrayWords);

            $negativeWords = array_diff($arrayWords, $arraySentences);

            $negativeWords = array_count_values($negativeWords);

            return $negativeWords;
        }

        return false;
    }

    /**
     * @param $sentences
     * @return array|bool
     *
     * Split text and count word in sentences
     *
     */
    protected function analyzeSentences($sentences)
    {
        $result = array();

        if(is_array($sentences)){
            for($i=0, $n = count($sentences); $i<$n; $i++){
                $result[$i+1] = str_word_count($sentences[$i]);
            }

            return $result;
        }

        return false;
    }

    /**
     * @param $analyze
     * @return array|bool
     *
     * Count word's average value and count median
     */
    protected function averageAndMediana($analyze)
    {
        if(is_array($analyze)){
            $average = array_sum($analyze) / count($analyze);

            rsort($analyze);

            $middle = round(count($analyze) / 2);
            if (count($analyze) % 2 == 0) {
                $total = ($analyze[$middle] + $analyze[$middle - 1]) / 2;
            } else {
                $total = $analyze[$middle];
            }

            $result[$average] = $total;

            return $result;
        }

        return false;
    }

    /**
     * @param $sentences
     * @return array
     *
     * Return sentences in span tag for highlight
     *
     */
    protected function fullText($sentences)
    {
        $result = array();
        $i = 1;

        foreach ($sentences as $item => $value) {
            $result[$i] = "<span id='sentences-" . $i . "'>" . $value . "</span>";
            $i++;
        }

        return $result;
    }

    /**
     * @param $sentences
     * @return array
     */
    protected function splitSentences($sentences)
    {
        return preg_split("/(?<=[.?!\n])\s+(?=[a-z, а-я])/i", $sentences);
    }
}