<?php

use function CatPaw\SDL\button;
use function CatPaw\SDL\color;
use function CatPaw\SDL\init;
use function CatPaw\SDL\message;
use function CatPaw\SDL\messagebox;

const BUTTON_ID_YES = 1;
const BUTTON_ID_NO  = 0;
function main() {
    init();

    message('Message box title', 'This is an example message box');
    
    
    $buttons = [
        button(BUTTON_ID_YES, 'Yes'),
        button(BUTTON_ID_NO, 'No'),
    ];
    $colors = [
        SDL_MessageBoxColor::BACKGROUND        => color(0, 0, 0),
        SDL_MessageBoxColor::TEXT              => color(255, 255, 255),
        SDL_MessageBoxColor::BUTTON_BORDER     => color(255, 0, 0),
        SDL_MessageBoxColor::BUTTON_BACKGROUND => color(0, 255, 0),
        SDL_MessageBoxColor::BUTTON_SELECTED   => color(0, 0, 255),
    ];
    $dialog = messagebox("Message box data", 'Select Yes or No', $buttons, $colors);
    if (0 === $dialog->Show($buttonId)) {
        echo 'Button selection: ', BUTTON_ID_YES === $buttonId ? 'Yes' : 'No', PHP_EOL;
    } else {
        // printSdlErrorAndExit();
    }
}