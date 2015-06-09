<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 09/06/15
 * Time: 11:26
 */

namespace Kanok\Generators\Framework\Helpers;
use App\User;

class Auth {

    /**
     *  returns the logged in user
     * @return Collection
     */
    public static function user()
    {
        return User::find(session('_user_id'));
    }

    /**
     * Perform login process via user object
     * @param User $user
     */
    public static function LoginWithUser(User $user)
    {
        session()->put('_user_id',$user->id);
    }

    /**
     * Attempts for login process
     * @param array $data
     * @return bool
     */
    public static function attempt($data)
    {
        if($user = User::where($data)->first())
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