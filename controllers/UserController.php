<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Users\User;
use app\models\Users\UserSearch;
use app\repositories\UserRepository;
use app\services\FileUploadService;
use Yii;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class UserController extends Controller
{
    private readonly UserRepository $userRepository;
    private readonly FileUploadService $fileUploadService;

    public function __construct(
        string $id,
        Module $module,
        UserRepository $userRepository,
        FileUploadService $fileUploadService,
        array $config = []
    ) {
        $this->userRepository = $userRepository;
        $this->fileUploadService = $fileUploadService;

        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $searchModel = new UserSearch();
        $dataProvider = $this->userRepository->search($searchModel);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): Response|string
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $model->documentFile = UploadedFile::getInstance($model, 'documentFile');
            if (!$model->validate()) {
                return $this->asJson($model->getErrors());
            }

            if (!$this->userRepository->saveUser($model)) {
                Yii::$app->session->setFlash('error', 'Failed to save user');

                return $this->redirect('index');
            }

            if ($model->documentFile) {
                $filePath = $this->fileUploadService->uploadFile($model->documentFile, 'uploads');
                if ($filePath) {
                    $model->document = $filePath;
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to upload the document.');
                }
            }

            $this->userRepository->update($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete(string $id): Response
    {
        $model = $this->userRepository->getUserById((int)$id);

        if ($model !== null) {
            $this->fileUploadService->deleteFile($model->document);
            $this->userRepository->deleteUser($model);
        }

        return $this->redirect(['index']);
    }

    public function actionDownload(string $id): Response
    {
        $model = $this->userRepository->getUserById((int)$id);
        $filePath = Yii::getAlias('@webroot') . '/' . $model->document;

        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        }

        throw new NotFoundHttpException('The requested file does not exist.');
    }

    protected function findModel($id): ?User
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
