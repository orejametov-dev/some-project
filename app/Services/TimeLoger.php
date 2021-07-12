<?php


namespace App\Services;


use DB;

class TimeLoger
{
    public $name;
    public $started_at;
    public $ended_at;
    public $diff;
//    public $timeLoger;

    public function __construct($name)
    {
        DB::connection('');
    }

    public static function start()
    {

    }

    public static function end()
    {
        // time now (end)
        // calculate diff
//        $this->save();
    }

    private function save()
    {
        // check env config before save.
        // save to db
    }
}
