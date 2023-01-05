<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Forms\SignInFormFactory;
use App\Presenters\BasePresenter;
use Nette;
use App\Forms\SignUpFormFactory;
use Nette\Application\UI\Form;


class SignPresenter extends BasePresenter
{
    /** @persistent */
    public $backlink = '';

    /** @var SignInFormFactory */
    private $signInFactory;


    public function __construct(SignInFormFactory $signInFactory)
    {
        $this->signInFactory = $signInFactory;
    }

    protected function createComponentSignInForm(): Form
    {
        return $this->signInFactory->create(function (): void {
            $this->restoreRequest($this->backlink);
            $this->redirect('Dashboard:');
        });
    }

    public function actionOut(): void
    {
        $this->getUser()->logout();
    }
}
