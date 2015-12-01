<?php namespace EFrane\Newsletter;

use EFrane\Newsletter\Model\Message;
use EFrane\Newsletter\Model\Subscription;

class Newsletter
{
    public static function createMessage($message = '')
    {
        return new Message(['message' => $message]);
    }

    public static function send(Message $message, Subscription $list)
    {
        // TODO: implement sending of a message to all recipients
    }
}