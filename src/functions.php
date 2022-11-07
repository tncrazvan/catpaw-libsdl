<?php
namespace CatPaw\SDL;

use SDL_Event;
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

abstract class WindowHelper {
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
 * Create a window
 * @param  string       $title
 * @param  int          $x
 * @param  int          $y
 * @param  int          $width
 * @param  int          $height
 * @param  int          $windowFlags
 * @return WindowHelper
 */
function window(
    string $title,
    int $x = 0,
    int $y = 0,
    int $width = 640,
    int $height = 480,
    int $windowFlags = SDL_WINDOW_SHOWN | SDL_WINDOW_RESIZABLE,
):WindowHelper {
    if (!$window = SDL_CreateWindow($title, $x, $y, $width, $height, $windowFlags)) {
        return [null,null];
    }

    if (!$renderer = SDL_CreateRenderer($window, -1, 0)) {
        return [null,null];
    }

    SDL_SetRenderDrawColor($renderer, 0, 0, 0, 0);
    SDL_RenderClear($renderer);
    SDL_RenderPresent($renderer);
    
    $helper = new class extends WindowHelper {
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
 * Check if an event is triggered.
 * @param  int[] $flags Event flags, e.g. `event(SDL_QUIT, SDL_MOUSEBUTTONDOWN)`
 * @return bool
 */
function event(int ...$flags):bool {
    static $event = new SDL_Event;
    SDL_PollEvent($event);
    return in_array($event->type, $flags);
}