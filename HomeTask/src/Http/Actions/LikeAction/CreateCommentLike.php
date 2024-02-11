<?php

namespace GB\HomeTask\Http\Actions\LikeAction;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Blog\Like\ArticleLike;
use GB\HomeTask\Blog\Like\CommentLike;
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
use GB\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use GB\HomeTask\Repositories\Likes\SqLiteArticleLikesRepo;
use GB\HomeTask\Repositories\Likes\SqLiteCommentLikesRepo;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateCommentLike implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepositiry,
        private SqLiteCommentLikesRepo $likesRepository,
        private LoggerInterface $logger,
        private TokenAuthenticationInterface $authentication
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new comment like");
        $id = UUID::random();

        try{
            $commentId = new UUID($request->jsonBodyField('commentId'));
        }catch (HttpException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $comment = $this->commentsRepositiry->get($commentId);
        }catch (ArticleNotFoundException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse("No comment created");
        }

        try{
            $user = $this->authentication->user($request);
        }catch (AuthException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try {
            $this->likesRepository->likeExist($comment,$user);
        }catch (LikeExsistException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $this->likesRepository->save(new CommentLike(
            $id,
            $commentId,
            $user->getId()
        ));

        return new SuccessResponse([
            'message'=> "Like successfully saved $id."
        ]);
    }

}
