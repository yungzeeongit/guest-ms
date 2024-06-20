<?php

namespace App\Http\Controllers;

use App\Models\Host;
use Inertia\Inertia;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class HostController extends Controller
{


    protected string  $host = '';

    public function __construct(Host $host)
    {
 
                $this->host = $host;
            }

    public function index()
    {
        $hosts = Host::all();
        return Inertia::render('Hosts', [
            'hosts' => $hosts,
        ]);
    }

  

    public function store(Request $request)
    {
    
        $host = Host::create($request->all());
    }


    
    

    public function update(Request $request, $host_id)
    {
        $host = Host::findOrFail($host_id);

     
        $request->validate([
            'host_name' => 'required|string|max:255',
                   'unique:hosts,host_email,' . $host_id . ',host_id',
                     'host_phone' => 'required|string|max:15',
                     'host_position' => 'required|string|max:255',
                    'host_department' => 'required|string|max:255',
        
        ]);

        


        // Update the visitor
        $host->fill($request->all());
        $host->save();

        return redirect()->back()->with('success', 'Host has been updated successfully.');
    }


    public function destroy(Host $host)
    {
        $host->delete();
    }
    // public function destroy(Host $host)
    // {
    //     $host->delete();
    //     return redirect()->route('dashboard')->with('success', 'Host deleted successfully.');
    // }
}
