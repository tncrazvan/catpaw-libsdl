<?php
namespace CatPaw\SDL;

if (!extension_loaded('sdl')) {
    fprintf(STDERR, "The sdl extension is not loaded. Make sure it is in the system and there is a line for it on the php.ini file (eg \"extension=sdl.so\")");
    exit(1);
}

/**
 * Initialize SDL.
 * @return bool
 */
function init():bool {
    if (SDL_Init(SDL_INIT_EVERYTHING) !== 0) {
        return false;
    }
    return true;
}