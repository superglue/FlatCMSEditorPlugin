<?php

class FlatCMSEditorPluginController extends sgBaseController
{
  public function PUT()
  {
    ZendAuthPluginConfiguration::redirectIfNotAuth();
    $data = filter_input_array(INPUT_POST, array(
      'id' => FILTER_SANITIZE_STRING,
      'value' => FILTER_UNSAFE_RAW,
      'path' => FILTER_SANITIZE_URL,
      '_method' => FILTER_SANITIZE_STRING, //not really needed, but good practice
    ));
    
    $pagePath = FlatCMSPluginPageModel::getPagePath($data['path']);
    $session = new Zend_Session_Namespace(Zend_Auth::getInstance()->getStorage()->getNamespace());
    if (filemtime($pagePath) !== $session->FlatCMSEditorPluginFileMTime)
    { 
      exit('Has changed since you last edited. Please refresh page.');
    }
    else
    {
      if (!$pagePath)
      {
        exit('Invalid path.');
      }
      
      $page = FlatCMSPluginPageModel::getPage($data['path']);
      $clean = $this->filterValue($data['id'], $data['value']);
      $page['content'][$data['id']] = $clean;
      file_put_contents($pagePath, Spyc::YAMLDump($page), LOCK_EX);
      clearstatcache();
      $session->FlatCMSEditorPluginFileMTime = filemtime($pagePath);
      
      return $clean;
    }
  }
  
  private function filterValue($id, $value)
  {
    if (in_array($id, sgConfiguration::get('settings.FlatCMSEditorPlugin.textarea_fields')))
    {
      return FlatCMSEditorPluginConfiguration::getPurifier()->purify($value);
    }
    
    return filter_var($value, FILTER_SANITIZE_STRING);
  }
}
