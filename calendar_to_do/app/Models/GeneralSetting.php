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
        if ($value) {
            $items = json_decode($value, true);
            return $this->formatCalendarJson($items);
        }
        return [];
    }
    
    public function setCalendarJsonAttribute($value)
    {
        $this->attributes['calendar_json'] = json_encode(
            $this->formatCalendarJson($value)
        );
        return $this;
    }

    private function formatCalendarJson($value)
    {
        return [
            'type' => isset($value['type']) ? $value['type'] :'week',
            'mode' => isset($value['mode']) ? $value['mode'] :'column',
            'weekday' => isset($value['weekday']) ? $value['weekday'] : [0, 1, 2, 3, 4, 5, 6],
        ];
    } 
    
}
