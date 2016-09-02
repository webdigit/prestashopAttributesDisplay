<?php
if (! defined ( '_PS_VERSION_' ))
	exit ();
class WdPsAdmin extends Module {
	public function __construct() {
		$this->name = 'wdpsadmin';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'WEBDIGIT sprl';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array (
				'min' => '1.6',
				'max' => _PS_VERSION_ 
		);
		$this->bootstrap = true;
		
		parent::__construct ();
		
		$this->displayName = $this->l ( 'Webdigit Prestashop Admin' );
		$this->description = $this->l ( 'Ajout de fonctionnalités d\'administration à Prestashop' );
		
		$this->confirmUninstall = $this->l ( 'Are you sure you want to uninstall?' );
		
		if (! Configuration::get ( 'WEBDIGIT_PS_ADMIN' ))
			$this->warning = $this->l ( 'No name provided' );
	}
	public function install() {
		if (Shop::isFeatureActive ())
			Shop::setContext ( Shop::CONTEXT_ALL );
		
		if (! parent::install () || ! $this->registerHook ( 'leftColumn' ) || ! $this->registerHook ( 'header' ) || ! Configuration::updateValue ( 'MYMODULE_NAME', 'WEBDIGIT_PS_ADMIN' ))
			return false;
		
		return true;
	}
	public function uninstall() {
		if (! parent::uninstall () || ! Configuration::deleteByName ( 'WEBDIGIT_PS_ADMIN' ))
			return false;
		
		return true;
	}
}