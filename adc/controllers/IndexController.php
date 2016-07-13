<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;


class IndexController extends Controller
{
    public $layout="menu.php";
    public $enableCsrfValidation = false;
    public function actionLsts(){
        //查询他是否有登录
        $session = \Yii::$app->session;
        $session->open();
        $re=$session->get('uname');
        if($re){
            return  $this->render("lsts");
        }else{
            return $this->redirect("index.php?r=login/index");
        }

    }
    //IP展示
    public function actionIp(){
        $arr=\Yii::$app->db->createCommand("select * from we_ip")->queryAll();
        return $this->render("ip",['arr'=>$arr]);
    }
    //ip删除
    public function actionDel(){
        $id=\Yii::$app->request->get("id");
        $arr=\Yii::$app->db->createCommand()->delete("we_ip",['iid'=>$id])->execute();
        if($arr){
            return $this->redirect("index.php?r=index/ip");
        }else{
            echo "删除失败";
        }
    }
    public function actionRemove(){
        $session = Yii::$app->session;
        $session->open();
        $session->remove('uname');
        return $this->redirect("index.php?r=index/lsts");
    }
    //ip展示
    public function actionAdd(){
        if(empty($_POST)){
            return $this->render("insert");
        }else{
            $session = \Yii::$app->session;
            $session->open();
            $re=$session->get('uname');
            $ip=\Yii::$app->request->post("ip");
            $res=\Yii::$app->db->createCommand("select * from we_ip where iip='$ip' and iuser='$re'")->queryAll();
            if($res){
                echo 1;
            }else{
                $arr=\Yii::$app->db->createCommand()->insert("we_ip",['iip'=>$ip,"iuser"=>$re])->execute();
                if($arr){
                    echo 2;
                }
            }

        }
    }
}
