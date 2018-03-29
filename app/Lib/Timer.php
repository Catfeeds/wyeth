<?php

namespace App\Lib;

class Timer
{
    /**
     * start
     * 开始时间
     *
     * @var double
     */
    protected $start;

    /**
     * last
     * 上次记录点
     *
     * @var double
     */
    protected $last;

    /**
     * unit
     * 时间单位
     *
     * @var string
     */
    protected $unit;

    protected function __construct($unit)
    {
        $this->start = microtime(true);
        $this->last = $this->start;
        $this->unit = $unit;
    }

    /**
     * start
     * 开始一个新的计时器
     *
     * @param string $unit
     * @return Timer
     */
    public static function start($unit = 'ms')
    {
        return new self($unit);
    }

    /**
     * mark
     * 计时器打点
     *
     * 返回值结果如下
     *   - now      当前UNIX时间戳 含毫秒部分
     *   - duration 上次记录点到本次记录点的时间
     *   - total    计时器开始到本次记录点的时间
     *   - unit     时间单位
     *
     * @return array
     */
    public function mark()
    {
        $now = microtime(true);
        $duration = $now - $this->last;
        $total = $now - $this->start;
        $this->last = $now;

        return [
            'd' => $this->convert($duration),
            't' => $this->convert($total)
        ];
    }

    /**
     * convert
     * 时间单位转换
     *
     * @param double $millisecond
     * @return double
     */
    protected function convert($millisecond)
    {
        if ($this->unit === 'ms') {
            return intval(round($millisecond * 1000));
        } else {
            return $millisecond;
        }
    }
}
