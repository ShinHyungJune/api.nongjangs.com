<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailVerificationResource;
use App\Models\EmailVerification;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function index()
    {
        return EmailVerificationResource::collection(EmailVerification::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'code' => ['required'],
            'verified_at' => ['nullable', 'date'],
        ]);

        return new EmailVerificationResource(EmailVerification::create($data));
    }

    public function show(EmailVerification $emailVerification)
    {
        return new EmailVerificationResource($emailVerification);
    }

    public function update(Request $request, EmailVerification $emailVerification)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'code' => ['required'],
            'verified_at' => ['nullable', 'date'],
        ]);

        $emailVerification->update($data);

        return new EmailVerificationResource($emailVerification);
    }

    public function destroy(EmailVerification $emailVerification)
    {
        $emailVerification->delete();

        return response()->json();
    }
}
