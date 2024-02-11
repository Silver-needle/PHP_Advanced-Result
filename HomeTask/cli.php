<?php
require_once __DIR__."/vendor/autoload.php";
$con = new PDO('sqlite:'.__DIR__.'/../blog.sqlite');

use GB\HomeTask\Blog\Article\CreateArticleCommand;
use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Comment\CreateCommentCommand;
use GB\HomeTask\Blog\Like\Like;
use GB\HomeTask\Blog\User\CreateUserCommand;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Commands\PopulateDB\PopulateDB;
use GB\HomeTask\Commands\Posts\DeletePost;
use GB\HomeTask\Commands\Users\CreateUser;
use GB\HomeTask\Commands\Users\UpdateUser;
use GB\HomeTask\Common\Arguments;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\SomeClass;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Container\DIContainer;
use GB\HomeTask\Exceptions\AppException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use GB\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use GB\HomeTask\Repositories\Likes\SqLiteLikesRepo;
use GB\HomeTask\Repositories\Users\InMemoryUsersRepo;
use GB\HomeTask\Repositories\Users\SqLiteUserRepo;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use Symfony\Component\Console\Application;

$container = require_once __DIR__.'/bootstrap.php';

try{
    $application = new Application();

    $commandClasses = [
        CreateUser::class,
        DeletePost::class,
        UpdateUser::class,
        PopulateDB::class
        ];

    foreach ($commandClasses as $class){
        $command = $container->get($class);
        $application->add($command);
    }

    $application->run();
}catch (AppException|Exception $e){
    echo $e->getMessage();
}
