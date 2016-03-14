# Conway's Game of Life Game

PHP CLI game to run Conway's game of life

## Requirements

PHP 7 is required to run the application.  Additionally, there are a few OS X specifics, and it has not been tested on
any other operating systems or terminals.

What has been tested:

- Macbook Air mid 2012
- OS X 10.11.12
- Default terminal
- Bash 4.2

## Running the Game

The game may be run after checking out the project through bin/gameoflife.  There are a variety of options that may be
passed to the game as well.

- `--no-render` will disable rendering.  Be sure and also include `--generations` or it will run forever.
- `--paused` will start the game paused
- `--no-music` will not play the in-game music
- `--empty` will start the game with an empty grid
- `--generations=10` will run the game for 10 generations. It defaults to no limit.
- `--fps=20` will run the game at 20 fps.  It defaults to 60 fps.
- `--rows=10` will create a grid with 10 rows.  It defaults to 30.
- `--cols=10` will create a grid with 10 colums.  It defaults to 60.
- `--life-chance=30` will specify a 30% chance a cell will be alive.  It defaults to 50.

## In Game Controls

There are a few options available while the game is running.

- `q` will quit the game.  This is important as `^C` is disabled.
- `p` will pause the game.
- `n` will step to the next generation.
- `+/=` will increase the framerate.
- `-` will decrease the framerate
- `wasd/hjkl` will move the cursor around the grid.
- `t` will toggle a cell

## Your terminal

If the game crashes, your terminal could be jacked.  Reset it by running `reset`.

Here are the changes made, that are reset when the game is closed normall.

- `stty -icanon` disables line buffering and allows handling single key presses
- `sttyl -echo` disables output of user input
- `stty -isig` disables `^C` and `^Z`.  This is necessary because music will continue to
play if the application is quit through `^C`.
