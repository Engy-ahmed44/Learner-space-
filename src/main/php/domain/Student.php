<?php

declare(strict_types=1);

namespace domain;

use dao\NotificationsDAO;
use DateTime;
use domain\enum\GenreEnum;
use dao\StudentsDAO;
use domain\interface\IObserver;
use repositories\Database;
use repositories\pdo\MySqlPDODatabase;

/**
 * Responsible for representing student-type users.
 */
class Student extends User implements IObserver
{
    //-------------------------------------------------------------------------
    //        Attributes
    //-------------------------------------------------------------------------
    private $photo;


    //-------------------------------------------------------------------------
    //        Constructor
    //-------------------------------------------------------------------------
    /**
     * Creates a representation of a student-type user.
     *
     * @param       int $id Student id
     * @param       string $name Student name
     * @param       GenreEnum $genre Student genre
     * @param       DateTime $birthdate Student birthdate
     * @param       string $email Student email
     * @param       string $photo [Optional] Name of the student photo file
     */
    public function __construct(
        int $id,
        string $name,
        GenreEnum $genre,
        DateTime $birthdate,
        string $email,
        ?string $photo = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->genre = $genre;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->photo = empty($photo) ? "" : $photo;
    }

    public function update(int $id)
    {
        $dbConnection = MySqlPDODatabase::shared();
        $notificationDAO = new NotificationsDAO($dbConnection, $this->id);
        $notificationDAO->create_function($this->id, "One of your courses has been updated" . $id);
    }
    //-------------------------------------------------------------------------
    //        Getters
    //-------------------------------------------------------------------------
    /**
     * Gets the name of the student photo file.
     * 
     * @return      string Name of the student photo file or empty string if
     * the student does not have a registered photo.
     */
    public function getPhoto(): string
    {
        return $this->photo;
    }


    //-------------------------------------------------------------------------
    //        Serialization
    //-------------------------------------------------------------------------
    /**
     * {@inheritDoc}
     *  @see \JsonSerializable::jsonSerialize()
     *
     *  @Override
     */
    public function jsonSerialize(): array
    {
        $json = parent::jsonSerialize();
        $json['photo'] = $this->photo;

        return $json;
    }
}
