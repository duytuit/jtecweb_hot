<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ModelEventLogger
 * @package App\Traits
 *
 *  Automatically Log Add, Update, Delete events of Model.
 */
trait ActivityLogger {

    protected static function boot()
    {

        parent::boot();

         // Hook into eloquent events that only specified in $eventToBeRecorded array,
        // checking for "updated" event hook explicitly to temporary hold original
        // attributes on the model as we'll need them later to compare against.
        if(Cache::get('maintenance')){
            return redirect()->back();
        }
        DB::listen(function ($query) {
            $reflect = new \ReflectionClass(get_called_class());
            try {
                $new_str = substr($query->sql,0,10);
                //app(\App\User::class)->getTable();

                $string_table_name =  app('\\'.$reflect->getName())->getTable();
                if(str_contains($new_str, 'select') && strpos($query->sql, $string_table_name)){
                    $sql = substr($query->sql,strpos($query->sql,'where'),strlen($query->sql)) ;
                    $new_query = self::getQueries($sql,$query->bindings);
                    // Log::info(
                    //     $query->sql,
                    //     [
                    //     'connectionName'=> $query->connectionName,
                    //         'bindings' => $query->bindings,
                    //         'time' => $query->time,
                    //         'table' => 'tu___'.$reflect->getShortName()
                    //     ]
                    // );
               }
                if(str_contains($new_str, 'update') && strpos($query->sql, $string_table_name)){
                    $sql = self::getQueries($query->sql,$query->bindings);
                    $new_query = substr($sql,strpos($sql,'where'),strlen($sql)) ;
                    $sql = "select * from `exams` where `id` in ('24', '25', '26', '27', '28', '29', '30', '19', '20', '23', '21', '22') and `exams`.`deleted_at` is null";
                    //$fdgdfg = DB::table(DB::raw("($sql) as tb1"))->get();
                   // DB::table(DB::raw("($sql) as tb1"))->first();
                   // dd( $fdgdfg );
                    // Log::info(
                    //     '$new_query: '. json_encode($new_query) ,
                    //     [
                    //     'connectionName'=> $query->connectionName,
                    //         'bindings' => $query->bindings,
                    //         'time' => $query->time,
                    //         'table' => 'tu___'.$reflect->getShortName()
                    //     ]
                    // );
               }
            } catch (\Exception $e) {
                Log::debug($e->getMessage());
            }
        });
        static::eventsToBeRecorded()->each(function ($eventName) {

            static::$eventName(function (Model $model) use ($eventName) {
                try {
                    $reflect = new \ReflectionClass($model);
                    $query= DB::getQueryLog();
                    $lastQuery= end($query);
                    $activity = Activity::create([
                        'user_id' => @Auth::id(),
                        'content_id' => $model->attributes[$model->primaryKey],
                        'content_type' => get_class($model),
                        'action' => $eventName,
                        'description' => ucfirst($eventName) . " a " . $reflect->getShortName(),
                        'old_data' => json_encode($model->getOriginal()),
                        'new_data' => json_encode($model->getDirty()),
                        'ip_address' =>  $_SERVER['REMOTE_ADDR'],
                        'sql' =>  json_encode($lastQuery)
                    ]);
                    return $activity;
                } catch (\Exception $e) {
                    Log::debug($e->getMessage());
                }
            });
        } );
    }
    public static function getFinalSql($sql_str,$bindings)
    {
        $wrapped_str = str_replace('?', "'?'", $sql_str);
        return str_replace_array('?', $bindings, $wrapped_str);
    }
    protected static function bootLogsActivity(): void
    {

    }
     /**
     * Get the event names that should be recorded.
     **/
    protected static function eventsToBeRecorded(): Collection
    {
        if (isset(static::$recordEvents)) {
            return collect(static::$recordEvents);
        }

        $events = collect([
            'created',
            'updated',
            'deleted'
        ]);

        // if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
        //     $events->push('restored');
        // }

        return $events;
    }

    public static function getQueries($sql,$buildings)
    {
        $addSlashes = str_replace('?', "'?'", $sql);
        return vsprintf(str_replace('?', '%s', $addSlashes), $buildings);
    }

}
