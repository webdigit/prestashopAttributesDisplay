<?php
if (! defined ( '_PS_VERSION_' ))
	exit ();
class WdPsAdmin extends Module {
	
	protected static $cache_htmlDeclinaisons;
	
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
		
		$this->config_inputs = array (
				array (
						'name' => 'wd_ps_admin_render_homedeclinaisons',
						'label' => '[HOMEPAGE] Affichage des déclinaisons' 
				),
				array (
						'name' => 'wd_ps_admin_render_homereservations',
						'label' => '[HOMEPAGE] Affichage des réservations' 
				),
				array (
						'name' => 'wd_ps_admin_render_productdeliverydelay',
						'label' => '[PRODUCT PAGE] Affichage des délais de livraison' 
				) 
		);
		
		if (! Configuration::get ( 'WEBDIGIT_PS_ADMIN' ))
			$this->warning = $this->l ( 'No name provided' );
	}
	public function install() {
		if (Shop::isFeatureActive ())
			Shop::setContext ( Shop::CONTEXT_ALL );
		
		if (! parent::install () || ! $this->registerHook ( 'leftColumn' ) || ! $this->registerHook ( 'header' ) || ! $this->registerHook ( 'footer' ) || ! $this->registerHook ( 'displayProductDeclinaisons' ) || ! $this->registerHook ( 'dashboardZoneOne' ) || ! $this->registerHook ( 'dashboardZoneTwo' ) || ! $this->registerHook ( 'dashboardData' ) || ! Configuration::updateValue ( 'MYMODULE_NAME', 'WEBDIGIT_PS_ADMIN' ))
			return false;
		
		return true;
	}
	public function uninstall() {
		if (! parent::uninstall () || ! Configuration::deleteByName ( 'WEBDIGIT_PS_ADMIN' ))
			return false;
		
		return true;
	}
	public function getContent() {
		$output = null;
		if (Tools::isSubmit ( 'submit' . $this->name )) {
			
			foreach ( $this->config_inputs as $config_input ) {
				$config_label_value = strval ( Tools::getValue ( $config_input ['name'] ) );
				if (! $config_label_value || empty ( $config_label_value ) || ! Validate::isGenericName ( $config_label_value ))
					$output .= $this->displayError ( $this->l ( 'Invalid Configuration value' ) );
				else {
					Configuration::updateValue ( $config_input['name'], $config_label_value );
					$output .= $this->displayConfirmation ( $this->l ( 'Settings updated' ) );
				}
			}
		}
		return $output . $this->displayForm ();
	}
	public function displayForm() {
		// Get default language
		$default_lang = ( int ) Configuration::get ( 'PS_LANG_DEFAULT' );
		
		// Init Fields form array
		
		$options = array (
				array (
						'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
						'name' => 'activé'
				), // The value of the text content of the <option> tag.
				array (
						'id_option' => 2,
						'name' => 'désactivé'
				)
		);
		
		$inputs = array ();
		foreach ( $this->config_inputs as $config_input ) {
			array_push ( $inputs, array (
					'type' => 'select',
					'label' => $config_input['label'],
					'name' => $config_input['name'],
					'required' => true,
					'options' => array (
							'query' => $options,
							'id' => 'id_option',
							'name' => 'name' 
					) 
			) );
		}
		
		$fields_form [0] ['form'] = array (
				'legend' => array (
						'title' => $this->l ( 'Settings WEBDIGIT Prestashop Admin' ) 
				),
				'input' => $inputs,
				'submit' => array (
						'title' => $this->l ( 'Save' ),
						'class' => 'btn btn-default pull-right' 
				) 
		);
		
		$helper = new HelperForm ();
		
		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite ( 'AdminModules' );
		$helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
		
		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		
		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true; // false -> remove toolbar
		$helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit' . $this->name;
		$helper->toolbar_btn = array (
				'save' => array (
						'desc' => $this->l ( 'Save' ),
						'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name . '&token=' . Tools::getAdminTokenLite ( 'AdminModules' ) 
				),
				'back' => array (
						'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite ( 'AdminModules' ),
						'desc' => $this->l ( 'Back to list' ) 
				) 
		);
		
		// Load current value
		foreach ( $this->config_inputs as $config_input ) {
			$helper->fields_value [$config_input['name']] = Configuration::get ( $config_input['name'] );
		}
		
		return $helper->generateForm ( $fields_form );
	}
	public function hookDisplayFooter($params)
	{
		$html_render = '';
		foreach ( $this->config_inputs as $config_input ) {
			if(Configuration::get ( $config_input['name'] ) && Configuration::get ( $config_input['name'] ) == 1){
				$html_render .= $config_input['name'].'<br />';
			}
		}
		return $html_render;
	}
	public function hookDisplayProductDeclinaisons($params)
	{		
		$this->smarty->assign(array(
				'combinaisons' => $this->retrieveCombinaisons($params['id_product']),
				'product_id' => $product_id
		));
		return $this->display(__FILE__, 'htmlDeclinaisons.tpl');
	}
	public function retrieveCombinaisons($product_id){
				
		$product = new Product ($product_id, $this->context->language->id);
		$combinaisons = $product->getAttributeCombinations($this->context->language->id);
		
		/*
		 * On réorganise les combinaisons, car chaque ligne correspond à un attribut.
		 * Nous on souhaite p.ex : Taille/couleur : S/vert, M/vert, L/vert, S/jaune, M/jaune, L/jaune 
		 */
		$group_name = array();
		foreach($combinaisons as $key=>$comb){
			$group_name[$comb['id_attribute_group']] = $comb['group_name'];
		}
		$combinaisons['group_name'] = $group_name;
		
		return $combinaisons;
	}
}