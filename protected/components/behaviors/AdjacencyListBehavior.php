<?php

Yii::import('behaviors.LitsBehavior');

/**
 * @property string $parentAttribute
 * @property string $parentRelation
 *
 * @property integer[] $childsArray
 * @property mixed $tabList
 * @property string $path
 * @property string $breadcrumbs
 */
class AdjacencyListBehavior extends LitsBehavior
{
    /**
     * @var string model attribute
     */
    public $parentAttribute = 'parent_id';
    /**
     * @var string model parent BELONGS_TO relation
     */
    public $parentRelation = 'parent';

    /**
     * Returns array of primary keys of children items
     * @param mixed $parent number, object or array of numbers
     * @return array
     */
    public function getChildsArray($parent = 0, $recursive = true)
    {
        $parents = $this->processParents($parent);

        $this->cached();

        $criteria = $this->getOwnerCriteria();
        $criteria->select = 't.'. $this->primaryKeyAttribute . ', t.' . $this->titleAttribute . ', t.' . $this->parentAttribute;
        $command = $this->createFindCommand($criteria);
        $items = $command->queryAll();
        $this->clearOwnerCriteria();

        $result = array();

        foreach ($parents as $parent_id){
            if($recursive)
                $this->_childsArrayRecursive($items, $result, $parent_id);
            else 
                $this->_childsArray($items, $result, $parent_id);
        }

        return array_unique($result);
    }

    protected function _childsArrayRecursive(&$items, &$result, $parent_id)
    {
        foreach ($items as $item){
            if ((int)$item[$this->parentAttribute] == (int)$parent_id){
                $result[] = $item[$this->primaryKeyAttribute];
                $this->_childsArrayRecursive($items, $result, $item[$this->primaryKeyAttribute]);
            }
        }
    }
    
    protected function _childsArray(&$items, &$result, $parent_id)
    {
        foreach ($items as $item){
            if ((int)$item[$this->parentAttribute] == (int)$parent_id){
                $result[] = $item[$this->primaryKeyAttribute];
            }
        }
    }

    /**
     * Returns associated array ($id=>$fullTitle, $id=>$fullTitle, ...)
     * @param mixed $parent number, object or array of numbers
     * @return array
     */
    public function getAssocList($parent=0)
    {
        $this->cached();

        $items = $this->getFullAssocData(array(
            $this->primaryKeyAttribute,
            $this->titleAttribute,
            $this->parentAttribute,
        ), $parent);

        $associated = array();
        foreach ($items as $item){
            $associated[$item[$this->primaryKeyAttribute]] = $item;
        }
        $items = $associated;

        $result = array();

        foreach($items as $item){
            $titles = array($item[$this->titleAttribute]);

            $temp = $item;
            while (isset($items[(int)$temp[$this->parentAttribute]])){
                $titles[] = $items[(int)$temp[$this->parentAttribute]][$this->titleAttribute];
                $temp = $items[(int)$temp[$this->parentAttribute]];
            }

            $result[$item[$this->primaryKeyAttribute]] = implode(' - ', array_reverse($titles));
        }

        return $result;
    }

    /**
     * Returns tabulated array ($id=>$title, $id=>$title, ...)
     * @param mixed $parent number, object or array of numbers
     * @return array
     */
    public function getTabList($parent=0)
    {
        $parents = $this->processParents($parent);

        $this->cached();

        $items = $this->getFullAssocData(array(
            $this->primaryKeyAttribute,
            $this->titleAttribute,
            $this->parentAttribute
        ), $parent);

        $result = array();
        foreach ($parents as $parent_id){
            $this->_getTabListRecursive($items, $result, $parent_id);
        }

        return $result;
    }

    protected function _getTabListRecursive(&$items, &$result, $parent_id, $indent=0)
    {
        foreach ($items as $item){
            if ((int)$item[$this->parentAttribute] == (int)$parent_id && !isset($result[$item[$this->primaryKeyAttribute]])){
                $result[$item[$this->primaryKeyAttribute]] = str_repeat('-- ', $indent) . $item[$this->titleAttribute];
                $this->_getTabListRecursive($items, $result, $item[$this->primaryKeyAttribute], $indent + 1);
            }
        }
    }

    /**
     * Returns items for zii.widgets.CMenu widget
     * @param mixed $parent number, object or array of numbers
     * @param int $sub levels
     * @return array
     */
    public function getMenuList($sub=0, $parent=0)
    {
        $criteria = $this->getOwnerCriteria();

        if (!$parent)
            $parent = $this->getOwner()->getPrimaryKey();

        if ($parent)
            $criteria->compare($this->primaryKeyAttribute, $this->getChildsArray($parent));

        $items = $this->cached($this->getOwner())->findAll($criteria);

        $categories = array();
        foreach ($items as $item){
            $categories[(int)$item->{$this->parentAttribute}][] = $item;
        }

        return $this->_getMenuListRecursive ($categories, $parent, $sub);
    }

    protected function _getMenuListRecursive($items, $parent, $sub)
    {
        $parent = (int)$parent;
        $resultArray = array();
        if (isset($items[$parent]) && $items[$parent]){
            foreach ($items[$parent] as $item){
                $active = $item->{$this->linkActiveAttribute};
                $resultArray[$item->getPrimaryKey()] = array(
                    'id'=>$item->getPrimaryKey(),
                    'label'=>$item->{$this->titleAttribute},
                    'url'=>$item->{$this->urlAttribute},
                    'icon'=>$this->iconAttribute !== null ? $item->{$this->iconAttribute} : '',
                    'active'=>$active,
                    'itemOptions'=>array('class'=>'item_' . $item->getPrimaryKey()),
                    'linkOptions'=>$active ? array('rel'=>'nofollow') : array(),
                ) + ($sub ? array('items'=>$this->_getMenuListRecursive($items, $item->getPrimaryKey(), $sub - 1)) : array());
            }
        }
        return $resultArray;
    }

    /**
     * Constructs breadcrumbs for zii.widgets.CBreadcrumbs widget
     * @param bool $lastLink if you can have link in last element
     * @return array
     */
    public function getBreadcrumbs($lastLink=false)
    {
        if ($lastLink){
            $breadcrumbs = array($this->getOwner()->{$this->titleAttribute} => $this->getOwner()->{$this->urlAttribute});
        } else {
            $breadcrumbs = array($this->getOwner()->{$this->titleAttribute});
        }
        $page = $this->getOwner();

        $i = 50;

        while ($i-- && $this->cached($page)->{$this->parentRelation}){
            $breadcrumbs[$page->{$this->parentRelation}->{$this->titleAttribute}] = $page->{$this->parentRelation}->{$this->urlAttribute};
            $page = $page->{$this->parentRelation};
        }
        return array_reverse($breadcrumbs);
    }

    /**
     * Constructs full title for current model
     * @param string $separator
     * @return string
     */
    public function getFullTitle($inverse=false, $separator=' - ')
    {
        $titles = array($this->getOwner()->{$this->titleAttribute});

        $item = $this->getOwner();
        $i=50;
        while ($i-- && $this->cached($item)->{$this->parentRelation}){
            $titles[] = $item->{$this->parentRelation}->{$this->titleAttribute};
            $item = $item->{$this->parentRelation};
        }
        return implode($inverse ? $titles : array_reverse($titles), $separator);
    }

    /**
     * Optional redeclare this method in your model for use (@link getMenuList())
     * or define in (@link requestPathAttribute) your $_GET attribute for url matching
     * @return bool true if current request url matches with category path
     */
    public function getLinkActive()
    {
        return mb_strpos(Yii::app()->request->getParam($this->requestPathAttribute), $this->getOwner()->path, null, 'UTF-8') === 0;
    }

    protected function getFullAssocData($attributes, $parent=0)
    {
        $criteria = $this->getOwnerCriteria();

        $attributes = $this->aliasAttributes(array_unique(array_merge($attributes, array($this->primaryKeyAttribute))));

        $criteria->select = implode(', ', $attributes);

        if (!$parent)
            $parent = $this->getOwner()->getPrimaryKey();

        if ($parent)
            $criteria->compare($this->primaryKeyAttribute, array_merge(array($parent), $this->getChildsArray($parent)));

        $command = $this->createFindCommand($criteria);
        $this->clearOwnerCriteria();

        return $command->queryAll();
    }

    protected function processParents($parent)
    {
        if (!$parent)
            $parent = $this->getOwner()->getPrimaryKey();
        $parents = $this->arrayFromArgs($parent);
        return $parents;
    }

    protected function arrayFromArgs($items)
    {
        $array = array();

        if (!$items){
            $items = array(0);
        } elseif (!is_array($items)){
            $items = array($items);
        }

        foreach ($items as $item) {
            if (is_object($item)) {
                $array[] = $item->getPrimaryKey();
            } else {
                $array[] = $item;
            }
        }

        return array_unique($array);
    }
    
    public function beforeDelete($event){
        $id = Yii::app()->request->getQuery('id');
                
        if($id){
            $children = $this->owner->getChildsArray($id, false);
            
            // Reset parent_id attribute from model direct children
            foreach($children as $child){
                 $this->owner->updateByPk($child, array(
                    'parent_id' => 0,
                ));
            }
        }       
    }
}