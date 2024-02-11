<?php

namespace GB\HomeTask\Http\Actions\ArticleAction;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\AuthException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\Auth\Interfaces\AuthenticationInterface;
use GB\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private TokenAuthenticationInterface $authentication,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started create new article");
        $id = UUID::random();
        try{
            $title = $request->jsonBodyField('title');
            $text = $request->jsonBodyField('text');
        }catch (HttpException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $user = $this->authentication->user($request);
        }catch (AuthException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $this->articlesRepository->save(new Article($id, $user->getId(),$title,$text));

        return new SuccessResponse([
            "message"=> "Article successful created with Id = $id"
        ]);
    }

}
