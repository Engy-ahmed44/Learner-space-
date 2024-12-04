<?php

namespace domain\interface;

use DateTime;

interface IPerson
{
	public function getId(): int;
	public function getName(): string;
	public function getBirthdate(): DateTime;
}
