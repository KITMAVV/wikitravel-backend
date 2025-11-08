<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $q = Country::query();

        // прості фільтри/пошук
        if ($search = $request->query('search')) {
            $q->where(function($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('code', 'like', "%{$search}%")
                   ->orWhere('region', 'like', "%{$search}%");
            });
        }

        $perPage = (int)($request->query('per_page', 15));
        return response()->json($q->orderBy('name')->paginate($perPage));
    }

    public function show(Country $country)
    {
        return response()->json($country);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:5|unique:countries,code',
            'region'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $country = Country::create($data);
        return response()->json($country, Response::HTTP_CREATED);
    }

    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'code'        => 'sometimes|required|string|max:5|unique:countries,code,' . $country->id,
            'region'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $country->update($data);
        return response()->json($country);
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
