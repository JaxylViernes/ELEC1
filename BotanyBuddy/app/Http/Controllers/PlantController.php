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
        $apiKey = '9nyi5mKWgJB1LaSTuigkNQMX5c97yxKJVPTb3XemTabJMS29ev'; // Replace with your actual API key

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
        $apiKey = '9nyi5mKWgJB1LaSTuigkNQMX5c97yxKJVPTb3XemTabJMS29ev'; // Replace with your actual API key
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


    public function savePlant(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'identifiedPlantName' => 'required|string',
            'probability' => 'required|numeric',
            'plantDescription' => 'required|string',
            'similarImages' => 'required|array',
        ]);

        // Create a new Plant instance and save it to the database
        Plant::create($validatedData);
        return redirect()->route('plant.result')->with('success', 'Category created successfully!');

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Plant details saved successfully.');
    }
}
