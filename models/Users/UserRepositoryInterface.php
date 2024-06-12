<?php

declare(strict_types=1);

namespace app\models\Users;

use yii\data\ActiveDataProvider;

interface UserRepositoryInterface
{
    public function getAllUsers(UserSearch $searchModel): array;

    public function update(User $user): void;

    public function getUserById(int $id): ?User;

    public function saveUser(User $user): bool;

    public function deleteUser(User $user): false|int;

    public function search(UserSearch $search): ActiveDataProvider;
}
