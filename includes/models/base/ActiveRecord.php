<?php

namespace appforge\coreex\includes\models\base;

use appforge\coreex\includes\models\WPCore;
use appforge\coreex\includes\models\base\ActiveQuery;
class ActiveRecord
{
    /**
     * new
     * edited
     * 
     */
    private $modifiedAttributes = [];
    protected $isNew; 

    private $_attributes = [];
    private $_oldAttributes = [];
    private $_relationDependencies = [];
    private $_relation = [];

    static function tableName()
    {

    }

    public static function prefix()
    {
        return '';
    }

    static function getDb()
    {
        return WPCore::$app->db->wpdb;
    }

    static function find()
    {
        return new ActiveQuery(static::className()); 
    }

    static function findOne($condition)
    {
        $query = new ActiveQuery(static::className()); 
        return $query->where($condition)->one();
    }

    static function findAll()
    {
        $query = new ActiveQuery(static::className()); 
        return $query->all();
    }

    

    function setOldAttribute($name, $value)
    {
            $this->_oldAttributes[$name] = $value;
    }

    function setAttribute($name, $value)
    {
            $this->_attributes[$name] = $value;
    }

    function setRelationDependencies($name)
    {
        if(!array_key_exists($name, $this->_relationDependencies))
        {
            $this->_relationDependencies[$name] = null;
        }
    }

    static function getPrimary()
    {
        foreach(static::getSchema() as $row)
        {
            if($row->Key == 'PRI')
                return $row->Field;
        }
    }

    function getColumnNames()
    {
        $columns = [];
        foreach(static::getSchema() as $row)
        {
            if($row->Key != 'PRI')
                $columns[] = $row->Field;
        }
        return $columns;
    }

    static function getSchema()
    {
        $tablename = static::prefix().static::tableName();
        $wpdb = static::getDb();
        $sql = "DESCRIBE `$tablename`;";
        return $wpdb->get_results($sql);
    }

    function __get($name)
    {
        if($this->hasAttribute($name))
            return $this->_attributes[$name];

        if(array_key_exists($name, $this->_relationDependencies))
        {
            if(!array_key_exists($name, $this->_relation))
            {
                //populate
                $this->populateRelation($name);
            }
            return $this->_relation[$name];
        }
    }

    private function populateRelation($name)
    {
        $relation = $this->_relationDependencies[$name];
        $localProp = $relation->localProp;
        $query = new ActiveQuery($relation->type);
        $this->_relation[$name] = $query->where([$relation->foreignProp => $this->$localProp])->all();
    }

    function __set($name, $value)
    {
        if($this->hasAttribute($name))
        {
            $this->_attributes[$name] = $value;
        }
        else
        {
            $this->$name = $value;
        }

        if(in_array($name, $this->getColumnNames()))
            if($this->_oldAttributes !== null && $this->_oldAttributes[$name] != $value)
                $this->modifiedAttributes[$name] = $value;
    }

    function save()
    {
        //update
        if($this->_oldAttributes !== null && count($this->modifiedAttributes) > 0)
        {
            $primary = $this->getPrimary();
            $data = $this->modifiedAttributes;
            $where = [$primary => $this->_oldAttributes[$primary]];
            $format = [];
            foreach($this->modifiedAttributes as $key => $value)
            {
                if(is_int($value))
                    $format[] = '%d';
                else if(is_bool($value))
                    $format[] = '%d';
                else if(is_date($value))
                    $format[] = '%s';
                //else if(is_string($this->_attributes[$column]))
                else    
                    $format[] = '%s';
            }
            $where_format = [];
            foreach($where as $key => $value)
            {
                if(is_int($value))
                    $where_format[] = '%d';
                else if(is_bool($value))
                    $where_format[] = '%d';
                else if(is_date($value))
                    $where_format[] = '%s';
                //else if(is_string($this->_attributes[$column]))
                else    
                    $where_format[] = '%s';
            }

            $this->beforeSave();

            $result = static::getDb()->update($this->prefix().$this->tableName(), $data, $where, $format, $where_format);
            var_dump($result);
            return $result;
        }
        else if($this->_oldAttributes === null) //insert because new
        {
            $data = $this->modifiedAttributes;
            //$required = $this->getRequired();
            $format = [];
            foreach($data as $key => $value)
            {
                if(is_int($value))
                    $format[] = '%d';
                else if(is_bool($value))
                    $format[] = '%d';
                else if(is_date($value))
                    $format[] = '%s';
                //else if(is_string($this->_attributes[$column]))
                else    
                    $format[] = '%s';
            }

            $this->beforeSave();
            $result = static::getDb()->insert($this->prefix().$this->tableName(), $data, $format);
            var_dump($result);
            return $result;
        }
        else
            return true;

        if(!empty($sql))
            var_dump(static::getDb()->get_results($sql));
    }

    function hasAttribute($name)
    {
        $keys = array_keys($this->_attributes);
        if(in_array($name, $keys))
            return true;
        return false;
    }

    function getIsNewRecord()
    {
        return $this->_oldAttributes === null;
    }

    function getRequired()
    {
        $required = [];
        foreach($this->getSchema() as $row)
        {
            if($row->Null == 'NO')
                $required[] = $row->Field;
        }
        return $required;
    }

    function hasMany($classname, $filter = [])
    {
        $query = new ActiveQuery($classname);
        
        $key = array_key_first($filter);
        $value = $filter[$key];

        $keyValue = $this->$key;
        $items = [];

        $query = $query->where([$value => $keyValue]);
        return $query;
        //var_dump($items);
        //return $items;
    }

    public static function className() 
    { 
         return get_called_class(); 
    } 

    function beforeSave()
    {
        return true;
    }

    function afterFind()
    {

    }

    public function rules()
    {

    }

    public function getConstrains()
    {
        $tablename = $this->prefix().$this->tableName();

        $wpdb = $this->getDb();

        $sql = 'SELECT TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = "'.$wpdb->dbname.'"';
        //AND TABLE_NAME = "'.$tablename.'";';

        $rows = $wpdb->get_results($sql);
        $constraints = [];

        //echo $tablename.'<br />';

        //constrains
        $pk = [];
        foreach($rows as $constraint )
        {
            if($constraint->TABLE_NAME == $tablename && $constraint->CONSTRAINT_NAME == 'PRIMARY')
                $pk[] = $constraint->COLUMN_NAME;
        }
        foreach($rows as $constraint )
        {
            if($constraint->TABLE_NAME == $tablename && $constraint->CONSTRAINT_NAME != 'PRIMARY')
            {
                //echo $constraint->TABLE_NAME.'|'.$constraint->COLUMN_NAME.'|'.$constraint->CONSTRAINT_NAME.'|'.$constraint->REFERENCED_TABLE_NAME.'|'.$constraint->REFERENCED_COLUMN_NAME.'<br />';

                $propName = strtolower(str_replace($wpdb->prefix, '', $constraint->REFERENCED_TABLE_NAME));
                $dataType = str_replace('_', '', ucwords($propName, " \t\r\n\f\v_"));
                
                //detect pk
                $pkFound = false;
                foreach($rows as $row )
                {
                    $pkFound = false;
                    if($row->TABLE_NAME == $constraint->REFERENCED_TABLE_NAME && $row->COLUMN_NAME == $constraint->REFERENCED_COLUMN_NAME && $row->CONSTRAINT_NAME == 'PRIMARY')
                        $pkFound = true;
                }

                if(!$pkFound)
                    $propName .= 'Collection';

                $newobj = new stdClass();
                $newobj->Field = $propName;
                $newobj->Type = $dataType;
                $newobj->localProp = $constraint->COLUMN_NAME;
                $newobj->methodName = str_replace('_', '', ucwords($propName, " \t\r\n\f\v_"));
                $newobj->foreignProp = $constraint->REFERENCED_COLUMN_NAME;

                if(!array_key_exists($propName, $constraints))
                    $constraints[$propName] = $newobj ;
                else
                {
                    $index = 1;
                    $tmpPropName = $propName.$index;
                    while(array_key_exists($tmpPropName, $constraints))
                    {
                        $index++;
                        $tmpPropName = $propName.$index;
                    }
                    $newobj->Field = $tmpPropName;
                    $newobj->methodName = str_replace('_', '', ucwords($tmpPropName, " \t\r\n\f\v_"));
                    $constraints[$tmpPropName] = $newobj;

                }
            }

            //rückwärtssuche
            if($constraint->REFERENCED_TABLE_NAME == $tablename && in_array($constraint->REFERENCED_COLUMN_NAME, $pk))
            {
                $propName = strtolower(str_replace($wpdb->prefix, '', $constraint->TABLE_NAME));
                $dataType = str_replace('_', '', ucwords($propName, " \t\r\n\f\v_"));

                //detect pk
                $pkFound = false;
                foreach($rows as $row )
                {
                    $pkFound = false;
                    if($row->TABLE_NAME == $constraint->TABLE_NAME && $row->COLUMN_NAME == $constraint->COLUMN_NAME && $row->CONSTRAINT_NAME == 'PRIMARY')
                        $pkFound = true;
                }

                if(!$pkFound)
                    $propName .= 'Collection';

                $newobj = new stdClass();
                $newobj->Field = $propName;
                $newobj->Type = $dataType;
                $newobj->localProp = $constraint->REFERENCED_COLUMN_NAME;
                $newobj->methodName = str_replace('_', '', ucwords($propName, " \t\r\n\f\v_"));
                $newobj->foreignProp = $constraint->COLUMN_NAME;

                if(!array_key_exists($propName, $constraints))
                    $constraints[$propName] = $newobj;
                else
                {
                    $index = 1;
                    $tmpPropName = $propName.$index;
                    while(array_key_exists($tmpPropName, $constraints))
                    {
                        $index++;
                        $tmpPropName = $propName.$index;
                    }
                    $newobj->Field = $tmpPropName;
                    $newobj->methodName = str_replace('_', '', ucwords($tmpPropName, " \t\r\n\f\v_"));
                    $constraints[$tmpPropName] = $newobj;

                }
            }
        }
        return $constraints;
    }

    function populateDependencies()
    {
        $constraints = $this->getConstrains();

        foreach($constraints as $constraint)
        {
            if(!array_key_exists($constraint->Field, $this->_relationDependencies))
            {
                $obj = new stdClass();
                $obj->type = $constraint->Type;
                $obj->localProp = $constraint->localProp;
                $obj->foreignProp = $constraint->foreignProp;

                $this->_relationDependencies[$constraint->Field] = $obj;
            }
        }

    }

}
?>