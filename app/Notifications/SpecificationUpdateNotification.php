<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SpecificationUpdateNotification extends Notification
{
    use Queueable;

    protected $success = true;
    protected $message = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($success, $message, ...$args)
    {
        $this->success = $success;
        $this->message = $message;

        if (count($args) > 0) {
            $this->message = vsprintf($this->message, $args);
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        if (!config('services.slack.ci.enabled')) {
            return [];
        }

        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $message = new SlackMessage();
        $message->success();

        if (!$this->success) {
            $message->warning();
        }

        $message->to(config('services.slack.ci.channel'));
        $message->content($this->message);

        return $message;
    }

    public static function liveVersionUpdateSuccessfulNotification($treeish, $currentHead)
    {
        return new self(
            true,
            'Updated live version for %s to <https://github.com/OParl/spec/commit/%s|%s>',
            $treeish,
            $currentHead,
            $currentHead
        );
    }

    public static function liveVersionUpdateFailedNotification($treeish)
    {
        return new self(
            false,
            'Updating the live version to %s failed',
            $treeish
        );
    }

    public static function downloadsUpdateSuccesfulNotification($treeish, $currentHead)
    {
        return new self(
            true,
            'Updated specification downloads for %s to <https://github.com/OParl/spec/commit/%s|%s>',
            $treeish,
            $currentHead,
            $currentHead
        );
    }

    public static function downloadsUpdateFailedNotification($treeish)
    {
        return new self(
            false,
            'Updating the downloads for %s failed',
            $treeish
        );
    }

    public static function schemaUpdateSuccesfulNotification($treeish, $currentHead)
    {
        return new self(
            true,
            'Updated schema assets for %s to <https://github.com/OParl/spec/commit/%s|%s>',
            $treeish,
            $currentHead,
            $currentHead
        );
    }

    public static function schemaUpdateFailedNotification($treeish)
    {
        return new self(
            false,
            'Updating the schema to %s failed',
            $treeish
        );
    }
}
