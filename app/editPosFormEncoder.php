<?php

namespace App;

class editPosFormEncoder {

	/**
	 * Form structure
	 *
		 * @var object
	 **/
	var $form_data;

	/**
	 * Form action
	 *
	 * @var string
	 **/
	var $arrFieldName= array (' ');
	var $arrFieldData= array (' ');
	/**
	 * Constructor
	 *
	 * @param string $form_json
	 * @return void
	 * @access public
	 **/
	public function __construct($form_json, $form_action,$arrFieldName,$arrFieldData)
	{
		$this->form_data = json_decode(str_replace('\\', '', $form_json));
		$this->arrFieldName = $arrFieldName;
		$this->arrFieldData = $arrFieldData;
		$this->action = $form_action;
	}

	/**
	 * Render the form
	 *
	 * @return void
	 * @access public 
	 **/
	public function render_form()
	{
		if( empty($this->form_data) || empty($this->action) ) {
			throw new Exception("Error Processing Request", 1);
		}
		$fields = '';

		foreach ($this->form_data->fields as $field) {
			
			
			// Checkboxes
			if($field->type == 'element-checkboxes') {
				$fields .= $this->element_checkboxes($field);
			}

			// Multiple choice
			if($field->type == 'element-multiple-choice') {
				$fields .= $this->element_multiple_choice($field);
			}

			// Dropdown
			if($field->type == 'element-dropdown') {
				$fields .= $this->element_dropdown($field);
			}

			

		}

		$form = $this->open_form($fields);
		return $form;
	}

	/**
	 * Render the form header
	 *
	 * @param object $fields
	 * @return string $html
	 * @access private 
	 **/
	private function open_form($fields)
	{
		// $html = sprintf('<form action="%s" method="post" accept-charset="utf-8" role="form"  >', $this->action);
		$html = $fields;
		// $html .= '<button type="submit" class="btn btn-primary">Submit</button>';
		// $html .= '</form>';
		return $html;
	}

	/**
	 * Encode element title
	 *
	 * @param string $title
	 * @return string $str
	 * @access private 
	 **/
	private function encode_element_title($title)
	{
		$str = str_replace(' ', '_', strtolower($title));
		$str = preg_replace("/[^a-zA-Z0-9.-_]/", "", $str);
		$str = htmlentities($str, ENT_QUOTES, 'UTF-8');
		$str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

		return $str;
	}

	/**
	 * Get formatted label for form element
	 *
	 * @param string $id
	 * @param string $title
	 * @param mixed $required
	 * @return string
	 * @access private
	 **/
	private function make_label($id, $title, $required)
	{
		if( $required ) {
			$html = sprintf('<label for="%s">%s <span style="color: red">*</span></label>', $id, $title);
		} else {
			$html = sprintf('<label for="%s">%s </label>', $id, $title);
		}

		return $html;
	}

	/**
	 * Render single line text
	 *
	 * @param object $field
	 * @return string $html
	 * @access private 
	 **/
	

	/**
	 * Checkboxes
	 *
	 * @param object $field
	 * @return string $html
	 * @access private
	 **/
	private function element_checkboxes($field)
	{
		error_log('message');
		
		$id = $this->encode_element_title($field->title);
		$required = ($field->required) ? 'required' : FALSE;

		$html = '<div class="form-group">';
		$html .= $this->make_label($id, $field->title, $required);
		
	    // Render choices
		for($i=0; $i < count($field->choices); $i++) {
			// $checked = $field->choices[$i]->checked ? "checked" : '';
			$value = $field->choices[$i]->value;
			$checked = ' ';
	    	foreach ($this->arrFieldData as $FieldValue) {
	    		if ($FieldValue == $value) {
	    			$checked = "checked";
	    			break;
	    		}else{
	    			$checked = ' '; 
	    		}
	    	}
			$html .= '<div class="checkbox"><label>';
			$html .= sprintf('<input type="checkbox" class="flat-red" name="%s[]" id="%s-%d" value="%s" %s> %s', $id, $id, $i, $field->choices[$i]->value, $checked, $field->choices[$i]->title);
			$html .= '</label></div>';
		}

	  	$html .= '</div>';
		//echo "$id<br/>";
	  	return $html;
	}

	/**
	 * Mutliple choice
	 *
	 * @param object $field
	 * @return string $html
	 * @access private
	 **/
	private function element_multiple_choice($field)
	{
		$id = $this->encode_element_title($field->title);
		$required = ($field->required) ? 'required' : FALSE;

		$html = '<div class="form-group">';
		$html .= $this->make_label($id, $field->title, $required);
		
	    // Render choices
		for($i=0; $i < count($field->choices); $i++) {
			// $checked = $field->choices[$i]->checked ? "checked" : '';
			$value = $field->choices[$i]->value;
			$checked = ' ';
	    	foreach ($this->arrFieldData as $FieldValue) {
	    		if ($FieldValue == $value) {
	    			$checked = "checked";
	    			break;
	    		}else{
	    			$checked = ' '; 
	    		}
	    	}
	    	$html .= '<div class="radio">';
			$html .= '<label>';
			$html .= sprintf('<input type="radio" class="flat-red" name="%s" id="%s_%d" value="%s" %s>', $id, $id, $i, $field->choices[$i]->value, $checked);
			$html .= sprintf(' %s',$field->choices[$i]->value);
			$html .= '</label>';
			$html .= '</div>';
		}

	  	$html .= '</div>';
	  	//echo "$id<br/>";
	  	return $html;
	}

	/**
	 * Render dropdown
	 *
	 * @param object $field
	 * @return string $html
	 * @access private
	 **/
	private function element_dropdown($field)
	{
		$id = $this->encode_element_title($field->title);
		$required = ($field->required) ? 'required' : FALSE;

		$html = '<div class="form-group">';
		$html .= $this->make_label($id, $field->title, $required);
	    $html .= sprintf('<select name="%s" id="%s" class="form-control %s">', $id, $id, $required);
	    $html .= "<option selected='true' disabled='disabled'>-Select-</option>";

	    // Render choices
	    foreach ($field->choices as $choice) {
	    	$checked = ' ';
	    	foreach ($this->arrFieldData as $FieldValue) {
	    		if ($FieldValue == $choice->value) {
	    			$checked = "selected";
	    			break;
	    		}else{
	    			$checked = ' '; 
	    		}
	    	}
	    	$html .= sprintf('<option value="%s" %s>%s</option>', $choice->value, $checked, $choice->title);
	    }

	  	$html .= '</select></div>';
	  	return $html;
	  	echo "$html";
	}

} // End formLoader.php