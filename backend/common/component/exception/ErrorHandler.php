<?php
namespace common\component\exception;

/**

* User: tt@gmail.com

* Date: 2020/9/23

* Time: 14:24

*/

use yii;

use yii\base\ErrorHandler as BaseErrorHandler;

use common\component\earlywarning\EarlyWarning;


class ErrorHandler extends BaseErrorHandler

{

    public $errorView = '@app/views/errorHandler/error.php';

    public function renderException($exception)

    {

        if(Yii::$app->request->getIsAjax()){

             exit( json_encode( array('code' =>$exception->getCode(),'msg'  =>$exception->getMessage()) ));

        }else{

            //将500的代码，发送监控预警

            if(!empty($exception->getCode()) && $exception->getCode() ==8){

            $params = [];

            $params['projectName'] = "oct-youban";

            $params['level'] = 5;

            $params['title'] = "500：".$exception->getMessage();

            $params['value'] = $exception->getCode();

            $params['message'] = $exception->getFile()."：".$exception->getLine();

            $params['bizcode'] = 8;

            $params['subcode'] = 8001;

            EarlyWarning::WarninApi($params);

        }
        exit( json_encode( array('code' =>$exception->getCode(),'msg'  =>$exception->getMessage()) ));
      }
    }
}

