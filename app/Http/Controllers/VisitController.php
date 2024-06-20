<?php

namespace App\Http\Controllers;
//use App\Http\Requests\VisitorRequest;
use App\Models\Host;
use Inertia\Inertia;
use App\Models\Visit;
use Inertia\Response;
use App\Models\Visitor;
use Illuminate\Http\Request;
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
        $visits = Visit::with(['visitor', 'host'])->get();

        $visitsData = $visits->map(function ($visit) {
            return [
                'visit_id' => $visit->visit_id,
                'check_in_time' => $visit->check_in_time,
                'check_out_time' => $visit->check_out_time,
                'purpose' => $visit->purpose,
                'status' => $visit->status,
                'visitor_name' => $visit->visitor->full_name,
                'host_name' => $visit->host->host_name,
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
 
    // public function index(): Response
    // {
    //     $visits = Visit::with(['visitor', 'host'])->get();

    //     $visitsData = $visits->map(function ($visit) {
    //         return [
    //             'visit_id' => $visit->visit_id,
    //             'date' => $visit->date,
    //             'check_in_time' => $visit->check_in_time,
    //             'check_out_time' => $visit->check_out_time,
    //             'purpose' => $visit->purpose,
    //             'status' => $visit->status,
    //             'visitor_name' => $visit->visitor->full_name,
    //             'visitor_email' => $visit->visitor->email,
    //             'visitor_company' => $visit->visitor->company,
    //             'host_name' => $visit->host->host_name,
    //             'host_department' => $visit->host->host_department,
    //             'host_position' => $visit->host->host_position,
    //         ];
    //     });

    //     return Inertia::render('Visits', [
    //         'visits' => $visitsData,
    //     ]);
    // }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request  $request)
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)



    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'visitor_name' => 'required|string|max:255',
       
        
        'host_name' => 'required|string|max:255',
        
       
      
        'check_in_time' => 'required',
        'check_out_time' => 'required',
        'purpose' => 'required|string|max:255',
        'status' => 'required|string|max:255',
    ]);

    // Fetch visitor_id from the visitors table
    $visitor = Visitor::where('full_name', $request->visitor_name)
        ->firstOrFail();

    // Fetch host_id from the hosts table
    $host = Host::where('host_name', $request->host_name)
        ->firstOrFail();

    // Create the visit with the fetched ids
    $visit = Visit::create([
        'visitor_id' => $visitor->id,
        'host_id' => $host->host_id,
        'check_in_time' => $request->check_in_time,
        'check_out_time' => $request->check_out_time,
        'purpose' => $request->purpose,
        'status' => $request->status,
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $visitors = Visitor::all(); // Adjust according to your actual data fetching logic

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

    //*

//     public function search(Request $request)
// {
//     $query = $request->input('query');
//     $visitors = Visitor::where('name', 'LIKE', "%{$query}%")->get(['id', 'full_name']); // Adjust the fields as needed
//     return response()->json($visitors);
// }



    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $visit_id)
    {
        $visit = Visit::findOrFail($visit_id);

        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'host_name' => 'required|string|max:255',
        
            'check_in_time' => 'required',
            'check_out_time' => 'required',
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
// //    public function destroy(string $id)
    public function destroy(Visit $visit)
    {

        
    
        
        $visit->delete();
    }
}
