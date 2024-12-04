<?php

use domain\interface\IPerson;

class UserComposite
{
	private $users = [];

	public function addUser(IPerson $user)
	{
		$this->users[] = $user;
	}

	public function removeUser(IPerson $user)
	{
		$index = array_search($user, $this->users);
		if ($index !== false) {
			unset($this->users[$index]);
		}
	}

	public function getUsers()
	{
		return $this->users;
	}
}
