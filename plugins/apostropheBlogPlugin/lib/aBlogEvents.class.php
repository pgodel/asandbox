<?php

class aBlogEvents
{
  // command.post_command
  static public function listenToCommandPostCommandEvent(sfEvent $event)
  {
    $task = $event->getSubject();
    
    if ($task->getFullName() === 'apostrophe:migrate')
    {
      self::migrate();
    }
  }
  
  static public function migrate()
  {
    $migrate = new aMigrate(Doctrine_Manager::connection()->getDbh());
    $blogIsNew = false;
    echo("Migrating apostropheBlogPlugin...\n");
    
    if (!$migrate->tableExists('a_blog_item'))
    {
      $migrate->sql(array(
"        CREATE TABLE a_blog_editor (blog_item_id BIGINT, user_id BIGINT, PRIMARY KEY(blog_item_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;",
"CREATE TABLE a_blog_item (id BIGINT AUTO_INCREMENT, author_id BIGINT, page_id BIGINT, title VARCHAR(255) NOT NULL, slug_saved TINYINT(1) DEFAULT '0', excerpt TEXT, status VARCHAR(255) DEFAULT 'draft' NOT NULL, allow_comments TINYINT(1) DEFAULT '0' NOT NULL, template VARCHAR(255) DEFAULT 'singleColumnTemplate', published_at DATETIME, type VARCHAR(255), start_date DATE, start_time TIME, end_date DATE, end_time TIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, slug VARCHAR(255), INDEX a_blog_item_type_idx (type), UNIQUE INDEX a_blog_item_sluggable_idx (slug), INDEX author_id_idx (author_id), INDEX page_id_idx (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;",
"        ALTER TABLE a_blog_editor ADD CONSTRAINT a_blog_editor_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id);",
"        ALTER TABLE a_blog_editor ADD CONSTRAINT a_blog_editor_blog_item_id_a_blog_item_id FOREIGN KEY (blog_item_id) REFERENCES a_blog_item(id);",
"        ALTER TABLE a_blog_item ADD CONSTRAINT a_blog_item_page_id_a_page_id FOREIGN KEY (page_id) REFERENCES a_page(id) ON DELETE CASCADE;",
"        ALTER TABLE a_blog_item ADD CONSTRAINT a_blog_item_author_id_sf_guard_user_id FOREIGN KEY (author_id) REFERENCES sf_guard_user(id) ON DELETE SET NULL;"
      ));
    }
    
    if (!$migrate->columnExists('a_blog_item', 'location'))
    {
      $migrate->sql(array(
        'ALTER TABLE a_blog_item ADD COLUMN location varchar(300)'
      ));
    }
    
    if (!$migrate->columnExists('a_blog_item', 'start_time'))
    {
      $migrate->sql(array(
        'ALTER TABLE a_blog_item ADD COLUMN start_time TIME',
        'ALTER TABLE a_blog_item ADD COLUMN end_time TIME'));
    }
    
    if (!$migrate->tableExists('a_page_to_category'))
    {
      $migrate->sql(array(
        "CREATE TABLE a_page_to_category (page_id BIGINT, category_id BIGINT, PRIMARY KEY(page_id, category_id)) ENGINE = INNODB;"
      ));
    }
    
    if (!$migrate->tableExists('a_blog_item_to_category'))
    {
      $migrate->sql(array(
        "CREATE TABLE a_blog_item_to_category (blog_item_id BIGINT, category_id BIGINT, PRIMARY KEY(blog_item_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;",
        "ALTER TABLE a_blog_item_to_category ADD CONSTRAINT a_blog_item_to_category_category_id_a_category_id FOREIGN KEY (category_id) REFERENCES a_category(id) ON DELETE CASCADE;",
        "ALTER TABLE a_blog_item_to_category ADD CONSTRAINT a_blog_item_to_category_blog_item_id_a_blog_item_id FOREIGN KEY (blog_item_id) REFERENCES a_blog_item(id) ON DELETE CASCADE;"
        ));  
      
      echo("Migrating blog categories to Apostrophe categories...\n");
        
      $oldCategories = array();
      if ($migrate->tableExists('a_blog_category'))
      {
        $oldCategories = $migrate->query('SELECT * FROM a_blog_category');
      }
      $newCategories = $migrate->query('SELECT * FROM a_category');
      $nc = array();
      foreach ($newCategories as $newCategory)
      {
        $nc[$newCategory['name']] = $newCategory;
      }
      $oldIdToNewId = array();
      foreach ($oldCategories as $category)
      {
        if (isset($nc[$category['name']]))
        {
          $oldIdToNewId[$category['id']] = $nc[$category['name']]['id'];
        }
        else
        {
          // Blog categories didn't have slugs
          $category['slug'] = aTools::slugify($category['name']);
          $migrate->query('INSERT INTO a_category (name, description, slug) VALUES (:name, :description, :slug)', $category);
          $oldIdToNewId[$category['id']] = $migrate->lastInsertId();
        }
      }
      echo("Migrating from aBlogItemCategory to aBlogItemToCategory...\n");
      
      if ($migrate->tableExists('a_blog_item_category'))
      {
        $itemIds = $migrate->query('SELECT id FROM a_blog_item');
        $validItemIds = array();
        foreach ($itemIds as $row)
        {
          $validItemIds[$row['id']] = true;
        }
        $oldMappings = $migrate->query('SELECT * FROM a_blog_item_category');
        foreach ($oldMappings as $info)
        {
          $info['category_id'] = $oldIdToNewId[$info['blog_category_id']];
          if (isset($validItemIds[$info['blog_item_id']]))
          {
            $migrate->query('INSERT INTO a_blog_item_to_category (blog_item_id, category_id) VALUES (:blog_item_id, :category_id)', $info);
          }
        }
      }
    }

    // Older updates may not have categories on the virtual page
    
    $blogPagesById = array();
    $blogPageIdInfos = $migrate->query("SELECT id, page_id FROM a_blog_item");
    foreach ($blogPageIdInfos as $info)
    {
      $blogPagesById[$info['id']] = $info['page_id'];
    }
    
    $blogToCategories = $migrate->query("SELECT * FROM a_blog_item_to_category");
    foreach ($blogToCategories as $toCategory)
    {
      $migrate->query("INSERT INTO a_page_to_category (category_id, page_id) VALUES (:category_id, :page_id) ON DUPLICATE KEY UPDATE category_id = category_id", array('category_id' => $toCategory['category_id'], 'page_id' => $blogPagesById[$toCategory['blog_item_id']]));
    }
        
    // Older versions did not have taggings on the virtual page
    
    $blogTaggings = $migrate->query("SELECT * FROM tagging WHERE taggable_model IN ('aBlogPost', 'aEvent')");
    $blogTagsById = array();
    foreach ($blogTaggings as $tagging)
    {
      $blogTagsById[$tagging['taggable_id']][$tagging['tag_id']] = true;
    }
    $pageTaggings = $migrate->query("SELECT * FROM tagging WHERE taggable_model IN ('aPage')");
    $pageTagsById = array();
    foreach ($pageTaggings as $tagging)
    {
      $pageTagsById[$tagging['taggable_id']][$tagging['tag_id']] = true;
    }
    foreach ($blogTagsById as $blogId => $tags)
    {
      if (!isset($blogPagesById[$blogId]))
      {
        // No virtual page - just a stale tagging
        continue;
      }
      foreach ($tags as $tagId => $dummy)
      {
        if (!isset($pageTagsById[$blogPagesById[$blogId]][$tagId]))
        {
          $migrate->query('INSERT INTO tagging (taggable_model, taggable_id, tag_id) VALUES ("aPage", :taggable_id, :tag_id)', array('taggable_id' => $blogPagesById[$blogId], 'tag_id' => $tagId));
        }
      }
    }
    
    $migrate->query('UPDATE a_page SET engine = "aBlog" WHERE slug LIKE "@a_blog_search_redirect%"');
    $migrate->query('UPDATE a_page SET engine = "aEvent" WHERE slug LIKE "@a_event_search_redirect%"');
    // Older blog post virtual pages won't have published_at
    $migrate->query('update a_page p inner join a_blog_item bi on bi.page_id = p.id set p.published_at = bi.published_at');
    // Really old events may have full timestamps in start_date and end_date, break them out
    $migrate->query('UPDATE a_blog_item SET start_time = substr(start_date, 12), start_date = substr(start_date, 1, 10) WHERE (length(start_date) > 10) AND start_time IS NULL');
    $migrate->query('ALTER TABLE a_blog_item modify column start_date date;');
    $migrate->query('UPDATE a_blog_item SET end_time = substr(end_date, 12), end_date = substr(end_date, 1, 10) WHERE (length(end_date) > 10) AND end_time IS NULL');
    $migrate->query('ALTER TABLE a_blog_item modify column end_date date;');
    // Migrate old full day events from before we started defining this as a null start and end time
    $migrate->query('UPDATE a_blog_item SET start_time = null, end_time = null WHERE start_time = "00:00:00" AND end_time = "00:00:00"');
    
    if ($migrate->tableExists('a_blog_category_user'))
    {
      $oldCategoryUsers = $migrate->query('SELECT * FROM a_blog_category_user');
      $oldCategories = $migrate->query('SELECT * from a_blog_category');
      $newCategories = $migrate->query('SELECT * from a_category');
      $oldByName = array();
      foreach ($oldCategories as $oldCategory)
      {
        $oldByName[$oldCategory['name']] = $oldCategory['id'];
      }
      $newByName = array();
      foreach ($newCategories as $newCategory)
      {
        $newByName[$newCategory['name']] = $newCategory['id'];
      }
      $oldToNew = array();
      foreach ($oldByName as $name => $id)
      {
        $oldToNew[$id] = $newByName[$name];
      }
      foreach ($oldCategoryUsers as $oldCategoryUser)
      {
        $migrate->query('INSERT INTO a_category_user (category_id, user_id) VALUES (:category_id, :user_id) ON DUPLICATE KEY UPDATE category_id = category_id', array('category_id' => $oldToNew[$oldCategoryUser['blog_category_id']], 'user_id' => $oldCategoryUser['user_id']));
      }
    }
    if ($migrate->tableExists('a_blog_category_group'))
    {
      $oldCategoryGroups = $migrate->query('SELECT * FROM a_blog_category_group');
      $oldCategories = $migrate->query('SELECT * from a_blog_category');
      $newCategories = $migrate->query('SELECT * from a_category');
      $oldByName = array();
      foreach ($oldCategories as $oldCategory)
      {
        $oldByName[$oldCategory['name']] = $oldCategory['id'];
      }
      $newByName = array();
      foreach ($newCategories as $newCategory)
      {
        $newByName[$newCategory['name']] = $newCategory['id'];
      }
      $oldToNew = array();
      foreach ($oldByName as $name => $id)
      {
        $oldToNew[$id] = $newByName[$name];
      }
      foreach ($oldCategoryGroups as $oldCategoryGroup)
      {
        if (!isset($oldToNew[$oldCategoryGroup['blog_category_id']]))
        {
          echo("WARNING: there is no a_blog_category with the id " . $oldCategoryGroup['blog_category_id'] . "\n");
          continue;
        }
        $migrate->query('INSERT INTO a_category_group (category_id, group_id) VALUES (:category_id, :group_id) ON DUPLICATE KEY UPDATE category_id = category_id', array('category_id' => $oldToNew[$oldCategoryGroup['blog_category_id']], 'group_id' => $oldCategoryGroup['group_id']));
      }
    }
    // Blog item tags must also be on the virtual page, ditto for categories
    if (!$migrate->getCommandsRun())
    {
      echo("Your database is already up to date.\n\n");
    }
    else
    {
      echo($migrate->getCommandsRun() . " SQL commands were run.\n\n");
    }
    echo("Done!\n");
    
  }
}

