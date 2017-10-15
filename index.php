<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/bootstrap.php';

$app->map(['GET', 'POST'], "/{$settings['token']}", function (Request $request, Response $response){
    $message = $request->getParsedBody();
    $chatId  = $message['message']['chat']['id'];

    if ($message['message']['text'] === '/go') {
        $categories = $this->db->table('categories')->select('name')->get();
        $categories = $categories->map(function($category){
            return [$category->name];
        })->toArray();

        if ($this->db->table('conversations')->where('chat_id', $chatId)->count() === 0) {
            $this->db->table('conversations')->insert([
                'chat_id' => $chatId,
                'last_message' => '/go'
            ]);
        } else {
            $this->db->table('conversations')->where('chat_id', $chatId)->update(['last_message' => '/go']);
        }

        try {
            $keyboard = new TelegramBot\Api\Types\ReplyKeyboardMarkup($categories, true, true);
            $this->bot->sendMessage($chatId, 'Выберите категорию', null, false, null, $keyboard);
        } catch (TelegramBot\Api\Exception $e) {
            dd($e->getMessage());
        }
    } else {
        // Otherwise session for /go command
        $conversation = $this->db->table('conversations')->where('chat_id', $chatId)->first();
        if ($conversation->last_message === '/go') {
            $this->db->table('conversations')->where('chat_id', $chatId)->update(['last_message' => $message['message']['text']]);
            $slug = $this->db->table('categories')->where('name', $message['message']['text'])->first()->slug;
            $events = $this->http->get($this->settings['kudago_api'] . 'events',[
                'query' => [
                    'categories' => $slug,
                    'fields' => implode(['title', 'site_url', 'description'], ','),
                    'page_size' => 3,
                    'text_format' => 'plain'
                ]
            ]);
            $events = json_decode($events->getBody());
            foreach ($events->results as $event) {
                $textMessage  = "<strong>$event->title</strong>\n";
                $textMessage .= $event->description . "\n" . $event->site_url;
                echo $textMessage;
                $this->bot->sendMessage($chatId, $textMessage, 'HTML');
            }
        }
    }
});

$app->run();
