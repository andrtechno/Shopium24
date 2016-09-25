<?php
class StripTagsValidator extends CValidator {

	/**
	 * The attributes boud in the unique contstraint with attribute
	 * @var string
	 */
	public $tags=array();

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute) {
            $strip = strip_tags($object->$attribute);
           
            $object->$attribute = $strip;
  
	}

}