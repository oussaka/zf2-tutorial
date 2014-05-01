<?php
namespace Album\Form;

use Zend\Form\Form;

class CouleurForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('couleurs');
        $this->setAttribute('method', 'post');

        $this->add(array(
                'name' => 'id',
                'attributes' => array(
                        'type'  => 'hidden',
                        'id' => 'id',
                ),
        ));
        $this->add(array(
                'name' => 'nom',
                'attributes' => array(
                        'type'  => 'text',
                        'id' => 'nom',
                ),
                'options' => array(
                        'label' => 'Nom',
                ),
        ));

        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                        'type'  => 'submit',
                        'value' => 'Go',
                        'id' => 'submitbuttonAdd',
                        'onclick' => 'savecouleur()',//IMPORTANT POUR L'EXECUTION DE LA FUNCTION JAVASCRIPT AU MOMENT DE L'ENREGISTREMENT
                ),
        ));
    }
}
