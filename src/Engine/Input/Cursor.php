<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Input;

use function Tebru\assertCount;

/**
 * Class Cursor
 *
 * Manipulates a cursor in the terminal
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Cursor
{
    /**#@+
     * Cursor Movements
     */
    const CURSOR_UP = "\033[A";
    const CURSOR_RIGHT = "\033[C";
    const CURSOR_DOWN = "\033[B";
    const CURSOR_LEFT = "\033[D";
    /**#@-*/

    /**
     * Show the cursor
     */
    const CURSOR_HIDE = "\033[?25l";

    /**
     * Hide the cursor
     */
    const CURSOR_SHOW = "\033[?25h";

    /**
     * Move the cursor
     *
     * $velocity must be a tuple
     *
     * @param array $velocity
     */
    public function move(array $velocity)
    {
        $direction = $this->getDirection($velocity);

        switch ($direction->getValue()) {
            case Direction::UP:
                $this->doMove($velocity[1], self::CURSOR_UP);
                break;
            case Direction::RIGHT:
                $this->doMove($velocity[0], self::CURSOR_RIGHT);
                break;
            case Direction::DOWN:
                $this->doMove($velocity[1], self::CURSOR_DOWN);
                break;
            case Direction::LEFT:
                $this->doMove($velocity[0], self::CURSOR_LEFT);
                break;
        }
    }

    /**
     * Move the cursor to a specific location
     *
     * @param int $x
     * @param int $y
     */
    public function moveTo(int $x, int $y)
    {
        $x++;
        $y++;
        echo "\033[${y};${x}H";
    }

    /**
     * Hide the cursor
     */
    public function hide()
    {
        echo self::CURSOR_HIDE;
    }

    /**
     * Show the cursor
     */
    public function show()
    {
        echo self::CURSOR_SHOW;
    }

    /**
     * Get the direction from the velocity
     *
     * @param array $velocity
     * @return $this
     */
    private function getDirection(array $velocity)
    {
        assertCount(2, $velocity);

        if (0 === $velocity[1]) {
            return ($velocity[0] < 0) ? Direction::create(Direction::LEFT) : Direction::create(Direction::RIGHT);
        } else {
            return ($velocity[1] < 0) ? Direction::create(Direction::UP) : Direction::create(Direction::DOWN);
        }
    }

    /**
     * Move the cursor
     *
     * @param int $times
     * @param string $move
     */
    private function doMove(int $times, string $move)
    {
        for ($i = 0; $i < abs($times); $i++) {
            echo $move;
        }
    }
}
