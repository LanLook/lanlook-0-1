<?php

/**
 * SearchForm class.
 * SearchForm is the data structure for keeping
 * search form data. It is used by the 'search' action of 'SiteController'.
 */
class SearchForm extends CFormModel
{
	public $categories;
	public $itemID;
	public $itemName;
	public $body;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('categories, itemID, itemName', 'required', 'on'=>'search'),
			// email has to be a valid email address
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
				'categories'=>'Categories',
				'itemID'=>'ID Number',
				'itemName'=>'Item Name',
				'body'=>'Body',
		);
	}
}