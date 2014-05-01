<?php
/**
 * factory :
 * Quand une classes nécessite pus de traitements au moment de l’instanciation, on utilisera une fabrique (qui nous retourne un objet configuré).
 * pour ce faire on peut soit, utiliser une classe qui implémente l’interface ‘Zend\ServiceManager\FactoryInterface‘ et donc qui contient une méthode createService($sm) qui va retourner l’objet créé, soit utiliser une fonction callback (qui retourne aussi l’objet configuré) :
 * si on utilise une classe fabrique il est conseillé de l’ajouter dans le même dossier que la classe qu’elle doit créer et on ajoutera Factory au nom de la clase (c’est l’approche de l’équipe ZF2):
 * par exemple si on veut créer une classe ‘SimpleService’ via une classe factory, on va appeler la classe de fabrication ‘SimpleServiceFactory’. La méthode ‘createService()’ de cette classe va retourner une classe ‘SimpleService’ configurée et pourra l’instancier avec l’opérateur new et lui injecter des dépendances via un/des setter(s) (ex: setOptions, setParams, etc… ), ou lui passer des dépendances via le constructeur, ou les deux. l’important c’est de retourner un objet configuré, et les dépendances injectées.
 * Important : les fabriques reçoivent une instance du ServiceManager en paramètre, ce qui permet de demander un autre objet au SM, que l’on peut fournir à notre classe (via son constructeur, ou via un setter)
 */
namespace Album\Invokables;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

// classe servant à fabriquer un objet configuré
// l'interface FactoryInterface impose la présence de createService()
// qui renvoie l'objet configuré avec ses dépendances
class SimpleServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
		// on crée notre objet
		$myService = new SimpleService();

		// on récupère une autre dépendance que l'on injecte
		// $dependency = $sm->get('AnyDependency');

		// on le passe à notre classe
		// $myService->setParam($dependency);

		// on retourne l'objet
		return $myService;
	}
}