<?php

namespace App\Http\Controllers;

use App\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class EventsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

    }

    public function getEvents(Request $request)
    {

        $events = Events::with('eventDays')->latest()->get();
        return $events;
    }

    public function live()
    {

        $events = Events::with('eventDays')->limit(1)->orderByDesc('id')->get();
        dd($events) ;
    }


    public function setEvent(Request $request)
    {

        $validate = $request->validate([
            'eventTitle' => ['required'],
            'toDate' => ['required', 'after:fromDate'],
            'toDate' => ['required', 'after:fromDate'],
//            'eventDay' => 'required',
        ]);


        $events = Events::create([
            'from' => $request->fromDate,
            'to' => $request->toDate,
            'title' => ($request->eventTitle) ? $request->eventTitle : '',
        ]);

        for($x=0; $x < count($request->eventDay); $x++){

            $events->eventDays()->create([
                'day' => $request['eventDay'][$x]
            ]);
        }
    }
}
