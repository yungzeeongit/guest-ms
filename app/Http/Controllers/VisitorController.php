<?php

namespace App\Http\Controllers;
//use App\Http\Requests\VisitorRequest;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected string  $visitor = '';

    public function __construct(Visitor $visitor)
    {
//        $this->middleware('auth:api');
        $this->visitor = $visitor;
    }
    public function index():Response
    {
        //
        $visitors = Visitor::all();
        return Inertia::render('Visitors', [
            'visitors' => $visitors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request  $request)
    {

//        $visitor = User::create($request->validated());
//        return response()->json($visitor, 201);
//        return Inertia::render('Dashboard');
//        return Redirect::to('/dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $visitor = Visitor::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        // Validate the request
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:visitors,email,' . $id
            ],
        ]);

        // Update the visitor
        $visitor->fill($request->all());
        $visitor->save();

        return redirect()->back()->with('success', 'Visitor has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
//    public function destroy(string $id)
    public function destroy(Visitor $visitor)
    {
        //
        $visitor->delete();
    }
}
