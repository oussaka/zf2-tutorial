<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Album\Model\Student;

class FormAnnotationController extends AbstractActionController
{
    public function addAction()
    {
        $student    = new Student();
        $builder    = new AnnotationBuilder();
        $form       = $builder->createForm($student);

        $request = $this->getRequest();
        if ($request->isPost()){
            $form->bind($student);
            $form->setData($request->getPost());
            if ($form->isValid()){
                print_r($form->getData());
            }
        }

        return array('form'=>$form);
    }
}