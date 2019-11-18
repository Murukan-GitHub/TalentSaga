<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Suitcore\Accounts\SocialAccountInterface;
use Suitcore\Repositories\SuitRepository;

class UserRepository extends SuitRepository
{
    public function __construct()
    {
        $this->mainModel = new User;
    }

    protected function getMainColumnFromAccount(SocialAccountInterface $account)
    {
        $columns = [];

        if ($idKey = $account->getIdentifierColumn()) {
            $columns[$idKey] = $account->getId();
        }

        if ($tokenKey = $account->getTokenColumn()) {
            $columns[$tokenKey] = $account->getAccessToken();
        }

        if ($secretKey = $account->getSecretColumn()) {
            $columns[$secretKey] = $account->getSecret();
        }

        return $columns;
    }

    /**
     * @param SocialAccountInterface $account
     * @return User|null
     */
    public function createFromSocialAccount(SocialAccountInterface $account)
    {
        $columns = $this->getMainColumnFromAccount($account);

        // Find by social account or by email.
        /** @var User $user */
        $user = $this->mainModel->where(function (Builder $query) use ($columns, $account) {
            foreach ($columns as $field => $value) {
                $query->orWhere($field, $value);
            }

            $query->orWhere('email', $account->getEmail());
        })->first();

        if (null !== $user) {
            // Just in case user login with different socmed credential but their email address is still registered.
            // Here we update their socmed credential if needed.
            $user->fill($columns);

            if ($user->isDirty() && $user->isValid('update')) {
                $user->save();
            }

            return $user;
        }

        $user = $this->mainModel->newInstance();

        // if new user
        if (! $user->exists) {
            $user->username = Str::slug($account->getName());
            $user->name     = $account->getName();
            $user->email    = $account->getEmail();
            $user->role     = User::USER;
            $user->status   = User::STATUS_ACTIVE;
            $user->password = \Hash::make($password = $account->getRandomPassword());
            $user->language_setting = config('app.fallback_locale', 'en');
        }

        if (! $user->picture) {
            $user->picture  = $account->getPicture();
        }

        foreach ($columns as $field => $value) {
            if (! $user->$field) {
                $user->$field = $value;
            }
        }

        if ($user->save()) {
            \Mail::send('emails.welcomewithpassword', [
                'name' => $account->getName(),
                'email' => $account->getEmail(),
                'username' => $account->getUsername(),
                'password' => $password,
                'accountType' => $account->getType(),
                'accountName' => $account->getName()
            ], function (Message $message) use ($account) {
                $message
                    ->to($account->getEmail(), $account->getName())
                    ->subject('Welcome to Talentsaga');
            });

            return $user;
        }

        return null;
    }
}
