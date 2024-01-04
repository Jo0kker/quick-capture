<?php

namespace App\Http\Controllers;

use App\Models\CaptureLinkedin;
use App\Services\OpenApiFormater;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class LinkedinController extends Controller
{

    // on récupère le contenu de la page html de linkedin
    // on doit filtrer pour eviter d'envoyer des données inutiles
    // on envoye le html filtré à openApi pour qu'il nous renvoie un json avec les infos essentials
    // on renvoi en json les infos essentials à notre front
    public function getDataFromOpenApi(Request $request)
    {
        $html = $request->input('content');

        $html = substr($html, 0, 600);

        $html = OpenApiFormater::cleanLinkedinHtml($html);
        $html = OpenApiFormater::addInstructionForLinkedin($html);



        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $html,
                ]
            ],
        ]);

        // parse data in json
        return response()->json([
            'message' => 'success',
            'data' => json_decode($response->choices[0]->message->content),
            'tokens' => $response->usage->totalTokens
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => '',
            'email' => '',
            'phone' => '',
            'function' => '',
            'entreprise' => '',
            'lieu' => '',
        ]);

        $user = CaptureLinkedin::create($request->all());

        return response()->json([
            'message' => 'success',
            'data' => $user,
        ]);
    }

}
