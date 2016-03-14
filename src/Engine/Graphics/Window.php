<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Graphics;

use RuntimeException;
use Tebru\GameOfLife\Engine\Input\Cursor;
use Tebru\GameOfLife\Engine\Input\CursorAware;
use Tebru\GameOfLife\Engine\Input\Io;
use Tebru\GameOfLife\Engine\Input\Key;

/**
 * Class Window
 *
 * Displays drawable items in a terminal window
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Window implements CursorAware
{
    /**
     * Clear the window
     */
    const CLEAR = "\033[2J\033[H";

    /**
     * A Cursor instance to manipulate the cursor
     *
     * @var Cursor
     */
    private $cursor;

    /**
     * What to limit the framerate to
     *
     * @var float
     */
    private $framerateLimit = 60.0;

    /**
     * If the window is open
     *
     * @var bool
     */
    private $open = true;

    /**
     * The last time the window was updated
     *
     * @var float
     */
    private $lastUpdate = 0.0;

    /**
     * The current fps the window is rendering
     *
     * @var float
     */
    private $fps = 0.0;

    /**
     * The current x position of the cursor
     *
     * @var int
     */
    private $cursorX = 0;

    /**
     * The current y position of the cursor
     *
     * @var int
     */
    private $cursorY = 0;

    /**
     * Window events
     *
     * @var array
     */
    private $events = [];

    /**
     * Constructor
     *
     * @param Cursor $cursor
     */
    public function __construct(Cursor $cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * Get the framerate limit
     *
     * @return float
     */
    public function getFramerateLimit(): float
    {
        return $this->framerateLimit;
    }

    /**
     * Set the framerate limit
     *
     * @param float $framerateLimit
     */
    public function setFramerateLimit(float $framerateLimit)
    {
        $this->framerateLimit = $framerateLimit;
    }

    /**
     * Increase the framerate limit by 1 fps
     */
    public function increaseFramerateLimit()
    {
        $this->framerateLimit++;
    }

    /**
     * Decrease the framerate limit by 1 fps
     */
    public function decreaseFramerateLimit()
    {
        if ($this->framerateLimit > 1) {
            $this->framerateLimit--;
        }
    }

    /**
     * Return if the window is currently open
     *
     * @return boolean
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     * Set the state of the window
     *
     * @param boolean $open
     */
    public function setOpen(bool $open)
    {
        $this->open = $open;
    }

    /**
     * Get the last updated time
     *
     * @return float
     */
    public function getLastUpdate(): float
    {
        return $this->lastUpdate;
    }

    /**
     * Get the current fps the window is rendering at
     *
     * @return float
     */
    public function getFps(): float
    {
        return $this->fps;
    }

    /**
     * Check for events
     *
     * @param WindowEvent $event
     * @return bool
     */
    public function pollEvent(WindowEvent $event): bool
    {
        $input = '';
        if (Io::read(STDIN, $input)) {
            try {
                $this->addEvent(WindowEvent::KEY_PRESSED, (string) Key::create($input));
            } catch (RuntimeException $exception) {
                // if we don't support the key, ignore it
            }
        }

        if (empty($this->events)) {
            return false;
        }

        $event->setType(key($this->events));
        $event->setValue(array_shift($this->events));

        return true;
    }

    /**
     * Add an event to the window
     *
     * @param string $key
     * @param string|null $value
     */
    public function addEvent(string $key, string $value = null)
    {
        $this->events[$key] = $value;
    }

    /**
     * Clear the window
     */
    public function clear()
    {
        ob_start();

        echo self::CLEAR;
    }

    /**
     * Draw an item on the window
     *
     * @param Drawable $drawable
     */
    public function draw(Drawable $drawable)
    {
        $this->moveCursorTo($drawable->getX(), $drawable->getY());

        echo $drawable->draw();
    }

    /**
     * Display drawn contents
     *
     * @param bool $shouldSync If we should sync fps
     */
    public function display(bool $shouldSync = false)
    {
        if ($shouldSync) {
            // wait to sync with target framerate
            while (microtime(true) - $this->lastUpdate < 1.0 / $this->framerateLimit);
        }

        $currentTime = microtime(true);
        $this->fps = 1 / ($currentTime - $this->lastUpdate);
        $this->lastUpdate = $currentTime;

        ob_end_flush();
    }

    /**
     * Move the cursor to an x/y coordinate
     * @param int $x
     * @param int $y
     */
    public function moveCursorTo(int $x, int $y)
    {
        $this->cursorX = $x;
        $this->cursorY = $y;

        $this->cursor->moveTo($x, $y);
    }

    /**
     * Move the cursor up a set amount
     *
     * @param int $amount
     */
    public function moveCursorUp(int $amount)
    {
        $this->cursor->move([0, -$amount]);
        $this->cursorY -= $amount;
    }

    /**
     * Move the cursor down a set amount
     *
     * @param int $amount
     */
    public function moveCursorDown(int $amount)
    {
        $this->cursor->move([0, $amount]);
        $this->cursorY += $amount;
    }

    /**
     * Move the cursor right a set amount
     *
     * @param int $amount
     */
    public function moveCursorRight(int $amount)
    {
        $this->cursor->move([$amount, 0]);
        $this->cursorX += $amount;
    }

    /**
     * Move the cursor left a set amount
     *
     * @param int $amount
     */
    public function moveCursorLeft(int $amount)
    {
        $this->cursor->move([-$amount, 0]);
        $this->cursorX -= $amount;
    }

    /**
     * Hide the cursor
     */
    public function hideCursor()
    {
        $this->cursor->hide();
    }

    /**
     * Show the cursor
     */
    public function showCursor()
    {
        $this->cursor->show();
    }

    /**
     * The the cursor's x coordinate
     *
     * @return int
     */
    public function getCursorX(): int
    {
        return $this->cursorX;
    }

    /**
     * The the cursor's y coordinate
     *
     * @return int
     */
    public function getCursorY(): int
    {
        return $this->cursorY;
    }
}
