<?php

namespace app\models;

use app\models\KeywordFunctions;

/**
 * Class KeywordCleaner
 */
class KeywordMedians extends KeywordFunctions
{
    private $ourArticle;
    private $keywordMedian;
    private $articles;


    /**
     * @return string
     */
    public function getKeywordMedian()
    {
        return $this->keywordMedian;
    }

    /**
     * @param $keywordMedian
     */
    public function setKeywordMedian($keywordMedian)
    {
        $this->keywordMedian = $keywordMedian;
    }

    /**
     * @return string
     */
    public function getOurArticle()
    {
        return $this->ourArticle;
    }

    /**
     * @param $ourArticle
     *
     */
    public function setOurArticle($ourArticle)
    {
        $this->ourArticle = $ourArticle;
    }

    /**
     * @return array
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }

    /**
     * @return json
     */
    public function getData()
    {
        $errors = $this->getErrorMessage($this->ourArticle, $this->keywordMedian);

        if (!empty($errors)) {
            $data = [
                'success' => false,
                'errors' => $errors,
            ];
        } else {
            $ourArticle = $this->getFoundedOurArticles();
            $thirdArticles = $this->getFoundedArticles();

            $percents_our_articles = array($this->countPercent($ourArticle, $this->ourArticle));

            $thirds_percents = [];

            for ($i = 0, $n = count($thirdArticles); $i < $n; $i++) {
                $thirds_percents[$i] = array($this->countPercent($thirdArticles[$i], $this->articles[$i]));
            }

            $result = [];

            for ($i = 0, $n = count($thirds_percents); $i < $n; $i++) {
                for ($j = 0, $z = count($thirds_percents[$i]); $j < $z; $j++) {
                    foreach ($thirds_percents[$i][$j] as $key => $value) {
                        $result[$key][$i] = $value;
                    }
                }
            }

            $rows = [];
            foreach ($result as $key => $values) {
                $rows_in = [$key];

                foreach ($values as $value) {
                    array_push($rows_in, $value);
                }
                $data = $this->averageAndMediana($values);
                array_push($data, $percents_our_articles[0][$key]);
                if (empty($data[0])) {

                    $rows_in[11] = '0';
                    $rows_in[12] = '0';
                    $rows_in[13] = $percents_our_articles[0][$key];
                } else {
                    $rows_in = array_merge($rows_in, $data);
                }
                $rows[] = $rows_in;

            }

            $data = [
                'success' => true,
                'data' => $rows,
            ];
        }

        return json_encode($data);
    }

    /**
     * @return array|bool
     */
    protected function getFoundedOurArticles()
    {
        $ourArticle = $this->removeElements($this->ourArticle);
        $keywordMedian = $this->removeElements($this->keywordMedian);

        $arraySentences = explode(" ", $ourArticle);
        $arrayWords = explode(" ", $keywordMedian);

        if (is_array($arraySentences) && is_array($arrayWords)) {
            sort($arraySentences);
            sort($arrayWords);

            $positiveWords = array_count_values(array_intersect($arraySentences, $arrayWords));
            $result = [];
            foreach ($arrayWords as $word) {
                if (count($positiveWords) > 0) {
                    foreach ($positiveWords as $k => $v) {
                        if (isset($v[$word])) {
                            $result[$word] = $v[$word];
                        } else {
                            $result[$word] = 0;
                        }
                    }
                } else {
                    $result[$word] = 0;
                }

            }
            return $result;
        }

        return [];
    }

    /**
     * @return array|bool
     */
    protected function getFoundedArticles()
    {
        $article = [];

        for ($i = 0, $n = count($this->articles); $i < $n; $i++) {
            $article[$i] = $this->removeElements($this->articles[$i]);
        }

        $keywordMedian = $this->removeElements($this->keywordMedian);
        $arrayWords = explode(" ", $keywordMedian);

        $result = [];

        for ($i = 0, $n = count($article); $i < $n; $i++) {

            $arraySentences = explode(" ", $article[$i]);
            if (is_array($arraySentences) && is_array($arrayWords)) {
                sort($arraySentences);
                sort($arrayWords);
                $positiveWords = array_count_values(array_intersect($arraySentences, $arrayWords));

                foreach ($arrayWords as $word) {
                    $result[$i][$word] = 0;
                    if (isset($positiveWords[$word])) {
                        $result[$i][$word] = $positiveWords[$word];
                    }

                }
            }
        }
        return $result;
    }

    /**
     * @param $data
     * @param $text
     * @return array|bool
     */
    protected function countPercent($data, $text)
    {
        $result = [];
        $wordsCount = null;
        if (!empty($text)) {
            $wordsCount = str_word_count($text);
        }

        foreach ($data as $item => $value) {
            $result[$item] = 0;

            if (intval($value) > 0) {
                if (!empty($wordsCount) && !empty($value)) {
                    $result[$item] = number_format((($value / $wordsCount) * 100), 2) . "%";
                }
            }
        }

        return $result;
    }

    /**
     * @param $articles
     * @return bool
     */
    protected function averageAndMediana($articles)
    {
        $avg = [];
        if (is_array($articles)) {
            foreach ($articles as $k => $value) {
                if ($value > 0) {
                    $avg[] = floatval($value);
                } else {
                    unset($articles[$k]);
                }
            }
            if (!empty($articles)) {
                sort($avg);
                $average = array_sum($avg) / count($avg);
                $middle = round(count($articles) / 2);

                if (count($avg) % 2 == 0) {
                    $total = ($avg[$middle] + $avg[$middle - 1]) / 2;
                } else {
                    if (count($avg) > 1) {
                        $total = $avg[$middle-1];
                    } else {
                        $total = $avg[0];
                    }
                }

                $avg = [$average . "%", $total . "%"];
            }
        }

        return $avg;
    }
}
