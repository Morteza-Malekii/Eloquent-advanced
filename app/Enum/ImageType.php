<?php

namespace App\Enum;

enum ImageType: string
{
    case Avatar = 'avatar';
    case Cover = 'cover';
    case Regular = 'regular';

    public function baseDir(): string
    {
        return match ($this) {
            self::Avatar  => 'images/avatars',
            self::Cover   => 'images/covers',
            self::Regular => 'images/regular',
        };
    }

    public function extension()
    {
        return 'jpg';
    }
}
