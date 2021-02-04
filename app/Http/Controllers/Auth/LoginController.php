<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Validator;
use Socialite;
use App\User;
use App\SocialLgoin;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        ($this->middleware('guest')->except(['logout','customOut']));

    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function customLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,mobile',
            'password' => 'required|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            //$this->validateLogin($request);

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->customAttemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function customAttemptLogin(Request $request)
    {
        return $this->guard()->attempt(
           $this->customCredentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function customCredentials(Request $request)
    {
        return $request->only('mobile', 'password');
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        
        if(strtolower($user->roles[0]->name)==strtolower('Admin')){

           return redirect('/dashboard');
        }else{
            if ($request->ajax()){
                return response()->json([
                    'auth' => auth()->check(),
                    'user' => $user,
                    'intended' => $this->redirectPath(),
                ]);

            }

        }


    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customLogout(Request $request)
    {
        //dd($request->all());
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider,Request $request)
    {
        if (! $request->input('code')) {
            return redirect('/')->withErrors('Login failed: '.$request->input('error').' - '.$request->input('error_reason'));
        }
        $user= Socialite::driver($provider)->stateless()->user();

       // dd($user);
        //dd(['name'=>$user->name,'email'=>$user->email,'gender'=>(isset($user->user['gender']))?$user->user['gender']:'','photo'=>$user->avatar_original]);
            if($shana_user=User::where('email',$user->email)->first())
                {
                   $final_user=auth()->loginUsingId($shana_user->id);
                    //  dd($final_user);
                    $path=publicAsset('');
                    // dd($path);
                     return redirect('/');
                }
                else
                { 
                    $request->session()->put(['name'=>$user->name,'email'=>$user->email,'gender'=>(isset($user->user['gender']))?$user->user['gender']:'','photo'=>$user->avatar_original]);
                    $user_id= User::insertGetId([ 'mobile' => isset($request->mobile)?$request->mobile:null,
                        'email' => $user->email,
                        'password' => bcrypt('password'),
                        'name' => $user->name,
                        'device_token' => '',
                        'self_ref_code' => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 6),
                        'total_ref_amt' => 0,
                        'photo' => isset($user->avatar_original) ? $user->avatar_original : ('uploads/profile/profile.jpg'),]);
                    $social=SocialLgoin::insert(['email'=>$user->email,'name'=>$user->name,'profile'=>isset($user->avatar_original) ? $user->avatar_original : ('uploads/profile/profile.jpg'),'social_id'=>$request->social_id,'user_id'=>$user_id]);
                    if($social){
                         Auth::loginUsingId($user_id);
                         Auth::user()->photo = cloudUrl(Auth::user()->photo);
                    }
                    return redirect('/');
                }
                // $request->session()->put(['name'=>$user->name,'email'=>$user->email,'gender'=>(isset($user->user['gender']))?$user->user['gender']:'','photo'=>$user->avatar_original]);
                //     return redirect('signUp');
      }
}
