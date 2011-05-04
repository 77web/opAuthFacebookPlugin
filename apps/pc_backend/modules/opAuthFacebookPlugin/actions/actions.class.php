<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthFacebookPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage opAuthFacebookPlugin
 * @author     Hiromi Hishida<info@77-web.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class opAuthFacebookPluginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('opAuthFacebookPlugin', 'config');
  }
  
  public function executeConfig(sfWebRequest $request)
  {
    $adapter = new opAuthAdapterFacebook('Facebook');
    $this->form = $adapter->getAuthConfigForm();
    if($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('auth'.$adapter->getAuthModeName()));
      if($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('opAuthFacebookPlugin/config');
      }
    }
  }
}
