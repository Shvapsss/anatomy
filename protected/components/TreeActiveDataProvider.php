<?php
 
class TreeActiveDataProvider extends CActiveDataProvider
{
    public $childRelation = 'child_items';
 
    /**
     * Fetches the data from the persistent data storage.
     * @return array list of data items
     */
    protected function fetchData()
    {
        $criteria=clone $this->getCriteria();
 
        if(($pagination=$this->getPagination())!==false) {
            $paginateCriteria = clone $criteria;
            
            if(!empty($paginateCriteria->condition)){
                $pagination->setItemCount($this->getTotalItemCount());
            }
            else {
                // Paginate by first level items only
                $paginateCriteria->addCondition('t.parent_id iS NULL OR t.parent_id = 0');
                $pagination->setItemCount(count($this->model->findAll($paginateCriteria)));
            }
            
            $pagination->applyLimit($criteria);
        }
 
        $baseCriteria=$this->model->getDbCriteria(false);
 
        if(($sort=$this->getSort())!==false) {
            // set model criteria so that CSort can use its table alias setting
            if($baseCriteria!==null)
            {
                $c=clone $baseCriteria;
                $c->mergeWith($criteria);
                $this->model->setDbCriteria($c);
            }
            else
                $this->model->setDbCriteria($criteria);
            $sort->applyOrder($criteria);
        }
 
        $this->model->setDbCriteria($baseCriteria!==null ? clone $baseCriteria : null);
 
        $rootCriteria=clone $criteria;
        $isEmptyCondition=empty($rootCriteria->condition);
 
        if ($isEmptyCondition)
            $rootCriteria->addCondition('t.parent_id iS NULL OR t.parent_id = 0');
 
        $items=$this->model->findAll($rootCriteria);
 
        if ($isEmptyCondition)
            $items=$this->buildRecursive($items);
 
        $this->model->setDbCriteria($baseCriteria);  // restore original criteria
        return $items;
    }
 
    protected function buildRecursive($items, $indent=0, $foolproof=20)
    {
        $data=array();
        foreach ($items as $item)
        {
            $item->indent=$indent;
            $data[]=$item;
            if ($foolproof && $item->{$this->childRelation})
                $data=array_merge($data, $this->buildRecursive($item->{$this->childRelation}, $indent+1, $foolproof-1));
        }
        return $data;
    }
}