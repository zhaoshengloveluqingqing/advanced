<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use backend\service\TestService;
use common\help\tools;
use yii\data\pagination;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //\common\help\tools::uuid();
        $rr = TestService::getUserInfo();
        $rr= \common\models\User::test();
        $query = User::find();

        $pagination = new Pagination([
            'defaultPageSize' => 51,
            'totalCount' => $query->count(),
        ]);

        $list = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
        }
        print_r(Yii::$app->params['adminEmail']);die;
//        print_r(Yii::$app->db->createCommand('select * from user')->queryAll());die;
//        print_r(Yii::$app->request->get('id'));die;
        print_r(( new \yii\db\Query)->select('u.id')->from('user u')->join('left join','zs_application a','a.uid=u.id')->createCommand()->sql);die;
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
