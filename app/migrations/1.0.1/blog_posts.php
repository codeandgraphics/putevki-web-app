<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class BlogPostsMigration_101
 */
class BlogPostsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('blog_posts', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'createdBy',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'title',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'createdBy'
                        ]
                    ),
                    new Column(
                        'uri',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'title'
                        ]
                    ),
                    new Column(
                        'excerpt',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'uri'
                        ]
                    ),
                    new Column(
                        'content',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'excerpt'
                        ]
                    ),
                    new Column(
                        'preview',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'content'
                        ]
                    ),
                    new Column(
                        'metaKeywords',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'preview'
                        ]
                    ),
                    new Column(
                        'metaDescription',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'metaKeywords'
                        ]
                    ),
                    new Column(
                        'created',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'metaDescription'
                        ]
                    ),
                    new Column(
                        'active',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'created'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('id', ['id'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '121',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        $this->batchInsert('blog_posts', [
                'id',
                'createdBy',
                'title',
                'uri',
                'excerpt',
                'content',
                'preview',
                'metaKeywords',
                'metaDescription',
                'created',
                'active'
            ]
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->batchDelete('blog_posts');
    }

}
