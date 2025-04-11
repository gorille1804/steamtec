<?php
namespace Domain\Contact\Data\Contract;

class ContactRequest
{
    public string $nom;
    public string $prenom;
    public string $email;
    public string $type;
    public string $message;
}