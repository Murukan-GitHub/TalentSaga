<?php

namespace Suitcore\Emailer;

use Illuminate\Mail\Mailer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
*
*/
class Emailer
{
    protected $mailer;

    protected static $composeData = [];

    protected static $views = 'emails';

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function setViews($views)
    {
        static::$views = $views;
    }

    protected function getView($name)
    {
        $view = static::$views.'.'.$name;
        
        if (view()->exists($view)) {
            return $view;
        }

        return false;
    }

    public function compose(array $data = [])
    {
        static::$composeData = $data;
    }

    public function __call($func, $args)
    {
        $this->processCall($func, $args);
    }

    protected function processCall($func, $args)
    {
        $type = $func;
        $datas = isset($args[0]) ? (array) $args[0] : [];

        if (isset($datas['layout'])) {
            $func = $datas['layout'];
            unset($datas['layout']);
            $args[0] = $datas;
            return $this->processCall($func, $args);
        }

        $view = $this->getView($func);

        if ($view == false) {
            if (method_exists($this->mailer, $func)) {
                return call_user_func_array([$this->mailer, $func], $args);
            }
            
            throw new EmailerException("Method Not Found");
        }

        $viewDatas = $this->getViewDatas($type, $datas);
        $sendingCommand = (isset($viewDatas['noQueue']) && $viewDatas['noQueue'] == true) ? 'send' : 'queue';
        $recipients = $this->getRecipients($viewDatas);
        $callback = isset($args[1]) ? $args[1] : null;

        $this->send($sendingCommand, $view, $recipients, $viewDatas, $callback);
    }

    protected function getViewDatas($type, array $datas = [])
    {
        $composeData = static::$composeData;
        $base = (isset($composeData[$type]) ? (array) $composeData[$type] : []);
        
        if (isset($base['parent'])) {
            $parent = $composeData[$base['parent']];
            $base = array_replace_recursive($parent, $base);
        }

        return array_replace_recursive($composeData, $base, $datas);
    }

    protected function getRecipients($viewDatas)
    {
        $recipients = isset($viewDatas['to']) ? $viewDatas['to'] : collect();
     
        if (! ($recipients instanceof Collection)) {
            $recipients = collect([$recipients]);
        }

        if (isset($viewDatas['once']) && $viewDatas['once'] == true) {
            $recipients = [$recipients];
        }

     
        return $recipients;
    }

    protected function send($sendingCommand, $view, $recipients, $viewDatas, $callback)
    {
        foreach ($recipients as $recipient) {
            
            $viewDatas['to'] = $recipient;
            $func = function ($email) use ($viewDatas, $callback) {
                
                foreach ($viewDatas as $key => $value) {

                    if (in_array($key, ['from', 'sender', 'to', 'cc', 'bcc', 'replyTo'])) {
                        if ($value instanceof Collection) {
                            $value = $value->pluck('name', 'email');
                        } elseif (is_object($value)) {
                            $value = ['address' => $value->email, 'name' => $value->name];
                        }
                    }

                    if (method_exists($email, $key)) {
                        call_user_func_array([$email, $key], (array) $value);
                    }
                }

                if (is_callable($callback)) {
                    $callback($email);
                }
            };

            $args = [$view, $viewDatas, $func];
            call_user_func_array([$this->mailer, $sendingCommand], $args);
        }
    }
}
