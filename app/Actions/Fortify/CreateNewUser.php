<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Schedule;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'username' => $input['username'],
            'uuid' => Str::uuid(),
        ]);

        $uuid = Str::uuid();

        Schedule::create([
            "name" => "Default",
            "isPublic" => false,
            "owner" => $user->id,
            "slug" => $uuid,
            "uuid" => $uuid
        ]);

        return $user;
    }
}
