<?php
    class Payload
    {
        public $idUser;
        public $role;

        public function __construct($user)
        {
            $this->idUSer = $user->id;
            $this->role = $user->listRole;
        }
    }