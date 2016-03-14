<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Game;

use Tebru\GameOfLife\Engine\Graphics\Text;

/**
 * Class Hud
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Hud
{
    /**
     * Game title
     *
     * @var Text
     */
    private $title;

    /**
     * Game instructions
     *
     * @var Text
     */
    private $instructions;

    /**
     * Current running time
     *
     * @var Text
     */
    private $runningTime;

    /**
     * Current fps
     *
     * @var Text
     */
    private $fps;

    /**
     * Current number of generations
     *
     * @var Text
     */
    private $generations;

    /**
     * Constructor
     *
     * @param Text $title
     * @param Text $instructions
     * @param Text $runningTime
     * @param Text $fps
     * @param Text $generations
     */
    public function __construct(Text $title, Text $instructions, Text $runningTime, Text $fps, Text $generations)
    {
        $this->title = $title;
        $this->instructions = $instructions;
        $this->runningTime = $runningTime;
        $this->fps = $fps;
        $this->generations = $generations;
    }

    /**
     * @return Text
     */
    public function getTitle(): Text
    {
        return $this->title;
    }

    /**
     * @return Text
     */
    public function getInstructions(): Text
    {
        return $this->instructions;
    }

    /**
     * @return Text
     */
    public function getRunningTime(): Text
    {
        return $this->runningTime;
    }

    /**
     * @param Text $runningTime
     */
    public function setRunningTime(Text $runningTime)
    {
        $this->runningTime = $runningTime;
    }

    /**
     * Update the current running time
     *
     * @param float $startTime
     */
    public function updateRunningTime(float $startTime)
    {
        $runningTime = round(microtime(true) - $startTime, 4);
        $this->runningTime->setWithFormat($runningTime);
    }

    /**
     * Get fps
     *
     * @return Text
     */
    public function getFps(): Text
    {
        return $this->fps;
    }

    /**
     * Set fps
     *
     * @param Text $fps
     */
    public function setFps(Text $fps)
    {
        $this->fps = $fps;
    }

    /**
     * Update the fps
     *
     * @param float $fps
     */
    public function updateFps(float $fps)
    {
        $fps = round($fps, 4);
        $this->fps->setWithFormat($fps);
    }

    /**
     * Get generations
     *
     * @return Text
     */
    public function getGenerations(): Text
    {
        return $this->generations;
    }

    /**
     * Set generations
     *
     * @param Text $generations
     */
    public function setGenerations(Text $generations)
    {
        $this->generations = $generations;
    }

    /**
     * Update the generations
     *
     * @param int $currentGenerations
     */
    public function updateGenerations(int $currentGenerations)
    {
        $this->generations->setWithFormat($currentGenerations);
    }
}
