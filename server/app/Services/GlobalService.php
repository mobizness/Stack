<?php

namespace App\Services;

use App;
use App\SplashPage;
use Socialite;

class GlobalService 
{

    public static function getSocialiteProvider($splash_id, $providerName){
        $app = App::getFacadeRoot();

        $providerKey = SplashPage::getSocialiteConfig($splash_id, $providerName);
        if(!$providerKey){
            return false;
        }
        switch(strtolower($providerName)){
            case "facebook":
                $path = '\Laravel\Socialite\Two\FacebookProvider';
            break;
            case "github":
                $path = '\Laravel\Socialite\Two\GithubProvider';
            break;
            case "google":
                $path = '\Laravel\Socialite\Two\GoogleProvider';
            break;
            case "linkedin":
                $path = '\Laravel\Socialite\Two\LinkedInProvider';
            break;
        }
        if(!empty($path)){
            $provider = Socialite::buildProvider($path, $providerKey);
        }else{
            $provider = Socialite::driver( $providerName );

        }
        return $provider;
    }
    
    public static function encryptPassword($pwd, $challenge, $uamsecret) {
        $hex_chal = pack('H32', $challenge);                  //Hex the challenge
        $newchal = pack('H*', md5($hex_chal . $uamsecret));    //Add it to with $uamsecret (shared between chilli an this script)
        $response = md5("\0" . $pwd . $newchal);              //md5 the lot
        $newpwd = pack('a32', $pwd);                //pack again
        $password = implode('', unpack('H32', ($newpwd ^ $newchal))); //unpack again
        return $password;
    }        
    
}