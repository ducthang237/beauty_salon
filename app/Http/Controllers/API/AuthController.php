<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Role;
use App\Models\Technical;
use App\Models\User;
use App\Repositories\Salon\SalonRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    protected $salonRepo;

    public function __construct(SalonRepositoryInterface $salonRepo)
    {
        $this->salonRepo = $salonRepo;
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $input = $request->all();
        try {
            $validator = Validator::make($input, [
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    'unique:users,email'
                ],
                'phone' => [
                    'required',
                    'regex:/^\d{3}-\d{3}-\d{4}$/'
                ],
                'role' => [
                    'required',
                    'in:1,2,3'
                ],
                'password' => [
                    'required',
                    // 'min:8',
                    // function ($attribute, $value, $fail) { // check password format
                    //     if(!preg_match("/[a-z]/", $value))  {
                    //         $fail('The '.$attribute.' must contain at least one lowercase letter.');
                    //     }
                    //     elseif(!preg_match("/[A-Z]/", $value))  {
                    //         $fail('The '.$attribute.' must contain at least one uppercase letter.');
                    //     }
                    //     elseif(!preg_match("/[0-9]/", $value))  {
                    //         $fail('The '.$attribute.' must contain at least one digit.');
                    //     }
                    //     elseif(!preg_match("/[@$!%*#?&]/", $value))  {
                    //         $fail('The '.$attribute.' must contain a special character.');
                    //     }
                    // }
                ],
                'c_password' => [
                    'required',
                    'same:password'
                ],
                'salon_id' => [
                    Rule::requiredIf($input['role'] === 1 || $input['role'] === 2), // if user is manager or technical, salon_id is required
                    function ($attribute, $value, $fail) { // check if salon_id is valid or not
                        $salons = $this->salonRepo->getAll()->toArray();
                        $salonIds = Arr::pluck($salons, 'id');
                        if (!in_array($value, $salonIds)) {
                            $fail('The '.$attribute.' is invalid.');
                        }
                    },
                ]
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            DB::beginTransaction();

            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            // Add role for user
            $user->roles()->attach($input['role']);

            // If user is manager or technical, create new technical
            if ($input['role'] === 1 || $input['role'] === 2) {
                $technicalData = [
                    'user_id' => $user->id,
                    'salon_id' => $input['salon_id']
                ];
                Technical::create($technicalData);
            }

            DB::commit();

            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['email'] =  $user->email;

            return $this->sendResponse($success, 'User register successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Register user failed: '.$e->getMessage());
            return $this->sendError('Register user failed ', $e->getMessage(), 500);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user = $request->user();
                $success['token'] =  $user->createToken('MyApp')->accessToken;
                $success['userId'] =  $user->id;

                $roles = $user->roles->map(function ($item, $key) {
                    return $item->id;
                });

                $success['roles'] = $roles;

                return $this->sendResponse($success, 'Login successfully');
            }

            return $this->sendError('Unauthorised.', 'Unauthorised', 401);
        } catch (Exception $e) {
            Log::error('Login failed: '.$e->getMessage());
            return $this->sendError('Login failed', $e->getMessage(), 500);
        }
    }

    /**
     * Logout API
     *
    */

    public function logout (Request $request) {
        $request->user()->token()->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
