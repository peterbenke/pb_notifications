<?php

namespace PeterBenke\PbNotifications\ViewHelpers\Widget;

/**
 * TYPO3
 */
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Service\ExtensionService;

/**
 * TYPO3Fluid
 */
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Php
 */
use Closure;

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
 * <f:render partial="Pagination" arguments="{pagination: paginatedBlogs, configuration: configuration}" contentAs="childrens">
 * // use {paginatedBlogs} as you used {blogs} before, most certainly inside
 * </f:render>
 *   // a <f:for> loop.
 * </f:widget.paginate>
 * </code>
 *
 */
class PaginateViewHelper extends AbstractViewHelper
{

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('objects', 'mixed', 'array or queryresult', true);
        $this->registerArgument('as', 'string', 'new variable name', true);
        $this->registerArgument('configuration', 'array', 'configuration', true);
//        $this->registerArgument('name', 'string', 'unique identification - will take "as" as fallback', false, '');
    }

    /**
     * @param array $arguments
     * @param Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array                     $arguments,
        Closure                   $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string
	{
        $configuration = [
            'itemsPerPage' => 10,
            'insertAbove' => false,
            'insertBelow' => true,
            'maximumNumberOfLinks' => 99,
            'addQueryStringMethod' => '',
            'section' => ''
        ];

        ArrayUtility::mergeRecursiveWithOverrule($configuration, $arguments['configuration'], false);
        $configuration['currentPage'] = self::getPageNumber($renderingContext);

        $itemsPerPage = (int)$configuration['itemsPerPage'];

        if (isset($arguments['objects'])) {
            if ($arguments['objects'] instanceof ObjectStorage) {
                $objects = $arguments['objects']->toArray();
            } else if (is_array($arguments['objects'])) {
                $objects = $arguments['objects'];
            } else {
                return $renderChildrenClosure();
            }
        } else {
            return $renderChildrenClosure();
        }

        $paginator = new ArrayPaginator($objects, self::getPageNumber($renderingContext), $itemsPerPage);
        $pagination = new SimplePagination($paginator);

        $range = self::calculateDisplayRange($configuration, $paginator->getNumberOfPages());

        $templateVariableContainer = $renderingContext->getVariableProvider();
        $templateVariableContainer->add($arguments['as'], [
            'pages' => range(1, $paginator->getNumberOfPages()),
            'items' => $paginator->getPaginatedItems(),
            'paginator' => $pagination,
            'currentPage' => $configuration['currentPage'],
            'numberOfPages' => $paginator->getNumberOfPages(),
            'previousPage' => $pagination->getPreviousPageNumber(),
            'nextPage' => $pagination->getNextPageNumber(),
            'firstPage' => $pagination->getFirstPageNumber(),
            'lastPage' => $pagination->getLastPageNumber(),
            'displayRangeStart' => $range['displayRangeStart'],
            'displayRangeEnd' => $range['displayRangeEnd'],
            'hasLessPages' => $range['displayRangeStart'] > 2,
            'hasMorePages' => $range['displayRangeEnd'] + 1 < $paginator->getNumberOfPages()

        ]);
        $templateVariableContainer->add(
            'configuration', $configuration
        );
        return $renderChildrenClosure();
    }

    /**
     * If a certain number of links should be displayed, adjust before and after
     * amounts accordingly.
     * @author Peter Benke <pbenke@allplan.com>
     */
    protected static function calculateDisplayRange(array $configurations, int $numberOfPages): array
    {
        $maximumNumberOfLinks = $configurations['maximumNumberOfLinks'];
        if ($maximumNumberOfLinks > $numberOfPages) {
            $maximumNumberOfLinks = $numberOfPages;
        }
        $delta = floor($maximumNumberOfLinks / 2);
        $displayRangeStart = $configurations['currentPage'] - $delta;
        $displayRangeEnd = $configurations['currentPage'] + $delta - ($maximumNumberOfLinks % 2 === 0 ? 1 : 0);
        if ($displayRangeStart < 1) {
            $displayRangeEnd -= $displayRangeStart - 1;
        }
        if ($displayRangeEnd > $numberOfPages) {
            $displayRangeStart -= $displayRangeEnd - $numberOfPages;
        }
        return [
            'displayRangeStart' => (int)max($displayRangeStart, 1),
            'displayRangeEnd' => (int)min($displayRangeEnd, $numberOfPages)
        ];
    }

    /**
     * @param RenderingContextInterface $renderingContext
     * @return int
     */
    protected static function getPageNumber(RenderingContextInterface $renderingContext): int
    {
        /**
         * @todo ControllerContext is deprecated in v11 and removed in v12
         *
         * 11.5 Deprecation: #95139 - Extbase ControllerContext
         *   https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.5/Deprecation-95139-ExtbaseControllerContext.html
         * 12.0 Breaking: #96107 - Deprecated functionality removed
         *
         * Migration:
         * - Method getRequest() is available in controllers directly, and view-helpers receive the current request by calling RenderingContext->getRequest().
         */
        $request = null;
        if (method_exists($renderingContext, 'getRequest')) {
            $request = $renderingContext->getRequest();
        }

        if (!$request) {
            return 0;
        }

        $extensionName = $request->getControllerExtensionName();
        $pluginName = $request->getPluginName();
        $extensionService = GeneralUtility::makeInstance(ExtensionService::class);
        $pluginNamespace = $extensionService->getPluginNamespace($extensionName, $pluginName);

        /**
         * @todo _GP is deprecated in v12
         *
         *   Deprecation: #100053 - GeneralUtility::_GP()
         */
        $variables = GeneralUtility::_GP($pluginNamespace);
        if ($variables !== null) {
            if (!empty($variables['currentPage'])) {
                return (int)$variables['currentPage'];
            }
        }
        return 1;
    }

}
