<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tebru\GameOfLife\Engine\Graphics\AsciiImage;
use Tebru\GameOfLife\Engine\Input\Cursor;
use Tebru\GameOfLife\Game\Factory\BitmapFactory;
use Tebru\GameOfLife\Game\Game;
use Tebru\GameOfLife\Game\Grid;
use Tebru\GameOfLife\Game\Hud;
use Tebru\GameOfLife\Engine\Audio\Music;
use Tebru\GameOfLife\Engine\Graphics\Text;
use Tebru\GameOfLife\Engine\Graphics\Window;
use Tebru\GameOfLife\Game\World;

/**
 * Class RunCommand
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RunCommand extends Command
{
    const NAME = 'run';

    /**#@+
     * Command Options
     */
    const NO_RENDER = 'no-render';
    const NO_MUSIC = 'no-music';
    const PAUSED = 'paused';
    const EMPTY = 'empty';
    const GENERATIONS = 'generations';
    const FPS = 'fps';
    const ROWS = 'rows';
    const COLS = 'cols';
    const CHANCE = 'life-chance';
    /**#@-*/

    /**#@+
     * Default Options
     */
    const DEFAULT_GENERATIONS = PHP_INT_MAX;
    const DEFAULT_FPS = 60.0;
    const DEFAULT_ROWS = 30;
    const DEFAULT_COLS = 60;
    const DEFAULT_CHANCE = 50;
    /**#@-*/

    /**
     * Music process
     *
     * @var Music
     */
    private $music;

    /**
     * Constructor
     */
    public function __construct($name)
    {
        parent::__construct($name);

        // configure terminal
        // disable buffering a line at a time
        // this allows handling single characters
        system('stty -icanon');

        // disable output of input
        // to not show which characters are entered
        system('stty -echo');

        // disable ^C termination
        // because the music won't stop if quitting without q
        system('stty -isig');

    }

    public function __destruct()
    {
        // reset the terminal
        system('stty icanon');
        system('stty echo');
        system('stty isig');

        // enable the cursor
        echo Cursor::CURSOR_SHOW;

        // stop music
        if (null !== $this->music) {
            $this->music->stop();
        }
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::NAME);
        $this->addOption(self::NO_RENDER, null, InputOption::VALUE_NONE, 'Disable rendering of the grid');
        $this->addOption(self::PAUSED, null, InputOption::VALUE_NONE, 'Start the game paused');
        $this->addOption(self::NO_MUSIC, null, InputOption::VALUE_NONE, 'Turn off the music');
        $this->addOption(self::EMPTY, null, InputOption::VALUE_NONE, 'Create the grid without any alive cells');
        $this->addOption(self::GENERATIONS, null, InputOption::VALUE_REQUIRED, 'The number of generations to run');
        $this->addOption(self::FPS, null, InputOption::VALUE_REQUIRED, 'What to cap FPS at');
        $this->addOption(self::ROWS, null, InputOption::VALUE_REQUIRED, 'Number of rows');
        $this->addOption(self::COLS, null, InputOption::VALUE_REQUIRED, 'Number of columns');
        $this->addOption(self::CHANCE, null, InputOption::VALUE_REQUIRED, 'Percent chance that a cell will start alive');
    }
    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $map = require ROOT_DIR . '/resources/map.php';

        $render = !$input->getOption(self::NO_RENDER);
        $music = !$input->getOption(self::NO_MUSIC);
        $empty = (bool)$input->getOption(self::EMPTY);
        $paused = (bool)$input->getOption(self::PAUSED) | $empty;
        $generations = (int) $input->getOption(self::GENERATIONS) ?: self::DEFAULT_GENERATIONS;
        $fps = (float)$input->getOption(self::FPS) ?: self::DEFAULT_FPS;
        $rows = (int) $input->getOption(self::ROWS) ?: self::DEFAULT_ROWS;
        $cols = (int) $input->getOption(self::COLS) ?: self::DEFAULT_COLS;
        $chance = (float) $input->getOption(self::CHANCE) ?: self::DEFAULT_CHANCE;

        if ($music) {
            $file = ROOT_DIR . '/resources/music.mp3';
            $this->music = new Music($file);
            $this->music->play();
        }

        if ($fps === -1) {
            $fps = PHP_INT_MAX;
        }

        $cursor = new Cursor();
        $cursor->hide();

        $window = new Window($cursor);
        $window->setFramerateLimit($fps);

        $bitmapFactory = new BitmapFactory();
        $bitmap = ($empty)
            ? $bitmapFactory->make($rows, $cols, 0)
            : $bitmapFactory->make($rows, $cols, $chance);

        $y = 0;
        $titleText = new Text('Conway\'s Game of Life');
        $y += $titleText->getHeight();

        $aliveImage = new AsciiImage();
        $aliveImage->loadFromFile(ROOT_DIR . '/resources/alive');
        $deadImage = new AsciiImage();
        $deadImage->loadFromFile(ROOT_DIR . '/resources/dead');
        $grid = new Grid($bitmap, $cols);
        $world = new World($aliveImage, $deadImage, $grid, $map, $bitmapFactory);
        $world->setY($y);
        $y += $world->getHeight();

        $generationText = new Text(0, 'Generations: %d');
        $generationText->setY($y);
        $y += $generationText->getHeight();

        $runningTimeText = new Text(0.0, 'Running Time: %f');
        $runningTimeText->setY($y);
        $y += $runningTimeText->getHeight();

        $fpsText = new Text(0.0, 'FPS: %f');
        $fpsText->setY($y);
        $y += $fpsText->getHeight();

        $instructionText = new Text("Press q to quit");
        $instructionText->setY($y);

        $hud = new Hud($titleText, $instructionText, $runningTimeText, $fpsText, $generationText);

        $game = new Game($window, $world, $hud, $generations, $render, $paused);
        $game->run();
    }
}
