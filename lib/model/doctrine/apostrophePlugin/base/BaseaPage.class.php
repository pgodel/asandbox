<?php

/**
 * BaseaPage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $slug
 * @property string $template
 * @property boolean $view_is_secure
 * @property boolean $view_guest
 * @property boolean $edit_admin_lock
 * @property boolean $view_admin_lock
 * @property timestamp $published_at
 * @property boolean $archived
 * @property boolean $admin
 * @property integer $author_id
 * @property integer $deleter_id
 * @property string $engine
 * @property sfGuardUser $Author
 * @property sfGuardUser $Deleter
 * @property Doctrine_Collection $Areas
 * @property Doctrine_Collection $Accesses
 * @property Doctrine_Collection $GroupAccesses
 * @property Doctrine_Collection $aLuceneUpdate
 * @property Doctrine_Collection $Categories
 * @property Doctrine_Collection $aPageToCategory
 * @property Doctrine_Collection $aRedirect
 * @property Doctrine_Collection $aBlogItem
 * 
 * @method integer             getId()              Returns the current record's "id" value
 * @method string              getSlug()            Returns the current record's "slug" value
 * @method string              getTemplate()        Returns the current record's "template" value
 * @method boolean             getViewIsSecure()    Returns the current record's "view_is_secure" value
 * @method boolean             getViewGuest()       Returns the current record's "view_guest" value
 * @method boolean             getEditAdminLock()   Returns the current record's "edit_admin_lock" value
 * @method boolean             getViewAdminLock()   Returns the current record's "view_admin_lock" value
 * @method timestamp           getPublishedAt()     Returns the current record's "published_at" value
 * @method boolean             getArchived()        Returns the current record's "archived" value
 * @method boolean             getAdmin()           Returns the current record's "admin" value
 * @method integer             getAuthorId()        Returns the current record's "author_id" value
 * @method integer             getDeleterId()       Returns the current record's "deleter_id" value
 * @method string              getEngine()          Returns the current record's "engine" value
 * @method sfGuardUser         getAuthor()          Returns the current record's "Author" value
 * @method sfGuardUser         getDeleter()         Returns the current record's "Deleter" value
 * @method Doctrine_Collection getAreas()           Returns the current record's "Areas" collection
 * @method Doctrine_Collection getAccesses()        Returns the current record's "Accesses" collection
 * @method Doctrine_Collection getGroupAccesses()   Returns the current record's "GroupAccesses" collection
 * @method Doctrine_Collection getALuceneUpdate()   Returns the current record's "aLuceneUpdate" collection
 * @method Doctrine_Collection getCategories()      Returns the current record's "Categories" collection
 * @method Doctrine_Collection getAPageToCategory() Returns the current record's "aPageToCategory" collection
 * @method Doctrine_Collection getARedirect()       Returns the current record's "aRedirect" collection
 * @method Doctrine_Collection getABlogItem()       Returns the current record's "aBlogItem" collection
 * @method aPage               setId()              Sets the current record's "id" value
 * @method aPage               setSlug()            Sets the current record's "slug" value
 * @method aPage               setTemplate()        Sets the current record's "template" value
 * @method aPage               setViewIsSecure()    Sets the current record's "view_is_secure" value
 * @method aPage               setViewGuest()       Sets the current record's "view_guest" value
 * @method aPage               setEditAdminLock()   Sets the current record's "edit_admin_lock" value
 * @method aPage               setViewAdminLock()   Sets the current record's "view_admin_lock" value
 * @method aPage               setPublishedAt()     Sets the current record's "published_at" value
 * @method aPage               setArchived()        Sets the current record's "archived" value
 * @method aPage               setAdmin()           Sets the current record's "admin" value
 * @method aPage               setAuthorId()        Sets the current record's "author_id" value
 * @method aPage               setDeleterId()       Sets the current record's "deleter_id" value
 * @method aPage               setEngine()          Sets the current record's "engine" value
 * @method aPage               setAuthor()          Sets the current record's "Author" value
 * @method aPage               setDeleter()         Sets the current record's "Deleter" value
 * @method aPage               setAreas()           Sets the current record's "Areas" collection
 * @method aPage               setAccesses()        Sets the current record's "Accesses" collection
 * @method aPage               setGroupAccesses()   Sets the current record's "GroupAccesses" collection
 * @method aPage               setALuceneUpdate()   Sets the current record's "aLuceneUpdate" collection
 * @method aPage               setCategories()      Sets the current record's "Categories" collection
 * @method aPage               setAPageToCategory() Sets the current record's "aPageToCategory" collection
 * @method aPage               setARedirect()       Sets the current record's "aRedirect" collection
 * @method aPage               setABlogItem()       Sets the current record's "aBlogItem" collection
 * 
 * @package    asandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseaPage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('a_page');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('slug', 'string', 1000, array(
             'type' => 'string',
             'length' => 1000,
             ));
        $this->hasColumn('template', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('view_is_secure', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('view_guest', 'boolean', null, array(
             'type' => 'boolean',
             'default' => true,
             ));
        $this->hasColumn('edit_admin_lock', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('view_admin_lock', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('published_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('archived', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('admin', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('author_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('deleter_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('engine', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));


        $this->index('slugindex', array(
             'fields' => 
             array(
              'slug' => 
              array(
              'length' => 1000,
              'unique' => true,
              ),
             ),
             ));
        $this->index('engineindex', array(
             'fields' => 
             array(
              0 => 'engine',
             ),
             ));
        $this->option('type', 'INNODB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser as Author', array(
             'local' => 'author_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasOne('sfGuardUser as Deleter', array(
             'local' => 'deleter_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('aArea as Areas', array(
             'local' => 'id',
             'foreign' => 'page_id'));

        $this->hasMany('aAccess as Accesses', array(
             'local' => 'id',
             'foreign' => 'page_id'));

        $this->hasMany('aGroupAccess as GroupAccesses', array(
             'local' => 'id',
             'foreign' => 'page_id'));

        $this->hasMany('aLuceneUpdate', array(
             'local' => 'id',
             'foreign' => 'page_id'));

        $this->hasMany('aCategory as Categories', array(
             'refClass' => 'aPageToCategory',
             'local' => 'page_id',
             'foreign' => 'category_id'));

        $this->hasMany('aPageToCategory', array(
             'local' => 'id',
             'foreign' => 'page_id'));

        $this->hasMany('aRedirect', array(
             'local' => 'id',
             'foreign' => 'page_id'));

        $this->hasMany('aBlogItem', array(
             'local' => 'id',
             'foreign' => 'page_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             ));
        $nestedset0 = new Doctrine_Template_NestedSet(array(
             ));
        $taggable0 = new Taggable(array(
             ));
        $this->actAs($timestampable0);
        $this->actAs($nestedset0);
        $this->actAs($taggable0);
    }
}