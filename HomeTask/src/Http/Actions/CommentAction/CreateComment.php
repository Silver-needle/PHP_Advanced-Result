<?php

namespace GB\HomeTask\Http\Actions\CommentAction;

use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\AuthException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use Psr\Log\LoggerInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepository,
        private LoggerInterface $logger,
        private TokenAuthenticationInterface $authentication
    ) {}

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new comment");
        $id = UUID::random();
        try{
            $articleId = $request->jsonBodyField('articleId');
            $text = $request->jsonBodyField('text');
        }catch (HttpException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $user = $this->authentication->user($request);
        }catch (AuthException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $this->commentsRepository->save(new Comment($id, $user->getId(), new UUID($articleId), $text));

        return new SuccessResponse([
            "message"=>"Comment successful created with Id=$id"
        ]);
    }

}
