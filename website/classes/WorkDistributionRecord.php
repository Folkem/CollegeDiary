<?php


class WorkDistributionRecord
{
    private int $id;
    private string $subject;
    private User $teacher;
    private Group $group;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return WorkDistributionRecord
     */
    public function setId(int $id): WorkDistributionRecord
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return WorkDistributionRecord
     */
    public function setSubject(string $subject): WorkDistributionRecord
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return User
     */
    public function getTeacher(): User
    {
        return $this->teacher;
    }

    /**
     * @param User $teacher
     * @return WorkDistributionRecord
     */
    public function setTeacher(User $teacher): WorkDistributionRecord
    {
        $this->teacher = $teacher;
        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @return WorkDistributionRecord
     */
    public function setGroup(Group $group): WorkDistributionRecord
    {
        $this->group = $group;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->getSubject() . " (" . $this->getTeacher()->getFullName() .
            "; " . $this->getGroup()->getReadableName(false) . ")";
    }
}