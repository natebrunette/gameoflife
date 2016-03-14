<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Graphics;

/**
 * Interface Drawable
 *
 * Provides the necessary methods for drawing to the window
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Drawable
{
    /**
     * Return a string of what should be drawn to the window
     *
     * @return string
     */
    public function draw(): string;

    /**
     * Get the width of the drawable
     *
     * @return int
     */
    public function getWidth(): int;

    /**
     * Get the height of the drawable
     *
     * @return int
     */
    public function getHeight(): int;

    /**
     * Get the x coordinate of the drawable
     *
     * @return int
     */
    public function getX(): int;

    /**
     * Get the y coordinate of the drawable
     *
     * @return int
     */
    public function getY(): int;
}
