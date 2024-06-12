<?php

use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Users\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => SerialColumn::class],

            'id',
            'email',
            'phone',
            'last_name',
            'first_name',
            'middle_name',
            [
                'attribute' => 'document',
                'format' => 'raw',
                'value' => static function ($model): string {
                    return $model->document ?
                        Html::a(
                                'Download Document',
                                ['download', 'id' => $model->id],
                                ['class' => 'btn btn-info']
                        ) :
                        'No Document';
                },
            ],

            ['class' => ActionColumn::class],
        ],
    ]); ?>
</div>
