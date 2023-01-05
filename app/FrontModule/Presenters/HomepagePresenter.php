<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\CmsManager;


class HomepagePresenter extends BaseFrontPresenter
{
    public function renderDefault() : void
    {
        $this->template->article = $this->cmsManager->getHomePage();
    }
}
