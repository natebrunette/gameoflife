<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Game;

use Tebru\GameOfLife\Engine\Graphics\Drawable;
use Tebru\GameOfLife\Engine\Graphics\Window;
use Tebru\GameOfLife\Engine\Graphics\WindowEvent;
use Tebru\GameOfLife\Engine\Input\Key;

/**
 * Class Game
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Game
{
    /**
     * @var Window
     */
    private $window;

    /**
     * @var World
     */
    private $world;

    /**
     * @var Hud
     */
    private $hud;

    /**
     * When the game was started
     *
     * @var float
     */
    private $startTime = 0.0;

    /**
     * Number of generations
     *
     * @var int
     */
    private $currentGenerations = 0;

    /**
     * Maximum number of generations to run
     *
     * @var int
     */
    private $maxGenerations = 0;

    /**
     * If the game should render
     *
     * @var bool
     */
    private $shouldRender = true;

    /**
     * @var Drawable[]
     */
    private $drawables;

    /**
     * If the game is currently paused
     *
     * @var bool
     */
    private $paused = false;

    /**
     * Constructor
     *
     * @param Window $window
     * @param World $world
     * @param Hud $hud
     * @param int $maxGenerations
     * @param bool $shouldRender
     * @param bool $paused
     */
    public function __construct(
        Window $window,
        World $world,
        Hud $hud,
        int $maxGenerations,
        bool $shouldRender,
        bool $paused
    ) {
        $this->window = $window;
        $this->world = $world;
        $this->hud = $hud;
        $this->maxGenerations = $maxGenerations;
        $this->shouldRender = $shouldRender;

        // pause the game if true
        $this->pause($paused);

        // set up drawable elements
        $this->drawables = [
            $world,
            $hud->getTitle(),
            $hud->getRunningTime(),
            $hud->getFps(),
            $hud->getGenerations(),
            $hud->getInstructions(),
        ];
    }

    /**
     * Start the game loop
     */
    public function run()
    {
        if ($this->shouldRender) {
            $this->render();
            $this->window->moveCursorTo(0, $this->world->getY());
        }

        $this->startTime = microtime(true);
        while ($this->window->isOpen() && $this->currentGenerations < $this->maxGenerations) {
            $this->processEvents();

            if ($this->paused) {
                continue;
            }

            $this->update();

            if ($this->shouldRender) {
                $this->render();
            }
        }

        if (false === $this->shouldRender) {
            $end = microtime(true);
            echo 'Time: ' . ($end - $this->startTime) . PHP_EOL;
            echo 'Generations: ' . $this->currentGenerations . PHP_EOL;
            echo 'Generations/sec: ' . $this->currentGenerations / ($end - $this->startTime) . PHP_EOL;
        }
    }

    /**
     * Process window events
     */
    private function processEvents()
    {
        $event = new WindowEvent();
        while ($this->window->pollEvent($event)) {
            switch ($event->getType()) {
                case WindowEvent::KEY_PRESSED:
                    $this->handleInput($event->getValue());

                    break;
                case WindowEvent::CLOSED:
                    $this->window->setOpen(false);

                    break;
            }
        }
    }

    /**
     * Handle user key presses off the key code
     *
     * @param string $code
     */
    private function handleInput(string $code)
    {
        $currentX = $this->window->getCursorX();
        $currentY = $this->window->getCursorY();

        switch ($code) {
            case Key::W:
            case Key::K:
                $this->pause(true);
                $this->window->moveCursorUp(1);

                if ($this->window->getCursorY() < $this->world->getY()) {
                    $this->window->moveCursorDown(1);
                }

                break;
            case Key::A:
            case Key::H:
                $this->pause(true);
                $this->window->moveCursorLeft(2);

                if ($this->window->getCursorX() < $this->world->getX()) {
                    // todo for some reason, I can't move the cursor right again and wind up at (x, 1)
                    $this->window->moveCursorTo(0, $this->window->getCursorY());
                }

                break;
            case Key::S:
            case Key::J:
                $this->pause(true);
                $this->window->moveCursorDown(1);

                if ($this->window->getCursorY() > $this->world->getHeight()) {
                    $this->window->moveCursorUp(1);
                }

                break;
            case Key::D:
            case Key::L:
                $this->pause(true);
                $this->window->moveCursorRight(2);

                if ($this->window->getCursorX() >= $this->world->getWidth()) {
                    $this->window->moveCursorLeft(2);
                }

                break;
            case Key::Q:
                $this->window->addEvent(WindowEvent::CLOSED);

                break;
            case Key::P:
                $this->pause(!$this->paused);

                break;
            case Key::N:
                if (!$this->paused) {
                    $this->pause(true);
                    $currentX = $this->world->getX();
                    $currentY = $this->world->getY();
                }

                $this->update();
                $this->render();
                $this->window->moveCursorTo($currentX, $currentY);

                break;
            case Key::EQUAL:
            case Key::ADD:
                $this->window->increaseFramerateLimit();
                $this->updateFps();

                break;
            case Key::SUBTRACT:
                $this->window->decreaseFramerateLimit();
                $this->updateFps();

                break;
            case Key::T:
                if (!$this->paused) {
                    $this->pause(true);
                    $currentX = $this->world->getX();
                    $currentY = $this->world->getY();
                }

                $x = $currentX / 2;
                $y = $currentY - $this->world->getY();
                $this->world->toggle($x, $y);
                $this->window->draw($this->world);
                $this->window->moveCursorTo($currentX, $currentY);

                break;
        }
    }

    /**
     * Update game elements
     */
    private function update()
    {
        $this->currentGenerations++;

        $this->world->update();
        $this->hud->updateFps($this->window->getFps());
        $this->hud->updateGenerations($this->currentGenerations);
        $this->hud->updateRunningTime($this->startTime);
    }

    /**
     * Update fps only
     */
    private function updateFps()
    {
        if (!$this->paused) {
            return null;
        }

        $this->hud->updateFps($this->window->getFramerateLimit());
        $this->render();
    }

    /**
     * Render elements in the window
     */
    private function render()
    {
        $this->window->clear();

        foreach ($this->drawables as $drawable) {
            $this->window->draw($drawable);
        }

        $this->window->display(!$this->paused && $this->shouldRender);
    }

    /**
     * Set paused state
     *
     * @param bool $paused
     * @return null
     */
    private function pause(bool $paused)
    {
        if ($paused === $this->paused) {
            return null;
        }

        if ($paused) {
            $this->window->moveCursorTo($this->world->getX(), $this->world->getY());
            $this->window->showCursor();
        } else {
            $this->window->hideCursor();
        }

        $this->paused = $paused;
    }
}
