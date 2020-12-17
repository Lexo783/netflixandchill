<?php


namespace App\Services;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Profile
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getSessionProfile(){

        return $this->session->get('profile',[]);

    }

    public function setSessionProfile($profil){

        $this->session->remove('profile');
        $sessionProfile = $this->getSessionProfile();
        $sessionProfile['profile'] = $profil;
        $this->session->set('profile',$sessionProfile);

    }

    public function getProfile(){

        $sessionProfile = $this->getSessionProfile();
        if (!$sessionProfile){

            return null;

        }
        return $sessionProfile['profile'];

    }
}