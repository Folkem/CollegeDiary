<?php


class Grade
{
    private int $id;
    private ?int $gradeValue;
    private bool $presence;
    private User $student;
    private Lesson $lesson;
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): Grade
    {
        $this->id = $id;
        return $this;
    }
    
    public function getGradeValue(): ?int
    {
        return $this->gradeValue;
    }
    
    public function setGradeValue(?int $gradeValue): Grade
    {
        $this->gradeValue = $gradeValue;
        return $this;
    }
    
    public function isPresence(): bool
    {
        return $this->presence;
    }
    
    public function setPresence(bool $presence): Grade
    {
        $this->presence = $presence;
        return $this;
    }
    
    public function getStudent(): User
    {
        return $this->student;
    }
    
    public function setStudent(User $student): Grade
    {
        $this->student = $student;
        return $this;
    }
    
    public function getLesson(): Lesson
    {
        return $this->lesson;
    }
    
    public function setLesson(Lesson $lesson): Grade
    {
        $this->lesson = $lesson;
        return $this;
    }
}