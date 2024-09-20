<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartyRequest;
use App\Models\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $query = Party::query();

            if ($request->has('search')) {
                $query->where('party_name', 'like', '%' . $request->search . '%');
            }

            $query->latest();

            $data = $query->paginate(10);

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePartyRequest $request)
    {

        try {
            $data = $request->all();

            // Store the data
            Party::create($data);

            // Return success response
            return response()->json(['message' => 'Party stored successfully!'], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $party = Party::find($id);

            if ($party) {
                // Return success response
                return response()->json(['message' => 'Party fetch successfully!', 'data' => $party], 201);
            } else {
                // Return not found response
                return response()->json(['error' => 'Party not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePartyRequest $request, $id)
    {
        try {
            $party = Party::find($id);

            if ($party) {
                $data = $request->all();
                $party->update($data);

                // Return success response
                return response()->json(['message' => 'Party updated successfully!', 'data' => $party], 201);
            } else {
                // Return not found response
                return response()->json(['error' => 'Party not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $party = Party::find($id);

            if ($party) {
                $party->delete();

                // Return success response
                return response()->json(['message' => 'Party deleted successfully!'], 200);
            } else {
                // Return not found response
                return response()->json(['error' => 'Party not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
