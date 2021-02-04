<?php

class Group
{
    private int $id;
    private Speciality $speciality;
    private int $groupNumber;
    private int $groupYear;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Group
     */
    public function setId(int $id): Group
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Speciality
     */
    public function getSpeciality(): Speciality
    {
        return $this->speciality;
    }

    /**
     * @param Speciality $speciality
     * @return Group
     */
    public function setSpeciality(Speciality $speciality): Group
    {
        $this->speciality = $speciality;
        return $this;
    }

    /**
     * @return int
     */
    public function getGroupNumber(): int
    {
        return $this->groupNumber;
    }

    /**
     * @param int $groupNumber
     * @return Group
     */
    public function setGroupNumber(int $groupNumber): Group
    {
        $this->groupNumber = $groupNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getGroupYear(): int
    {
        return $this->groupYear;
    }

    /**
     * @param int $groupYear
     * @return Group
     */
    public function setGroupYear(int $groupYear): Group
    {
        $this->groupYear = $groupYear;
        return $this;
    }
}