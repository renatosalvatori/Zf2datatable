<?php
namespace Zf2datatable\Examples\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;

class Person extends Form implements InputFilterProviderInterface{
	
	public function __construct($name = null)
    {
        parent::__construct('Person');
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
        		'name' => 'displayName',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'displayName'
        		)
        ));
        
        $this->add(array(
        		'name' => 'familyName',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'familyName'
        		)
        ));
        
        
        
        $this->add(array(
        		'name' => 'givenName',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'givenName'
        		)
        ));
        
        
        $this->add(array(
        		'name' => 'email',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'email'
        		)
        ));
        
        $this->add(array(
                'type'=>'select',
        		'name' => 'gender',
        		'attributes' => array(
        				'type' => 'select'
        		),
        		'options' => array(
        				'label' => 'gender',
            		    'value_options' => array(
            		        '' => 'Select your gender',
            		        'f' => 'Female',
            		        'm' => 'Male'
            		    ),
        		)
        ));
        
        
        $this->add(array(
        		'name' => 'age',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'age'
        		)
        ));
        
        
        $this->add(array(
        		'name' => 'weight',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'weight'
        		)
        ));
        
        
        $this->add(array(
        		'name' => 'birthday',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'birthday'
        		)
        ));
        
        
        $this->add(array(
        		'name' => 'changeDate',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'changeDate'
        		)
        ));
        
        
        $this->add(array(
        		'name' => 'submit',
        		'attributes' => array(
        				'type' => 'submit',
        				'value' => 'Salva',
        				'id' => 'submitbutton'
        		)
        ));
        
        
        $this->add(array(
            'name' => 'cancel',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Cancel',
                'id' => 'submitbutton'
            )
        ));
    }
    
    /**
     * 
     * @return \Zend\InputFilter\InputFilter
     */
    public function getInputFilterSpecification()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory ();
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'displayName',
            'required' => false,)));
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'familyName',
            'required' => false,)));
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'givenName',
            'required' => false,)));
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'email',
            'required' => false,)));
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'gender',
            'required' => false,)));
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'age',
            'required' => false,)));
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'weight',
            'required' => false,)));
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'birthday',
            'required' => false,)));
        
        
        $inputFilter->add ( $factory->createInput ( array (
            'name' => 'changeDate',
            'required' => false,)));
        
        
        
        $this->filter = $inputFilter;
        
        
        return $inputFilter;
    }
    
    
    /**
     * 
     * @param InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }
    
    
}

?>