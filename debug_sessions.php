<?php
// debug_sessions.php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;

$activeExercise = Exercise::findOne(['active' => true]);

if ($activeExercise) {
    echo "Active Exercise ID: " . $activeExercise->id . " (Year: " . $activeExercise->year . ")\n";
    $sessions = Session::find()->where(['exercise_id' => $activeExercise->id])->orderBy('date ASC')->all();
    echo "Number of sessions: " . count($sessions) . "\n";
    foreach ($sessions as $s) {
        echo " - Session ID: " . $s->id . " | Date: " . $s->date . " | Active: " . ($s->active ? 'Yes' : 'No') . "\n";
    }
} else {
    echo "No active exercise found.\n";
}
