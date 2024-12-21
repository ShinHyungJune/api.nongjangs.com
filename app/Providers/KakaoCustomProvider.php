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

class KakaoCustomProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'KAKAO-CUSTOM';

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://kauth.kakao.com/oauth/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://kauth.kakao.com/oauth/token';
    }

    /**
     * Get the access token for the given code.
     *
     * @param string $code
     *
     * @return string
     */
    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->request('POST', $this->getTokenUrl(), [
            'form_params' => $this->getTokenFields($code),
        ]);

        $this->credentialsResponseBody = json_decode($response->getBody(), true);

        return $this->parseAccessToken($response->getBody());
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param string $code
     *
     * @return array
     */
    protected function getTokenFields($code)
    {
        $array = [
            'grant_type'   => 'authorization_code',
            'client_id'    => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'code'         => $code,
        ];

        if ($this->clientSecret) {
            $array['client_secret'] = $this->clientSecret;
        }

        return $array;
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->request('POST', 'https://kapi.kakao.com/v2/user/me', [
            'headers' => ['Authorization' => 'Bearer '.$token],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        $sex = Arr::get($user, 'kakao_account.gender');

        if($sex == "male")
            $sex = "남자";

        if($sex == "female")
            $sex = "여자";

        $validEmail = Arr::get($user, 'kakao_account.is_email_valid');
        $verifiedEmail = Arr::get($user, 'kakao_account.is_email_verified');

        return (new User())->setRaw($user)->map([
            'id'        => $user['id'],
            'nickname'  => Arr::get($user, 'properties.nickname'),
            'sex'      => $sex,
            'name'      => Arr::get($user, 'kakao_account.name'),
            'birth'      => Arr::get($user, 'kakao_account.birthyear'),
            'contact'     => str_replace("-", "", str_replace("+82 ", "0", Arr::get($user, 'kakao_account.phone_number'))),
            'email'     => $validEmail && $verifiedEmail ? Arr::get($user, 'kakao_account.email') : null,
            'avatar'    => Arr::get($user, 'properties.profile_image'),
        ]);
    }
}
