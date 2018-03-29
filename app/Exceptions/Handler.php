<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;
use Mail;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {

        $url = \Request::getRequestUri();
        //线上挂了的话发邮件
        if (!config('app.debug') && strpos($url, '_debugbar') === false){
            if (! $this->isHttpException($e)) {
                $response = (new SymfonyDisplayer(true))->createResponse($e);
                $res = $this->toIlluminateResponse($response, $e);

                Mail::send('email.exception', ['content'=>$res->getContent()."错误发生的URL:  ".$url], function ($message){
                    $message->to('xujin@corp-ci.com');
                    $to = [
                        'xuzhixiang@corp-ci.com',
                        'jinjinyuan@corp-ci.com',
                        'zhouzhenkang@corp-ci.com',
//                        'liyuanhao@corp-ci.com',
//                        'fengjiachen@corp-ci.com'
                    ];
                    foreach ($to as $email){
                        $message->cc($email);
                    }
                    $message->subject('Exception');
                });
            }
        }

        return parent::report($e);
        // $traces = explode("\n", $e->getTraceAsString());
        // $traces = array_filter($traces, function ($message) {
        //     $message = trim($message);
        //     return !Str::contains(substr($message, 0, 60), ['vendor', '{main}', 'Illuminate', 'public/index.php', 'internal function']);
        // });
        // $classBasename = class_basename($e);
        // if ($classBasename == 'NotFoundHttpException') {
        //     return true;
        // }
        // $message = "[{$e->getCode()}] '{$classBasename}' with message '{$e->getMessage()}' in {$e->getFile()}:{$e->getLine()}";
        // if ($traces) {
        //     $message = $message. "\n". implode("\n", $traces);
        // }
        // Log::error($message);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);
    }
}
