<?php
/**
 * factory :
 * Quand une classes n�cessite pus de traitements au moment de l�instanciation, on utilisera une fabrique (qui nous retourne un objet configur�).
 * pour ce faire on peut soit, utiliser une classe qui impl�mente l�interface �Zend\ServiceManager\FactoryInterface� et donc qui contient une m�thode createService($sm) qui va retourner l�objet cr��, soit utiliser une fonction callback (qui retourne aussi l�objet configur�) :
 * si on utilise une classe fabrique il est conseill� de l�ajouter dans le m�me dossier que la classe qu�elle doit cr�er et on ajoutera Factory au nom de la clase (c�est l�approche de l��quipe ZF2):
 * par exemple si on veut cr�er une classe �SimpleService� via une classe factory, on va appeler la classe de fabrication �SimpleServiceFactory�. La m�thode �createService()� de cette classe va retourner une classe �SimpleService� configur�e et pourra l�instancier avec l�op�rateur new et lui injecter des d�pendances via un/des setter(s) (ex: setOptions, setParams, etc� ), ou lui passer des d�pendances via le constructeur, ou les deux. l�important c�est de retourner un objet configur�, et les d�pendances inject�es.
 * Important : les fabriques re�oivent une instance du ServiceManager en param�tre, ce qui permet de demander un autre objet au SM, que l�on peut fournir � notre classe (via son constructeur, ou via un setter)
 */
namespace Album\Invokables;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

// classe servant � fabriquer un objet configur�
// l'interface FactoryInterface impose la pr�sence de createService()
// qui renvoie l'objet configur� avec ses d�pendances
class SimpleServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
		// on cr�e notre objet
		$myService = new SimpleService();

		// on r�cup�re une autre d�pendance que l'on injecte
		// $dependency = $sm->get('AnyDependency');

		// on le passe � notre classe
		// $myService->setParam($dependency);

		// on retourne l'objet
		return $myService;
	}
}