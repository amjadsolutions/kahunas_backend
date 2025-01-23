<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class ClientController extends Controller
{



    public function index(Request $request)
    {


        $pageNum = $request->query('pageNum', 1);

        // Validate the pageNum to ensure it's a positive integer
        if (!is_numeric($pageNum) || $pageNum <= 0) {
            return response()->json(['error' => 'Invalid pageNum. It must be a positive integer.'], 400);
        }



     

        // Define the number of items per page
        $records = $pageNum * 50;

        // Fetch clients for the authenticated coach with pagination
        $clients = Client::where('coach_id', Auth::id())->paginate($records);

        $customizedClients = [
            'current_page' => $clients->currentPage(),
            'total' => $clients->total(),
            'data' => $clients->items(),
        ];

  
        return response()->json(['clients' => $customizedClients], 200);
    }

    // Ensure the user is authenticated

    public function store(Request $request)
    {
        
        // Validate the request data
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);
    
        // Check if a client with the given email already exists
         $existingClient = Client::where('email', $request->email)->first();
    
        if ($existingClient) {
            return response()->json([
                'message' => 'Client with this email already exists',
          
            ], 409); // 409 Conflict
        }
    
        // Create a new client
        $client = Client::create([
            'coach_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make("password123"),
        ]);
    
        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ], 201); // 201 Created
    }
    

    public function update(Request $request, $id)
    {
        $client = Client::where('coach_id', Auth::id())->findOrFail($id);

        $this->validate($request, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:clients,email,' . $client->id,
        ]);

        $client->update($request->all());
        return response()->json(['message' => 'Client updated successfully', 'client' => $client], 200);
    }

    public function destroy($id)
    {
        $client = Client::where('coach_id', Auth::id())->findOrFail($id);
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }

}
