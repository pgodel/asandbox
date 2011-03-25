[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 12482 2008-10-31 11:13:22Z fabien $
 */
class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorHelper extends sfModelGeneratorHelper
{
  public function linkToNew($params)
  {
    return '<li class="a-admin-action-new">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'a_admin'), $this->getUrlForAction('new'), array() ,array("class"=>"a-btn big icon a-add alt", 'title' => 'Add')).'</li>';
  }

  public function linkToEdit($object, $params)
  {
    return '<li class="a-admin-action-edit">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'a_admin'), $this->getUrlForAction('edit'), $object, array('class'=>'a-btn icon a-edit no-label', 'title' => 'Edit')).'</li>';
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return '<li class="a-admin-action-delete">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'a_admin'), $this->getUrlForAction('delete'), $object, array('class'=>'a-btn no-label icon a-delete', 'title' => 'Delete', 'method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'a_admin') : $params['confirm'])).'</li>';
  }

  public function linkToList($params)
  {
    return '<li class="a-admin-action-list">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'a_admin'), $this->getUrlForAction('list'), array(), array('class'=>'a-btn icon a-cancel')).'</li>';
  }

  public function linkToSave($object, $params)
  {
    return '<li class="a-admin-action-save">' . a_anchor_submit_button(a_($params['label']), array('a-save')) . '</li>';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if (!$object->isNew())
    {
      return '';
    }
    return '<li class="a-admin-action-save-and-add">' . a_anchor_submit_button(a_($params['label']), array(), '_save_and_add') . '</li>';
  }

  public function getUrlForAction($action)
  {
    return 'list' == $action ? '<?php echo $this->params['route_prefix'] ?>' : '<?php echo $this->params['route_prefix'] ?>_'.$action;
  }
}
