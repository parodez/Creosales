<?php

class ContactPerson
{
    public function __construct(
        public int $id,
        public string $name,
        public string $position,
        public string $email,
        public string $number,
    ) {}
}