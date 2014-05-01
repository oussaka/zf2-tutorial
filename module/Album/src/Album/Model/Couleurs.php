<?php
namespace Album\Model;

class Couleurs {

    public $id;
    public $nom;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->nom = (isset($data['nom'])) ? $data['nom'] : null;
    }

    //nécessaire pour alimenter le formulaire de modification
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}

