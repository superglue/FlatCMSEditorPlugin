<?php

class FlatCMSEditorPluginConfiguration
{
  protected static $purifier = null;
  
  public static function getPurifier()
  {
    if (self::$purifier instanceof HTMLPurifier)
    {
      return self::$purifier;
    }
    require_once dirname(__FILE__) . '/../lib/vendor/htmlpurifier/library/HTMLPurifier.auto.php';
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.SerializerPath', sgConfiguration::getPath('settings.cache_dir'));
    self::$purifier = new HTMLPurifier($config);
    
    return self::$purifier;
  }
  
  public function preRender()
  {
    if (Zend_Auth::getInstance()->hasIdentity())
    {
      $controller = sgContext::getInstance()->getController();
      
      if ($controller instanceof FlatCMSPluginController)
      {
        $session = new Zend_Session_Namespace(Zend_Auth::getInstance()->getStorage()->getNamespace());
        $session->FlatCMSEditorPluginFileMTime = filemtime(FlatCMSPluginPageModel::getPagePath(sgContext::getInstance()->getCurrentPath()));
        
        //figure out better way to handle this so libraries aren't double loaded
        $controller->scripts[] = sgToolkit::url('/js/FlatCMSEditorPlugin/jquery.min.js');
        $controller->scripts[] = sgToolkit::url('/js/FlatCMSEditorPlugin/jquery.jeditable.mini.js');
        $controller->scripts[] = sgToolkit::url('/js/FlatCMSEditorPlugin/jquery.jeditable.autogrow.js');
        $controller->scripts[] = sgToolkit::url('/js/FlatCMSEditorPlugin/tinymce/jscripts/tiny_mce/jquery.tinymce.js');
        $controller->scripts[] = sgToolkit::url('/js/FlatCMSEditorPlugin/jquery.jeditable.tinymce.js');
        $controller->scripts[] = sgToolkit::url('/js/FlatCMSEditorPlugin/jquery.autogrow.js');
        $controller->scripts[] = sgToolkit::url('/js/FlatCMSEditorPlugin/init.js');
        
        $controller->js_settings['FlatCMSEditorPlugin'] = array(
          'saveURL' => sgToolkit::url(sgConfiguration::getPath('routing.FlatCMSEditorPlugin_save.path')),
          'currentPath' => sgContext::getInstance()->getCurrentPath(),
        );
        if (isset($controller->content) && is_array($controller->content))
        {
          $textarea_fields = sgConfiguration::getPath('settings.FlatCMSEditorPlugin.textarea_fields', array());
          foreach ($controller->content as $key => &$field)
          {
            if (in_array($key, $textarea_fields))
            {
              $field = '<div class="editable-area" id="' . $key . '">' . $field . '</div>';
            }
            else
            {
              $field = '<div class="editable" id="' . $key . '">' . $field . '</div>';
            }
          }
        }
      }
    }
  }
}
