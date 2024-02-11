<?php

namespace GB\HomeTask\Http\Actions\LikeAction;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Like\ArticleLike;
use GB\HomeTask\Blog\Like\Like;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArticleNotFoundException;
use GB\HomeTask\Exceptions\AuthException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Exceptions\LikeExsistException;
use GB\HomeTask\Exceptions\LikeNotFoundException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use GB\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use GB\HomeTask\Repositories\Likes\SqLiteArticleLikesRepo;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticleLike implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private SqLiteArticleLikesRepo $likesRepository,
        private LoggerInterface $logger,
        private TokenAuthenticationInterface $authentication,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new article like");
        $id = UUID::random();

        try{
            $articleId = new UUID($request->jsonBodyField('articleId'));
        }catch (HttpException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $article = $this->articlesRepository->get($articleId);
        }catch (ArticleNotFoundException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse("No article created");
        }

        try{
            $user = $this->authentication->user($request);
        }catch (AuthException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try {
            $this->likesRepository->likeExist($article,$user);
        }catch (LikeExsistException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $this->likesRepository->save(new ArticleLike(
            $id,
            $articleId,
            $user->getId()
        ));

        return new SuccessResponse([
            'message'=> "Like successfully saved $id."
        ]);
    }

}
