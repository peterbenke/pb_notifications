<?php
namespace PeterBenke\PbNotifications\ViewHelpers\Widget;

/**
 * PbNotifications
 */
use PeterBenke\PbNotifications\ViewHelpers\Widget\Controller\PaginateController;

/**
 * TYPO3
 */
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 * Class PaginateViewHelper
 * @package PeterBenke\PbNotifications\ViewHelpers\Widget
 *
 * This ViewHelper renders a Pagination of objects.
 *
 * = Examples =
 *
 * <code title="required arguments">
 * <f:widget.paginate objects="{blogs}" as="paginatedBlogs">
 *   // use {paginatedBlogs} as you used {blogs} before, most certainly inside
 *   // a <f:for> loop.
 * </f:widget.paginate>
 * </code>
 *
 */
class PaginateViewHelper extends AbstractWidgetViewHelper
{

	/**
	 * @var PaginateController
	 */
	protected $controller;

	/**
	 * Inject controller
	 * @param PaginateController $controller
	 */
	public function injectController(PaginateController $controller)
	{
		$this->controller = $controller;
		$this->registerArgument('objects', ObjectStorage::class, 'Objects', true);
		$this->registerArgument('as', 'string', 'as', true);
		$this->registerArgument('configuration', 'array', 'configuration', true);
		$this->registerArgument('initial', 'array', 'initial', false);
	}

	/**
	 * @return ResponseInterface
	 */
	public function render(): ResponseInterface
	{
		return $this->initiateSubRequest();
	}

}
