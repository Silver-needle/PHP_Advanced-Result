<?php

namespace GB\HomeTask\Commands\PopulateDB;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use GB\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    // Внедряем генератор тестовых данных и
// репозитории пользователей и статей
    public function __construct(
        private \Faker\Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private ArticlesRepositoryInterface $articlesRepository,
        private CommentsRepositiryInterface $commentsRepositiry
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption('users-number', 'u', InputOption::VALUE_OPTIONAL, 'number of test-users will be created')
            ->addOption('articles-number', 'a', InputOption::VALUE_OPTIONAL, 'number of test-articles will be created');
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        // Создаём десять пользователей
        $users = [];
        $posts = [];
        $users_count = empty($input->getOption('users-number')) ? 2 : $input->getOption('users-number');
        $articles_count = empty($input->getOption('articles-number')) ? 2 : $input->getOption('articles-number');
        echo $users_count.PHP_EOL.$articles_count;
        for ($i = 0; $i < $users_count; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }
        print_r($users);
        // От имени каждого пользователя
        // создаём по двадцать статей
        foreach ($users as $user){
            for ($i = 0; $i < $articles_count; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->getTitle());
}
        }
        foreach ($posts as $post){
            $rand_number = rand(0,$users_count-1);

            $rand_user = $users[$rand_number];

            $comment = $this->createFakeComment($rand_user, $post);
            $output->writeln('Comment created: ' . $comment->getText());
        }
        return Command::SUCCESS;
    }
    private function createFakeUser(): User
    {
        $user = User::createFrom(
            // Генерируем имя пользователя
            $this->faker->userName,
            // Генерируем пароль
            $this->faker->password,
            new Name(
                // Генерируем имя
                $this->faker->firstName,
                // Генерируем фамилию
                $this->faker->lastName
            )
        );
        // Сохраняем пользователя в репозиторий
        $this->usersRepository->save($user);
        return $user;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function createFakePost(User $author): Article
    {
        $post = new Article(
            UUID::random(),
            $author->getId(),
        // Генерируем предложение не длиннее шести слов
            $this->faker->sentence(6, true),
        // Генерируем текст
            $this->faker->realText
        );
        // Сохраняем статью в репозиторий
        $this->articlesRepository->save($post);
        return $post;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function createFakeComment(User $author, Article $article): Comment{
        $comment = new Comment(
            UUID::random(),
            $article->getId(),
            $author->getId(),
            $this->faker->sentence(rand(1,50),true)
        );

        $this->commentsRepositiry->save($comment);
        return $comment;
    }

}
