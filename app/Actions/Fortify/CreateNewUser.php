<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Controllers\AccountExpirationController;
use Illuminate\Support\Facades\Log;
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
// THÊM HÀM CONSTRUCTOR ĐỂ TIÊM DEPENDENCY (Dependency Injection)
    protected $expirationController;

    public function __construct(AccountExpirationController $expirationController)
    {
        $this->expirationController = $expirationController;
    } 

    public function create(array $input): User
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

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
          
        ]);
        // GỌI CONTROLLER ĐỂ ÁP DỤNG TRIAL
        $this->expirationController->assignFreeTrial($user);

        return $user;
    }
}
