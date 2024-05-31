<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Plant;

class PlantController extends Controller
{
    public function identifyPlant(Request $request)
    {
        Log::info('identifyPlant method called');

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: No image selected.');
            return redirect()->back()->with('error', 'No image selected. Please upload an image first.');
        }

        Log::info('Image uploaded successfully');

        $image = $request->file('image');
        $imageContent = base64_encode(file_get_contents($image->getRealPath()));
        $apiKey = '9814w2Nugc6oa0TJ9hDbVcJxa4cVGTSJNEt86tve2n82DN03lt'; // Replace with your actual API key

        $client = new Client();
        $url = 'https://plant.id/api/v3/identification';

        try {
            Log::info('Sending request to plant.id API');
            $response = $client->post($url, [
                'headers' => [
                    'Api-Key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'images' => ["data:image/jpeg;base64,$imageContent"],
                    'similar_images' => true
                ]
            ]);

            Log::info('Received response from plant.id API: ' . $response->getStatusCode());

            if ($response->getStatusCode() == 201) {
                $responseData = json_decode($response->getBody(), true);

                if (
                    isset($responseData['result']['classification']['suggestions']) &&
                    count($responseData['result']['classification']['suggestions']) > 0
                ) {
                    $suggestion = $responseData['result']['classification']['suggestions'][0];
                    $identifiedPlantName = $suggestion['name'];
                    $probability = $suggestion['probability'];
                    $similarImages = $suggestion['similar_images'];

                    Log::info('Plant identified: ' . $identifiedPlantName);

                    // Call fetchPlantDetails method to get access token and plant description
                    $accessToken = $this->fetchPlantDetails($identifiedPlantName);
                    $plantDescription = $this->fetchPlantDescription($accessToken);

                    // Pass identified plant details to the view
                    return view('plant.result', compact('identifiedPlantName', 'probability', 'similarImages', 'accessToken', 'imageContent', 'plantDescription'));
                } else {
                    Log::info('No plant identification suggestions found');
                    return redirect()->back()->with('message', 'No plant identification suggestions found.');
                }
            } else {
                Log::error('HTTP request failed with status: ' . $response->getStatusCode());
                return redirect()->back()->with('error', 'HTTP request failed with status: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            Log::error('Error identifying plant: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error identifying plant.');
        }
    }

    public function fetchPlantDetails($searchQuery)
    {
        $apiKey = '9814w2Nugc6oa0TJ9hDbVcJxa4cVGTSJNEt86tve2n82DN03lt'; // Replace with your actual API key
        $apiUrl = 'https://plant.id/api/v3/kb/plants/name_search';
        

        if (empty($searchQuery)) {
            return response()->json(['error' => 'Please enter a plant name.'], 400);
        }

        $url = "$apiUrl?q=$searchQuery";
        $headers = [
            'Api-Key' => $apiKey,
            'Content-Type' => 'application/json',
        ];

        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => $headers,
            ]);

            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody(), true);
                $entities = $responseData['entities'];

                if (!empty($entities)) {
                    $firstEntity = $entities[0];
                    $accessToken = $firstEntity['access_token'];
                    return $accessToken;
                } else {
                    return response()->json(['error' => 'No entities found.']);
                }
            } else {
                return response()->json(['error' => 'HTTP request failed.'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching access token.'], 500);
        }
    }

    public function fetchPlantDescription($accessToken)
    {
        $apiKey = '9814w2Nugc6oa0TJ9hDbVcJxa4cVGTSJNEt86tve2n82DN03lt'; // Replace with your actual API key
        $url = "https://plant.id/api/v3/kb/plants/$accessToken-?details=common_names,url,description,taxonomy,rank,gbif_id,inaturalist_id,image,synonyms,edible_parts,watering,propagation_methods&lang=en";
        $headers = [
            'Api-Key' => $apiKey,
            'Content-Type' => 'application/json',
        ];

        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => $headers,
            ]);

            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody(), true);
                $plantDescription = $responseData['description']['value'];
                return $plantDescription;
            } else {
                return response()->json(['error' => 'HTTP request failed.'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching plant description.'], 500);
        }
    }

    
    public function store(Request $request)
    {
        Plant::create([
            'name' => $request->identifiedPlantName, // Map form input to 'name'
            'probability' => $request->probability,
            'description' => $request->plantDescription, // Map form input to 'description'
            'similar_images' => $request->similarImages, // Map form input to 'similar_images'
        ]);

        // Redirect back with a success message
        return redirect()->route('index')->with('success', 'Plant details saved successfully.');
    }
    public function index()
    {
        $plants = Plant::all(); // Fetch all plants from the database
        return view('plant.display', compact('plants'));
    }
   
    public function destroy(Plant $plant){
        $plant->delete();
        return redirect()->route('plant.display');
}
private $apiKey = 'rG_dwqQaWWJvhEpeuvNb5wrfLyeJI-E2kD4Q5_oKe10'; // Replace with your Trefle API key

public function facts()
{
    $facts = [];
    $fact = '';
    $imageUrl = '';

    // Fetch random plant facts if no data is provided
    $url = "https://trefle.io/api/v1/plants?token={$this->apiKey}";
    $response = Http::get($url);

    if ($response->successful()) {
        $data = $response->json()['data'];
        if (count($data) >= 2) {
            $randomKeys = array_rand($data, 2);
            $plants = [$data[$randomKeys[0]], $data[$randomKeys[1]]];
            $facts = array_map(function($plant) {
                return [
                    'fact' => "{$plant['common_name']} belongs to the {$plant['family_common_name']} family. Scientific name: {$plant['scientific_name']}.",
                    'imageUrl' => $plant['image_url']
                ];
            }, $plants);
        } else {
            $facts = [["fact" => "Not enough plant data available.", "imageUrl" => null]];
        }
    } else {
        $facts = [["fact" => "Failed to fetch plant fact. Response status: " . $response->status() . ". Response body: " . $response->body(), "imageUrl" => null]];
    }

    return $facts;
}
}
