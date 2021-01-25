<?php


class CallScheduleItem
{
    private int $lessonNumber;
    private DateTimeImmutable $timeStart;
    private DateTimeImmutable $timeEnd;

    /**
     * @return int
     */
    public function getLessonNumber(): int
    {
        return $this->lessonNumber;
    }

    /**
     * @param int $lessonNumber
     * @return CallScheduleItem
     */
    public function setLessonNumber(int $lessonNumber): CallScheduleItem
    {
        $this->lessonNumber = $lessonNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeStart(): DateTimeImmutable
    {
        return $this->timeStart;
    }

    /**
     * @param int $timeStart
     * @return CallScheduleItem
     */
    public function setTimeStart(DateTimeImmutable $timeStart): CallScheduleItem
    {
        $this->timeStart = $timeStart;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeEnd(): DateTimeImmutable
    {
        return $this->timeEnd;
    }

    /**
     * @param int $timeEnd
     * @return CallScheduleItem
     */
    public function setTimeEnd(DateTimeImmutable $timeEnd): CallScheduleItem
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }
}