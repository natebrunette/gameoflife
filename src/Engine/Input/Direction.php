<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Input;

use Tebru\Enum\AbstractEnum;

/**
 * Class Direction
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Direction extends AbstractEnum
{
    const UP = 0;
    const RIGHT = 1;
    const DOWN = 2;
    const LEFT = 3;

    /**
     * Return an array of enum class constants
     *
     * @return array
     */
    public static function getConstants()
    {
        return [
            self::UP,
            self::DOWN,
            self::RIGHT,
            self::LEFT,
        ];
    }
}
