<?php 

require __DIR__ . '/vendor/autoload.php';
$env = new Dotenv\Dotenv(__DIR__);

// Need for dotenv load data method
if (!file_exists('.env')) {
    copy('.env.example', '.env');
}

$env->load();

$settings = require __DIR__ .'/settings.php';

$app = new Slim\App($settings);

$container = $app->getContainer();
$container['bot'] = function ($container) use ($settings){
    $bot = new TelegramBot\Api\BotApi($settings['token']);
    return $bot;
};


$container['db'] = function ($container){
    $capsule = new Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

$container['http'] = function ($container) {
    return new GuzzleHttp\Client();
};

