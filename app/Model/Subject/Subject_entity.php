<?php
/**
由ModelEntityFactory自动生成，请勿手动修改
*/
namespace App\Model\Subject;

/*科目表*/
class Subject_entity
{
   public $id;               /*isKey; int(11) unsigned; Null:NO ;Default:null ;Comment:主键*/
   public $collectId;        /*isKey; int(11) unsigned; Null:NO ;Default:null ;Comment:采集id*/
   public $school;           /*isKey; varchar(32); Null:NO ;Default:null ;Comment:院校*/
   public $profession;       /*isKey; varchar(64); Null:NO ;Default:null ;Comment:招生专业(类)*/
   public $professionSub;    /*varchar(255); Null:YES ;Default:null ;Comment:包含专业*/
   public $subject1;         /*isKey; varchar(32); Null:NO ;Default:null ;Comment:首选科目要求*/
   public $subject2;         /*isKey; varchar(64); Null:NO ;Default:null ;Comment:再选科目要求*/
   public $updTime;          /*datetime; Null:NO ;Default:null ;Comment:更新时间*/
}