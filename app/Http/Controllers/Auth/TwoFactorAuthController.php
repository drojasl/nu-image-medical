<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Services\ExternalApiService;

class TwoFactorAuthController extends Controller
{
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ExternalApiService $externalApiService)
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('tfa');
        $this->middleware('throttle:6,1')->only('tfa');
    }

    public function index() {
        return view('auth.tfa');
    }

    public function validateCode(Request $request) {
        $validatedData = $request->validate([
            'tfa_code' => 'required|digits:4',
        ]);

        if (auth()->user()->validateTFA($validatedData['tfa_code'])) {
            return redirect('/vin');
        }
        return redirect()->back()->withErrors(['error' => 'Invalid 2FA code']);
    }

    public function resendCode() {
        auth()->user()->generateTFA();

        $apiResponse = $this->externalApiService->sendTFA(auth()->user()->phone, auth()->user()->tfa_code);

        if(!$apiResponse['success']) {
            return redirect()->back()->withErrors(['error' => 'Error trying to send the 2FA code: ' . $apiResponse['message']]);
        }

        return redirect('/tfa');
    }
}
