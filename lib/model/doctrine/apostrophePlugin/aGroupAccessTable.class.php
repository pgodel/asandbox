<?php


class aGroupAccessTable extends PluginaGroupAccessTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aGroupAccess');
    }
}