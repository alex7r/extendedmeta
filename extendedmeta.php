<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.ExtendedMeta
 *
 * @copyright   Copyright (C) 2005-2016 JoomPlace, www.joomplace.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Plugin to enables ability for full SEO control
 *
 * @since  3.6
 */
class PlgSystemExtendedMeta extends JPlugin
{
	protected $title;

	/**
	 * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	public function onContentPrepare($context, &$article, &$params, $page = 0){
		// Don't run this plugin when the content is being indexed
		if ($context != 'com_content.article')
		{
			return true;
		}

		$active_menu = JFactory::getApplication()->getMenu()->getActive();
		if ($_SERVER['REMOTE_ADDR'] == '37.44.125.238')
		{
			/**
			 * if current menu item is menu item for current article and it has page_title set
			 * then it's configurations should be in priority
			 */
			if($active_menu->query['option']=='com_content' && $active_menu->query['view']=='article' && $active_menu->query['id']==$article->id && $active_menu->params->get('page_title','')){
				return true;
			}
			$title = $article->metadata->get('title','');
			if($title){
				$this->title = $title;
			}
		}
	}

	public function onContentPrepareForm($form, $data)
	{
		// Check we are manipulating a valid form.
		$name = $form->getName();

		$app    = JFactory::getApplication();
		$option = $app->input->get('option');

		switch ($option)
		{
			case 'com_content' :
				JForm::addFormPath(__DIR__ . '/forms');
				$form->loadFile('extratags_content', false);

				return true;
		}

	}

	public function onBeforeCompileHead(){
		$doc = JFactory::getDocument();
		if(isset($this->title)){
			$doc->setTitle($this->title);
		}
	}

}
