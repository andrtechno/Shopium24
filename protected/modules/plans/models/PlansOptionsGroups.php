<?php

class PlansOptionsGroups extends ActiveRecord {

    const MODULE_ID = 'plans';

    public function getGridColumns() {
        return array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/admin/plans/groups/update", "id"=>$data->id))',
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
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'name' => array(
                    'type' => 'text',
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    public function relations() {
        return array(
            'options' => array(self::HAS_MANY, 'PlansOptions', 'group_id')
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{plans_options_groups}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
            array('name', 'type', 'type' => 'string'),
            array('id, name, ordern', 'safe', 'on' => 'search'),
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
      //  $criteria->compare('ordern', $this->ordern);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
                //'sort' => self::getCSort()
        ));
    }

}
