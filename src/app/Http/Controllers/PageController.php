<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Discussion;
use App\Models\Hotel;
use App\Models\Itinerary;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{

    // PAGES

    public function index(Request $request)
    {
        return response()->json(['data' => [], 'meta' => ['total' => 0]]);
    }

    public function show($id)
    {
        return response()->json(['data' => null]);
    }

    public function showBySlug($slug)
    {
        return response()->json(['data' => null]);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Not implemented for "pages"'], 201);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Not implemented for "pages"']);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Not implemented for "pages"']);
    }

    public function revisions($id)
    {
        return response()->json(['data' => []]);
    }

    // SEARCH

    public function search(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '') {
            return response()->json(['data' => [], 'meta' => ['total' => 0]]);
        }

        $limit = (int) $request->get('limit', 10);

        $hotels = Hotel::query()->where('name', 'like', "%{$q}%")->limit($limit)->get(['id','name','slug']);
        $places = Place::query()->where('name', 'like', "%{$q}%")->limit($limit)->get(['id','name','slug']);
        $countries = Country::query()->where('name', 'like', "%{$q}%")->limit($limit)->get(['id','name','slug']);
        $itins = Itinerary::query()->where('title', 'like', "%{$q}%")->limit($limit)->get(['id','title','slug']);

        return response()->json([
            'data' => [
                'hotels'     => $hotels,
                'places'     => $places,
                'countries'  => $countries,
                'itineraries'=> $itins,
            ],
            'meta' => [
                'query' => $q,
            ],
        ]);
    }

    // HOTELS

    public function hotelsIndex(Request $request)
    {
        $query = Hotel::query();

        if ($countryId = $request->get('country_id')) {
            $query->where('country_id', $countryId);
        }

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('name', 'like', "%{$q}%");
        }

        return $this->paginate($query->orderByDesc('id'));
    }

    public function showHotel($id)
    {
        $hotel = Hotel::findOrFail($id);
        return response()->json(['data' => $hotel]);
    }

    public function storeHotel(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:hotels,slug',
            'country_id'  => 'nullable|integer|exists:countries,id',
            'address'     => 'nullable|string|max:255',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $hotel = Hotel::create($data);
        return response()->json(['data' => $hotel], 201);
    }

    public function updateHotel(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'slug'        => 'sometimes|nullable|string|max:255|unique:hotels,slug,'.$hotel->id,
            'country_id'  => 'sometimes|nullable|integer|exists:countries,id',
            'address'     => 'sometimes|nullable|string|max:255',
            'rating'      => 'sometimes|nullable|numeric|min:0|max:5',
            'description' => 'sometimes|nullable|string',
            'cover_image' => 'sometimes|nullable|string|max:255',
        ]);

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $hotel->update($data);
        return response()->json(['data' => $hotel]);
    }

    public function deleteHotel($id)
    {
        Hotel::findOrFail($id)->delete();
        return response()->json(['message' => 'Hotel deleted']);
    }

    // PLACES

    public function placesIndex(Request $request)
    {
        $query = Place::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->get('q').'%');
        }
        if ($countryId = $request->get('country_id')) {
            $query->where('country_id', $countryId);
        }

        return $this->paginate($query->orderByDesc('id'));
    }

    public function showPlace($id)
    {
        $place = Place::findOrFail($id);
        return response()->json(['data' => $place]);
    }

    public function storePlace(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:places,slug',
            'country_id'  => 'nullable|integer|exists:countries,id',
            'type'        => 'nullable|string|max:50', // park/bar/art, якщо є
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $place = Place::create($data);
        return response()->json(['data' => $place], 201);
    }

    public function updatePlace(Request $request, $id)
    {
        $place = Place::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'slug'        => 'sometimes|nullable|string|max:255|unique:places,slug,'.$place->id,
            'country_id'  => 'sometimes|nullable|integer|exists:countries,id',
            'type'        => 'sometimes|nullable|string|max:50',
            'description' => 'sometimes|nullable|string',
            'cover_image' => 'sometimes|nullable|string|max:255',
            'address'     => 'sometimes|nullable|string|max:255',
        ]);

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $place->update($data);
        return response()->json(['data' => $place]);
    }

    public function deletePlace($id)
    {
        Place::findOrFail($id)->delete();
        return response()->json(['message' => 'Place deleted']);
    }

    // DISCUSSIONS

    public function discussionsIndex(Request $request)
    {
        $query = Discussion::query();

        // фільтр за сутністю (hotel/place/country/itinerary) та її id
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->get('entity_type'));
        }
        if ($request->filled('entity_id')) {
            $query->where('entity_id', $request->get('entity_id'));
        }

        return $this->paginate($query->orderByDesc('id'));
    }

    public function showDiscussion($id)
    {
        $d = Discussion::findOrFail($id);
        return response()->json(['data' => $d]);
    }

    public function storeDiscussion(Request $request)
    {
        $data = $request->validate([
            'entity_type' => 'required|string|max:50', // hotel/place/country/itinerary
            'entity_id'   => 'required|integer',
            'title'       => 'nullable|string|max:255',
            'body'        => 'required|string',
            'user_id'     => 'nullable|integer|exists:users,id',
        ]);

        // якщо використовуєш sanctum, можна брати user_id з токена
        if (auth()->check() && empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        $d = Discussion::create($data);
        return response()->json(['data' => $d], 201);
    }

    public function updateDiscussion(Request $request, $id)
    {
        $d = Discussion::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|nullable|string|max:255',
            'body'  => 'sometimes|string',
        ]);

        $d->update($data);
        return response()->json(['data' => $d]);
    }

    public function deleteDiscussion($id)
    {
        Discussion::findOrFail($id)->delete();
        return response()->json(['message' => 'Discussion deleted']);
    }

    // ITINERARIES (1–3 дні)

    public function itinerariesIndex(Request $request)
    {
        $query = Itinerary::query();

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->get('q').'%');
        }
        if ($countryId = $request->get('country_id')) {
            $query->where('country_id', $countryId);
        }

        return $this->paginate($query->orderByDesc('id'));
    }

    public function showItinerary($id)
    {
        $itin = Itinerary::findOrFail($id);
        return response()->json(['data' => $itin]);
    }

    public function storeItinerary(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:itineraries,slug',
            'country_id'  => 'nullable|integer|exists:countries,id',
            'days'        => 'nullable|integer|min:1|max:30',
            'content'     => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $itin = Itinerary::create($data);
        return response()->json(['data' => $itin], 201);
    }

    public function updateItinerary(Request $request, $id)
    {
        $itin = Itinerary::findOrFail($id);

        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'slug'        => 'sometimes|nullable|string|max:255|unique:itineraries,slug,'.$itin->id,
            'country_id'  => 'sometimes|nullable|integer|exists:countries,id',
            'days'        => 'sometimes|nullable|integer|min:1|max:30',
            'content'     => 'sometimes|nullable|string',
            'cover_image' => 'sometimes|nullable|string|max:255',
        ]);

        if (array_key_exists('title', $data) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $itin->update($data);
        return response()->json(['data' => $itin]);
    }

    public function deleteItinerary($id)
    {
        Itinerary::findOrFail($id)->delete();
        return response()->json(['message' => 'Itinerary deleted']);
    }

    // COUNTRIES

    public function countriesIndex(Request $request)
    {
        $query = Country::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->get('q').'%');
        }

        return $this->paginate($query->orderBy('name'));
    }

    public function showCountry($id)
    {
        $country = Country::findOrFail($id);
        return response()->json(['data' => $country]);
    }

    public function storeCountry(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:countries,slug',
            'code'        => 'nullable|string|max:2',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $country = Country::create($data);
        return response()->json(['data' => $country], 201);
    }

    public function updateCountry(Request $request, $id)
    {
        $country = Country::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'slug'        => 'sometimes|nullable|string|max:255|unique:countries,slug,'.$country->id,
            'code'        => 'sometimes|nullable|string|max:2',
            'description' => 'sometimes|nullable|string',
            'cover_image' => 'sometimes|nullable|string|max:255',
        ]);

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $country->update($data);
        return response()->json(['data' => $country]);
    }

    public function deleteCountry($id)
    {
        Country::findOrFail($id)->delete();
        return response()->json(['message' => 'Country deleted']);
    }

    // helper: єдина пагінація у відповіді

    protected function paginate($query, Request $request = null)
    {
        $request = $request ?: request();
        $perPage = (int) $request->get('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $paginator = $query->paginate($perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
}
