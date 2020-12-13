<?php


namespace Tarre\Laravel46Elks\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Tarre\Laravel46Elks\Messages\SmsMessage;

/**
 * @property SmsMessage smsMessage
 */
class MessageSent
{
    use Dispatchable;

    public $smsMessage;

    public function __construct(SmsMessage $smsMessage)
    {
        $this->smsMessage = $smsMessage;
    }

}