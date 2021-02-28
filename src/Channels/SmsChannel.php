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

        // Add recpiients
        if (count($recipients = $message->recipients) > 0) {
            // Transform recipients if needed
            if (config('laravel-46elks.transform_recipients')) {
                $recipients = $this->transformRecipients($recipients);
            }
            $dispatcherService->setRecipients($recipients);
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

        try {
            $res = $dispatcherService->send();
            event(new MessageSent($message));
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    protected function transformRecipients(array $recipients)
    {
        $cc = config('laravel-46elks.transform_cc');

        $newRecipients = [];

        foreach ($recipients as $recipient) {
            $newRecipients[] = $this->transformRecipient($recipient, $cc);
        }

        return $newRecipients;
    }

    protected function transformRecipient($recipient, $cc)
    {
        /*
         * remove everything besides numbers and +
         */
        $recipient = preg_replace('/[^0-9+]+/g', '', $recipient);
        /*
         * Check if we need to prepend country code
         */
        if (substr($recipient, 0, 1) == '0') {
            $recipient = preg_replace('/^0(.*)/', $cc . '$1', $recipient);
        }

        return $recipient;
    }

}
