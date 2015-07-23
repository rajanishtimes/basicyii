<?php
/*
 * Project: CMS TimesCity 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\component;

use Yii;
use common\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Controller is the base class of web/module controllers.
 *
 * @author Mithun Mandal <mithun12000@gmail.com> * 
 */

class AccessController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),  
                'except' => [
                    'autosuggest',
                    'validate',
                    'fetch-venue',
                    'localityzonesuggest'
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
}

