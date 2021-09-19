<?php

namespace Tarre\Laravel46Elks\Messages;

use Illuminate\Notifications\Messages\MailMessage;

class SmsMessage
{
    public $lines = [];
    public $recipients = [];
    public $flash;
    public $whenDelivered;
    public $senderId;


    /**
     * SmsMessage constructor.
     * @param array|MailMessage $lines
     */
    public function __construct($lines = null)
    {
        if (is_array($lines)) {
            $this->lines = $lines;
        } elseif ($lines instanceof MailMessage) {
            $mm = $lines;
            /*
             * Add subject (sms will be to crowded)
            if(!empty($mm->subject)){
                $this->line($mm->subject);
                $this->line('---'); // spacer
            }
             */

            /*
             * Add greeting
             */
            if (!empty($mm->greeting)) {
                $this->line($mm->greeting);
                $this->line('-'); // spacer
            }
            /*
             * Add intro-lines aka "Lines"
             */
            foreach ($mm->introLines as $line) {
                $this->line($line);
            }
            /*
             * Add action
             */
            if (!empty($mm->actionUrl)) {
                $this->line('');
                $this->line("{$mm->actionText}: {$mm->actionUrl}");
            }
        }
    }


    /**
     * Line of text message. empty string = new line
     * @param $line
     * @return $this
     */
    public function line($line)
    {
        $this->lines[] = $line;
        return $this;
    }


    /**
     * Flash SMS are shown immediately on the receivers phone and do not disappear until the receiver has confirmed the message. This feature is perfect for messages that really have to be read by the receiver.
     * @param bool $state
     * @return $this
     */
    public function flash($state = true)
    {
        $this->flash = $state;
        return $this;
    }


    /**
     * Add more e164 recipients
     *
     * @param $recipient
     * @return $this
     */
    public function cc($recipient)
    {
        $this->recipients[] = $recipient;
        return $this;
    }


    /**
     * @param $senderId
     * @return $this
     */
    public function from($senderId)
    {
        $this->senderId = $senderId;
        return $this;
    }


    /**
     * You can specify a webhook URL that we'll post to whenever the delivery status of your message changes.
     * @param $url
     * @return $this
     */
    public function whenDelivered($url)
    {
        $this->whenDelivered = $url;
        return $this;
    }

}
