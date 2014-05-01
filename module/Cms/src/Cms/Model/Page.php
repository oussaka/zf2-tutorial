<?php
namespace Cms\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArraySerializableInterface;

class Page implements ArraySerializableInterface, InputFilterAwareInterface
{
    public $id;
    public $title;
    public $content;
    protected $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
    }

    public function getArrayCopy()
    {
        $data = array('id' => $this->id, 'title' => $this->title, 'content' => $this->content,);
        return $data;
    }

    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /**
     * Initialise et/ou retourne un objet de type InputFilter
     * qui contrôle les différents attributs d'un objet de type Page
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            //Vérifie que l'id est de type int
            $inputFilter->add($factory->createInput(array('name' => 'id', 'required' => true, 'filters' => array(array('name' => 'Int'),),)));
            //Vérifie que le titre est de type text avec une
            //longueur de moins de 100 caractère
            //Retire aussi les espaces inutiles et les balises html
            $inputFilter->add($factory->createInput(array('name' => 'title', 'required' => true, 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),), 'validators' => array(array('name' => 'StringLength', 'options' => array('encoding' => 'UTF-8', 'min' => 1, 'max' => 100,),),),)));
            //Vérifie que le titre est de type text avec une
            //longueur de moins de 10000 caractère
            //Retire aussi les espaces inutiles
            $inputFilter->add($factory->createInput(array('name' => 'content', 'required' => true, 'filters' => array(array('name' => 'StringTrim'),), 'validators' => array(array('name' => 'StringLength', 'options' => array('encoding' => 'UTF-8', 'min' => 1, 'max' => 10000,),),),)));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Les filtres sont définis directement dans la classe");
    }

}
