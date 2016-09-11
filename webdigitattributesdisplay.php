<?php
if (! defined ( '_PS_VERSION_' ))
	exit ();
class WebdigitAttributesDisplay extends Module {
	public function __construct() {
		$this->name = 'webdigitattributesdisplay';
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
		
		$this->displayName = $this->l ( 'Products Attributes Display' );
		$this->description = $this->l ( 'Permet l\'affichage des déclinaisons dans les listes produits' );
		
		$this->confirmUninstall = $this->l ( 'Are you sure you want to uninstall?' );
		
		$this->config_inputs = array (
				array (
						'name' => 'wd_attr_displ_render',
						'label' => 'Affichage des déclinaisons',
						'type' => 'select',
						'options' => array (
								array (
										'id_option' => 1,
										'name' => 'activé' 
								),
								array (
										'id_option' => 2,
										'name' => 'désactivé' 
								) 
						) 
				),
				array (
						'name' => 'wd_attr_displ_selectors',
						'label' => 'Classes des conteneurs de listes (séparé par une virgule)',
						'type' => 'text'
				),
				array (
						'name' => 'wd_attr_displ_type',
						'label' => 'Type de rendu d\'affichage de la box des déclinaisons',
						'type' => 'select',
						'options' => array (
								array (
										'id_option' => 1,
										'name' => 'activé' 
								),
								array (
										'id_option' => 2,
										'name' => 'désactivé' 
								) 
						) 
				),
				array (
						'name' => 'wd_attr_displ_position',
						'label' => 'Position de l\'affiche de la box dans les conteneurs de listes',
						'type' => 'select',
						'options' => array (
								array (
										'id_option' => 1,
										'name' => 'activé' 
								),
								array (
										'id_option' => 2,
										'name' => 'désactivé' 
								) 
						) 
				),
				array (
						'name' => 'wd_attr_displ_format',
						'label' => 'Type d\'affichage des attributs',
						'type' => 'select',
						'options' => array (
								array (
										'id_option' => 1,
										'name' => 'activé' 
								),
								array (
										'id_option' => 2,
										'name' => 'désactivé' 
								) 
						) 
				),
				array (
						'name' => 'wd_attr_displ_outstock',
						'label' => 'Affichage des combinaisons hors stock (pour le type d\'affiche attributs "combinaisons")',
						'type' => 'select',
						'options' => array (
								array (
										'id_option' => 1,
										'name' => 'activé' 
								),
								array (
										'id_option' => 2,
										'name' => 'désactivé' 
								) 
						) 
				),
				array (
						'name' => 'wd_attr_displ_page',
						'label' => 'Affichage de la box pour les pages suivantes',
						'type' => 'select',
						'options' => array (
								array (
										'id_option' => 1,
										'name' => 'activé' 
								),
								array (
										'id_option' => 2,
										'name' => 'désactivé' 
								) 
						) 
				) 
		);
		
		if (! Configuration::get ( 'WEBDIGIT_ATTRIBUTES_DISPLAY' ))
			$this->warning = $this->l ( 'No name provided' );
	}
	public function install() {
		if (Shop::isFeatureActive ())
			Shop::setContext ( Shop::CONTEXT_ALL );
		
		if (! parent::install () || ! $this->registerHook ( 'displayProductPriceBlock' ) || ! $this->registerHook ( 'header' ) || ! Configuration::updateValue ( 'MYMODULE_NAME', 'WEBDIGIT_ATTRIBUTES_DISPLAY' ))
			return false;
		
		return true;
	}
	public function uninstall() {
		if (! parent::uninstall () || ! Configuration::deleteByName ( 'WEBDIGIT_ATTRIBUTES_DISPLAY' ))
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
					Configuration::updateValue ( $config_input ['name'], $config_label_value );
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
		
		$inputs = array ();
		foreach ( $this->config_inputs as $config_input ) {
			array_push ( $inputs, array (
					'type' => $config_input ['type'],
					'label' => $config_input ['label'],
					'name' => $config_input ['name'],
					'required' => true,
					'options' => array (
							'query' => $config_input ['options'],
							'id' => 'id_option',
							'name' => 'name' 
					) 
			) );
		}
		
		$fields_form [0] ['form'] = array (
				'legend' => array (
						'title' => $this->l ( 'Paramétrage WEBDIGIT Prestashop attributes display' ) 
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
			$helper->fields_value [$config_input ['name']] = Configuration::get ( $config_input ['name'] );
		}
		
		return $helper->generateForm ( $fields_form );
	}
	public function hookDisplayHeader($params) {
		$allowed_controllers = array (
				'index',
				'product',
				'category' 
		);
		$_controller = $this->context->controller;
		if (isset ( $_controller->php_self ) && in_array ( $_controller->php_self, $allowed_controllers )) {
			$this->context->controller->addCss ( $this->_path . 'views/css/wdattrdispl.css', 'all' );
			$this->context->controller->addJs ( $this->_path . 'views/js/wdattrdispl.js', 'all' );
		}
		$wdAttrDisplSelectors = Configuration::get ( 'wd_attr_displ_selectors' );
		return '<script>var wdAttrDisplSelectors = "'.$wdAttrDisplSelectors.'";</script>';
	}
	public function hookDisplayProductPriceBlock($params) {
		if (isset ( $params ['type'] ) && $params ['type'] == 'after_price') {
			$_controller = $this->context->controller;
			if ($_controller->php_self == 'product' && isset ( $params ['product']->specificPrice )) {
				$product_id = $params ['product']->specificPrice ['id_product'];
			} else {
				$product_id = $params ['product'] ['id_product'];
			}
			$product = new Product ( $product_id, $this->context->language->id );
			$link = new Link ();
			$product_link = $link->getProductLink ( $product );
			$this->smarty->assign ( array (
					'combinaisons' => $this->retrieveCombinaisons ( $params ),
					'product_id' => $product_id,
					'product_link' => $product_link 
			) );
			return $this->display ( __FILE__, 'htmlDeclinaisons.tpl' );
		}
	}
	public function generateUrl($id_attribute, $group_name, $attribute_name) {
		// return '/' . $id_attribute . '-' . $group_name . '-' . $attribute_name;
		return strtolower ( str_replace ( array (
				' ',
				'.' 
		), '_', '/' . $group_name . '-' . $attribute_name ) );
	}
	public function convertStrUrl($link, $str) {
		return str_replace ( '##link##', iconv ( 'UTF-8', 'ASCII//TRANSLIT', $link ), $str );
	}
	public function retrieveCombinaisons($params) {
		$_controller = $this->context->controller;
		if ($_controller->php_self == 'product' && isset ( $params ['product']->specificPrice )) {
			$product_id = $params ['product']->specificPrice ['id_product'];
			// $product_link = 'test';
		} else {
			$product_id = $params ['product'] ['id_product'];
			// $product_link = $params['product']['link'];
		}
		$product = new Product ( $product_id, $this->context->language->id );
		$link = new Link ();
		$product_link = $link->getProductLink ( $product ) . '#';
		$combinaisons = $product->getAttributeCombinations ( $this->context->language->id );
		/*
		 * On réorganise les combinaisons, car chaque ligne correspond à un attribut.
		 * Avec lien sur les combinaisons
		 * Nous on souhaite p.ex : Taille/couleur : S/vert M/vert L/vert S/jaune M/jaune L/jaune
		 */
		$group_name = array ();
		$group_name_string = '';
		$group_name_combinaisons = array ();
		$group_name_combinaisons_string = '';
		$link_combinaison = '';
		$link_combinaison_array = array ();
		foreach ( $combinaisons as $key => $comb ) {
			$attribute_name = $comb ['attribute_name'];
			$first_item = false;
			$last_item = false;
			if ($key == 0) {
				$first_item = true;
			}
			if ($key == count ( $combinaisons ) - 1) {
				$last_item = true;
			}
			if (! array_key_exists ( $comb ['id_attribute_group'], $group_name )) {
				$group_name [$comb ['id_attribute_group']] = $comb ['group_name'];
				$group_name_string .= $comb ['group_name'] . '/';
			}
			
			if (! array_key_exists ( $comb ['id_product_attribute'], $group_name_combinaisons )) {
				$link_combinaison_array [$comb ['id_product_attribute']] = '';
				
				if ($first_item) {
					$group_name_combinaisons_string .= '<a href="##link##">';
					$group_name_combinaisons_string .= $comb ['attribute_name'];
					// $link_combinaison .= '/' . $comb ['id_attribute'] . '-' . $comb ['group_name'] . '-' . $attribute_name;
					$link_combinaison .= $this->generateUrl ( $comb ['id_attribute'], $comb ['group_name'], $attribute_name );
				} elseif ($last_item) {
					// $group_name_combinaisons_string = str_replace ( '##link##', iconv('UTF-8','ASCII//TRANSLIT',strtolower ( $product_link . $link_combinaison )), $group_name_combinaisons_string );
					$group_name_combinaisons_string = $this->convertStrUrl ( $product_link . $link_combinaison, $group_name_combinaisons_string );
					$link_combinaison = '';
					// $link_combinaison .= '/' . $comb ['id_attribute'] . '-' . $comb ['group_name'] . '-' . $attribute_name;
					$link_combinaison .= $this->generateUrl ( $comb ['id_attribute'], $comb ['group_name'], $attribute_name );
					$group_name_combinaisons_string .= ' <a href="##link##">';
					$group_name_combinaisons_string .= $comb ['attribute_name'];
					$group_name_combinaisons_string .= '</a>';
				} else {
					// $group_name_combinaisons_string = str_replace ( '##link##', iconv('UTF-8','ASCII//TRANSLIT',strtolower ( $product_link . $link_combinaison )), $group_name_combinaisons_string );
					$group_name_combinaisons_string = $this->convertStrUrl ( $product_link . $link_combinaison, $group_name_combinaisons_string );
					$link_combinaison = '';
					// $link_combinaison .= '/' . $comb ['id_attribute'] . '-' . $comb ['group_name'] . '-' . $attribute_name;
					$link_combinaison .= $this->generateUrl ( $comb ['id_attribute'], $comb ['group_name'], $attribute_name );
					$group_name_combinaisons_string .= '</a>';
					$group_name_combinaisons_string .= ' <a href="##link##">';
					$group_name_combinaisons_string .= $comb ['attribute_name'];
				}
			} else {
				if ($last_item) {
					$group_name_combinaisons_string .= '</a>';
					$link_combinaison .= 'replace-';
					// $group_name_combinaisons_string = str_replace ( '##link##', iconv('UTF-8','ASCII//TRANSLIT',strtolower ( $product_link . $link_combinaison )), $group_name_combinaisons_string );
					$group_name_combinaisons_string = $this->convertStrUrl ( $product_link . $link_combinaison, $group_name_combinaisons_string );
					$link_combinaison = '';
				} else {
					// var_dump('add');
					// $link_combinaison .= 'add-';
					// $link_combinaison .= '/' . $comb ['id_attribute'] . '-' . $comb ['group_name'] . '-' . $attribute_name;
					$link_combinaison .= $this->generateUrl ( $comb ['id_attribute'], $comb ['group_name'], $attribute_name );
					$group_name_combinaisons_string .= '/';
					// $link_combinaison .= '/';
				}
			}
			// $link_combinaison .= '/' . $comb ['id_attribute'] . '-' . $comb ['group_name'] . '-' . $attribute_name;
			// var_dump($link_combinaison);
			// var_dump($last_item);
			// $group_name_combinaisons_string .= $comb ['attribute_name'];
			if ($last_item) {
				$group_name_combinaisons_string .= '</a>';
				// $group_name_combinaisons_string = str_replace ( '##link##', iconv('UTF-8','ASCII//TRANSLIT',strtolower ( $product_link . $link_combinaison )), $group_name_combinaisons_string );
				$group_name_combinaisons_string = $this->convertStrUrl ( $product_link . $link_combinaison, $group_name_combinaisons_string );
			}
			$group_name_combinaisons [$comb ['id_product_attribute']] [] = $comb ['attribute_name'];
			
			$link_combinaison_array [$comb ['id_product_attribute']] .= $link_combinaison;
		}
		$group_name_string = substr ( $group_name_string, 0, - 1 );
		// var_dump($link_combinaison_array);
		// var_dump($group_name_combinaisons_string);
		$combinaisons ['group_name'] = $group_name;
		$combinaisons ['group_name_string'] = $group_name_string;
		$combinaisons ['group_name_combinaisons'] = $group_name_combinaisons;
		$combinaisons ['group_name_combinaisons_string'] = $group_name_combinaisons_string;
		return $combinaisons;
	}
}