<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
    	// Set the method for the display form to POST
    	$this->setMethod('post');
    	$this->setName('user');
    	 
    	$id = new Zend_Form_Element_Hidden('iduser');
    	$id->addFilter('Int');
    	 
    	$display_name = new Zend_Form_Element_Text('display_name');
    	$display_name->setLabel('Display Name')
	    	->setRequired(true)
	    	->addValidator('NotEmpty', true)
	    	->addFilter('StripTags')
	    	->addFilter('StringTrim')
	    	->addValidator('StringLength',false,array(3,200))
	    	->setAttrib('size', 30)
	    	->setAttrib('placeholder', "Nombre")
	    	->setAttrib('maxlength', 255);
    	
    	$genre = new Zend_Form_Element_Radio('genre');
    	$genre->setLabel('Genre')
	    	->setMultiOptions(array('1'=>'Female', '0'=>'Male'))
	    	->setRequired(true)
	    	->addValidator('NotEmpty', true);
    	 
    	$email = new Zend_Form_Element_Text('email');
    	$email->setLabel('Email')
    	->setRequired(true)
    	->addValidator('NotEmpty', true)
    	->addFilter('StripTags')
    	->addFilter('StringTrim')
    	->addValidator('StringLength',false,array(3,200))
    	->addValidator('emailAddress', true)
    	->setAttrib('size', 30)
    	->setAttrib('placeholder', "mail@mail.com")
    	->addValidator(
    			'Db_NoRecordExists',
    			FALSE,
    			array(
    					'table' => 'users',
    					'field' => 'iduser',
    			)
    	)
    	->setAttrib('maxlength', 80)
    	//->setDecorators(array(array('ViewScript', array('viewScript' => 'forms/_elemet_text.phtml'))))
    	;
    	 
    	$password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
        	->setRequired(TRUE)
       		->addValidator('NotEmpty', TRUE)
         	->addFilter('StripTags')
         	->addFilter('StringTrim')
         	->addValidator('StringLength', FALSE, array(3, 20))
         	->setAttrib('size', 30)
        	->setAttrib('maxlength', 80)
        	->setAttrib('placeholder', 'Password');

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Repeat password')
        	->setRequired(TRUE)
        	->addValidator('NotEmpty', TRUE)
        	->addFilter('StripTags')
        	->addFilter('StringTrim')
        	->addValidator('StringLength', FALSE, array(3, 20))
        	->addValidator('Identical', FALSE, array('token' => 'password'))
        	->setAttrib('size', 30)
         	->setAttrib('maxlength', 80)
         	->setAttrib('placeholder', 'Repeat password');
	    	
    	$description = new Zend_Form_Element_Textarea('description');
    	$description->setAttrib('rows', '4');
		$description->setAttrib('cols', '30');
		$description->setLabel('Description')
	    	->setRequired(false)
	    	->addFilter('StripTags')
	    	->addFilter('StringTrim')
	    	->addValidator('StringLength',false,array(3,200))
	    	->setAttrib('size', 50)
	    	->setAttrib('placeholder', "Nombre")
	    	->setAttrib('maxlength', 500);
		
		$nif = new Zend_Form_Element_Text('nif');
		$nif->setLabel('NIF')
			->setRequired(true)
			->addValidator('NotEmpty', true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('StringLength',false,array(9,9))
			->setAttrib('size', 30)
			->setAttrib('placeholder', "12345678A")
			->addValidator(
				'Db_NoRecordExists',
				FALSE,
				array(
					'table' => 'users',
					'field' => 'iduser',
				)
			)
			->setAttrib('maxlength', 20);
		
		//imagen
		$image = new Zend_Form_Element_File('photo');
		$image->setLabel('Image (512kb size, jpg,png,gif)')
			->addValidator('NotEmpty', true)
			->setDestination('img')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotExists', false, array('img'))
			->addValidator('Count', false, array(1))
			->addValidator('Extension', true, array('jpg,png,gif'))
			->addValidator('Size', false, array(512000));
		
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->setLabel('Status')
			->setRequired(true)
			->addValidator('NotEmpty', true)
			->setmultiOptions(array('1'=>'Activo', '0'=>'Inactivo'))
			->setAttrib('maxlength', 200)
			->setAttrib('size', 1);
			
		//token
		$token = new Zend_Form_Element_Hash('token');
        $token->setSalt('register-user-form');
		
		//captcha
		$captcha = new Zend_Form_Element_Captcha(
			'foo', array(
				'label' => "Please verify you're a human",
				'captcha' => array(
					'captcha' => 'Figlet',
					'wordLen' => 6,
					'timeout' => 300,
				),
			)
		);
		
		$idusertype = new Zend_Form_Element_Select('idusertype');
		$idusertype->setLabel('User Type')
			->setRequired(true)
			->addValidator('NotEmpty', true)
			->setmultiOptions($this->_selectOptions())
			->setAttrib('maxlength', 200)
			->setAttrib('size', 1);
		   	 
    	$submit = new Zend_Form_Element_Submit('submit');
    	$submit->setAttrib('id', 'submitbutton');
    	 
    	$this->addElements(array(
    				$id,
    				$display_name, 
    				$genre,
    				$email, 
    				$password,
    				$password2,
    				$description, 
    				$nif,
    				$image, 
    				$status, 
    				$token, 
    				$captcha, 
    				$idusertype,
    				$submit
    	));
    }
    protected function _selectOptions()
    {
    	//$result = array(1=>'Cineasta', 2=>'Productora', 3=>'Jurado');
    	//return $result;
    	$result = array();
    	$usertype = new Application_Model_UserTypeMapper();
    	$result=$usertype->fetchAll();
    	 
    	 
    	return $result;
    }
    
}
