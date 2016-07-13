<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;


class InstallController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        //安装第一个页面，同意协议
        //判断是否安装
        if(is_file("assets/existence.php")){
            $this->redirect("index.php?r=index/lsts");
        }else{
            return $this->renderPartial("one");
        }
    }
    public function actionTwo()
    {
        //操作系统
        $system = php_uname('s');
        $system = isset($system) ? $system : "不支持";
        //php版本
        $php_edition = phpversion();
        $php_detection = substr($php_edition, 0, 3);
        if ($php_detection >= "5.5" || $php_detection <= "5.4") {
            $php_edition = phpversion();
        } else {
            $php_edition = "PHP版本必须在5.4以上";
        }
        //检测是否支持GD库
        $gd = function_exists('gd_info');
        if (get_extension_funcs("gd") != "") {
            $gd = "支持";
        } else {
            $gd = "不支持";
        }
        //判断mysql数据库是否存在
        //检测目录位置
        $path = dirname(dirname(__FILE__));
        //检测文件大小
        $path_max = filesize($path);
        //判断是否pdo操作
        if (get_extension_funcs("PDO") != "") {
            $PDO = "支持";
        } else {
            $PDO = "不支持";
        }
        //判断是否支持curl操作
        if (get_extension_funcs("curl") != "") {
            $curl = "支持";
        } else {
            $curl = "不支持";
        }
        //判断是否支持数据库
        if (get_extension_funcs("mysqli") != "") {
            $mysqli = "支持";
        } else {
            $mysqli = "不支持";
        }
        //判断支持是否支持session
        if (get_extension_funcs("session") != "") {
            $session = "支持";
        } else {
            $session = "不支持";
        }
        $arp = $path . "＼" . "jiac.lo";
        if (mkdir("$arp", 0700)) {
            $privileges = "整目录可写";
            rmdir($arp);
        } else {
            $privileges = "<font color='red'>目录不可写,求修改/目录权限</font>";
        }
        $data = array(
            'php_edition' => $php_edition,
            'system' => $system,
            'jpg' => $gd,
            'png' => $gd,
            'gif' => $gd,
            'path' => $path,
            'mysql' => $mysqli,
            'curl' => $curl,
            'session' => $session,
            'path_max' => $path_max,
            'privilege' => $privileges
        );
        return $this->renderPartial("two", ['arr' => $data]);
    }
    public function actionThree(){
        return $this->renderPartial("three");
    }
    public function actionInstall(){

        $post=\Yii::$app->request->post();
        $host=$post['dbhost'];
        $name=$post['dbname'];
        $pwd=$post['dbpwd'];
        $db=$post['db'];
        $uname=$post['uname'];
        $upwd=$post['upwd'];
        if (@$link= mysql_connect("$host","$name","$pwd")){
            $db_selected = mysql_select_db("$db", $link);
            if($db_selected){
                $sql="drop database ".$post['db'];
                mysql_query($sql);
            }
            $sql="create database ".$post['db'];
            mysql_query($sql);
            $file=file_get_contents('./assets/yan.sql');
            $arr=explode('-- ----------------------------',$file);
            $db_selected = mysql_select_db($post['db'], $link);
            for($i=0;$i<count($arr);$i++){
                if($i%2==0){
                    $a=explode(";",trim($arr[$i]));
                    array_pop($a);
                    foreach($a as $v){
                        mysql_query($v);
                    }
                }
            }
            $str="<?php
					return [
						'class' => 'yii\db\Connection',
						'dsn' => 'mysql:host=".$post['dbhost'].";port=3306;dbname=".$post['db']."',
						'username' => '".$post['dbname']."',
						'password' => '".$post['dbpwd']."',
						'charset' => 'utf8',
						'tablePrefix' => 'we_',   //加入前缀名称we_
					];";
            file_put_contents('../config/db.php',$str);
            $str1="<?php
                \$pdo=new PDO('mysql:host= $host;port=3306;dbname=$db','$name','$pwd',array(PDO::MYSQL_ATTR_INIT_COMMAND=>'set names utf8'));
                   ?>";
            file_put_contents('./assets/abc.php',$str1);
            $sql="insert into we_user (uname,upwd) VALUES ('$uname','$upwd')";
            mysql_query($sql);
            $sql1="insert into we_ip (iip,iuser) VALUES ('127.0.0.1','$uname')";
            mysql_query($sql1);
            mysql_close($link);
            $counter_file       =   'assets/existence.php';//文件名及路径,在当前目录下新建锁
            $fopen                     =   fopen($counter_file,'wb');//新建文件命令
            fputs($fopen,   'aaaaaa ');//向文件中写入内容;
            fclose($fopen);
            /*$strs=str_replace("//'db' => require(__DIR__ . '/db.php'),","'db' => require(__DIR__ . '/db.php'),",file_get_contents("../config/web.php"));
            file_put_contents("../config/web.php",$strs);*/
            $this->redirect("index.php?r=index/lsts");
        }else{
            echo "<script>
                        if(alert('数据库账号或密码错误')){
                             location.href='index.php?r=install/three';
                        }else{
                            location.href='index.php?r=install/three';
                        }
            </script>";
        }
    }
}