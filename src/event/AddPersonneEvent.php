<?php

namespace App\event;

use App\Entity\Personne;
use Symfony\Contracts\EventDispatcher\Event;

class AddPersonneEvent extends Event
{
const Add_PERSONNE_EVENT = 'add.personne';
public function __construct(private Personne $personne){
}
public function getPersonne(): Personne{
    return $this->personne;
}
}