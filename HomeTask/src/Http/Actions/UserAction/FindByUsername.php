<?php

namespace GB\HomeTask\Http\Actions\UserAction;

use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class FindByUsername implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {}
    // Функция, описанная в контракте
    public function handle(Request $request): Response
    {
        $this->logger->info("Started finding user by username");
        try {
    // Пытаемся получить искомое имя пользователя из запроса
            $username = $request->query('username');
        } catch (HttpException $e) {
    // Если в запросе нет параметра username -
    // возвращаем неуспешный ответ,
    // сообщение об ошибке берём из описания исключения
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }
        try {
    // Пытаемся найти пользователя в репозитории
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
    // Если пользователь не найден -
    // возвращаем неуспешный ответ
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }
    // Возвращаем успешный ответ
        return new SuccessResponse([
            'username' => $user->getUsername(),
            'name' => $user->getName()->getFirstName() . ' ' . $user->getName()->getLastName(),
        ]);
    }

}
