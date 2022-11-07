<?php


use function Amp\delay;
use function CatPaw\SDL\event;
use function CatPaw\SDL\init;
use function CatPaw\SDL\quit;
use function CatPaw\SDL\window;

function main() {
    init();
    $helper = window('hello');
    while (true) {
        if (event(SDL_QUIT, SDL_MOUSEBUTTONDOWN)) {
            break;
        }
        $time = microtime();
        $helper->window->setTitle("hello $time");
        yield delay(20);
    }
    $helper->destroy();
    quit();
}
