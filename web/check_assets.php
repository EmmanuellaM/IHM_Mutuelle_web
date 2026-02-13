<?php
// Diagnostic script to check Yii Asset Manager
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
$app = new yii\web\Application($config);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Asset Manager Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .ok { color: green; }
        .error { color: red; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Asset Manager Diagnostic</h1>
    
    <div class="section">
        <h2>Asset Manager Configuration</h2>
        <pre><?php
        $assetManager = Yii::$app->assetManager;
        echo "Base Path: " . $assetManager->basePath . "\n";
        echo "Base URL: " . $assetManager->baseUrl . "\n";
        echo "Assets directory exists: " . (is_dir($assetManager->basePath) ? 'YES' : 'NO') . "\n";
        echo "Assets directory writable: " . (is_writable($assetManager->basePath) ? 'YES' : 'NO') . "\n";
        ?></pre>
    </div>
    
    <div class="section">
        <h2>AppAsset Bundle</h2>
        <pre><?php
        try {
            $bundle = \app\assets\AppAsset::register(Yii::$app->view);
            echo "Bundle registered successfully\n\n";
            echo "CSS Files:\n";
            foreach ($bundle->css as $css) {
                $cssPath = is_array($css) ? $css[0] : $css;
                echo "  - $cssPath\n";
            }
            echo "\nJS Files:\n";
            foreach ($bundle->js as $js) {
                echo "  - $js\n";
            }
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage();
        }
        ?></pre>
    </div>
    
    <div class="section">
        <h2>Generated HTML Head</h2>
        <pre><?php
        Yii::$app->view->beginPage();
        Yii::$app->view->head();
        $head = ob_get_clean();
        echo htmlspecialchars($head);
        ?></pre>
    </div>
    
    <div class="section">
        <h2>Check if CSS loads in browser</h2>
        <p>The following should have colored backgrounds if CSS is loading:</p>
        <?php
        \app\assets\AppAsset::register(Yii::$app->view);
        Yii::$app->view->beginPage();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <?php Yii::$app->view->head() ?>
        </head>
        <body>
            <div class="alert alert-success">If this has a green background, Bootstrap CSS is loading!</div>
            <div class="btn btn-primary">If this is a blue button, Bootstrap CSS is working!</div>
        </body>
        </html>
        <?php Yii::$app->view->endPage(); ?>
    </div>
</body>
</html>
