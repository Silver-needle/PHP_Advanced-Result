<?php
require_once __DIR__."/vendor/autoload.php";
$con = new PDO('sqlite:'.__DIR__.'/../blog.sqlite');

use GB\HomeTask\Exceptions\AppException;
use GB\HomeTask\Http\Actions\ArticleAction\CreateArticle;
use GB\HomeTask\Http\Actions\ArticleAction\DeleteArticleById;
use GB\HomeTask\Http\Actions\ArticleAction\FindArticleById;
use GB\HomeTask\Http\Actions\Auth\LogIn;
use GB\HomeTask\Http\Actions\Auth\LogOut;
use GB\HomeTask\Http\Actions\CommentAction\CreateComment;
use GB\HomeTask\Http\Actions\CommentAction\FindCommentById;
use GB\HomeTask\Http\Actions\LikeAction\CreateArticleLike;
use GB\HomeTask\Http\Actions\LikeAction\CreateCommentLike;
use GB\HomeTask\Http\Actions\LikeAction\CreateLike;
use GB\HomeTask\Http\Actions\LikeAction\FindLikesByArticle;
use GB\HomeTask\Http\Actions\UserAction\CreateUser;
use GB\HomeTask\Http\Actions\UserAction\FindByUsername;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use GB\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use GB\HomeTask\Repositories\Users\SqLiteUserRepo;
use Psr\Log\LoggerInterface;

http_response_code(200);

$request = new Request($_GET, $_SERVER, file_get_contents("php://input"));
$container = require_once __DIR__.'/bootstrap.php';
$logger = $container->get(LoggerInterface::class);
try {
    $path = $request->path();
} catch (\GB\HomeTask\Exceptions\HttpException $e) {
    $logger->error($e->getMessage());
    (new ErorrResponse($e->getMessage()))->send();
    return;
}

try {
    // Пытаемся получить HTTP-метод запроса
    $method = $request->method();
} catch (\GB\HomeTask\Exceptions\HttpException $e) {
    // Возвращаем неудачный ответ,
    // если по какой-то причине
    // не можем получить метод
    $logger->error($e->getMessage());
    (new ErorrResponse($e->getMessage()))->send();
    return;
}

$routes = [
// Добавили ещё один уровень вложенности
// для отделения маршрутов,
// применяемых к запросам с разными методами
    'GET' => [
        '/show/user' => FindByUsername::class,
        '/show/article' =>FindArticleById::class,
        '/show/comment' =>FindCommentById::class,
    ],
    'POST' => [
        // Добавили новый маршрут
        '/create/user' => CreateUser::class,
        '/create/article' => CreateArticle::class,
        '/create/comment' => CreateComment::class,
        '/create/article/like' => CreateArticleLike::class,
        '/create/comment/like' => CreateCommentLike::class,
        '/logIn'=> LogIn::class,
        '/logOut'=> LogOut::class

    ],
    'DELETE' => [
        '/delete/article' => DeleteArticleById::class
    ]
];

// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ
if (!array_key_exists($method, $routes)) {
    $logger->error('Method Not found');
    (new ErorrResponse('Method Not found'))->send();
    return;
}

// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
    $logger->error('Route Not found');
    (new ErorrResponse('Route Not found'))->send();
    return;
}

// Выбираем действие по методу и пути
$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    $this->logger->warning($e->getMessage());
    (new ErorrResponse($e->getMessage()))->send();
}

$response->send();
