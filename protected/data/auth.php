<?php
return array (
  'admin' => 
  array (
    'type' => 2,
    'description' => 'admin user.',
    'bizRule' => 'return Yii::app()->user->role === "admin";',
    'data' => NULL,
  ),
  'user' => 
  array (
    'type' => 2,
    'description' => 'user user.',
    'bizRule' => 'return Yii::app()->user->role === "user";',
    'data' => NULL,
  ),
);
