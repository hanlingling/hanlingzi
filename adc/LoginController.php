<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;


class LoginController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        return $this->renderPartial("index");
    }
    public function actionLst(){
        $arr=\Yii::$app->request->post();
        $ip_addr = $_SERVER['REMOTE_ADDR'] ;
        $sql="select *  from we_ip where iip='$ip_addr' and `iuser`='$arr[name]'";
        $data=\Yii::$app->db->createCommand($sql)->queryAll();
        if($data){
            $sql="select *  from we_user where uname='$arr[name]' and upwd='$arr[pwd]'";
            $res=\Yii::$app->db->createCommand($sql)->queryAll();
            if($res){
                $session = \Yii::$app->session;
                $session->open();
                $session->set("uname",$arr['name']);
                return $this->redirect("index.php?r=index/lsts");die;
            }else{
                echo "账号或密码错误";
            }
        }else{
            echo "拒绝该IP地址登录";
        }
    }
}