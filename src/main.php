<?php

use function Amp\delay;
use function CatPaw\SDL\event;
use function CatPaw\SDL\init;
use function CatPaw\SDL\quit;
use function CatPaw\SDL\window;

function main() {
    init();
    $window = window('hello world');   
    while (true) {
        if (event(SDL_QUIT, SDL_MOUSEBUTTONDOWN)) {
            break;
        }
        yield delay(20);
    }
    $window->destroy();
    quit();
}