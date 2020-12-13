<?php


namespace Tarre\Laravel46Elks\Channels;

use Illuminate\Notifications\Notification;
use Tarre\Laravel46Elks\Events\MessageSent;
use Tarre\Php46Elks\Clients\SMS\Services\SMSDispatcherService;

/**
 * @property SMSDispatcherService SMSDispatcherService
 */
class SmsChannel
{
    /**
     * SmsChannel constructor.
     * @param SMSDispatcherService $SMSDispatcherService
     */
    public function __construct(SMSDispatcherService $SMSDispatcherService)
    {
        $this->SMSDispatcherService = $SMSDispatcherService;
    }


    /**
     * @param $notifiable
     * @param Notification $notification
     * @return array|void
     * @throws \Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException
     * @throws \Tarre\Php46Elks\Exceptions\InvalidSenderIdException
     * @throws \Tarre\Php46Elks\Exceptions\NoRecipientsSetException
     */
    public function send($notifiable, Notification $notification)
    {

        if (!$to = $notifiable->routeNotificationFor('46elks', $notification)) {
            return;
        }

        if (is_array($to)) {
            $this->SMSDispatcherService->setRecipients($to);
        } else {
            $this->SMSDispatcherService->recipient($to);
        }

        // get notification message
        $message = $notification->to46Elks($notifiable);

        // initial setup
        $dispatcherService = $this
            ->SMSDispatcherService
            ->setLines($message->lines);

        // no iffs ands or butts -Mike Tyson
        if (count($message->recipients) > 0) {
            $dispatcherService->setRecipients($message->recipients);
        }

        if ($message->flash) {
            $dispatcherService->flash();
        }

        if ($message->senderId) {
            $dispatcherService->from($message->senderId);
        }

        if ($message->whenDelivered) {
            $dispatcherService->whenDelivered($message->whenDelivered);
        }

        // send the poor bastard
        try {
            $res = $dispatcherService->send();
            event(new MessageSent($message));
            return $res;
        } catch (\Exception $exception){
            throw $exception;
        }
    }

}
