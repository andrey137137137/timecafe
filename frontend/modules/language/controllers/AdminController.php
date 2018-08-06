<?php

namespace frontend\modules\language\controllers;

use yii\web\Controller;
use Yii;



class AdminController extends Controller
{
    public function actionIndex()
    {
      /*if (Yii::$app->user->isGuest || !Yii::$app->user->can('admin')) {
        throw new \yii\web\ForbiddenHttpException('Просмотр данной страницы запрещен.');
        return false;
      }*/

      $base_lang=Yii::$app->params['defaultLang'];
      $lg_list=Yii::$app->params['lg_list'];
      unset($lg_list[$base_lang]);

      $data=[
        'language_list'=>$lg_list,
        'total'=>['WARNING'=>0,'ERROR'=>0,'NOTICE'=>0]
      ];

      $data['lg']=array();
      foreach ($lg_list as $lg_k => $lg){
        $data['lg'][$lg_k]=array('total'=>['WARNING'=>0,'ERROR'=>0,'NOTICE'=>0]);
      }

      $bp=realpath(Yii::$app->basePath.'/../common/language').'/';
      $file_php_list=scandir($bp.$base_lang);
      foreach ($file_php_list as $file){
        if (in_array($file,array(".",".."))){
          continue;
        }

        $base=require($bp.$base_lang.'/'.$file);
        foreach ($lg_list as $lg_k => $lg){
          $path=$bp.$lg_k.'/'.$file;
          $data['lg'][$lg_k][$file]=['WARNING'=>array(),'ERROR'=>array(),'NOTICE'=>array(),'TYPE'=>'file_php','PATH'=>$path];
          if(!is_readable($path)){
            $data['lg'][$lg_k][$file]['WARNING'][]=['title'=>'Фаил отутствует'];
            continue;
          }
          $transl=require($path);

          foreach ($base as $k => $item){
            if(!isset($transl[$k])){
              $data['lg'][$lg_k][$file]['WARNING'][]=['title'=>'Параметр <b>'.$k.'</b> отутствует'];
              continue;
            }

            if(trim($transl[$k])==$item){
              $data['lg'][$lg_k][$file]['NOTICE'][]=['title'=>'Параметр <b>'.$k.'</b> совпадает с оригинальным языком'];
              continue;
            }

            if(strlen(trim($transl[$k]))<2){
              $data['lg'][$lg_k][$file]['ERROR'][]=['title'=>'Параметр <b>'.$k.'</b> не заполнен'];
              continue;
            }
          }
        }
        $data['lg'][$lg_k]['total']['WARNING']+=count($data['lg'][$lg_k][$file]['WARNING']);
        $data['lg'][$lg_k]['total']['NOTICE']+=count($data['lg'][$lg_k][$file]['NOTICE']);
        $data['lg'][$lg_k]['total']['ERROR']+=count($data['lg'][$lg_k][$file]['ERROR']);
        $data['total']['WARNING']+=count($data['lg'][$lg_k][$file]['WARNING']);
        $data['total']['NOTICE']+=count($data['lg'][$lg_k][$file]['NOTICE']);
        $data['total']['ERROR']+=count($data['lg'][$lg_k][$file]['ERROR']);

      }
/*
      $bp=Yii::$app->basePath.'/web/language/';
      $base=json_decode(file_get_contents($bp.$base_lang.'.json'),true);
      $file='json';
      foreach ($lg_list as $lg_k => $lg){
        $path=$bp.$lg_k.'.json';
        $data['lg'][$lg_k][$file]=['WARNING'=>array(),'ERROR'=>array(),'NOTICE'=>array(),'TYPE'=>'file_json','PATH'=>$path];
        if(!is_readable($path)){
          $data['lg'][$lg_k][$file]['WARNING'][]=['title'=>'Фаил отутствует'];
          continue;
        }
        $transl=json_decode(file_get_contents($path),true);

        foreach ($base as $k => $item){
          if(!isset($transl[$k])){
            $data['lg'][$lg_k][$file]['WARNING'][]=['title'=>'Параметр <b>'.$k.'</b> отутствует'];
            continue;
          }

          if(strlen(trim($transl[$k]))<2){
            $data['lg'][$lg_k][$file]['ERROR'][]=['title'=>'Параметр <b>'.$k.'</b> не заполнен'];
            continue;
          }

          if(trim($transl[$k])==$item){
            $data['lg'][$lg_k][$file]['NOTICE'][]=['title'=>'Параметр <b>'.$k.'</b> совпадает с оригинальным языком'];
            continue;
          }
        }
          $data['lg'][$lg_k]['total']['WARNING']+=count($data['lg'][$lg_k][$file]['WARNING']);
          $data['lg'][$lg_k]['total']['NOTICE']+=count($data['lg'][$lg_k][$file]['NOTICE']);
          $data['lg'][$lg_k]['total']['ERROR']+=count($data['lg'][$lg_k][$file]['ERROR']);
          $data['total']['WARNING']+=count($data['lg'][$lg_k][$file]['WARNING']);
          $data['total']['NOTICE']+=count($data['lg'][$lg_k][$file]['NOTICE']);
          $data['total']['ERROR']+=count($data['lg'][$lg_k][$file]['ERROR']);
      }
*/

      //ddd($data);
      return $this->render('index',$data);
    }
}
