<?php

use \Carbon\Carbon;

function short_timestamp($to_print) {
    $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $to_print);

    $day = $datetime->format('j');
    $monthName = $datetime->format('M');
    $year = $datetime->format('Y');

    $hours = $datetime->format('H');
    $minutes = $datetime->format('i');

    $date = Carbon::createFromFormat('Y-m-d', $datetime->format('Y-m-d'));
    $now = Carbon::now();
    $diff = $now->diff($date);

    if ($diff->y > 0 || $diff->m > 0)
        return $monthName . ' ' . $year;
    if ($diff->d > 1)
        return $monthName . ' ' . $day;
    if ($diff->d == 1)
        return "Yesterday, " . $hours . ':' . $minutes;
    return $hours . ':' . $minutes;
}


function short_date($to_print) {
    $datetime = Carbon::createFromFormat('Y-m-d', $to_print);
    return $datetime->format('M j');
}

function user_route_params($user) {
    return ['name' => str_replace(' ', '', $user->name), 'id' => $user->user_id];
}

function time_left($start, $seconds) {
    $limit = strtotime($start) + $seconds;
    $secondsLeft = $limit-time();

    $numberOfDays =  floor($secondsLeft / 86400);
    $numberOfHours =  floor(($secondsLeft - $numberOfDays * 86400)/ 3600);
    $durationStr = "";
    if($numberOfDays!=0){
        $durationStr .= strval($numberOfDays) . " day";
        if($numberOfDays != 1)
            $durationStr .="s";
        $durationStr .=" ";
    }
    if($numberOfHours == 0 && $numberOfDays == 0)
        $durationStr .= "<1 hour";
    else if($numberOfHours != 0) {
        $durationStr .= strval($numberOfHours) . " hour";
            if($numberOfHours != 1)
                $durationStr .="s";
    }
    return $durationStr;
}