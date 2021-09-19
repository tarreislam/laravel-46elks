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
        if (!$recipients = $notifiable->routeNotificationFor('46elks', $notification)) {
            return;
        }
        // get notification message.
        $message = $notification->to46Elks($notifiable);

        /*
         * Convert to recipient to array
         */
        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }
        /*
         * Merge SmsMessage "cc" AKA recipients into "$to"
         */
        $recipients = array_merge($recipients, $message->recipients);
        /*
         * Transform if needed
         */
        if (config('laravel-46elks.transform_recipients')) {
            $recipients = $this->transformRecipients($recipients);
        }
        /*
         * Cleanup
         */
        $recipients = array_values(array_unique($recipients));
        /*
         * "set" is mislreading, it should be "add",
         */
        $this->SMSDispatcherService->setRecipients($recipients);
        /*
         * Setup dispatcher
         */
        $dispatcherService = $this
            ->SMSDispatcherService
            ->setLines($message->lines);
        /*
         * Add custom settings
         */
        if ($message->flash) {
            $dispatcherService->flash();
        }

        if ($message->senderId) {
            $dispatcherService->from($message->senderId);
        }

        if ($message->whenDelivered) {
            $dispatcherService->whenDelivered($message->whenDelivered);
        }
        /*
         * Send message
         */
        try {
            $res = $dispatcherService->send();
            event(new MessageSent($message));
            if (config('laravel-46elks.dry_run')) {
                \Log::info('--46ELKS DRY RUN START--');
                \Log::info($res);
                \Log::info('--46ELKS DRY RUN END--');
            }
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
        $recipient = preg_replace('/[^0-9+]+/', '', $recipient);
        /*
         * Check if we need to prepend country code
         */
        if (substr($recipient, 0, 1) == '0') {
            $recipient = preg_replace('/^0(.*)/', $cc . '$1', $recipient);
        }

        return $recipient;
    }

}
