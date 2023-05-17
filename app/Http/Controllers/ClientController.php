<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Classes\MailHandler;

class ClientController extends Controller
{
    function create(Request $request) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'comments' => 'nullable|string|max:100',
            ]);

            $client = new Client();
            $client->name = $validatedData['name'];
            $client->email = $validatedData['email'];
            $client->phone = $validatedData['phone'];
            $client->save();

            $mailHandler = new MailHandler($client);
            $mailHandler->sendEmail();

        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error validating the input data', 'error' => $e->getMessage()], 422);

        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Error saving in database', 'error' => $e->getMessage()], 503);
            
        } catch(\Symfony\Component\Mailer\Exception\TransportException $e) {
            // TODO: Send to a queue to retry the mail notification
            return response()->json(['message' => 'Error sending the email', 'error' => $e->getMessage()], 503);
            
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error trying to create client', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Client saved succesfully'], 200);
    }
}
