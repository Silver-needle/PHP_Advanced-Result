B<?php

namespace GB\HomeTask\Http\Actions\LikeAction;

use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArticleNotFoundException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Exceptions\LikeNotFoundException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use GB\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use GB\HomeTask\Http\ErorrResponse;
use Psr\Log\LoggerInterface;

class FindLikesByArticle implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
        private ArticlesRepositoryInterface $articlesRepository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        $this->logger->info("Started finding Likes by an article");
        $message = [];
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

        try{
            $articleLikes = $this->likesRepository->getAllByArticle(new UUID($id));
        }catch (LikeNotFoundException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $i=0;
        foreach ($articleLikes as $like){
            $message[$i] = [
                "id"=>(string)$like->getLike(),
                "article"=>(string)$like->getArticle(),
                "user"=> (string)$like->getUser()
            ];
            $i++;
        }
        return new SuccessResponse($message);
    }

}
