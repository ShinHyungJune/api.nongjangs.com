<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Kakao\KakaoProvider;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use SocialiteProviders\Naver\NaverExtendSocialite;

class NaverCustomProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'NAVER-CUSTOM';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://nid.naver.com/oauth2.0/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://nid.naver.com/oauth2.0/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://openapi.naver.com/v1/nid/me',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     *
     * @see https://developers.naver.com/docs/login/profile/
     */
    protected function mapUserToObject(array $user)
    {
        $contact = Arr::get($user, 'response.mobile');

        if($contact)
            $contact = str_replace("-", "", $contact);

        $sex = Arr::get($user, 'response.gender');

        if($sex == "F")
            $sex = "여자";

        if($sex == "M")
            $sex = "남자";

        if($sex == "U")
            $sex = null;

        return (new User())->setRaw($user)->map([
            'id'        => Arr::get($user, 'response.id'),
            'name'      => Arr::get($user, 'response.name'),
            'nickname'  => Arr::get($user, 'response.nickname'),
            'email'     => Arr::get($user, 'response.email'),
            'avatar'    => Arr::get($user, 'response.profile_image'),
            'sex'    => $sex,
            'birth'    => Arr::get($user, 'response.birthyear'),
            'contact'    => $contact,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
