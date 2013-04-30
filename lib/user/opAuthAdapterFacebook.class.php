<?php

class opAuthAdapterFacebook extends opAuthAdapter
{
  protected
    $authModuleName = 'Facebook',
    $appId = null,
    $appSecret = null,
    $callbackUrl = null,
    $requestPerms = 'email,user_birthday';
  
  
  public function configure()
  {
    $this->appId = $this->getAuthConfig('facebook_app_id');
    $this->appSecret = $this->getAuthConfig('facebook_app_secret');
    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
    $this->callbackUrl = url_for('member/login?authMode=Facebook', true);
    
    if (!empty($this->appId) && !empty($this->appSecret))
    {
      $this->facebook = new Facebook(array('appId'=>$this->appId, 'secret'=>$this->appSecret));
    }

  }
  
  public function authenticate()
  {
    $result = parent::authenticate();
    if (!isset($this->facebook))
    {
      return $result;
    }
    
    $fbUserId = $this->facebook->getUser();
    
    if ($fbUserId)
    {
      try
      {
        $fbIdConfig = Doctrine::getTable('MemberConfig')->findOneByNameAndValue('facebook_user_id', $fbUserId);
        if($fbIdConfig)
        {
          //already member of my sns
          $member = Doctrine::getTable('Member')->find($fbIdConfig->getMemberId());
        }
        else
        {
          //new member
          $member = Doctrine::getTable('Member')->createPre();
          $member->setConfig('facebook_user_id', $fbUserId);
          $member->setIsActive(true);
          
          //get member info
          $me = $this->facebook->api('/me');
          $member->setName($me['name']);
          $member->setConfig("pc_address",$me['email']);
        }
        $member->save();
        
        $result = $member->getId();
      }
      catch (Exception $e)
      {
        //if error nothing to do
        var_dump($e->getMessage());
      }
      $uri = sfContext::getInstance()->getUser()->getAttribute('next_uri');
      if ($uri)
      {
        sfContext::getInstance()->getUser()->setAttribute('next_uri', false);
        $this->getAuthForm()->setNextUri($uri);
      }
      
      return $result;
    }
    
    sfContext::getInstance()->getUser()->setAttribute('next_uri', $this->getAuthForm()->getValue('next_uri'));
    header('Location: '.$this->facebook->getLoginUrl(array('scope'=>$this->requestPerms)));
    exit;
  }

  public function registerData($memberId, $form)
  {
    $member = Doctrine::getTable('Member')->find($memberId);
    if (!$member)
    {
      return false;
    }

    $member->setIsActive(true);

    return $member->save();
  }


  public function isRegisterBegin($member_id = null)
  {
    opActivateBehavior::disable();
    $member = Doctrine::getTable('Member')->find((int)$member_id);
    opActivateBehavior::enable();

    if (!$member || $member->getIsActive())
    {
      return false;
    }

    return true;
  }

  public function isRegisterFinish($member_id = null)
  {
    return false;
  }
}