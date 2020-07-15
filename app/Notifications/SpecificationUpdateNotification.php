<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackAttachment;
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
     * @param bool $success
     * @param string $message
     * @param array  $args
     */
    public function __construct(bool $success, string $message, ...$args)
    {
        $this->success = $success;
        $this->message = $message;

        if (count($args) > 0) {
            $this->message = vsprintf($this->message, $args);
        }
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

    public static function liveVersionUpdateFailedNotification($treeish, $reason)
    {
        return new self(
            false,
            'Updating the live version to %s failed: %s',
            $treeish,
            $reason
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

    public static function downloadsUpdateFailedNotification($treeish, $reason)
    {
        return new self(
            false,
            'Updating the downloads for %s failed: %s',
            $treeish,
            $reason
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

    public static function schemaUpdateFailedNotification($treeish, $reason)
    {
        return new self(
            false,
            'Updating the schema to %s failed: %s',
            $treeish,
            $reason
        );
    }

    public static function resourcesUpdateSuccesfulNotification($treeish, $currentHead)
    {
        return new self(
            true,
            'Updated resources for %s to <https://github.com/OParl/resources/commit/%s|%s>',
            $treeish,
            $currentHead,
            $currentHead
        );
    }

    public static function resourcesUpdateFailedNotification($treeish, $reason)
    {
        return new self(
            false,
            'Updating the resources to %s failed: %s',
            $treeish,
            $reason
        );
    }

    public static function endpointInfoUpdateFailedNotification($endpointUrl, $reason)
    {
        return new self(
            false,
            'Updating the endpoint info for %s failed: %s',
            $endpointUrl,
            $reason
        );
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
        $message->attachment(function (SlackAttachment $attachment) {
            $attachment->color(($this->success) ? '#5e9d5f' : '#b0403f');
            $attachment->content($this->message);
        });

        return $message;
    }
}
