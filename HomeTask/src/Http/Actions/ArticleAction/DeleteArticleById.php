<?php

namespace GB\HomeTask\Http\Actions\ArticleAction;

use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use Psr\Log\LoggerInterface;

class DeleteArticleById implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        $this->logger->info("Started deleted the article");
        try{
            $id = $request->query('id');
        }catch (HttpException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }
        $this->articlesRepository->deleteById(new UUID($id));

        return new SuccessResponse([
            "message" => "article successful deleted"
        ]);
    }

}
