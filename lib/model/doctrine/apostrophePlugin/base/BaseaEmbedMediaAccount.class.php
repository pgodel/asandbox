<?php

/**
 * BaseaEmbedMediaAccount
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $service
 * @property string $username
 * 
 * @method integer            getId()       Returns the current record's "id" value
 * @method string             getService()  Returns the current record's "service" value
 * @method string             getUsername() Returns the current record's "username" value
 * @method aEmbedMediaAccount setId()       Sets the current record's "id" value
 * @method aEmbedMediaAccount setService()  Sets the current record's "service" value
 * @method aEmbedMediaAccount setUsername() Sets the current record's "username" value
 * 
 * @package    asandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseaEmbedMediaAccount extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('a_embed_media_account');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('service', 'string', 100, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('username', 'string', 100, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 100,
             ));

        $this->option('type', 'INNODB');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}