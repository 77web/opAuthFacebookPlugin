<?php

class opAuthLoginFormFacebook extends opAuthLoginForm
{
  public function configure()
  {
    $this->setOption('is_use_remember_me', false);
    
    parent::configure();
  }
  
  public function setNextUri($uri)
  {
    $this->values['next_uri'] = $uri;
  }
}