<?php

declare(strict_types=1);

namespace app\models\Users;

use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['email', 'phone', 'last_name', 'first_name', 'middle_name', 'document'], 'safe'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'document', $this->document]);

        return $dataProvider;
    }
}
