<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Input;

/**
 * Interface CursorAware
 *
 * Methods needed to interact with a cursor
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface CursorAware
{
    /**
     * Move the cursor up a set amount
     *
     * @param int $amount
     */
    public function moveCursorUp(int $amount);

    /**
     * Move the cursor down a set amount
     *
     * @param int $amount
     */
    public function moveCursorDown(int $amount);

    /**
     * Move the cursor right a set amount
     *
     * @param int $amount
     */
    public function moveCursorRight(int $amount);

    /**
     * Move the cursor left a set amount
     *
     * @param int $amount
     */
    public function moveCursorLeft(int $amount);

    /**
     * Hide the cursor
     */
    public function hideCursor();

    /**
     * Show the cursor
     */
    public function showCursor();
}
