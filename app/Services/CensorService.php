<?php
namespace App\Services;

use App\Contracts\CensorContract;

// 敏感词
class CensorService implements CensorContract
{

    /**
     * char for padding value
     */
    const CHAR_PAD = ' ';

    /**
     * stop chars
     */
    const CHAR_STOP = ',.? ';

    /**
     * file handle
     * @var resource
     */
    private $file;

    /**
     * fixed row length
     * @var int
     */
    private $rowLength = 0;

    /**
     * fixed value length
     * @var int
     */
    private $valueLength = 0;

    /**
     * first chars cache
     * @var array
     */
    private $start = array();

    public function __construct()
    {

    }

    public function make($input, $output)
    {

    }
    /**
     * search $str, return words found in dict:
     * array(
     *   'word1' => array('value' => 'value1', 'count' => 'count1'),
     *   ...
     * )
     * @param string $str
     * @return array
     */
    public function search($str)
    {

    }

    /**
     * replace words to $to
     * @param string $str
     * @param mixed $to
     * @return string
     */
    public function replace($str, $to = '*')
    {

        // incluce dict
        $badword = config('badword.BADWORDS');
        $count = count($badword);
        if ($count) {
            $badword1 = array_combine($badword, array_fill(0, $count, $to));
            return strtr($str, $badword1);
        } else {
            return $str;
        }

    }

}
