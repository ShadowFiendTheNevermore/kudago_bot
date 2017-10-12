<?php 
require __DIR__ . './bootstrap/application.php';

try {

    $bot = new TelegramBot\Api\Client(getenv('TELEGRAM_BOT_API_TOKEN'));

    $bot->command('test', function ($message) use ($bot){
        $bot->sendMessage("bot just works");
    });

    $bot->run();

} catch (TelegramBot\Api\Exception $e) {
    $e->getMessage();
}



