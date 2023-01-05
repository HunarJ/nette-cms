<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;


/**
 * Class CategoryManager
 * @package App\Model
 */
class CategoryManager extends DatabaseManager
{

    /** Konstanty pro práci s databází */
    const
        TABLE_NAME = 'category',
        COLUMN_ID = 'id',
        COLUMN_TITLE = 'name',
        COLUMN_URL = 'url';

    public function getCategories(): Selection
    {
        return $this->database->table(self::TABLE_NAME)->order(self::COLUMN_ID . ' ASC');
    }

    public function getAllCategory()
    {
        return $this->database->table(self::TABLE_NAME)->fetchPairs(self::COLUMN_ID, self::COLUMN_TITLE);
    }

    public function getCategory(string $url): ActiveRow
    {
        return $this->database->table(self::TABLE_NAME)
            ->where(self::COLUMN_URL, $url)->fetch();
    }

    public function removeCategory(string $url)
    {
        $this->database->table(self::TABLE_NAME)->where(self::COLUMN_URL, $url)->delete();
    }

    public function saveCategory(array $category)
    {
        if (empty($category[self::COLUMN_ID])) {
            unset($category[self::COLUMN_ID]);
            $this->database->table(self::TABLE_NAME)->insert($category);
        } else {
            $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $category[self::COLUMN_ID])->update($category);
        }
    }
}