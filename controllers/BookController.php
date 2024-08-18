<?php
namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\Subscription;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class BookController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Только авторизованные пользователи могут создавать, редактировать и удалять книги
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionList(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find()->with('authors'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return Response|string
     */
    public function actionCreate()
    {
        $model = new Book();

        if ($model->load(Yii::$app->request->post())) {
            $model->coverImageFile = UploadedFile::getInstance($model, 'coverImageFile');
            if ($model->uploadCoverImage() && $model->save()) {

                // Получаем всех подписчиков авторов данной книги
                $authors = $model->getAuthors();
                $subscribers = Subscription::find()
                    ->where(['author_id' => $model->authorIds])
                    ->all();

                // Отправляем уведомления каждому подписчику
                foreach ($subscribers as $subscription) {

                    if (!empty($subscription->phone)) {
                        $author = $authors->where(['id' => $subscription->author_id])->one();
                        $message = "New book \"{$model->title}\" by your subscribed author \"{$author->full_name}\" has been added!";
                        Yii::$app->smsService->sendSms($subscription->phone, $message);
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response|string
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->coverImageFile = UploadedFile::getInstance($model, 'coverImageFile');
            if ($model->uploadCoverImage() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['list']);
    }

    /**
     * Subscribe to author
     * 
     * @param int $authorId
     * @return Response
     */
    public function actionSubscribe(int $authorId): Response
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $subscription = new Subscription();
        $subscription->user_id = Yii::$app->user->id;
        $subscription->author_id = $authorId;

        if ($subscription->save()) {
            Yii::$app->session->setFlash('success', 'You have subscribed to the author.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to subscribe.');
        }

        return $this->redirect(['author/view', 'id' => $authorId]);
    }

    /**
     * @param int $id
     * @return Book
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Book
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
