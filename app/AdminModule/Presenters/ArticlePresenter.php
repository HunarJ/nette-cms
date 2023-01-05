<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\ArticleManager;
use App\Model\CategoryManager;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Tomaj\Form\Renderer\BootstrapVerticalRenderer;


/**
 * Class ArticlePresenter
 * @package App\AdminModule\Presenters
 */
class ArticlePresenter extends BaseAdminPresenter
{
    /** @var CategoryManager */
    private $categoryManager;
    /** @var ArticleManager */
    private $articleManager;

    /**
     * ArticlePresenter constructor.
     * @param ArticleManager $articleManager
     * @param CategoryManager $categoryManager
     */
    public function __construct(ArticleManager $articleManager, CategoryManager $categoryManager)
    {
        parent::__construct();
        $this->categoryManager = $categoryManager;
        $this->articleManager = $articleManager;
    }

    public function renderList()
    {
        $this->template->articles = $this->articleManager->getAllArticles();
    }

    public function actionRemove(string $url = null)
    {
        $this->articleManager->removeArticle($url);
        $this->flashMessage('Článek byl úspěšně odstraněn');
        $this->redirect('Article:list');
    }

    public function actionEditor(string $url = null)
    {
        if ($url) {
            if (!($article = $this->articleManager->getArticle($url))) {
                $this->flashMessage('Článek nebyl nalezen');
            } else {
                $this['editorForm']->setDefaults($article);
            }
        }
    }

    protected function createComponentEditorForm(): Form
    {
        $form = new Form;
        $form->setRenderer(new BootstrapVerticalRenderer);
        $form->addHidden('id');
        $form->addText('title', 'Titulek')->setRequired();
        $form->addText('url', 'URL')->setRequired();
        $form->addUpload('picture', 'Obrázek:')
            ->setRequired(false)
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE);
        $form->addText('short_description', 'Popisek')->setRequired();
        $form->addTextArea('description', 'Obsah');
        $categories = $this->categoryManager->getAllCategory();
        $form->addSelect('categories', 'Kategorie:', $categories)
            ->setPrompt('Zvolte kategorii');
        $form->addSubmit('save', 'Uložit článek');
        $form->onSuccess[] = function (Form $form, array $values) {
            try {
                $this->articleManager->saveArticle($values);
                $this->flashMessage('Článek byl úspěšně uložen');
                $this->redirect('Article:list');
            } catch (UniqueConstraintViolationException $e) {
                $this->flashMessage('Článek s touto URL adresou již existuje');
            }
        };
        return $form;
    }

}
