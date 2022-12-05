<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'sip' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'ktp' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'] ?? null,
            'gender' => $input['gender'] ?? null,
            'address' => $input['address'] ?? null,
            'email' => $input['email'],
            'phonenumber' => $input['phonenumber'] ?? null,
            // 'password' => Hash::make($input['password']),
            'password' => $input['password'],
            'role_id' => $input['role_id'],
            'sip' => $input['sip'] ?? null,
            'ktp' => $input['ktp'] ?? null,
        ]);
    }
}
