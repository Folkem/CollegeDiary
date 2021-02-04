<?php

class Speciality
{
    private int $id;
    private string $fullName;
    private string $code;
    private string $shortName;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Speciality
     */
    public function setId(int $id): Speciality
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return Speciality
     */
    public function setFullName(string $fullName): Speciality
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Speciality
     */
    public function setCode(string $code): Speciality
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     * @return Speciality
     */
    public function setShortName(string $shortName): Speciality
    {
        $this->shortName = $shortName;
        return $this;
    }
}