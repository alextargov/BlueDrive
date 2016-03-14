<?php

namespace App\Http\Controllers\Auth;
use DB;
use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    
    //redirecting property
    private $redirectTo = '';
    
    
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
    
    protected $username = 'username';
     
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $getID = User::orderBy('userid', 'desc')->FirstOrFail();
        $arr = array('Снимки', 'Документи', 'Музика', 'Видео');
        if($getID) {
            foreach($arr as $one){
            $fileData = [
            'userid' => $getID->userid + 1,
            'folder' => $one
            ];
                DB::table('folders')->insert($fileData);
            }  
        }
        else {
            foreach($arr as $one){
            $fileData = [
            'userid' => 1,
            'folder' => $one
        ];
            DB::table('folders')->insert($fileData);
        }
        }
        
        
              
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
    
}
