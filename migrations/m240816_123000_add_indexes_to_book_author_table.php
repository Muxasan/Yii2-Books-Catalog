<?php
use yii\db\Migration;

class m240816_123000_add_indexes_to_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-book-author-book-id',
            'book_author',
            'book_id'
        );

        $this->createIndex(
            'idx-book-author-author-id',
            'book_author',
            'author_id'
        );

        $this->createIndex(
            'uq-book-author',
            'book_author',
            ['book_id', 'author_id'],
            true // Unique index
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-book-author-book-id', 'book_author');
        $this->dropIndex('idx-book-author-author-id', 'book_author');
        $this->dropIndex('uq-book-author', 'book_author');
    }
}