<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\SetPass;
use common\models\LoginForm;
use common\models\ForgetForm;
use yii\filters\VerbFilter;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','forgot','reset-password','test'],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        
        $this->view->params['breadcrumbs'] = [ 
            [
                'label' => 'Dashboard'
            ]
        ];
        $this->view->params['pagename'] = 'Dashboard';
        
        return $this->render('index');
    }

    public function actionLogin()
    {        
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionForgot()
    {        
        // load post data and send email
        $model = new ForgetForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendForgotEmail()) {
            Yii::$app->session->setFlash("notification", Yii::t("app", "Instructions to reset your password have been sent"));
            return $this->goHome();
        }

        // render
        return $this->render("forgot", [
            "model" => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionResetPassword($token) {        
        if($model = SetPass::findByPasswordResetToken($token)){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash("notification", Yii::t("app", "Your have recovered your password. Now you can login"));
                return $this->goHome();
            }

            return $this->render("setpassword", [
                "model" => $model,
            ]);
        }else{
            Yii::$app->session->setFlash("Home-error", Yii::t("app", "Password Recovery Toekn expired!"));
            return $this->goHome();
        }
        
    }
    
    public function actionTest(){
        $affliate_mapper = new \common\component\AffiliateMapper();
        $affliate_mapper->loadData();
        $affliate_mapper->run();
    }
}
