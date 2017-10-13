<?php 

require __DIR__ . '/bootstrap/application.php';

try {

    $bot = new TelegramBot\Api\Client(getenv('TELEGRAM_BOT_API_TOKEN'));

    $bot->command('test', function ($message) use ($bot){
        $bot->sendMessage($message->getChat()->getId(), $message->getText());
    });

    $bot->command('categories', function ($message) use ($bot){
        $http = new GuzzleHttp\Client();
        $categories = json_decode($http->request('GET', 'https://kudago.com/public-api/v1.3/event-categories/')->getBody());
        $categoryButtons = [];

        foreach ($categories as $category) {
            $categoryButtons[] = [[
                'text' => $category->name
            ]];
        }

        $keyboard = new TelegramBot\Api\Types\ReplyKeyboardMarkup($categoryButtons, true, true);

        $bot->sendMessage($message->getChat()->getId(), 'Выберите категорию', null, false, null, $keyboard);
    });

    $bot->command('start', function ($message) use ($bot){

        // $bot->sendMessage(
        //     $message->getChat()->getId(), 
        // );
    });

    $bot->run();

} catch (TelegramBot\Api\Exception $e) {
    echo $e->getMessage();
}




