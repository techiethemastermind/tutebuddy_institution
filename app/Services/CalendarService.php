<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\Lesson;
use Carbon\Carbon;
use App\User;
use App\Models\Grade;
use App\Models\Division;

use App\Models\ScheduleTimeTable;

class CalendarService
{
    public function generateCalendarData($data, $colorService)
    {
        $calendarData = [];

        $courses = Course::all();

        foreach($courses as $course) {

            $schedules = $course->schedule;
            $color_set = $colorService->getScheduleColor($course->style);

            if(!empty($schedules)) {
                foreach($schedules as $schedule) {

                    $course_title = (strlen($course->title) > 12) ? (substr($course->title, 0, 12) . '...') : $course->title;

                    $base_date = Carbon::parse($schedule['date'] . ' ' . $schedule['start_time']);
                    $start_date = Carbon::parse($data['start']); // start date
                    $end_date = Carbon::parse($data['end']); // End date

                    $day_index = $base_date->dayOfWeek; // Week of scheduled date
                    $view_weeks = $end_date->diffInWeeks($start_date);

                    for ($i = 0; $i < $view_weeks; $i++) {

                        $skip = ($i * 7) + $day_index;
                        $i_date = $start_date->addDays($skip);

                        // Difference i_date and base_date
                        $diff_weeks = $base_date->diffInWeeks($i_date);

                        $start = $i_date->format('Y-m-d') . 'T' . Carbon::parse($schedule['start_time'])->format('H:i:s');
                        $end = $i_date->format('Y-m-d') . 'T' . Carbon::parse($schedule['end_time'])->format('H:i:s');

                        $item = [
                            'id' => $schedule->id,
                            'title' => 'Course: '. $course_title,
                            'start' => $start,
                            'end' => $end,
                            'display' => 'block',
                            'color' => $color_set['background_color'],
                            'textColor' => $color_set['font_color'],
                            'timezone' => $schedule->timezone
                        ];

                        // lesson check
                        if(!empty($schedule->lesson_id)) {
                            $lesson = Lesson::find($schedule->lesson_id);
                            if(!empty($lesson)) {
                                $lesson_title = (strlen($lesson->title) > 10) ? (substr($lesson->title, 0, 10) . '...') : $lesson->title;
                                $item['lesson'] = $lesson->position . '. ' . $lesson_title;
                            }
                        }

                        array_push($calendarData, $item);
                        $start_date = Carbon::parse($data['start']); // start date
                    }
                }
            }
        }
        
        return $calendarData;
    }

    public function getOnePeriodSchedule($course_id) {

        $course = Course::find($course_id);
        $schedules = $course->schedule->sortBy('date');

        $calendarData = [];

        if(count($schedules) > 0) {

            $start_date = Carbon::parse($schedules[0]->date);

            foreach($schedules as $schedule) {
                $day_index = Carbon::parse($schedule->date)->dayOfWeek;
                $day_str = Schedule::WEEK_DAYS[$day_index];
                
                $cur_date = Carbon::parse($schedule->date);
                $diff_date = $cur_date->diffInDays($start_date);

                $start_time = Carbon::parse($schedule->start_time)->format('H:i');
                $end_time = Carbon::parse($schedule->end_time)->format('H:i');

                $week_str = $start_time . '-' . $end_time . ' ' . $day_str . ' of Week ' . (ceil(($diff_date + 1) / 7));
                $available = !empty($schedule->lesson_id) ? 'false' : 'true';

                array_push($calendarData, ['id' => $schedule->id, 'content' => $week_str, 'available' => $available]);
            }
        }

        return $calendarData;
    }

    public function generateTimetableData($data, $colorService)
    {
        $tableData = [];
        $schedules = ScheduleTimeTable::all();

        $today = Carbon::now();
        $firstDayOfWeek = $today->startOfWeek();

        foreach($schedules as $schedule) {

            $color_set = $colorService->getScheduleColor($schedule->style);
            $day = $firstDayOfWeek->add($schedule->weekday - 1, 'day');

            $start = $day->format('Y-m-d') . 'T' . Carbon::parse($schedule->time)->format('H:i:s');
            $end = $day->format('Y-m-d') . 'T' . Carbon::parse($schedule->time)->addHours(1)->format('H:i:s');

            $user = User::find($schedule->user_id);
            $grade = Grade::find($schedule->grade_id);
            $division = Division::find($schedule->division_id);

            $title = $user->fullName() . '<br> ' . $grade->name . ' ' . $division->name;

            $item = [
                'id' => $schedule->id,
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'display' => 'block',
                'color' => $color_set['background_color'],
                'textColor' => $color_set['font_color'],
                'timezone' => $schedule->timezone
            ];

            array_push($tableData, $item);
            $firstDayOfWeek = $today->startOfWeek();
        }

        return $tableData;
    }
}
