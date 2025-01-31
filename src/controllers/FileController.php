<?php

namespace nemmo\attachments\controllers;

use nemmo\attachments\models\File;
use nemmo\attachments\models\UploadForm;
use nemmo\attachments\ModuleTrait;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class FileController extends Controller
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'download-multiple') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionUpload()
    {
        $model = new UploadForm();
        $model->file = UploadedFile::getInstances($model, 'file');

        if ($model->rules()[0]['maxFiles'] == 1 && sizeof($model->file) == 1) {
            $model->file = $model->file[0];
        }

        //check max-post_data
        if(intval($_SERVER['CONTENT_LENGTH'])>0 && count($_POST)===0){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'error' => $this->getModule()->t('attachments', 'PHP discarded POST data because of request exceeding post_max_size.')
            ];
        }

        if ($model->file && $model->validate()) {
            $result['uploadedFiles'] = [];
            if (is_array($model->file)) {
                foreach ($model->file as $file) {
                    $path = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $file->name;
                    $file->saveAs($path);
                    $result['uploadedFiles'][] = $file->name;
                }
            } else {
                $path = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $model->file->name;
                $model->file->saveAs($path);
                $result['uploadedFiles'][] = $model->file->name;
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'error' => $model->getErrors('file')
            ];
        }
    }

    public function actionDownload($id)
    {
        $file = File::findOne(['id' => $id]);
        $filePath = $this->getModule()->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;

        return Yii::$app->response->sendFile($filePath, "$file->name.$file->type");
    }


    public function actionDownloadMultiple()
    {
        $ids = Yii::$app->request->post('ids', []);
        if (is_array($ids)) {

            if (count($ids) == 1) {
                return $this->actionDownload($ids[0]);
            }

            $zip = new \ZipArchive();
            $temp_file = tempnam(sys_get_temp_dir(), 'zipped');
            if ($zip->open($temp_file, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Cannot create a zip file');
            }

            foreach ($ids as $id) {
                $file = File::findOne(['id' => $id]);
                $filePath = $this->getModule()->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;
                $zip->addFile($filePath, "$file->name.$file->type");
            }
            $zip->close();

            return Yii::$app->response->sendFile($temp_file, "files.zip");
        }
        return false;
    }

    public function actionDelete($id)
    {
        if ($this->getModule()->detachFile($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function actionDeleteMultiple()
    {
        $ids = Yii::$app->request->post('ids', []);
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $this->getModule()->detachFile($id);
            }
            return true;
        }
        return false;
    }

    public function actionDownloadTemp($filename)
    {
        $filePath = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $filename;

        return Yii::$app->response->sendFile($filePath, $filename);
    }

    public function actionDeleteTemp($filename)
    {
        $userTempDir = $this->getModule()->getUserDirPath();
        $filePath = $userTempDir . DIRECTORY_SEPARATOR . $filename;
        unlink($filePath);
        if (!sizeof(FileHelper::findFiles($userTempDir))) {
            rmdir($userTempDir);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [];
    }
}
