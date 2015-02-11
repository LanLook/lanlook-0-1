<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @author Armen Bablanyan http://www.armos.am
 * @copyright 2012 by Armen Bablanyan
 * @version v0.1
 *
 */
class Controller extends CController {
	/**
	 * The standard php object containing all meta tags that will be added in head of main page.
	 *
	 * @var object of all meta tags
	 */
	public $pageMetaTags;

	/**
	 * Page author meta value
	 *
	 * @var string
	 */
	public $pageAuthor = '';

	/**
	 *
	 * @var string the default layout for the controller view.
	 * 		Defaults to '//layouts/column1', meaning using a single column layout.
	 *      See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column2';
	
	/**
	 * Duration of cache in the default caching system
	 * 0<cach duration, 0=mean unlimit duration, -1=disable caching
	 * if $cache_duration=-1 there is activating CDummyCache
	 * @var int
	 */
	public $cache_duration = -1;
	
	/**
	 * Duration of page content cache in the default caching system
	 * 0<cach duration, 0=delete cache, -1=disable caching
	 * @var int
	 */
	public $content_cache_duration = -1;

	/**
	 * All menus.
	 * This property will be assigned to {@link CMenu::items}.
	 *
	 * @var array of objects of details of all menu items.
	 */
	public $menus = array ();

	/**
	 * Data of selected menu
	 *
	 * @var array of objects of details of selected menu
	 */
	public $selMenu;

	/**
	 * All categories and subcategories
	 *
	 * @var array of objects of details of all categories
	 */
	public $categories = array ();

	/**
	 * Data of selected category
	 *
	 * @var array of objects of details of selected category
	 */
	public $selCategory;

	/**
	 * Selected products by some critery
	 *
	 * @var array of objects of details of all items
	 */
	public $items = array ();

	/**
	 * Data of selected product
	 *
	 * @var array of objects of details of selected item
	 */
	public $selItem;

	/**
	 *
	 * @var array the breadcrumbs of the current page.
	 * 		The value of this property will be assigned to {@link CBreadcrumbs::links}.
	 *      Please refer to {@link CBreadcrumbs::links} for more details on how to specify this property.
	 */
	public $breadcrumbs = array ();

	/**
	 * Route of Current request
	 *
	 * @var string
	 */
	public $currentRoute;

	/**
	 * Character count of page title
	 *
	 * @var int
	 */
	public $countTitle = 70;

	/**
	 * Character count of meta keywords
	 *
	 * @var int
	 */
	public $countKeyw = 200;

	/**
	 * Character count of meta description
	 *
	 * @var int
	 */
	public $countDesc = 200;

	/**
	 * Character count of URL with seo slag
	 *
	 * @var int
	 */
	public $countURL = 2000;
	

	public function __construct($id, $module = null) {
		parent::__construct ( $id, $module );
		
		// Initiate cache duration from config
		if(isset(Yii::app ()->params ['cache_duration'])) {
			$this->cache_duration = Yii::app ()->params ['cache_duration'];
		}
		
		//disable cache by activating CDummyCache
		if ($this->cache_duration==-1) {
				Yii::app()->setComponent('cache',new CDummyCache);
		}
		// Initiate page content cache duration from config
		if (isset(Yii::app ()->params ['content_cache_duration'])) {
			$this->content_cache_duration = Yii::app ()->params ['content_cache_duration'];
		}
		
		// This is used for Console Applications only
		if(!isset(Yii::app()->detectMobileBrowser))
			return;
		
		// Detecting requester's system and
		// redirect to mobile version of web site
		if (Yii::app()->detectMobileBrowser->showMobile) {
        	$this->layout='//layouts/mobile';
        	//if mobile version of site is in module.
        	//$this->redirect(array('site/mobile' ));

        } else {

			$baseUrl = Yii::app()->getAssetManager()->publish(Yii::app()->theme->basePath.DIRECTORY_SEPARATOR.'css');
			$cs = Yii::app()->clientScript;
			$cs->registerCSSFile($baseUrl.'/screen.css', 'screen, projection');
			$cs->registerCSSFile($baseUrl.'/print.css', 'print');
			$cs->registerCSSFile($baseUrl.'/main.css');
			$cs->registerCSSFile($baseUrl.'/form.css');
			$cs->registerCSSFile($baseUrl.'/helper.css');		
			
			// THEMEING jQuery-UI, PUBLISH NEW ASSETS BY DETAILS OF CONFIGURATION (main.php)
			// ATTACHE ASSETS FILES (css, js) AS DEFAULT THEME FILES OF JQUERY-UI  
			if(isset(Yii::app()->widgetFactory->widgets['CJuiWidget']['theme'])) {
				$juiUrl = Yii::app()->getAssetManager()->publish(Yii::app()->theme->basePath.DIRECTORY_SEPARATOR.'jui');
				if(isset(Yii::app()->widgetFactory->widgets['CJuiWidget']['cssFile'])) {
					$cssFile = Yii::app()->widgetFactory->widgets['CJuiWidget']['cssFile'];
				} else {
					$cssFile = 'jquery-ui.css';
				}				
				$cs->scriptMap[$cssFile]= $juiUrl.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.Yii::app()->widgetFactory->widgets['CJuiWidget']['theme'].DIRECTORY_SEPARATOR.$cssFile;
				
				if(isset(Yii::app()->widgetFactory->widgets['CJuiWidget']['scriptFile'])) {
					$scriptFile = Yii::app()->widgetFactory->widgets['CJuiWidget']['scriptFile'];
					$cs->scriptMap[$scriptFile]= $juiUrl.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$scriptFile;
				}
			}

			// Detecting requester's browser.
			// if IE8 and lower register special styles
			if (strtolower(Yii::app()->browser->getBrowser())=='internet explorer' && Yii::app()->browser->getVersion()<8.0)
			{
				//$this->layout = '//layouts/column1';
				$cs->registerCSSFile($baseUrl.'/ie.css', 'screen, projection');
			}
       }

		// If there is a post-request, redirect the application to the provided url of the selected language
		if (isset ( $_POST ['language'] )) {
			$lang = CHtml::encode ( $_POST ['language'] );
			$MultilangReturnUrl = $_POST [$lang];
			$this->redirect ( $MultilangReturnUrl );
		}
		// Set the application language if provided by GET, session or cookie
		if (isset ( $_GET ['language'] )) {
			Yii::app ()->language = CHtml::encode ( $_GET ['language'] );
			Yii::app ()->user->setState ( 'language', CHtml::encode ( $_GET ['language'] ) );
			$cookie = new CHttpCookie ( 'language', CHtml::encode ( $_GET ['language'] ) );
			$cookie->expire = time () + (60 * 60 * 24 * 365); // (1 year)
			Yii::app ()->request->cookies ['language'] = $cookie;
		} elseif (Yii::app ()->user->hasState ( 'language' )) {
			Yii::app ()->language = Yii::app ()->user->getState ( 'language' );
		} elseif (isset ( Yii::app ()->request->cookies ['language'] )) {
			Yii::app ()->language = Yii::app ()->request->cookies ['language']->value;
		}
		// INITIATION OF PARAMS FOR SEO OPTIMIZATIONS//
		date_default_timezone_set ( 'Asia/Yerevan' );
		$this->pageMetaTags = new stdClass ();

		foreach ( Yii::app ()->params ['pageMetaTags'] as $metaType => $meta ) {
			$this->pageMetaTags->$metaType = "<meta ";
			foreach ( $meta as $paramName => $paramValue ) {
				if ($paramName == 'content') {
					switch ($metaType) {
						case 'contenttype' :
							$paramValue = 'text/html; charset=' . Yii::app ()->charset;
							break;
						case 'contentlanguage' :
							$paramValue = Yii::app ()->language;
							break;
						case 'author' :
						case 'generator' :
							$paramValue = $this->pageAuthor = Yii::t ( 'msg', $paramValue );
							break;
						case 'copyright' :
							$paramValue .= Yii::t ( 'msg', Yii::app ()->name );
							break;
						case 'expires' :
							$paramValue = date ( 'r', strtotime ( "+{$paramValue} hours" ) );
							break;
					}
				}
				$this->pageMetaTags->$metaType .= $paramName . '="' . $paramValue . '" ';
			}
			$this->pageMetaTags->$metaType .= " />\n";
		}

		define ( 'NOT_FOUND', Yii::t ( 'msg', 'Requested items not found.' ) );
		define ( 'UNDER_CONSTRUCT', Yii::t ( 'msg', 'Dear Visitor, {enter} The Page is under construction.', array ('{enter}' => '<br /><br />' ) ) );

		$this->getMenus ();
		$this->getCategories ();
		
	}
	
	/**
	 * Filter for page content cacheing
	 * Used multiple dependencies functionality of Yii named CChainedCacheDependency
	 * (non-PHPdoc)
	 * @see CController::filters()
	 */
	public function filters()
	{
		// Add some dependencies for page content cache
		$dependencies = array();
		$dependencies[] = new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `menu_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')");
		$dependencies[] = new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `category_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')");
		$dependencies[] = new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `product_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')");
		$dependency_chain = new CChainedCacheDependency($dependencies);
	
		return array(
				array(
						'COutputCache',
						'duration' => $this->content_cache_duration,
						'varyByParam' => array('pid', 'language', 'slag'),
						'requestTypes' => array('GET'),
						'dependency' => $dependency_chain,
				),
		);
	}

	/**
	 * Prepare language urls for creating language links
	 * Also prepareing slag in selected language
	 *
	 * @param string $lang
	 * @return multitype:array |multitype:array
	 */
	public function createMultilanguageReturnUrl($lang = 'en') {
		$params = array_splice ( $this->getActionParams (), 1 );

		// approch for antihacking if pid!=numeric
		if (! isset ( $params ['pid'] ) && preg_match ( '[Gii|^index|routing]', $this->currentRoute )) {
			unset ( $params );
			$params = array ('pid' => 0 );
		}

		$params ['language'] = $lang;
		unset($params ['slag']);

		if (isset ( $params ['pid'] ) && is_numeric ( $params ['pid'] )) {

			if (strtolower ( $this->getRoute () ) == 'site/list') {
				// for list
				$params ['slag'] = $this->getSEOSlag ( $params ['pid'], 'list', $lang );
			} elseif (strtolower ( $this->getRoute () ) == 'site/item') {
				// for product items
				$params ['slag'] = $this->getSEOSlag ( $params ['pid'], 'item', $lang );
			} else {
				// for menu items
				$params ['slag'] = $this->getSEOSlag ( $params ['pid'], 'menu', $lang );
			}
		}
		return $this->createUrl ( $this->currentRoute, $params );
	}

	/**
	 * This method gets hierarchy structure of menu items from database
	 *
	 * @param int $parent_id first layer of data. Default is 0
	 * @return array of menu items like:
	 *         array(
	 *         		array('label'=>'Home', 'url'=>array('site/index')), items=>array(
	 *         			array('label'=>'Search', 'url'=>array('site/search')),
	 *         			array('label'=>'About', 'url'=>array('site/content', 'pid'=>100)),
	 *         			array('label'=>'Contact', 'url'=>array('site/contact')),
	 *         		)
	 *         );
	 */
	protected function getMenus($parent_id = 0) {
		
		$cache_id = "menus_".Yii::app ()->language."_".$parent_id;
		$menus=Yii::app()->cache->get($cache_id);
		if($menus===false)
		{
			$menus = array ();
			$t = new CDbCriteria ();
			$t->with = array ('menuDetails.lng' );
			$t->condition = 't.parent_id=:parentID and lng.lng_code=:lng and t.menu_status>:stat';
			$t->params = array (':parentID' => $parent_id, ':lng' => Yii::app ()->language, ':stat' => 1 );
			$t->order = 'menu_order ASC';
			$obj = MenuStructure::model ()->findAll ( $t );
			foreach ($obj as $menu ) {
				$struct_tmp = $menu->getStructure ();
				if($struct_tmp) {
					$menus [] = $struct_tmp;
				}
			}
			//set cache with dependency from modified_date
			Yii::app()->cache->set($cache_id,$menus,$this->cache_duration, new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `menu_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')"));
		}
			
		$this->menus = $menus;
		return $menus;
	}

	/**
	 * This method gets array of objects of details of selected menu
	 *
	 * @return array of object of selected menu details
	 */
	protected function getSelectedMenu() {
		$params = $this->getActionParams ();
		$menu = new stdClass ();
		$breadcrumbLinks = array ();
		$this->currentRoute = 'site/routing';

		if (! isset ( $params ['pid'] ) || ! $params ['pid'] || ! is_numeric ( $params ['pid'] )) {
			$this->selMenu->msg = $menu->msg = NOT_FOUND;
			return false;
		} else {
			// Used caching functionality of Yii
			$cache_id = "menu_".Yii::app ()->language."_".$params ['pid'];
			$menu=Yii::app()->cache->get($cache_id);
			if($menu===false)
			{
				$c = new CDbCriteria ();
				$c->together = true;
				$c->with = array ('lng', 'menu' );
				$c->condition = 't.menu_id=:menuID and lng.lng_code=:lng and menu.menu_status>:stat';
				$c->params = array (':menuID' => CHtml::encode ( $params ['pid'] ), ':lng' => Yii::app ()->language, ':stat' => 1 );
				$menu = MenuDetails::model ()->find ( $c );
				//set cache with dependency from modified_date
				Yii::app()->cache->set($cache_id,$menu,$this->cache_duration,new CDbCacheDependency("SELECT `modified_date` FROM `menu_details` WHERE `id`=".$menu->id));
			}
			
			if ($menu) {
				// Used caching functionality of Yii
				$cache_id = "path_arr_".Yii::app ()->language."_".$params ['pid'];
				$path_arr=Yii::app()->cache->get($cache_id);
				if($path_arr===false)
				{
					$path_arr = $menu->menu->getDepthPath ();
					//set cache with dependency from modified_date
					Yii::app()->cache->set($cache_id,$path_arr,$this->cache_duration,new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `menu_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')") );
				}
				if (! $path_arr) {
					$this->setError (NOT_FOUND);
					unset ( $menu );
					$this->selMenu->msg = $menu->msg = NOT_FOUND;
				} else {
					// ADD SLAGS, KEYWORDS, DESCRIPTIONS OF ALL PARENT ELEMENTS
					$title = (isset ( $menu->page_title ) && $menu->page_title != '') ? $menu->page_title : $menu->menu_name;
					$title = CHtml::encode ( $this->multiSubString ( $title, $this->countTitle ) );

					if ($menu->menu->menu_status >= 3) {

						$path_str = implode ( ' ', array_keys ( $path_arr ) );

						$desc = (isset ( $menu->description ) && $menu->description != '') ? $path_str . ' ' . $menu->description : $path_str . ' ' . $title;
						$desc = CHtml::encode ( $this->multiSubString ( $desc, $this->countDesc ) );

						$keywords = (isset ( $menu->keywords ) && $menu->keywords != '') ? $path_str . ' ' . $menu->keywords : $path_str . ' ' . $desc;
						$keywords = CHtml::encode ( $this->multiSubString ( $this->makeKeyWords ( $keywords ), $this->countKeyw ) );

						array_splice ( $path_arr, - 1, 1, array ($menu->menu_name ) );

						$this->pageTitle = $title;
						$this->breadcrumbs = $path_arr;
						$this->pageMetaTags->description = "<meta name=\"description\" content=\"{$desc}\" />\n";
						$this->pageMetaTags->keywords = "<meta name=\"keywords\" content=\"{$keywords}\" />\n";

						$this->selMenu = $menu;

					} else{

						unset ( $menu );
						$this->setError (UNDER_CONSTRUCT, $title);
						$this->selMenu->msg = $menu->msg = UNDER_CONSTRUCT;
						$this->selMenu->menu->menu_action = 'underfix';
					}

				}
			} else {
				$this->setError (NOT_FOUND);
				$this->selMenu->msg = $menu->msg = NOT_FOUND;
			}
		}

		return $menu;
	}

	/**
	 * This method is gets hierarchy structure of category items from database
	 *
	 * @param int $parent_id first layer of data. Default is 0
	 * @return array of category items like:
	 *         array(
	 *         		array('label'=>'Home', 'url'=>array('site/index')), items=>array(
	 *         			array('label'=>'Search', 'url'=>array('site/search')),
	 *         			array('label'=>'About', 'url'=>array('site/content', 'pid'=>100)),
	 *         			array('label'=>'Contact', 'url'=>array('site/contact')),
	 *         		)
	 *         );
	 */
	protected function getCategories($parent_id = 0) {
		
		// Used caching functionality of Yii
		$cache_id = "categories_".Yii::app ()->language."_".$parent_id;
		$categories=Yii::app()->cache->get($cache_id);
		if($categories===false)
		{
			$categories = array ();
			$t = new CDbCriteria ();
			$t->with = array ('categoryDetails.lng' );
			$t->condition = 't.parent_id=:parentID and lng.lng_code=:lng and t.category_status>:stat';
			$t->params = array (':parentID' => $parent_id, ':lng' => Yii::app ()->language, ':stat' => 1 );
			$t->order = 'category_order ASC';
			foreach ( CategoryStructure::model ()->findAll ( $t ) as $category ) {
				$struct_tmp = $category->getStructure ();
				if($struct_tmp) {
					$categories [] = $struct_tmp ;
				}
			}
			//set cache with dependency from modified_date
			Yii::app()->cache->set($cache_id,$categories,$this->cache_duration, new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `category_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')"));
		}

		$this->categories = $categories;
		return $categories;
	}

	/**
	 * This method gets array of objects of details of selected category
	 *
	 * @return array of objects of details of selected category
	 */
	protected function getSelectedCategory() {
		$params = $this->getActionParams ();
		$category = new stdClass ();
		$items = array ();
		$breadcrumbLinks = array ();
		$this->currentRoute = 'site/list';

		if (! isset ( $params ['pid'] ) || ! $params ['pid'] || ! is_numeric ( $params ['pid'] )) {
			return false;
		} else {
			// Used caching functionality of Yii
			$cache_id = "category_".Yii::app ()->language."_".$params ['pid'];
			$category=Yii::app()->cache->get($cache_id);
			if($category===false)
			{
				$c = new CDbCriteria ();
				$c->with = array ('lng', 'category' );
				$c->condition = 't.category_id=:catID and lng.lng_code=:lng and category.category_status>:stat';
				$c->params = array (':catID' => CHtml::encode ( $params ['pid'] ), ':lng' => Yii::app ()->language, ':stat' => 1 );
				$category = CategoryDetails::model ()->find ( $c );

				//set cache with dependency from modified_date
				Yii::app()->cache->set($cache_id,$category,$this->cache_duration,new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `category_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')"));
			}
	
			// Used caching functionality of Yii
			$cache_id = "items_".Yii::app ()->language."_".$params ['pid'];
			$items=Yii::app()->cache->get($cache_id);
			if($items===false)
			{
				$items = $category->category->getOwnItems() ;
				//set cache with dependency from modified_date
				Yii::app()->cache->set($cache_id,$items,$this->cache_duration,new CChainedCacheDependency(array(
					new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `category_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')")),
					new CDbCacheDependency("SELECT MAX(`modified_date`) FROM `product_details` WHERE `lng_id`=(SELECT `lng_id` FROM `languages` WHERE `lng_code`='".Yii::app ()->language."')")) 
					);
			}

			if ($category) {
				$path_arr = $category->category->getDepthPath ();
				if (! $path_arr) {
					$this->setError (NOT_FOUND);
					$this->items = $items = array ('msg' => NOT_FOUND );
				} else {
					// ADD SLAGS, KEYWORDS, DESCRIPTIONS OF ALL PARENT ELEMENTS
					$title = (isset ( $category->page_title ) && $category->page_title != '') ? $category->page_title : $category->category_name;
					$title = CHtml::encode ( $this->multiSubString ( $title, $this->countTitle ) );

					$path_str = implode ( ' ', array_keys ( $path_arr ) );

					$desc = (isset ( $category->description ) && $category->description != '') ? $path_str . ' ' . $category->description : $path_str . ' ' . $title;
					$desc = CHtml::encode ( $this->multiSubString ( $desc, $this->countDesc ) );

					$keywords = (isset ( $category->keywords ) && $category->keywords != '') ? $path_str . ' ' . $category->keywords : $path_str . ' ' . $desc;
					$keywords = CHtml::encode ( $this->multiSubString ( $this->makeKeyWords ( $keywords ), $this->countKeyw ) );

					array_splice ( $path_arr, - 1, 1, array ($category->category_name ) );

					$this->pageTitle = $title;
					$this->breadcrumbs = $path_arr;
					$this->pageMetaTags->description = "<meta name=\"description\" content=\"{$desc}\" />\n";
					$this->pageMetaTags->keywords = "<meta name=\"keywords\" content=\"{$keywords}\" />\n";

					$this->selCategory = $category;
					
					if (count ( $items ) > 0) {
						$this->items = $items;
					} else {
						$this->setError (NOT_FOUND, $title);
						$this->items = $items = array ('msg' => NOT_FOUND );
					}
				}
			} else {
				$this->setError (NOT_FOUND);
				$this->items = $items = array ('msg' => NOT_FOUND );
			}
		}
// 		Yii::log(1,'warning',  'application.controller.seoSlag');
// 		Yii::log(json_encode($path_arr),'warning',  'application.controller.seoSlag');
		return $items;
	}

	/**
	 * This method gets array of objects of details of selected product
	 *
	 * @return array of objects of details of selected product.
	 */
	protected function getSelectedItem() {
		$params = $this->getActionParams ();
		$category = new stdClass ();
		$item = new stdClass ();
		$breadcrumbLinks = array ();
		$this->currentRoute = 'site/item';

		if (! isset ( $params ['pid'] ) || ! $params ['pid'] || ! is_numeric ( $params ['pid'] )) {
			return false;
		} else {
			// Used caching functionality of Yii
			$cache_id = "item_".Yii::app ()->language."_".$params ['pid'];
			$item=Yii::app()->cache->get($cache_id);
			if($item===false)
			{
				$c = new CDbCriteria ();
				$c->with = array ('lng', 'product' );
				$c->condition = 'product.product_id=:prodID and lng.lng_code=:lng and product.product_status>:stat';
				$c->params = array (':prodID' => CHtml::encode ( $params ['pid'] ), ':lng' => Yii::app ()->language, ':stat' => 1 );
				$item = ProductDetails::model ()->find ( $c );
				
				//set cache with dependency from modified_date
				Yii::app()->cache->set($cache_id,$item,$this->cache_duration,new CDbCacheDependency("SELECT `modified_date` FROM `product_details` WHERE `id`=".$item->id));
			}
			// Used caching functionality of Yii
			$cache_id = "item_category_".Yii::app ()->language."_".$item->product->category_id;
			$category=Yii::app()->cache->get($cache_id);
			if($category===false)
			{
				$c = new CDbCriteria ();
				$c->with = array ('lng', 'category' );
				$c->condition = 't.category_id=:catID and lng.lng_code=:lng and category.category_status>:stat';
				$c->params = array (':catID' => $item->product->category_id, ':lng' => Yii::app ()->language, ':stat' => 1 );
				$category = CategoryDetails::model ()->find ( $c );
				
				//set cache with dependency from modified_date
				Yii::app()->cache->set($cache_id,$category,$this->cache_duration,new CDbCacheDependency("SELECT `modified_date` FROM `category_details` WHERE `id`=".$category->id));
			}

			if ($category) {
				$tmp_arr = $category->category->getDepthPath ();
				if ($tmp_arr && $item) {

					// ADDs SLAGS, KEYWORDS, DESCRIPTIONS OF ALL PARENT ELEMENTS
					$title = (isset ( $item->page_title ) && $item->page_title != '') ? $item->page_title : $item->product_name;
					$title = CHtml::encode ( $this->multiSubString ( $title, $this->countTitle ) );

					$path_arr = array_keys ( $tmp_arr );
					array_push ( $path_arr, $item->product_name );
					$path_str = implode ( ' ', $path_arr );

					$desc = (isset ( $item->description ) && $item->description != '') ? $path_str . ' ' . $item->description : $path_str . ' ' . $title;
					$desc = CHtml::encode ( $this->multiSubString ( $desc, $this->countDesc ) );

					$keywords = (isset ( $item->keywords ) && $item->keywords != '') ? $path_str . ' ' . $item->keywords : $path_str . ' ' . $desc;
					$keywords = CHtml::encode ( $this->multiSubString ( $this->makeKeyWords ( $keywords ), $this->countKeyw ) );

					$bread_arr = $tmp_arr;
					array_push ( $bread_arr, $item->product_name );

					$this->pageTitle = $title;
					$this->breadcrumbs = $bread_arr;
					$this->pageMetaTags->description = "<meta name=\"description\" content=\"{$desc}\" />\n";
					$this->pageMetaTags->keywords = "<meta name=\"keywords\" content=\"{$keywords}\" />\n";

					$this->selItem = $item;
				} else {
					$this->setError (NOT_FOUND);
					unset ( $item );
					$this->selItem->msg = $item->msg = NOT_FOUND;
				}
			} else {
				$this->setError (NOT_FOUND);
				unset ( $item );
				$this->selItem->msg = $item->msg = NOT_FOUND;
			}
		}
		return $item;
	}

	/**
	 * This method is configuring and returning SEO slag for clicked menu, item or category
	 * It get all names of elements in the hierarchy of selected item's path
	 *
	 * @param int $pid
	 * @param string $action
	 * @param string $lng
	 * @return string SEO slag for URLs
	 */
	public function getSEOSlag($pid = null, $action = null, $lng = null) {
		
		if (!Yii::app()->params['seo_url_slag']) {
			return 'index';
		}
			
		if ($pid == null || ! is_numeric ( $pid )) {
			$params = $this->getActionParams ();

			if (! isset ( $params ['pid'] ) || ! is_numeric ( $pid )) {
				$this->setError (NOT_FOUND);
				return Yii::t ( 'msg', 'Requested items not found.', array (), null, $lng );
			} else {
				$pid = ( int ) $params ['pid'];
			}
		}
		$lng = ($lng == null) ? Yii::app ()->language : $lng;
		$action = ($action == null) ? $this->action->id : $action;
		
		// Used caching functionality of Yii
		$cache_id = "seo_".$action."_".$lng."_".$pid;
		$seoSlag=Yii::app()->cache->get($cache_id);
		if($seoSlag===false)
		{
			switch ($action) {
				case 'item' :
	
					$c = new CDbCriteria ();
					$c->with = array ('lng', 'product' );
					$c->condition = 't.product_id=:pid and lng.lng_code=:lng and product.product_status>:stat';
					$c->params = array (':pid' => CHtml::encode ( $pid ), ':lng' => CHtml::encode ( $lng ), ':stat' => 1 );
					$item = ProductDetails::model ()->find ( $c );
	
					$c->with = array ('lng', 'category' );
					$c->condition = 't.category_id=:catID and lng.lng_code=:lng and category.category_status>:stat';
					$c->params = array (':catID' => ( int ) $item->product->category_id, ':lng' => CHtml::encode ( $lng ), ':stat' => 1 );
					$category = CategoryDetails::model ()->find ( $c );
	
					if ($category) {
						$path_arr = $category->category->getDepthPath ( false, $lng );
						(isset ( $item->seo_slag ) && $item->seo_slag != '') ? $path_arr [$item->seo_slag] = $path_arr [$item->product_name] = true : $path_arr [$item->product_name] = true;
						$seoSlag = implode ( ' ', array_keys ( $path_arr ) );
					} else {
						$seoSlag = Yii::t ( 'msg', 'Requested items not found.', array (), null, $lng );
					}
					$dependency = array(new CDbCacheDependency("SELECT `modified_date` FROM `product_details` WHERE `id`=".$item->id));
					break;
				case 'list' :

					$c = new CDbCriteria ();
					$c->together = true;
					$c->with = array ('lng', 'category' );
					$c->condition = 't.category_id=:catID and lng.lng_code=:lng and category.category_status>:stat';
					$c->params = array (':catID' => CHtml::encode ( $pid ), ':lng' => CHtml::encode ( $lng ), ':stat' => 1 );
					$category = CategoryDetails::model ()->find ( $c );
					
					if ($category) {
						$path_arr = $category->category->getDepthPath ( false, $lng );
						(isset ( $category->seo_slag ) && $category->seo_slag != '') ? $path_arr [$category->seo_slag] = true : $path_arr [$category->category_name] = true;
						$seoSlag = implode ( ' ', array_keys ( $path_arr ) );
					} else {
						$seoSlag = Yii::t ( 'msg', 'Requested items not found.', array (), null, $lng );
					}
					$dependency = array(new CDbCacheDependency("SELECT `modified_date` FROM `category_details` WHERE `id`=".$category->id),new CDbCacheDependency("SELECT MAX(d.modified_date) FROM `product_details` d JOIN `product_structure` s ON s.product_id=d.product_id WHERE s.category_id=".$category->category_id));
					break;
				case 'menu' :

					$c = new CDbCriteria ();
					$c->together = true;
					$c->with = array ('lng', 'menu' );
					$c->condition = 't.menu_id=:menuID and lng.lng_code=:lng and menu.menu_status>:stat';
					$c->params = array (':menuID' => CHtml::encode ( $pid ), ':lng' => CHtml::encode ( $lng ), ':stat' => 1 );
					$menu = MenuDetails::model ()->find ( $c );

					if ($menu) {
						$path_arr = $menu->menu->getDepthPath ( false, $lng );
						
						(isset ( $menu->seo_slag ) && $menu->seo_slag != '') ? $path_arr [$menu->seo_slag] = true : $path_arr [$menu->menu_name] = true;
						$seoSlag = implode ( ' ', array_keys ( $path_arr ) );
					} else {
						$seoSlag = Yii::t ( 'msg', 'Requested items not found.', array (), null, $lng );
					}
					$dependency = array(new CDbCacheDependency("SELECT `modified_date` FROM `menu_details` WHERE `id`=".$menu->id));
					break;
			}
			$seoSlag = $this->multiSubString ( $seoSlag, $this->countURL );
			//set cache with dependency from modified_date
			Yii::app()->cache->set($cache_id,$seoSlag,$this->cache_duration, new CChainedCacheDependency($dependency));
		}
		return $seoSlag;
	}
	
	/**
	 * This is a default list action for displaying list of items
	 */
	public function actionList()
	{
		
		$this->render('list', array());
	}
	
	/**
	 * This is a default item action for displaying content of items
	 */
	public function actionItem()
	{
		
		$this->render('item', array());
	}
	
	/**
	 * This is a default action for routing menu actions 
	 * by type of content from DB. It will render if content is static text from DB,
	 * or redirect(forward) to real action which name is received from DB.
	 */
	public function actionRouting()
	{
		if ($this->selMenu->menu->menu_action=='Content'){
			$this->render('content', array());
		} else {
			$this->forward($this->selMenu->menu->menu_action, true);
		}
	}

	/**
	 * Initialize all nececcary properties for error.
	 */
	protected function setError($message, $title='Error Page 404') {
		$this->pageTitle = CHtml::encode ( Yii::t ( 'msg',  $title) );
		(is_array($this->breadcrumbs) && count($this->breadcrumbs)>0) ? '' : $this->breadcrumbs = array (CHtml::encode ( Yii::t ( 'msg', $title ) ) );
		$this->pageMetaTags->description = "<meta name=\"description\" content=\"" . CHtml::encode ( $this->strip_html_tags($message) ) . "\" />\n";
		$this->pageMetaTags->keywords = "<meta name=\"keywords\" content=\"{$this->makeKeyWords(CHtml::encode($this->strip_html_tags($message)))}\" />\n";
		$this->pageMetaTags->googlebot = "<meta name=\"googlebot\" content=\"noindex,nofollow\" />\n";
		$this->pageMetaTags->robots = "<meta name=\"robots\" content=\"noindex,nofollow\" />\n";
		$this->pageMetaTags->cachecontrol = "<meta name=\"Cache-Control\" content=\"no-cache\" />\n";
		$this->pageMetaTags->pragma = "<meta name=\"Pragma\" content=\"no-cache\" />\n";
		$this->pageMetaTags->expires = "<meta name=\"Expires\" content=\"".date ( 'r')."\" />\n";
	}

	/**
	 * Make keywords separated with commas and return comma separated string.
	 *
	 * @param string $string string that will changed.
	 * @return string comma separated.
	 */
	public function makeKeyWords($string) {
		if ($string != '') {
			return CHtml::encode ( str_replace ( ' ', ",", $this->strip_html_tags ( trim ( $string ) ) ) );
		}
	}

	/**
	 * Cut multilangue string (Utf-8) by given size with multibayte functions.
	 * Used php_mbstring extension of php.
	 *
	 * @param string $str string that will be changed.
	 * @param int $charCount size of string that will be returned.
	 * @param string $direction from which side it will be cuten.
	 * @return string changed string.
	 */
	public function multiSubString($str, $charCount, $direction = 'r') {
		if ($direction == 'r') {
			return mb_substr ( $this->strip_html_tags ( trim ( $str ) ), mb_strlen ( $this->strip_html_tags ( trim ( $str ) ), Yii::app ()->charset ) - $charCount, mb_strlen ( $this->strip_html_tags ( trim ( $str ) ), Yii::app ()->charset ), Yii::app ()->charset );
		} else {
			return mb_substr ( $this->strip_html_tags ( trim ( $str ) ), 0, $charCount, Yii::app ()->charset );
		}
	}

	/**
	 * Special HTML Strip_Tags Function.
	 *
	 * @param string $input.
	 * @param string $validTags.
	 * @return string changed plane string.
	 */
	public function strip_html_tags($input, $validTags = '')
	{

	    $regex = '#\s*<(/?\w+)\s+(?:on\w+\s*=\s*(["\'\s])?.+?\(\1?.+?\1?\);?\1?|style=["\'].+?["\'])\s*>#is';

	    $document = preg_replace($regex, '<${1}>',strip_tags($input, $validTags));

	    $search = array (
	    			"/\r/",                                  // Non-legal carriage return
			        "/[\n\t]+/",                             // Newlines and tabs
			        '/[ ]{2,}/',                             // Runs of spaces, pre-handling
			        '/<script[^>]*>.*?<\/script>/i',         // <script>s -- which strip_tags supposedly has problems with
			        '/<style[^>]*>.*?<\/style>/i',           // <style>s -- which strip_tags supposedly has problems with
			        //'/<!-- .* -->/',                       // Comments -- which strip_tags might have problem a with
			        '/<h[123][^>]*>(.*?)<\/h[123]>/ie',      // H1 - H3
			        '/<h[456][^>]*>(.*?)<\/h[456]>/ie',      // H4 - H6
			        '/<p[^>]*>/i',                           // <P>
			        '/<br[^>]*>/i',                          // <br>
			        '/<b[^>]*>(.*?)<\/b>/ie',                // <b>
			        '/<strong[^>]*>(.*?)<\/strong>/ie',      // <strong>
			        '/<i[^>]*>(.*?)<\/i>/i',                 // <i>
			        '/<em[^>]*>(.*?)<\/em>/i',               // <em>
			        '/(<ul[^>]*>|<\/ul>)/i',                 // <ul> and </ul>
			        '/(<ol[^>]*>|<\/ol>)/i',                 // <ol> and </ol>
			        '/<li[^>]*>(.*?)<\/li>/i',               // <li> and </li>
			        '/<li[^>]*>/i',                          // <li>
			        '/<a [^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/ie',
			                                                 // <a href="">
			        '/<hr[^>]*>/i',                          // <hr>
			        '/(<table[^>]*>|<\/table>)/i',           // <table> and </table>
			        '/(<tr[^>]*>|<\/tr>)/i',                 // <tr> and </tr>
			        '/<td[^>]*>(.*?)<\/td>/i',               // <td> and </td>
			        '/<th[^>]*>(.*?)<\/th>/ie',              // <th> and </th>
			        '/&(nbsp|#160);/i',                      // Non-breaking space
			        '/&(quot|rdquo|ldquo|#8220|#8221|#147|#148);/i',
					                                         // Double quotes
			        '/&(apos|rsquo|lsquo|#8216|#8217);/i',   // Single quotes
			        '/&gt;/i',                               // Greater-than
			        '/&lt;/i',                               // Less-than
			        '/&(amp|#38);/i',                        // Ampersand
			        '/&(copy|#169);/i',                      // Copyright
			        '/&(trade|#8482|#153);/i',               // Trademark
			        '/&(reg|#174);/i',                       // Registered
			        '/&(mdash|#151|#8212);/i',               // mdash
			        '/&(ndash|minus|#8211|#8722);/i',        // ndash
			        '/&(bull|#149|#8226);/i',                // Bullet
			        '/&(pound|#163);/i',                     // Pound sign
			        '/&(euro|#8364);/i',                     // Euro sign
			        '/&[^&;]+;/i',                           // Unknown/unhandled entities
			        '/[ ]{2,}/'	                             // Runs of spaces, post-handling
			        );


		$replace = array (
					'',                                     // Non-legal carriage return
			        ' ',                                    // Newlines and tabs
			        ' ',                                    // Runs of spaces, pre-handling
			        '',                                     // <script>s -- which strip_tags supposedly has problems with
			        '',                                     // <style>s -- which strip_tags supposedly has problems with
			        //'',                                     // Comments -- which strip_tags might have problem a with
			        "strtoupper(\"\n\n\\1\n\n\")",          // H1 - H3
			        "ucwords(\"\n\n\\1\n\n\")",             // H4 - H6
			        "\n\n\t",                               // <P>
			        "\n",                                   // <br>
			        'strtoupper("\\1")',                    // <b>
			        'strtoupper("\\1")',                    // <strong>
			        '_\\1_',                                // <i>
			        '_\\1_',                                // <em>
			        "\n\n",                                 // <ul> and </ul>
			        "\n\n",                                 // <ol> and </ol>
			        "\t* \\1\n",                            // <li> and </li>
			        "\n\t* ",                               // <li>
			        '$this->_build_link_list("\\1", "\\2")',
			                                                // <a href="">
			        "\n-------------------------\n",        // <hr>
			        "\n\n",                                 // <table> and </table>
			        "\n",                                   // <tr> and </tr>
			        "\t\t\\1\n",                            // <td> and </td>
			        "strtoupper(\"\t\t\\1\n\")",            // <th> and </th>
			        ' ',                                    // Non-breaking space
			        '"',                                    // Double quotes
			        "'",                                    // Single quotes
			        '>',
			        '<',
			        '&',
			        '(c)',
			        '(tm)',
			        '(R)',
			        '--',
			        '-',
			        '*',
			        '£',									// Pound sign £
			        '€',                                  	// Euro sign.
			        '',                                     // Unknown/unhandled entities
			        ' '                                     // Runs of spaces, post-handling
			        );

		$plain_text = str_replace("\r\n","",preg_replace($search, $replace, $document));

	    return $plain_text;
	}

	/**
	 * Simple function for converting object into string.
	 * All properties will be serialized into one big string.
	 *
	 * @param object $obj
	 * @return string $str
	 */
	public function toString($obj) {
		$str = '';
		foreach ( $obj as $property ) {
			$str .= $property;
		}
		return $str;
	}
	
	/**
	 * This is a usage of closure to automate transaction process.
	 * return $controller->transact(function() use($ids) {
     *   	try {
     *   		Some functionality with database content
	 *       	...
     *   		return true;
     *   	} catch (CDbException $e) {
     *   		echo "\nError: ".$e->getMessage();
     *   		return false;
     *   	}
     * });
	 * @param Closure $function
	 * @return boolean
	 */
	public function transact(Closure $function)
	{
		$t = Yii::app()->db->beginTransaction();

	    if ($function() === true) {
	      $t->commit();
	      echo "\nTransaction is commited.\n";
	      return true;
	    }

	    $t->rollback();
	    echo "\nTransaction is rolled back.";
	    return false;
	}
	
	/**
	 * Recursivly convert multidimentional array into PHP standart object
	 * @param array $array
	 * @return string|stdClass|boolean
	 */
	public function makeObjects($array) {
		if(!is_array($array)) {
	        return $array;
	    }
	    $object = new stdClass();
	    if (is_array($array) && count($array) > 0) {
	      foreach ($array as $name=>$value) {
	         $name = strtolower(trim($name));
	         if (!empty($name)) {
	            $object->$name = $this->makeObjects($value);
	         }
	      }
	      return $object; 
	    }
	    else {
	      return FALSE;
	    }
	}
}