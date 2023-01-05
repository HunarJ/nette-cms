<?php

declare(strict_types=1);

namespace App\Model;

use Nette\SmartObject;
use Nette\Database\Explorer;



abstract class DatabaseManager
{
    use SmartObject;

    /** @var Explorer */
    protected $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

}