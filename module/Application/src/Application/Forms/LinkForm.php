<?php
namespace Application\Forms;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
// use Zend\InputFilter\Factory as InputFactory;
// use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\InputFilterAwareInterface;


class LinkForm extends Form implements InputFilterAwareInterface // InputFilterProviderInterface
{
    protected $inputFilter;

    public function __construct($name = null)
    {
        parent::__construct('link-content');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'well input-append');

        $this->prepareElements();
    }

    public function prepareElements()
    {
        $this->add(array(
            'name' => 'url',
            'type'  => 'Zend\Form\Element\Url',
            'attributes' => array(
                'class' => 'span11',
                'placeholder' => 'Share a link!',
                // 'required' => true,
            ),
        ));
        $this->add(new Element\Csrf('csrf'));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'class' => 'btn'
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
    	return array(
    			'url' => array(
    					'required' => true,
    			)
    	);
    }

    /* public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    } */
    // La méthode setInputFilter ne sera pas utilisé ici...
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
    	$this->inputFilter = $inputFilter;
    }

    // La méthode qui nous intéresse
    public function getInputFilter()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();

    		$inputFilter->add(
    				array(
    						'name'     => 'url',               // Le nom du champ / de la propriété
    						'required' => true,                 // Champ requis
    						'validators' => array(              // Des validateurs
    								array(
    										'name'    => 'NotEmpty',// Pour vérifier la longueur du nom
    										),
    						),
    				)
    		);
    		$this->inputFilter = $inputFilter;
    	}
    		return $this->inputFilter;
    }

    /* public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

        	$inputFilter->add($factory->createInput(array(
        			'name'     => 'url',
        			'required' => true,
        	)));
        }
    	return $this->inputFilter;
    }*/

}
