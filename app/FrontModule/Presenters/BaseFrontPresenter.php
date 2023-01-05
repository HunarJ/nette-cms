<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use Nette;
use App\Presenters\BasePresenter;
use App\Model\CategoryManager;
use App\Model\CmsManager;



class BaseFrontPresenter extends BasePresenter
{
    /** @var CategoryManager */
    protected $categoryManager;

    /** @var CmsManager */
    protected $cmsManager;

    /**
     * @param CategoryManager $categoryManager
     * @param CmsManager $cmsManager
     */
    public function injectManagerDependencies(CategoryManager $categoryManager, CmsManager $cmsManager)
    {
        $this->categoryManager = $categoryManager;
        $this->cmsManager = $cmsManager;
    }

    protected function beforeRender()
    {
        parent::beforeRender();
        $this->template->domain = $this->getHttpRequest()->getUrl()->getHost();
        $this->template->categories = $this->categoryManager->getCategories();
        $this->template->menuItems = $this->cmsManager->getMenuItems();
    }
}
