<?php

namespace App\Http\Controllers;

use App\Models\Host;
use Inertia\Inertia;
use App\Models\Visit;
use Inertia\Response;
use App\Models\Visitor;
use Illuminate\Http\Request;
use App\Mail\NewVisitNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;


class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected string  $visit = '';

    public function __construct(Visit $visit)
    {
        //       ;
        $this->visit = $visit;
    }

    public function index(): Response
    {
        $visits = Visit::with(['visitor', 'host'])->latest()->get();

        $visitsData = $visits->map(function ($visit) {
            return [
                'visit_id' => $visit->visit_id,
                'purpose' => $visit->purpose,
                'status' => $visit->status,
                'check_in_time' => $visit->created_at,
                'check_out_time' => $visit->updated_at,
                'visitor_name' => $visit->visitor->full_name,
                'host_name' => $visit->host->host_name,
                'duration' => $visit->duration
            ];
        });

        // Collect all visitor names for suggestions
        $visitorNames = Visitor::select('id', 'full_name')->get();
        $hostNames = Host::select('host_id', 'host_name')->get();

        $allVisitors = Visitor::all();
        $allHosts = Host::all();

        return Inertia::render('Visits', [
            'visits' => $visitsData,
            'visitorNames' => $visitorNames,
            'hostNames'  => $hostNames,
            'allHosts' => $allHosts,
            'allVisitors' => $allVisitors
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request  $request)
    {


        return Inertia::render('Dashboard', [
            'visits' => Visit::all(), // Adjust as needed
            'allVisitors' => Visitor::all(),
            'allHosts' => Host::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'host_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'duration' => 'required',

        ]);


        $visitor = Visitor::where('full_name', $request->visitor_name)
            ->firstOrFail();


        $host = Host::where('host_name', $request->host_name)
            ->firstOrFail();



        $visit = Visit::create([
            'visitor_id' => $visitor->id,
            'host_id' => $host->host_id,
            'purpose' => $request->purpose,
            'status' => 'visit-in',
            'duration' => $request->duration,
            'updated_at' => 'pending'
        ]);

        $hostEmail = $host->host_email;


        Mail::to($hostEmail)->send(new NewVisitNotification($visit));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $visitors = Visitor::all();

        return response()->json([
            'visitors' => $visitors
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }



    public function checkout(Request $request, $visit_id)
    {
        // Find the visit record or fail if it doesn't exist
        $visit = Visit::findOrFail($visit_id);

        // Validate only the fields we want to update
        $request->validate([
            'status' => 'required|string|max:255',
            'duration' => 'required'
        ]);

        // Update the necessary fields
        $visit->status = $request->input('status');
        $visit->duration = $request->input('duration');

        // Save the updated visit record
        $visit->save();

        echo (response()->json(['message' => 'Visit status updated successfully']));
    }



    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $visit_id)
    {
        $visit = Visit::findOrFail($visit_id);

        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'host_name' => 'required|string|max:255',

            'purpose' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Fetch visitor_id from the visitors table
        $visitor = Visitor::where('full_name', $request->visitor_name)
            ->firstOrFail();

        // Fetch host_id from the hosts table
        $host = Host::where('host_name', $request->host_name)
            ->firstOrFail();





        // Update the visitor
        $visit->fill($request->all());
        $visit->save();

        return redirect()->back()->with('success', 'Host has been updated successfully.');
    }

    //     /**
    //      * Remove the specified resource from storage.
    //      */
    // /
    public function destroy(Visit $visit)
    {
        $visit->delete();
    }
}
