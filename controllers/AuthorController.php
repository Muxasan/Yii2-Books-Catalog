<?php
namespace app\controllers;

use Yii;
use app\models\Author;
use app\models\ReportForm;
use app\models\Subscription;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;

class AuthorController extends Controller
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
                        'roles' => ['@'], // Только авторизованные пользователи могут создавать, редактировать и удалять авторов
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
            'query' => Author::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return string
     */
    public function actionView(int $id): string
    {
        $author = $this->findModel($id);
        $books = $author->getBooks()->all();

        $subscriptionModel = new Subscription();
        $isGuest = Yii::$app->user->isGuest;
        $isSubscribed = !$isGuest && Subscription::find()->where(['user_id' => Yii::$app->user->id, 'author_id' => $author->id])->exists();

        return $this->render('view', [
            'author' => $author,
            'books' => $books,
            'subscriptionModel' => $subscriptionModel,
            'isGuest' => $isGuest,
            'isSubscribed' => $isSubscribed,
        ]);
    }

    /**
     * @param integer $id
     * @return Response
     */
    public function actionSubscribe(int $id): Response
    {
        $subscriptionModel = new Subscription();
        $subscriptionModel->author_id = $id;

        if (!Yii::$app->user->isGuest) {
            $subscriptionModel->user_id = Yii::$app->user->id;
        }

        if ($subscriptionModel->load(Yii::$app->request->post()) && $subscriptionModel->validate()) {
            $subscriptionModel->save();
            Yii::$app->session->setFlash('success', 'You have successfully subscribed!');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to subscribe. Please check your phone number.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @return Response|string
     */
    public function actionCreate()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
    
        return $this->redirect(['list']);
    }

    /**
     * @param int $id
     * @return Author
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Author
    {
        if (($model = Author::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Report top 10 authors by year
     *
     * @param ?int $year
     * @return string
     */
    public function actionReport(?int $year = null): string
    {
        $reportModel = new ReportForm();

        if ($reportModel->load(Yii::$app->request->get()) && $reportModel->validate()) {
            $year = $reportModel->year;
        } else {
            $year = date('Y'); // По умолчанию текущий год
        }

        // SQL-запрос для получения ТОП-10 авторов
        $sql = "
            SELECT a.id, a.full_name, COUNT(b.id) AS book_count
            FROM author a
            JOIN book_author ba ON a.id = ba.author_id
            JOIN book b ON ba.book_id = b.id
            WHERE b.year = :year
            GROUP BY a.id, a.full_name
            ORDER BY book_count DESC
            LIMIT 10
        ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':year' => $year],
            'totalCount' => 10,
            'pagination' => false,
        ]);

        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'year' => $year,
            'reportModel' => $reportModel
        ]);
    }
}
