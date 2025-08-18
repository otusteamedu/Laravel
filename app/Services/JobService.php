<?

namespace App\Services;

class JobService
{

    public static function add($text=null){
        if($text!=null){
            $job = new \App\Jobs\SendTgJobs(
                $text,
                'debug'
            );
            dispatch($job)->onQueue('telegram')->afterResponse();
        }
    }
}