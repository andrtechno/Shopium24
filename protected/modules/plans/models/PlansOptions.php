<?php

class PlansOptions extends ActiveRecord {

    const MODULE_ID = 'plans';

    public function getGridColumns() {
        return array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/admin/plans/options/update", "id"=>$data->id))',
            ),
            array(
                'name' => 'group_id',
                'type' => 'raw',
                'filter' => Html::listData(PlansOptionsGroups::model()->findAll(), 'id', 'name'),
                'value' => 'Html::link(Html::encode($data->group->name), array("/admin/plans/groups/update", "id"=>$data->group->id))',
                'htmlOptions' => array('class' => 'text-center')
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'ext.sortable.SortableColumn')
            ),
        );
    }

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        return array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => Yii::t('admin', 'Общая '),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                        ),
                        'group_id' => array(
                            'type' => 'dropdownlist',
                            'items' => CHtml::listData(PlansOptionsGroups::model()->findAll(), 'id', 'name')
                        ),
                        'hint' => array(
                            'type' => 'textarea',
                        ),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'SAVE')
                )
            )
        );
    }

    public function relations() {
        return array(
            'group' => array(self::BELONGS_TO, 'PlansOptionsGroups', array('group_id' => 'id')),
            'rels' => array(self::HAS_MANY, 'PlansOptionsRel', 'option_id', 'order' => '`rels`.`plan_id` ASC')
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{plans_options}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name, group_id', 'required'),
            array('hint', 'type', 'type' => 'string'),
            array('id, name, hint,ordern', 'safe', 'on' => 'search'),
        );
    }

    public static function getCSort() {
        $sort = new CSort;
        $sort->defaultOrder = 'ordern DESC';


        return $sort;
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->order = 'ordern DESC';
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('hint', $this->hint, true);
     //   $criteria->compare('ordern', $this->ordern);
        $criteria->compare('group_id', $this->group_id);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => self::getCSort()
        ));
    }

}
