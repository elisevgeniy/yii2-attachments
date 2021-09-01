<?php
namespace nemmo\attachments\components;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\bootstrap4\Widget;

use nemmo\attachments\ModuleTrait;
use yii\base\InvalidConfigException;
use nemmo\attachments\behaviors\FileBehavior;
use nemmo\attachments\assets\AttachmentsManagerAsset;

/**
 * Created by Sergi Mateu.
 */
class AttachmentsManager extends Widget
{
    use ModuleTrait;

    /** @var ActiveRecord */
    public $model;

    public $editorMode = true;
    public $listView = false;

    public function init()
    {
        parent::init();

        if (empty($this->model)) {
            throw new InvalidConfigException("Property {model} cannot be blank");
        }

        $hasFileBehavior = false;
        foreach ($this->model->getBehaviors() as $behavior) {
            if (is_a($behavior, FileBehavior::class)) {
                $hasFileBehavior = true;
            }
        }
        if (!$hasFileBehavior) {
            throw new InvalidConfigException("The behavior {FileBehavior} has not been attached to the model.");
        }
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     * @throws \ReflectionException
     */
    public function run()
    {
        $view = $this->getView();

        //register assets
        AttachmentsManagerAsset::register($view);

        //delete button
        $confirm = Yii::t('yii', 'Are you sure you want to delete this item?');
        $js = <<<JS
        $(".delete-button").click(function(){
            var tr = this.closest('tr');
            var url = $(this).data('url');
            if (confirm("$confirm")) {
                $.ajax({
                    method: "POST",
                    url: url,
                    success: function(data) {
                        if (data) {
                            tr.remove();
                        }
                    }
                });
            }
        });
JS;
        $view->registerJs($js);

        return $view->render('@vendor/smateu/yii2-attachments/src/views/managerWidget', [
            'model' => $this->model,
            'editorMode' => $this->editorMode,
            'listView' => $this->listView,
        ]);
    }
}
