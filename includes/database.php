<?php
class Database
{
    public $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    function getPrefix()
    {
        return $this->wpdb->prefix;
    }

    function getSchema($tablename)
    {
        $prefix = $this->getPrefix();
        $tablename = $prefix.$tablename;
        $sql = "DESCRIBE `$tablename`;";
        return $this->wpdb->get_results($sql);
    }

    public function getConstrains($tablename)
    {
        $wpdb = $this->wpdb;
        $prefix = $this->getPrefix();

        $sql = 'SELECT TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = "'.$this->dbName.'"';
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

            if($constraint->TABLE_NAME == $tablename && $constraint->CONSTRAINT_NAME != 'PRIMARY')
            {
                //echo $constraint->TABLE_NAME.'|'.$constraint->COLUMN_NAME.'|'.$constraint->CONSTRAINT_NAME.'|'.$constraint->REFERENCED_TABLE_NAME.'|'.$constraint->REFERENCED_COLUMN_NAME.'<br />';

                $propName = strtolower(str_replace($prefix, '', $constraint->REFERENCED_TABLE_NAME));
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
                    $constraints[$tmpPropName] = $newobj;

                }
            }

            //rückwärtssuche
            if($constraint->REFERENCED_TABLE_NAME == $tablename && in_array($constraint->REFERENCED_COLUMN_NAME, $pk))
            {
                $propName = strtolower(str_replace($prefix, '', $constraint->TABLE_NAME));
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
                    $constraints[$tmpPropName] = $newobj;

                }
            }
        }

        return $constraints;
    }
}
?>