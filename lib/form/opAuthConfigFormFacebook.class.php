<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthConfigFormFacebook represents a form to configuration.
 *
 * @package    opAuthFacebookPlugin
 * @subpackage form
 * @author     Hiromi Hishida<info@77-web.com>
 */
class opAuthConfigFormFacebook extends opAuthConfigForm
{
  public function setup()
  {
    opAuthConfigForm::setup();
    if ($w = $this->getWidget('facebook_app_id'))
    {
      $w->setAttribute('size', 60);
    }
    if ($w = $this->getWidget('facebook_app_secret'))
    {
      $w->setAttribute('size', 60);
    }
  }
}
