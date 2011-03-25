  public function executeRemoveFilter(sfWebRequest $request)
  {
    $name = $request->getParameter('name');
    $value = $request->getParameter('value');

    $filters = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.filters', $this->configuration->getFilterDefaults(), 'admin_module');
    unset($filters[$name]);
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.filters', $filters, 'admin_module');
    $this->setPage(1);
    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }