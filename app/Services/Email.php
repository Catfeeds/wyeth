<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/23
 * Time: 上午10:34
 */

namespace App\Services;


use Illuminate\Support\Facades\Mail;

//邮件轰炸
class Email
{
    const EMAIL_BOX_ALL = 'email_box_all';

    const EMAIL_BOX_PHP = 'email_box_php';

    const EMAIL_BOX_WEEX = 'email_box_weex';

    const EMAIL_BOX_COURSE_PUSH = 'email_box_course_push';

    const EMAIL_BOX_CIDATA = 'email_box_cidata';

    public static function SendEmail($subject, $content, $to = []){
        $to = self::getEmailBox($to);
        try{
            Mail::send('email.text', ['content'=>$content], function ($message) use ($subject, $to){
                if (is_array($to)){
                    if ($to){
                        foreach ($to as $email){
                            $message->to($email);
                        }
                    }else{
                        $message->to('xujin@corp-ci.com');
                    }
                }else{
                    $message->to($to);
                }

                $message->subject($subject);
            });
        }catch (\Exception $e){

            \Log::error('发送邮件失败', [
                'subject' => $subject,
                'content' => $content,
                'to' => $to,
                'error' => $e
            ]);
            return false;
        }

        return true;
    }

    public static function getEmailBox($key){
        if (!is_string($key)){
            return $key;
        }
        $array = [
            self::EMAIL_BOX_ALL => [
                'xujin@corp-ci.com',
                'xuzhixiang@corp-ci.com',
                'jinjinyuan@corp-ci.com',
                'zhouzhenkang@corp-ci.com',
                'liyuanhao@corp-ci.com',
                'fengjiacheng@corp-ci.com',
            ],
            self::EMAIL_BOX_PHP => [
                'xujin@corp-ci.com',
                'xuzhixiang@corp-ci.com',
                'jinjinyuan@corp-ci.com',
                'zhouzhenkang@corp-ci.com',
            ],
            self::EMAIL_BOX_WEEX => [
                'xujin@corp-ci.com',
                'zhouzhenkang@corp-ci.com',
                'liyuanhao@corp-ci.com',
                'fengjiacheng@corp-ci.com',
            ],
            self::EMAIL_BOX_COURSE_PUSH => [
                'xujin@corp-ci.com',
                'jinjinyuan@corp-ci.com',
                'wangjingjing@corp-ci.com',
            ],
            self::EMAIL_BOX_CIDATA => [
                'jinjinyuan@corp-ci.com'
            ]
        ];
        if (isset($array[$key])){
            return $array[$key];
        }else{
            return $key;
        }
    }
}