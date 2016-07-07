<?php

namespace Laravel\Socialite\Two;

use DB;
use Carbon\Carbon;

class JinshujuProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'public',
        'forms',
        'read_entries',
        'form_setting',
    ];
    
    /**
     * The user fields being requested.
     *
     * @var array
     */
    protected $fields = ['email', 'nickname', 'avatar', 'paid'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://account.jinshuju.net/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://account.jinshuju.net/oauth/token';
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return array_add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://api.jinshuju.net/v4/me?access_token='.$token;

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions()
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'email' => array_get($user, 'email'), 'nickname' => array_get($user, 'nickname'),
            'avatar' => array_get($user, 'avatar'),'paid' => array_get($user, 'paid')
        ]);
    }

    /**
     * Get the default options for an HTTP request.
     *
     * @return array
     */
    protected function getRequestOptions()
    {
        return [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormByToken($token)
    {
        $formUrl = 'https://api.jinshuju.net/v4/forms?access_token='.$token;

        $response = $this->getHttpClient()->get(
            $formUrl, $this->getRequestOptions()
        );
        
        //分页获取处理
        $next = $response->getHeader('Link');
        
        $result = $response->getBody();

        if(@strpos($next[0],"next")!==false){
           $a = (strrpos($next[0],"<"));
           $b = (strrpos($next[0],">"));
           $match = substr($next[0],$a+1,$b-1);

           $response = $this->getHttpClient()->get(
                $match, $this->getRequestOptions()
            );

           $temp = $response->getBody();
           $next = $response->getHeader('Link');

           $result = json_encode(
              array_merge(json_decode($result,true),json_decode($temp, true))
            );

           while(@strpos($next[0],",")!==false){
               $str = explode(', ', $next[0]);
               $a = (strripos($str[1],"<"));
               $b = (strripos($str[1],">"));
               $match = substr($str[1],$a+1,$b-1);

               $response = $this->getHttpClient()->get(
                    $match, $this->getRequestOptions()
                );

               $temp = $response->getBody();
               $next = $response->getHeader('Link');

               $result = json_encode(
                  array_merge(json_decode($result,true),json_decode($temp, true))
                );

           }
           
        }

        return json_decode($result , true);
    }

    /**
     * {@inheritdoc}
     */
    public function getFeildByToken($form,$token)
    {
        $detailUrl = 'https://api.jinshuju.net/v4/forms/'.$form.'?access_token='.$token;
        
        $response = $this->getHttpClient()->get(
            $detailUrl, $this->getRequestOptions()
        );
        
        //分页获取处理
        $next = $response->getHeader('Link');
        
        $result = $response->getBody();

        $select = json_decode($result, true);

        return json_encode($select['fields']);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByToken($form,$token)
    {
        $dataUrl = 'https://api.jinshuju.net/v4/forms/'.$form.'/entries?access_token='.$token;

        $response = $this->getHttpClient()->get(
            $dataUrl, $this->getRequestOptions()
        );

        //分页获取处理
        $next = $response->getHeader('Link');
        
        $result = $response->getBody();

        if(@strpos($next[0],"next")!==false){
           $a = (strrpos($next[0],"<"));
           $b = (strrpos($next[0],">"));
           $match = substr($next[0],$a+1,$b-1);

           $response = $this->getHttpClient()->get(
                $match, $this->getRequestOptions()
            );

           $temp = $response->getBody();
           $next = $response->getHeader('Link');

           $result = json_encode(
              array_merge(json_decode($result,true),json_decode($temp, true))
            );

           while(@strpos($next[0],",")!==false){
               $str = explode(', ', $next[0]);
               $a = (strripos($str[1],"<"));
               $b = (strripos($str[1],">"));
               $match = substr($str[1],$a+1,$b-1);

               $response = $this->getHttpClient()->get(
                    $match, $this->getRequestOptions()
                );

               $temp = $response->getBody();
               $next = $response->getHeader('Link');

               $result = json_encode(
                  array_merge(json_decode($result,true),json_decode($temp, true))
                );

           }
           
        }

        return json_decode($result, true);
    }
    
    /**
     * {@inheritdoc}
     */
    public function user()
    {

            if ($this->hasInvalidState()) {
                 throw new InvalidStateException;
            }

            $response = $this->getAccessTokenResponse($this->getCode());

            $user = $this->mapUserToObject($this->getUserByToken(
               $token = array_get($response, 'access_token')
            ));
            
            $refreshToken = array_get($response, 'refresh_token');
            $expires = array_get($response, 'expires_in') + array_get($response, 'created_at');

            date_default_timezone_set('Asia/Shanghai');
            $current_time = Carbon::now();

            $avatar = $user->getAvatar();
            if( $avatar == null){
                $avatar = "https://gd-prod-assets.b0.upaiyun.com/assets/avatar_default-1ef06a094c3f62c55d221b402a0f6f10.png";
            }

            DB::table('users')->insert(
                        [ 
                          'email' => $user->getEmail(),
                          'nickname' => $user->getNickname(),
                          'avatar' => $avatar,
                          'access_token' => $token, 
                          'refresh_token' => $refreshToken,  
                          'expires_in' => $expires, 
                          'created_at' => $current_time,
                          'updated_at' => $current_time 
                        ]
                        );
            session(['email'=> $user->getEmail()]);
        // session(['nickname'=> $user->getNickname()]);
        // session(['avatar'=> $user->getAvatar()]);
        // session(['access_token'=> $token]);
        // session(['refresh_token'=> $refreshToken]);
        // session(['expires_in'=> $expires]); 
        
        return $user->setToken($token)
                    ->setRefreshToken($refreshToken)
                    ->setExpiresIn($expires);

    }

    /**
     * {@inheritdoc}
     */
    public function refresh()
    {
        if(\Session::has('email')){
            
            $email= session('email');

            $user = DB::table('users')->where('email',$email)
                                      ->get();

            $refreshToken = $user[0]->refresh_token;
            $ExpiresIn = $user[0]->expires_in;

            $now = \Carbon\Carbon::now();

            if (strtotime($now) >= $ExpiresIn){
                $response = $this->getAccessTokenRefresh($refreshToken);
                
                $user = $this->mapUserToObject($this->getUserByToken(
                    $token = array_get($response, 'access_token')
                ));
                           
                $refreshToken = array_get($response, 'refresh_token');
                $expires = array_get($response, 'expires_in') + array_get($response, 'created_at');

                DB::table('users')
                             ->where('email',$email)
                             ->update([ 
                                      'access_token' => $token, 
                                      'refresh_token' => $refreshToken,  
                                      'expires_in' => $expires, 
                                    ]);

            }
        }else{
            return redirect("/");
        }
    }


}
