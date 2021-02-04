<?php

class User
{
    private int $id;
    private string $firstName;
    private ?string $middleName;
    private string $lastName;
    private string $fullName;
    private string $email;
    private string $password;
    private int $role;
    private ?string $avatarPath;

    /**
     * @return int user's record id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string user's first name
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string user's middle name (if present)
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @return string user's full name, composed in "Last F. [M.]" format
     * (last name shows fully, whereas first and middle (if present)
     * names are shortened to 1 symbol and followed by a dot
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string user's last name
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string user's email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string user's hashed password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int user's role number (1 for admin, 2 for student etc.)
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @return string path to the user's avatar in the media/user_avatars folder
     */
    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    /**
     * @param int $id user's id
     * @return User this object (for chaining)
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $firstName user's first name
     * @return User this object (for chaining)
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param ?string $middleName user's middle name
     * @return User this object (for chaining)
     */
    public function setMiddleName(?string $middleName): User
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * @param string $fullName user's full name
     * @return User this object (for chaining)
     */
    public function setFullName(string $fullName): User
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @param string $lastName user's last name
     * @return User this object (for chaining)
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param string $email user's email
     * @return User this object (for chaining)
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password user's hashed password
     * @return User this object (for chaining)
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param int $role user's role number (1 - admin, 2 - student etc.)
     * @return User this object (for chaining)
     * @see UserRoles
     */
    public function setRole(int $role): User
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param ?string $avatarPath path to the user's avatar in the media/user_avatars folder
     * @return User this object (for chaining)
     */
    public function setAvatarPath(?string $avatarPath): User
    {
        $this->avatarPath = $avatarPath;
        return $this;
    }
}