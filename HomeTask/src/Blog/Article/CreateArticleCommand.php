<?php

namespace GB\HomeTask\Blog\Article;

use GB\HomeTask\Common\Arguments;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArgumentsException;
use GB\HomeTask\Exceptions\CommandException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticleCommand
{
    private ArticlesRepositoryInterface $usersRepository;
    private LoggerInterface $logger;

    public function __construct(ArticlesRepositoryInterface $usersRepository, LoggerInterface $logger) {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ArgumentsException
     */
    public function handle(Arguments $arguments, UUID $authorId):void{
        $this->logger->info("started created new Article by command line");
        $id = UUID::random();
        $title = $arguments->getArg('title');
        $text = $arguments->getArg('text');

        $this->usersRepository->save(new Article($id, $authorId, $title,$text));
    }

}
