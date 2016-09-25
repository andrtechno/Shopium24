<?php

/**
 * HandleColumn class file.
 *
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @uses CGridColumn
 * @package widgets.adminList.columns
 */
Yii::import('zii.widgets.grid.CGridColumn');

class TelColumn extends CGridColumn {

    public $name;
    public $value;
    public $type = 'html';

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $htmlOptions = array('class' => 'tel-column');

    /**
     * @var array the HTML options for the header cell tag.
     */
    public $headerHtmlOptions = array('class' => 'tel-column');

    /**
     * @var array the HTML options for the footer cell tag.
     */
    public $footerHtmlOptions = array('class' => 'tel-column');

    /**
     * @var array the HTML options for the checkboxes.
     */
    public $checkBoxHtmlOptions = array();

    protected function renderHeaderCellContent() {
        if ($this->grid->enableSorting && $this->name !== null)
            echo $this->grid->dataProvider->getSort()->link($this->name, $this->header, array('class' => 'sort-link'));
        elseif ($this->name !== null && $this->header === null) {
            if ($this->grid->dataProvider instanceof ActiveDataProvider)
                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
            else
                echo CHtml::encode($this->name);
        } else
            parent::renderHeaderCellContent();
    }

    protected function renderDataCellContent($row, $data) {
        if ($this->value !== null)
            $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
        elseif ($this->name !== null)
            $value = CHtml::value($data, $this->name);
        echo Html::link($value, 'tel:' . $value);
    }

}
