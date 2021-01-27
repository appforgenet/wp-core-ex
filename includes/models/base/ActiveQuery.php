<?php
    
class ActiveQuery
{
    private $where = [];

    private $select;

    private $orderBy;

    private $classname;

    private $distinct;

    private $limit;

    private $offset;


    function __construct($classname)
    {
        $this->classname = $classname;
    }

    function select($condition)
    {
        $this->select = $condition;
        return $this;
    }

    function where($condition)
    {
        if(!is_array($condition))
            throw new \Exception('Condition must be an array');
        
        
        $whereCondition = new stdClass();
        $whereCondition->type = '';

        if(count($condition) == 3)
        {
            $whereCondition->operator = $condition[0];
            $whereCondition->key = $condition[1];
            $whereCondition->value = $condition[2];
        }
        else
        {
            $key = array_key_first($condition);
            $value = $condition[$key];
            
            $whereCondition->key = $key;
            $whereCondition->value = $value;
            $whereCondition->operator = '=';
        }


        $this->where[] = $whereCondition;
        
        return $this;
    }

    function andWhere($condition)
    {
        if(!is_array($condition))
            throw new \Exception('Condition must be an array');

        $whereCondition = new stdClass();
        $whereCondition->type = 'and';

        if(count($condition) == 3)
        {
            $whereCondition->operator = $condition[0];
            $whereCondition->key = $condition[1];
            $whereCondition->value = $condition[2];
        }
        else
        {
            $key = array_key_first($condition);
            $value = $condition[$key];

            $whereCondition->key = $key;
            $whereCondition->value = $value;
            $whereCondition->operator = '=';
        }

        $this->where[] = $whereCondition;
        
        return $this;
    }

    function orWhere($condition)
    {
        if(!is_array($condition))
            throw new \Exception('Condition must be an array');

        $last = count($this->where);

        $whereCondition = new stdClass();
        $whereCondition->type = 'or';
        if(count($condition) == 3)
        {
            $whereCondition->operator = $condition[0];
            $whereCondition->key = $condition[1];
            $whereCondition->value = $condition[2];
        }
        else
        {
            $key = array_key_first($condition);
            $value = $condition[$key];

            $whereCondition->key = $key;
            $whereCondition->value = $value;
            $whereCondition->operator = '=';
        }

        $this->where[$last][] = $whereCondition;

        return $this;
    }

    function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    function orderBy($condition)
    {
        $this->orderBy = $condition;
        return $this;
    }

    private function prepare()
    {
        //select
        $sql = 'select ';
        if(!empty($this->select))
            $sql .= $this->select;
        else
            $sql .= '* ';

        //from tablename
        $sql .= 'from ';
        $className = $this->classname;
        $sql .= $className::prefix().$className::tableName().' ';

        //where
        foreach($this->where as $whereConditions)
        {
            if(is_array($whereConditions) && count($whereConditions) > 1)
            {
                //if($whereConditions[0]->type == )

                //$sql .= '(';
                foreach($whereConditions as $whereCondition)
                {
                    if($whereCondition->type == '')
                    {
                        $sql .= 'where `'.$whereCondition->key.'` '.$whereCondition->operator.' '.$this->prepareValue($whereCondition->value).' ';
                    }
                    else if($whereCondition->type == 'and')
                    {
                        $sql .= $whereCondition->type.' `'.$whereCondition->key.'` '.$whereCondition->operator.' '.$this->prepareValue($whereCondition->value).' ';
                    }
                    else if($whereCondition->type == 'or')
                    {
                        $sql .= $whereCondition->type.' `'.$whereCondition->key.'` '.$whereCondition->operator.' '.$this->prepareValue($whereCondition->value).' ';
                    }
                }
                //$sql .= ')';
            }
            else
            {
                //var_dump($whereConditions);
                $whereCondition = $whereConditions;
                if($whereCondition->type == '')
                {
                    $sql .= 'where `'.$whereCondition->key.'` '.$whereCondition->operator.' '.$this->prepareValue($whereCondition->value).' ';
                }
                else if($whereCondition->type == 'and')
                {
                    $sql .= $whereCondition->type.' `'.$whereCondition->key.'` '.$whereCondition->operator.' '.$this->prepareValue($whereCondition->value).' ';
                }
                else if($whereCondition->type == 'or')
                {
                    $sql .= $whereCondition->type.' `'.$whereCondition->key.'` '.$whereCondition->operator.' '.$this->prepareValue($whereCondition->value).' ';
                }
            }
        }

        //order by
        if($this->orderBy != null)
            $sql .= 'order by '.$this->orderBy;
        
        if($this->limit != null)
            $sql .= 'limit '.$this->limit;

        if($this->offset != null && $this->limit != null)
            $sql .= 'offset '.$this->offset;


        $sql .= ';';
        return $sql;
    }

    private function prepareValue($value)
    {
        if(is_int($value))
            return $value;
        else if(is_bool($value))
            return $value?'true':'false';
        else if(is_date($value))
            return '\''.$value.'\'';
        else if(is_null($value))
            return 'NULL';
        else    
            return '\''.$value.'\'';
    }

    private function onlyAndConditions($conditions)
    {
        $foundOr = false;
        foreach($conditions as $conditionSub)
        {
            if(is_array($conditionSub))
            {
                foreach($conditionSub as $condition)
                {
                    if($condition->type == 'or')
                        $foundOr = true;
                }
            }
            else if($conditionSub->type == 'or')
                $foundOr = true;
        }
        return !$foundOr;
    }

    function offset($offset)
    {
        $this->offset = $offset;
    }

    function limit($limit)
    {
        $this->limit = $limit;
    }

    private function populate($item)
    {
        $className = $this->classname;
        $model = new $className();
        foreach($className::getSchema() as $row)
        {
            $column = $row->Field;
            $model->setAttribute($column, $item->$column);
            $model->setOldAttribute($column, $item->$column);
            $model->afterFind();
        }

        //populate relaton dependencies
        $model->populateDependencies();

        return $model;
    }



    function one()
    {
        $db = WPCore::$app->db->wpdb;
        $this->limit(1);
        $sql = $this->prepare();
        //var_dump($sql);
        $rows = $db->get_results($sql);
        $items = [];

        foreach($rows as $item)
        {
            $items[] = $this->populate($item);
        }
        return $items[0];
    }

    function all()
    {
        $db = WPCore::$app->db->wpdb;
        $sql = $this->prepare();
        $rows = $db->get_results($sql);
        $items = [];

        foreach($rows as $item)
        {
            $items[] = $this->populate($item);
        }
        return $items;
    }
}
?>