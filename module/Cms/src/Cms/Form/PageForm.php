<?php
namespace Cms\Form;

use Zend\Form\Form, Zend\Form\FieldsetInterface, Zend\EventManager\EventManager;
use Cms\Listener\Logging;

class PageForm extends Form {

    protected $events = null;
    protected $logger = null;
    
    public function __construct() {
        parent::__construct();
        
        //Nom du formulaire
        $this -> setName('page');
        
        //Méthode d'envoie (GET,POST)
        $this -> setAttribute('method', 'post');
        
        //Définition des champs
        $this -> add(array('name' => 'id', 'attributes' => array('type' => 'hidden', ), ));
        $this -> add(array('name' => 'title', 'attributes' => array('type' => 'text', ), 'options' => array('label' => 'Titre', ), ));
        $this -> add(array('name' => 'content', 'attributes' => array('type' => 'textarea', ), 'options' => array('label' => 'Contenu', ), ));
        
        //Definition des Actions
        $this -> add(array('name' => 'submit', 'attributes' => array('type' => 'submit', 'value' => 'Envoyer', 'id' => 'submitbutton', ), ));
	}

	public function events() {

	    if ($this->events === null) {
	        $this->events = new EventManager(__CLASS__);
	        $logListener = new Logging();
	        $this->events()->attach(
	                'isValid.pre',
	                array($logListener, 'logOutput')
	        );
	        $this->events()->attach(
	                'isValid.post',
	                array($logListener, 'logOutput')
	        );
	    }

	    return $this->events;
	}

	public function isValid() {

	    $response = $this->events()->trigger(
	            __FUNCTION__ . '.pre', $this, $this->data
	    );
	    $isValid = parent::isValid();
	    $response = $this->events()->trigger(
	            __FUNCTION__ . '.post', $this, $this->data
	    );
	    return $isValid;
	}
}
