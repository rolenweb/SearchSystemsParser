<?php

namespace app\controllers;

use Yii;
use app\models\GoogleAnalysis;
use app\models\GoogleAnalysisSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Sort;

/**
 * GoogleAnalysisController implements the CRUD actions for GoogleAnalysis model.
 */
class GoogleAnalysisController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all GoogleAnalysis models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoogleAnalysisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStats()
    {
        $sort = new Sort([
            'attributes' => [
                'countDomain' =>[
                    'label' => 'Count'
                ],
                'domain.creation_date' => [
                    'label' => 'Created',
                ],
                
            ],
            'defaultOrder' => [
                'countDomain' => SORT_DESC,
            ]
        ]);

        $links = GoogleAnalysis::find()
            ->joinWith(['domain','keyword'])
            ->select(['COUNT({{domain}}.id) AS countDomain','{{google_analysis}}.domain_id','{{google_analysis}}.keyword_id'])
            ->groupBy('{{domain}}.id')
            ->orderBy($sort->orders)
            ->limit(100)
            ->all();

        return $this->render('stats', [
            'links' => $links,
            'sort' => $sort,
            
        ]);
    }

    /**
     * Displays a single GoogleAnalysis model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GoogleAnalysis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GoogleAnalysis();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GoogleAnalysis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GoogleAnalysis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GoogleAnalysis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoogleAnalysis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoogleAnalysis::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
