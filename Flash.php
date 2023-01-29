<?php
const FLASH = 'FLASH_MESSAGES';
const FLASH_ERROR = 'error';
const FLASH_SUCCESS = 'success';

function create_flash_message(string $name, string $message, string $type)
{
    // remove existing message with the name
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    }
    // add the message to the session
    $_SESSION[FLASH][$name] = ['message' => $message, 'type' => $type];
}

function format_flash_message(array $flash_message): string
{
    if($flash_message['type'] == 'error')
    {
        return sprintf('<p style="color: red">%s</p>',
            $flash_message['message']
        );
    }
    else{
        return sprintf('<p style="color: forestgreen">%s</p>',
            $flash_message['message']
        );
    }
}

function display_flash_message(string $name)
{
    if (!isset($_SESSION[FLASH][$name])) {
        return;
    }

    // get message from the session
    $flash_message = $_SESSION[FLASH][$name];

    // delete the flash message
    unset($_SESSION[FLASH][$name]);

    // display the flash message
    echo format_flash_message($flash_message);
}

function display_all_flash_messages()
{
    if (!isset($_SESSION[FLASH])) {
        return;
    }

    // get flash messages
    $flash_messages = $_SESSION[FLASH];

    // remove all the flash messages
    unset($_SESSION[FLASH]);

    // show all flash messages
    foreach ($flash_messages as $flash_message) {
        echo format_flash_message($flash_message);
    }
}

function flash(string $name = '', string $message = '', string $type = '')
{
    if ($name !== '' && $message !== '' && $type !== '') {
        create_flash_message($name, $message, $type);
    } elseif ($name !== '' && $message === '' && $type === '') {
        display_flash_message($name);
    } elseif ($name === '' && $message === '' && $type === '') {
        display_all_flash_messages();
    }
}
