<?php

namespace ITRLibraryBundle\Service;

class CommandResponse
{
    const SLACK_RESPONSE_PRIVATE = 'ephemeral';
    const SLACK_RESPONSE_BROADCAST = 'in_channel';

    public $text;
    public $broadcast = false;

    public function __construct($text, $broadcast = false)
    {
        $this->text = $text;
        $this->broadcast = $broadcast;

    }

    public function getSlackResponse()
    {
        return [
            'text' => $this->text,
            'response_type' => ($this->broadcast ? self::SLACK_RESPONSE_BROADCAST : self::SLACK_RESPONSE_PRIVATE)
        ];
    }

}