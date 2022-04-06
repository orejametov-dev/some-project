<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Exceptions\NotFoundException;
use App\HttpRepositories\Auth\AuthHttpRepository;
use App\HttpRepositories\HttpResponses\Auth\AuthHttpResponse;

class FindAuthUserByIdUseCase
{
    public function __construct(
        private AuthHttpRepository $authHttpRepository
    ) {
    }

    public function execute(int $id): AuthHttpResponse
    {
        $user = $this->authHttpRepository->getUserById($id);
        if ($user === null) {
            throw new NotFoundException('Пользователь не найден');
        }

        return $user;
    }
}
