<?php

namespace GB\HomeTask\Http\Actions\ArticleAction;

use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArticleNotFoundException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class FindArticleById implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        $this->logger->info("started finding article");
        try{
            $id = $request->query('id');
        }catch (HttpException $exception){
            $this->logger->warning($exception->getMessage(), ["error"=> $exception]);
            return new ErorrResponse($exception->getMessage());
        }

        try{
            $article = $this->articlesRepository->get(new UUID($id));
        }catch (ArticleNotFoundException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        return new SuccessResponse([
            "id"=> (string)$article->getId(),
            "authorId"=> (string)$article->getAuthorId(),
            "title"=> $article->getTitle(),
            "text"=> $article->getText()
        ]);
    }

}
