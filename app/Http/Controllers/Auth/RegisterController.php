<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\RefreshToken;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, HandlesOAuthErrors;


    /**
     * The authorization server.
     *
     * @var AuthorizationServer
     */
    protected $server;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send registration request, then login with and response with access token
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @internal param Request $request
     *
     * @internal param ServerRequestInterface $serverRequest
     *
     */
    public function registerJson(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $token = $user->createToken(null);

        $refreshToken = new RefreshToken();

        $expireDateTime = $token->token->expires_at->getTimestamp();

        $jwtAccessToken = $token->accessToken;

        $responseParams = [
            'token_type'   => 'Bearer',
            'expires_in'   => $expireDateTime - (new \DateTime())->getTimestamp(),
            'access_token' => (string) $jwtAccessToken,
        ];

        //if ($refreshToken instanceof RefreshTokenEntityInterface) {
        //    $refreshToken = $this->encrypt(
        //        json_encode(
        //            [
        //                'client_id'        => $token->token->client_id,
        //                'refresh_token_id' => $refreshToken->getIdentifier(),
        //                'access_token_id'  => $token->token->id,
        //                'scopes'           => $token->token->scopes,
        //                'user_id'          => $user->id,
        //                'expire_time'      => $refreshToken->getExpiryDateTime()->getTimestamp(),
        //            ]
        //        )
        //    );
        //
        //    $responseParams['refresh_token'] = $refreshToken;
        //}

        return (new JsonResponse($responseParams, 200))->withHeaders([
            'pragma'        =>  'no-cache',
            'cache-control' =>  'no-store',
            'content-type'  =>  'application/json; charset=UTF-8',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
