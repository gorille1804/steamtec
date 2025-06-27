<?php
namespace Domain\Contact\Data\Contract;

class ContactRequest
{
    public string $nom;
    public string $prenom;
    public string $societe;
    public string $email;
    public string $phone;
    public string $type;
    public string $message;
}