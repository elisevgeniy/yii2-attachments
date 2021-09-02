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

        var directsend = function(url, data, method) {
            if (method == null) method = 'POST';
            if (data == null) data = {};

            var form = $('<form>').attr({
                method: method,
                action: url
            }).css({
                display: 'none'
            });

            var addData = function(name, data) {
                if ($.isArray(data)) {
                    for (var i = 0; i < data.length; i++) {
                        var value = data[i];
                        addData(name + '[]', value);
                    }
                } else if (typeof data === 'object') {
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            addData(name + '[' + key + ']', data[key]);
                        }
                    }
                } else if (data != null) {
                    form.append($('<input>').attr({
                    type: 'hidden',
                    name: String(name),
                    value: String(data)
                    }));
                }
            };

            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    addData(key, data[key]);
                }
            }

            return form.appendTo('body');
        }


        $(".delete-multiple-button").click(function(e){
            e.preventDefault();
            var items = [];
            var url = $(this).attr('href');
            var postdata = [];
            $('.file-manager-content-body').find('input:checkbox:checked').each(function(){
                postdata.push($(this).data("id"));
                items.push($(this).closest('.file-manager-item'));
            });

            if (confirm("$confirm")) {
                $.ajax({
                    method: "POST",
                    url: url,
                    data: {ids: postdata},
                    success: function(data) {
                        if (data) {
                            $.each( items, function( index, item ){
                                $(item).fadeOut('slow', function() { $(this).remove('slow'); });
                            });
                        }
                    }
                });
            }
        });

        $(".delete-button").click(function(e){
            e.preventDefault();
            var item = this.closest('.file-manager-item');
            var url = $(this).attr('href');
            if (confirm("$confirm")) {
                $.ajax({
                    method: "POST",
                    url: url,
                    success: function(data) {
                        if (data) {
                            $(item).fadeOut('slow', function() { $(this).remove('slow'); });
                        }
                    }
                });
            }
        });

        $(".download-multiple-button").click(function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var postdata = [];
            $('.file-manager-content-body').find('input:checkbox:checked').each(function(){
                postdata.push($(this).data("id"));
            });

            directsend(url, {ids: postdata}, 'POST').submit();
        });

JS;
        $view->registerJs($js);

        return $view->render('@vendor/smateu/yii2-attachments/src/views/managerWidget', [
            'module' => $this->module,
            'model' => $this->model,
            'editorMode' => $this->editorMode,
            'listView' => $this->listView,
            'searchLabel' => $this->getModule()->t('attachments', 'Search'),
            'downloadLabel' => $this->getModule()->t('attachments', 'Download'),
            'deleteLabel' => $this->getModule()->t('attachments', 'Delete'),
        ]);
    }
}
