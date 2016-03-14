<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Input;

use RuntimeException;

/**
 * Class Io
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Io
{
    /**
     * Check if a key was pressed and set the key to $data
     *
     * @param $input
     * @param string $data
     * @return bool
     */
    public static function read($input, string &$data)
    {
        $read = [$input];
        $write = [];
        $except = [];
        $result = stream_select($read, $write, $except, 0);

        if ($result === false) {
            throw new RuntimeException('stream_select failed');
        }

        if (0 === $result) {
            return false;
        }

        $data = stream_get_line($input, 1);

        return true;
    }
}
