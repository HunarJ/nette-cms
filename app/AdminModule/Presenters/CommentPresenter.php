<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use Nette;
use Nette\Application\AbortException;
use App\Model\CommentManager;

class CommentPresenter extends BaseAdminPresenter
{
    /** @var CommentManager */
    private $commentManager;

    public function __construct(CommentManager $commentManager)
    {
        parent::__construct();
        $this->commentManager = $commentManager;
    }

    public function renderList()
    {
        $this->template->comments = $this->commentManager->getAllComments();
    }

    public function actionDelete(int $id = null)
    {
        $this->commentManager->deleteComment($id);
        $this->flashMessage('Komentář byl úspěšně smazán');
        $this->redirect('Comment:list');
    }

}
