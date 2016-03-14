<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Audio;

use Symfony\Component\Process\Process;

use function Tebru\assertNotNull;

/**
 * Class Music
 *
 * Stream a music file
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Music
{
    /**
     * The instance of the process playing the music
     *
     * @var Process
     */
    private $musicProcess;

    /**
     * Constructor
     */
    public function __construct(string $filename)
    {
        $this->openFromFile($filename);
    }

    /**
     * Create process from file
     *
     * @param string $filename
     */
    final public function openFromFile(string $filename)
    {
        if (null !== $this->musicProcess) {
            $this->stop();
        }

        $this->musicProcess = new Process(sprintf('afplay %s', $filename));
    }

    /**
     * Start playing music
     */
    public function play()
    {
        $this->musicProcess->start();
    }

    /**
     * Stop playing music
     */
    public function stop()
    {
        $this->musicProcess->stop();
    }
}
