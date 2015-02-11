<?php

/**
 * URL manager extended for adding multilangual SEO data after url
 * 
 * @author armos Armen Bablanyan http://www.armos.am
 * @copyright armos 2012 by Armen Bablanyan
 * @version v0.1
 *
 */
class UrlManager extends CUrlManager {
	
	/**
	 * For creating SEO approved URLs.
	 * Getting slag data from DB and adding after URL as params['slag'].
	 *
	 * @see CUrlManager::createUrl()
	 */
	public function createUrl($route, $params = array(), $ampersand = '&') {
		if (! isset ( $params ['language'] )) {
			if (Yii::app ()->user->hasState ( 'language' ))
				Yii::app ()->language = Yii::app ()->user->getState ( 'language' );
			elseif (isset ( Yii::app ()->request->cookies ['language'] ))
				Yii::app ()->language = Yii::app ()->request->cookies ['language']->value;
			$params ['language'] = Yii::app ()->language;
		}
		
		if (isset ( $params ['pid'] ) && (! isset ( $params ['slag'] ) || $params ['slag'] == '')) {
			
			if (strtolower ( $route ) == 'site/list') {
				// add here for list
				$params ['slag'] = Yii::app ()->controller->getSEOSlag ( $params ['pid'], 'list' );
			} elseif (strtolower ( $route ) == 'site/item') {
				// add here for items
				$params ['slag'] = Yii::app ()->controller->getSEOSlag ( $params ['pid'], 'item' );
			} else {
				// add here for menus
				$params ['slag'] = Yii::app ()->controller->getSEOSlag ( $params ['pid'], 'menu' );
			}
		}
		return str_replace ( '+', '-', rawurldecode ( parent::createUrl ( $route, $params, $ampersand ) ) );
	}
}
?>