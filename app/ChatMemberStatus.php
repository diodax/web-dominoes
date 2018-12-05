<?php

namespace App;

class ChatMemberStatus
{
    const ONLINE = 1;
    const PLAYING = 2;
    const AWAY = 3;
    const OFFLINE = 4;

    /**
     * Return list of status codes and labels

     * @return array
     */
    public static function listStatus()
    {
        return [
            self::ONLINE    => 'Online',
            self::PLAYING => 'Suspended',
            self::AWAY  => 'Inactive',
            self::OFFLINE => 'Offline'
        ];
    }
}
