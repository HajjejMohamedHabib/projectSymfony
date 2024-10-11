<?php

namespace App\listener;

use App\Entity\Personne;
use App\event\AddPersonneEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
public function __construct(private LoggerInterface $logger){}
    public function onAddPersonne(AddPersonneEvent $event){
    $personne = $event->getPersonne();
    $this->logger->info("Ajout personne "." " .$personne->getFirstname());
    }
}