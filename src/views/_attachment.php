<?php

/* @var $this yii\web\View */
/* @var $model array */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $key int the key value associated with the data item */
/* @var $index int the zero-based index of the data item in the items array returned by $dataProvider */
/* @var $widget ListView ListView widget instance itself */
/** @var boolean $editorMode */

$classHideEditorMode = !$editorMode? ' hide-editor-mode' : '';
?>

<div class="card file-manager-item file <?=$classHideEditorMode?>">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="customCheck<?=$key?>" />
        <label class="custom-control-label" for="customCheck<?=$key?>"></label>
    </div>
    <div class="card-img-top file-logo-wrapper">
        <div class="dropdown float-right">
            <i data-feather="more-vertical" class="toggle-dropdown mt-n25"></i>
        </div>
        <div class="d-flex align-items-center justify-content-center w-100">
            <img src="/img/file-icons/<?=$model->type?>.png" alt="file-icon" height="35" />
        </div>
    </div>
    <div class="card-body" onclick="window.location='<?=$model->getUrl()?>'">
        <div class="content-wrapper">
            <p class="card-text file-name mb-0"><?=$model->name?></p>
            <p class="card-text file-date mb-0"><?=Yii::$app->formatter->asShortSize($model->size)?></p>
        </div>
        <small class="file-accessed text-muted"><?=Yii::$app->formatter->asShortSize($model->size)?></small>
    </div>
</div>