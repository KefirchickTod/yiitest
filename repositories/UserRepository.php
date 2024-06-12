<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Users\User;
use app\models\Users\UserRepositoryInterface;
use app\models\Users\UserSearch;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers(UserSearch $searchModel): array
    {
        $query = User::find();

        if ($searchModel->load(\Yii::$app->request->queryParams) && $searchModel->validate()) {
            $query->andFilterWhere(['like', 'email', $searchModel->email])
                ->andFilterWhere(['like', 'phone', $searchModel->phone])
                ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
                ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
                ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name]);
        }

        return $query->all();
    }

    public function getUserById(int $id): ?User
    {
        return User::findOne($id);
    }

    public function saveUser(User $user): bool
    {
        return $user->save();
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function update(User $user): void
    {
        $user->update(false);
    }

    public function deleteUser(User $user): false|int
    {
        if ($user->document) {
            $filePath = \Yii::getAlias('@webroot') . '/' . $user->document;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        return $user->delete();
    }

    public function search(UserSearch $searchModel): ActiveDataProvider
    {
        $query = User::find();

        if ($searchModel->load(\Yii::$app->request->queryParams) && $searchModel->validate()) {
            $query->andFilterWhere(['like', 'email', $searchModel->email])
                ->andFilterWhere(['like', 'phone', $searchModel->phone])
                ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
                ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
                ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name]);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
