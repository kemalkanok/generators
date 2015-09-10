<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 09/06/15
 * Time: 11:26
 */

namespace Kanok\Generators\Framework\Helpers;


use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class Auth {

    /**
     *  returns the logged in user
     * @return Collection
     */
    public static function user()
    {
        return app(config('auth.model'))->find(session('_user_id'));
    }

    /**
     * Perform login process via user object
     * @param Model|User $user
     */
    public static function LoginWithUser(Model $user)
    {
        session()->put('_user_id',$user->id);
    }

    /**
     * Attempts for login process
     * @param Request $data
     * @return bool
     */
    public static function attempt($data)
    {
        $data = $data->only(config('auth.fields'));
        if($user = app(config('auth.model'))->where($data)->first())
        {
            session()->put('_user_id',$user->id);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * performs logout
     * @return bool
     */
    public static function logout()
    {
        session()->pull('_user_id');
        return true;
    }

    /**
     * determines the user whether a guest
     * @return bool
     */
    public static function guest()
    {
        return  !session('_user_id');
    }


}