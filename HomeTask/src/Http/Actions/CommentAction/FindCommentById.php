<?php

namespace GB\HomeTask\Http\Actions\CommentAction;

use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\CommentNotFoundException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use Psr\Log\LoggerInterface;

class FindCommentById implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        $this->logger->info("Started finding a comment by Uuid");
        try{
            $id = $request->query('id');
        }catch (HttpException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $comment = $this->commentsRepository->get(new UUID($id));
        }catch (CommentNotFoundException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        return new SuccessResponse([
            "id" => (string)$comment->getId(),
            "authorId"=> (string)$comment->getAuthorId(),
            "articleId"=> (string)$comment->getArticleId(),
            "text" => $comment->getText()
        ]);
    }

}
