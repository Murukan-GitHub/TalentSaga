<?php 

namespace Suitcore\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MySienta\User;
use Auth;
use Hash;
use Suitcore\Accounts\SocialAccountInterface;
use Suitcore\Repositories\Contract\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function findByIdentifier($identifier)
    {
        if($identifier)
        {
            return User::whereUsername($identifier)->orWhere('email', '=', $identifier)->first();
        }

        return false;
    }

    public function byIdentifier($identifier)
    {
        $email = $identifier;
        if(!is_email($email))
        {
            $user = $this->findByIdentifier($identifier);
            if($user)
            {
                $email = $user->email;
            }
        }
        return User::whereEmail($email)->get();
    }

    public function createFromSocialAccount(SocialAccountInterface $account)
    {
        $identifierColumn = $account->getIdentifierColumn();

        //find by social account id
        $user = User::where($identifierColumn, '=', $account->getId())->first();

        // if not found, find by email
        if(!$user && $account->getEmail())
        {
            $user = User::where('email', '=', $account->getEmail())->first();
        }

        // if still not found, create new
        if(!$user)
        {
            $user = new User;
        }

        // if new user
        if(!$user->exists)
        {
            $user->username = ($account->getId() . "@" . ($account->getType() == "facebook" ? "facebook.com" : "twitter.com"));
            $user->name     = $account->getName();
            $user->email    = ($account->getEmail() ? $account->getEmail() : "");
            $user->type     = User::MEMBER;
            $user->status   = User::ACTIVATED;
            $user->password = Hash::make($account->getRandomPassword());
        }

        if(!$user->profile_image_url)
        {
            $user->profile_image_url  = $account->getPicture();
        }

        // set social account ID
        if(!$user->{$identifierColumn})
        {
            $user->{$identifierColumn} = $account->getId();
        }

        if($user->save())
        {
            // Access Token
            if ($account->getTokenColumn())
            {
                $column = $account->getTokenColumn();
                $user->{$column} = $account->getAccessToken();
                $user->save();
            }
            // Secret
            if ($account->getSecretColumn())
            {
                $column = $account->getSecretColumn();
                $user->{$column} = $account->getSecret();
                $user->save();
            }
        }

        return $user;
    }

    public function deactivateSimilarAccount($user)
    {
        if(is_email($user->email))
        {
            return User::whereEmail($user->email)->where('id', '<>', $user->id)->update(['status' => User::BANNED]);
        }

        return false;
    }

    public function facebookConnect($user, $accessToken, $facebookId, $facebookEmail)
    {
        $user->fb_access_token = $accessToken;
        $user->save();
    }

    /**
     * Somehow if needed such as email not same with username
     *
     **/
    protected function generateUniqueUsername($email)
    {
        $temp = explode('@', $email);
        if(count($temp) >= 2)
        {
            list($username, $domain) = $temp;
        }
        else
        {
            $username = $temp[0];
        }

        $suffix = 0;

        $checkUsername = $username;
        do
        {
            $exist = User::where('username', '=', $checkUsername)->first();
            $suffix++;
            $checkUsername = $checkUsername . $suffix;
        }
        while($exist);

        $suffix--;
        if(0 == $suffix)
        {
            $suffix = '';
        }

        return $username . $suffix;
    }
}
