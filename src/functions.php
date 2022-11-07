<?php
namespace CatPaw\SDL;

use SDL_Event;
use SDL_MessageBoxButtonData;
use SDL_MessageBoxColor;
use SDL_MessageBoxData;
use SDL_Window;

if (!extension_loaded('sdl')) {
    fprintf(STDERR, "The sdl extension is not loaded. Make sure it is in the system and there is a line for it on the php.ini file (eg \"extension=sdl.so\")");
    exit(1);
}

/**
 * Initialize SDL.
 * @return bool
 */
function init():bool {
    static $initialized = false;

    if ($initialized) {
        return true;
    }
    if (SDL_Init(SDL_INIT_EVERYTHING) !== 0) {
        return false;
    }
    $initialized = true;
    return true;
}

function quit():void {
    SDL_Quit();
}

abstract class WindowManager {
    /**
     * The actual window.
     * @psalm-suppress MissingConstructor
     * @var SDL_Window
     */
    public SDL_Window $window;

    /**
     * The window renderer.
     * @var resource
     */
    public mixed $renderer;

    /**
     * Destroy the window.
     * @return void
     */
    public abstract function destroy():void;
}

/**
 * Create a window and return a window manager to control it.
 * @param  string        $title
 * @param  int           $x
 * @param  int           $y
 * @param  int           $width
 * @param  int           $height
 * @param  int           $windowFlags
 * @return WindowManager
 */
function window(
    string $title,
    int $x = 0,
    int $y = 0,
    int $width = 640,
    int $height = 480,
    int $windowFlags = SDL_WINDOW_SHOWN | SDL_WINDOW_RESIZABLE,
):WindowManager {
    if (!$window = SDL_CreateWindow($title, $x, $y, $width, $height, $windowFlags)) {
        return [null,null];
    }

    if (!$renderer = SDL_CreateRenderer($window, -1, 0)) {
        return [null,null];
    }

    SDL_SetRenderDrawColor($renderer, 0, 0, 0, 0);
    SDL_RenderClear($renderer);
    SDL_RenderPresent($renderer);
    
    $helper = new class extends WindowManager {
        public  function destroy():void {
            SDL_DestroyRenderer($this->renderer);
            SDL_DestroyWindow($this->window);
        }
    };
    $helper->window   = $window;
    $helper->renderer = $renderer;

    return $helper;
}

/**
 * Match an event using `$flags` and check if it's currently active.
 * @param  int[] $flags Event flags using `SDL_*` constants.
 *                      Example:
 *                      ```php
 *                      event(SDL_QUIT, SDL_MOUSEBUTTONDOWN);
 *                      ```
 * @return bool  `true` if the event is active, `false` otherwise.
 */
function event(int ...$flags):bool {
    static $event = new SDL_Event;
    SDL_PollEvent($event);
    return in_array($event->type, $flags);
}

/**
 * 
 * @param  int                      $id
 * @param  string                   $text
 * @param  int                      $flags Check `SDL_MessageBoxButtonData::` constants.
 * @return SDL_MessageBoxButtonData
 */
function button(
    int $id,
    string $text,
    int $flags = 0,
):SDL_MessageBoxButtonData {
    return new SDL_MessageBoxButtonData($flags, $id, $text);
}

/**
 * 
 * @param  string $title
 * @param  string $message
 * @param  int    $flags
 * @return int
 */
function message(
    string $title,
    string $message,
    int $flags = SDL_MESSAGEBOX_INFORMATION,
):int {
    return SDL_ShowSimpleMessageBox(
        $flags,
        $title,
        $message,
    );
}

/**
 * 
 * @param  string             $title
 * @param  string             $message
 * @param  array              $colors
 * @param  array              $buttons
 * @param  int                $flags
 * @return SDL_MessageBoxData
 */
function messagebox(
    string $title,
    string $message,
    array $buttons = [],
    array $colors = [],
    int $flags = SDL_MESSAGEBOX_INFORMATION,
):SDL_MessageBoxData {
    return new SDL_MessageBoxData(
        $flags,
        $title,
        $message,
        $buttons,
        $colors,
    );
}



function color(
    int $r,
    int $g,
    int $b,
):SDL_MessageBoxColor {
    return new SDL_MessageBoxColor($r, $g, $b);
}