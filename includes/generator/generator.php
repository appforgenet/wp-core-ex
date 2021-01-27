<?php
class MyGenerator
{
    private $dbName;
    private $data;
    private $_debug;

    public function __construct()
    {
        $this->dbName = WPCore::$app->db->wpdb->dbname;
    }

    public function generate($data)
    {
        $this->data = $data;
        //model
        $file = $this->render($this->getCodeTemplate().'/model.php', ['generator' => $this, 'properties' => $this->generateProperties(),'tableName' => str_replace($this->getDbPrefix(), '', $this->getTableName()), 'baseclass' => $this->getBaseClass(),'modelclass' => $this->getModelName(), 'prefix' => $this->getDbPrefix() ]);
        $destinationFile = $this->getPath().'/'.$this->getModelName().'.php';
        if(is_file($destinationFile))
            unlink($destinationFile);
        file_put_contents($destinationFile, $file);
        
        //$this->_debug = [];
        //$this->getConstrains($this->getTableName());
        //var_dump(json_encode( $this->_debug));
        
        return $destinationFile;
    }

    public function getTableName()
    {
        return $this->data['tablename'];
    }

    public function getModelName()
    {
        return $this->data['modelname'];
    }

    public function getDbPrefix()
    {
        return $this->data['prefix'];
    }

    public function getBaseClass()
    {
        return $this->data['baseclass'];
    }

    public function getPath()
    {
        return $this->data['path'];
    }

    public function getCodeTemplate()
    {
        return $this->data['code_template'];
    }

    /**
     * @param $modelFile Filename
     * @param $params 
     */
    public function render($modelFile, $params = [])
    {
        if($params != [] && $params != null)
            extract($params);
        ob_start();
        include($modelFile);
        $var = ob_get_contents(); 
        ob_end_clean();
        return $var;
    }

    public function getRequired()
    {
        $required = [];
        foreach($this->getSchema($this->getTableName()) as $row)
        {
            if($row->Null == 'NO')
                $required[] = $row->Field;
        }
        return $required;
    }

    public function getSchema($tablename)
    {
        $wpdb = WPCore::$app->db->wpdb;
        $sql = "DESCRIBE `$tablename`;";
        return $wpdb->get_results($sql);
    }

    public function getIndizes($tablename)
    {
        $wpdb = WPCore::$app->db->wpdb;
        $sql = "SHOW INDEX FROM `$tablename`;";
        return $wpdb->get_results($sql);
    }

    public function getConstrains($tablename)
    {
        $this->_debug[] = $tablename;
        $wpdb = WPCore::$app->db->wpdb;
        $sql = 'SELECT TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = "'.$this->dbName.'"';
        $this->_debug[] = $sql;
        //AND TABLE_NAME = "'.$tablename.'";';
        // if(WP_DEBUG_DISPLAY)
        //     echo $sql.'<br />'; 
        // if(WP_DEBUG_DISPLAY)
        //      echo $tablename.'<br />';  

        $rows = $wpdb->get_results($sql);
        //$this->_debug[] = $rows;
        $constraints = [];

        //echo $tablename.'<br />';

        //constrains
        $pk = [];

        //first find pk
        foreach($rows as $constraint )
        {
            if($constraint->TABLE_NAME == $tablename && $constraint->CONSTRAINT_NAME == 'PRIMARY')
            {
                $pk[] = $constraint->COLUMN_NAME;
                
            }
        }

        $this->_debug[] = $pk;

        foreach($rows as $constraint )
        {
            if($constraint->TABLE_NAME == $tablename && $constraint->CONSTRAINT_NAME != 'PRIMARY')
            {
                //echo $constraint->TABLE_NAME.'|'.$constraint->COLUMN_NAME.'|'.$constraint->CONSTRAINT_NAME.'|'.$constraint->REFERENCED_TABLE_NAME.'|'.$constraint->REFERENCED_COLUMN_NAME.'<br />';

                $propName = strtolower(str_replace($this->getDbPrefix(), '', $constraint->REFERENCED_TABLE_NAME));
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
            $this->_debug[] = $constraint->REFERENCED_COLUMN_NAME;
            $this->_debug[] = in_array($constraint->REFERENCED_COLUMN_NAME, $pk);

            //rückwärtssuche
            if($constraint->REFERENCED_TABLE_NAME == $tablename && in_array($constraint->REFERENCED_COLUMN_NAME, $pk))
            {
                $this->_debug[] = $constraint;
                $propName = strtolower(str_replace($this->getDbPrefix(), '', $constraint->TABLE_NAME));
                $dataType = str_replace('_', '', ucwords($propName, " \t\r\n\f\v_"));

                //detect pk
                $pkFound = false;
                foreach($rows as $row )
                {
                    $pkFound = false;
                    //if($row->TABLE_NAME == $constraint->TABLE_NAME && $row->COLUMN_NAME == $constraint->COLUMN_NAME && $row->CONSTRAINT_NAME == 'PRIMARY')
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

    public function generateProperties()
    {
        $properties = [];
        $rows = $this->getSchema($this->getTableName());

        //properties
        foreach($rows as $row)
        {
            $row->Type = $this->parseType($row->Type);
            $properties[$row->Field] = $row;
        }

        //constrains
        foreach($this->getConstrains($this->getTableName()) as $constraint )
        {
            $properties[$constraint->Field] = $constraint ;
        }

        return $properties;
    }

    public function parseType($type)
    {
        if(strpos($type,'bigint') === 0)
            return 'int';
        else if(strpos($type,'varchar') === 0)
            return 'string';
        else if(strpos($type,'text') === 0)
            return 'string';
        else if(strpos($type,'int') === 0)
            return 'int';
        else if(strpos($type,'datetime') === 0)
            return 'string';
        else
            return $type;
        // else if(strpos($type,'bigint') === 0)
        //     return 'int';
    }
}
?>