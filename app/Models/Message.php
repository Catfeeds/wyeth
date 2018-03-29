<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    const NORMAL = 0;
    const HAS_SEND = 1;
    const ANSWERED = 2;
    const HAS_SEND_XJT = 3;
    const ANSWERED_BY_XJT = 4;
    const CLOSED_BY_XJT = 5;

    /**
     * 类型 聊天
     */
    const STATE_CHAT = 6;

    /**
     * 消息类型 文本
     */
    const TYPE_TEXT = 1;

    /**
     * 消息类型 语音
     */
    const TYPE_VOICE = 2;

    /**
     * 消息类型 送花
     */
    const TYPE_PRESENT_FLOWER = 3;

    /**
     * 显示 是
     */
    const DISPLAY_YES = 1;

    /**
     * 显示 否
     */
    const DISPLAY_NO = 0;

    /**
     * 提问区类型
     * @var array
     */
    public static $STATE_QUESTIONS = [0, 1, 2, 3, 4, 5];


    public function setContentAttribute($value)
    {
        $censor = \App::make('censor');
        $content = $censor->replace($value);
        $this->attributes['content'] = $content;
    }
}
