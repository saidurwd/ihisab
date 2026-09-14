<?php

$file = Yii::app()->basePath . '/../uploads/documents/' . $model->doc_file;
if ((is_file($file)) && (file_exists($file))) {
    $content = file_get_contents($file);
} else {
    Yii::app()->user->setFlash('error', "The file <strong>" . $model->title . "</strong> does not exist");
    $this->redirect(array('/prizebond/form'));
}
header('Content-Description: File Transfer');
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' . basename($model->doc_file) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header("Content-Length: " . filesize($file));
ob_clean();
flush();
echo $content;
exit;
?>
