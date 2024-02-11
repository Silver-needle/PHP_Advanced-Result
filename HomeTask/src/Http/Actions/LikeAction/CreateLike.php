<?php

namespace GB\HomeTask\Http\Actions\LikeAction;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Like\Like;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArticleNotFoundException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Exceptions\LikeNotFoundException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use GB\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;

class CreateLike implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private ?UsersRepositoryInterface $usersRepository,
        private LikesRepositoryInterface $likesRepository
    ) {}

    public function handle(Request $request): Response
    {
        $id = UUID::random();

        try{
            $articleId = new UUID($request->jsonBodyField('articleId'));
            $userId = new UUID($request->jsonBodyField('userId'));
        }catch (HttpException|InvalidArgumentException $e){
            return new ErorrResponse($e->getMessage());
        }

        try{
            $article = $this->articlesRepository->get($articleId);
        }catch (ArticleNotFoundException $exception){
            return new ErorrResponse("No article created");
        }

        try{
            $user = $this->usersRepository->get($userId);
        }catch (UserNotFoundException $exception){
            return new ErorrResponse("No user created");
        }

        /*$articleLikes = $this->likesRepository->getAllByArticle($articleId);
        print_r($articleLikes);*/

        if(!$this->LikeExsist($article, $user)){
            return new ErorrResponse("Post already like by you");
        }

        $this->likesRepository->save(new Like(
            $id,
            $articleId,
            $userId
        ));

        return new SuccessResponse([
            'message'=> "Like successfully saved $id."
        ]);
    }

    private function LikeExsist(Article $article, User $user):bool{
        try {
            $articleLikes = $this->likesRepository->getAllByArticle($article->getId());
        }catch (LikeNotFoundException $exception){
            return true;
        }

        foreach ($articleLikes as $like){
            if($like->getUser() == $user->getId()){
                return false;
            }
        }
        return true;
    }

}
