<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $attributes = [
        "calendar_json" => "{}",
    ];


    protected $casts =[
        'calendar_json' => 'json'
    ];

    public function getCalendarJsonAttribute($value)
    {
        $value = json_decode($value,true);
        return [
            'type' => isset($value['type']) ? $value['type'] :'week',
            'mode' => isset($value['mode']) ? $value['mode'] :'column',
            'weekday' => isset($value['weekday']) ? $value['weekday'] : [0, 1, 2, 3, 4, 5, 6],
        ];
    }

    // public function setCalendarJsonAttribute($value)
    // {
    //     \Log::debug($value);
    //     // $value = json_decode($value);
    //     // $value = json_decode($value,true);

    //     // $value =  [
    //     //     'type' => isset($value['type']) ? $value['type'] :'week',
    //     //     'mode' => 'column',
    //     //     'weekday' => [0, 1, 2, 3, 4, 5, 6],
    //     // ];

    //     // $attributes["calendar_json"];
    //     // return json_encode($value,true);
    //     // return [
    //     //     'type' => isset($value['type']) ? $value['type'] :'week',
    //     //     'mode' => 'column',
    //     //     'weekday' => [0, 1, 2, 3, 4, 5, 6],
    //     // ];
    // }
    
}
