<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Shortened;
use App\Models\Visit;
use DB;

class ShortenerController extends Controller
{
    //
    public function shorten()
    {

        $resultTokenName = '';
        $urlToShorten = request()->enteredurl;

        $shortenedRecord = Shortened::where('full_url', $urlToShorten)->first();

        if($shortenedRecord)
        {
            $resultTokenName = $shortenedRecord->name_token;
        }
        else
        {
            $newKey = Str::random(6);

            $tokenRecord = Shortened::where(DB::raw('BINARY `name_token`'), $newKey)->first();

            while( !! $tokenRecord) //keep trying a new name token until not found in the DB
            {
                $newKey = Str::random(6);
                $tokenRecord = Shortened::where(DB::raw('BINARY `name_token`'), $newKey)->first();
            }
            
            $shortened = new Shortened;

            $shortened->full_url = $urlToShorten;
            $shortened->name_token = $newKey;
            $shortened->visits_count = 0;
            $shortened->created_at = $this->now_datetime();
            $shortened->save();

            $resultTokenName = $shortened->name_token;

        }
        

        echo json_encode(['shortenedurl' => URL::to('/') . '/' . $resultTokenName]);
        
    }

    public function registervisit()
    {
        
        $tokenRecord = Shortened::where(DB::raw('BINARY `name_token`'), request()->nametoken)->first();

        if($tokenRecord)
        {
            $tokenRecord->visits_count += 1; // record the visit in the Shortened table
            $tokenRecord->save();

            $visit = new Visit;

            $visit->shortened_id = $tokenRecord->id;
            $visit->ip_address = request()->ip();
            $visit->visit_time = $this->now_datetime();

            echo $visit->save();
        }
        else
        {
            echo 'not_found';
        }
    }

    public function stats()
    {
        $requestToken = request()->nametoken;

        $tokenRecord = Shortened::where(DB::raw('BINARY `name_token`'), $requestToken)->first();

        if($tokenRecord)
        {
            return $tokenRecord->visits()->paginate(5);
        }
    }

    public function fetchfull()
    {
        $tokenRecord = Shortened::where(DB::raw('BINARY `name_token`'), request()->nametoken)->first();

        if($tokenRecord)
        {
            echo json_encode(['full_url' => $tokenRecord->full_url, 'visit_count' => $tokenRecord->visits()->count()]);
        }
        else
        {
            echo 'not_found';
        }

    }
    
}
