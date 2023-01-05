<?php
declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use App\Model;

final class SignUpFormFactory
{
    use Nette\SmartObject;

    private const PASSWORD_MIN_LENGHT = 6;

    /*
     * @var FormFactory
     */
    private $factory;

    /** @var Model\UserManager */
    private $userManager;

    public function __construct(FormFactory $factory, Model\UserManager $userManager)
    {
        $this->factory = $factory;
        $this->userManager = $userManager;

    }

    public function create(callable $onSuccess): Form
    {
        $form = $this->factory->create();
        $form->addText('firstname', 'Jméno:')
            ->setRequired('Zadejte prosím jméno.');
        $form->addText('lastname', 'Příjmení:')
            ->setRequired('Zadejte prosím příjmení.');

        $form->addEmail('email', 'Email:')
            ->setRequired('Zadejte prosím platný email.');

        $form->addPassword('password', 'Password:')
            ->setOption('description', sprintf('Heslo musí mít minimálně %d znaků', self::PASSWORD_MIN_LENGHT))
            ->setRequired('Zvolte si prosím Vaše heslo.')
            ->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGHT);
        $form->addPassword('password_repeat', 'Heslo znovu')
            ->setOmitted()
            ->setRequired(false)
            ->addRule(Form::EQUAL, 'Hesla nesouhlasí', $form['password']);

        $roles = [
            'Role' => [
                'member' => 'Správce',
                'admin' => 'Administrátor'
            ]
        ];

        $form->addSelect('role', 'Role:', $roles)
            ->setPrompt('Vyberte roli');

        $form->addSubmit('send', 'Vytvořit uživatele');
        $form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
            try {
                $this->userManager->add($values->firstname, $values->lastname, $values->email, $values->password, $values->role);
            } catch (Model\DuplicateNameException $e) {
                $form['email']->addError('Tento email již v databázi existuje');
                return;
            }
            $onSuccess();
        };
        return $form;
    }
}

class DuplicateNameException extends \Exception {
    protected $message = 'Uživatel je s tímto emailem již zaregistrovaný.';
}
