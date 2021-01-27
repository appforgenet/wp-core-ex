<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

/**
 * This is the model class for table "<?= $tableName ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data->Type} \${$property}\n" ?>
<?php endforeach; ?>
*/
<?php if(!empty($baseclass)): ?>
class <?= $modelclass ?> extends <?= '\\' . ltrim($baseclass, '\\') . "\n" ?>
<?php else: ?>
class <?= $modelclass . "\n" ?> 
<?php endif; ?>
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '<?= $tableName ?>';
    }

    /**
     * {@inheritdoc}
     */
    public static function prefix()
    {
        return '<?= $prefix ?>';
    }

    public function rules()
    {
        return [
            <?php
                $required = $generator->getRequired();
                if(count($required) == 1)
                    echo '['.$required[0].',\'required\'],'."\n";
                else
                    echo '[[\''.implode('\', \'', $required).'\'],\'required\'],'."\n";
            
                $props = [];
                foreach($generator->getConstrains($prefix.$tableName) as $constraint)
                    $props[] = $constraint->Field;
            
                if(count($props) == 1)
                    echo "\t\t\t".'['.$props[0].',\'relation\'],'."\n";
                else
                    echo "\t\t\t".'[[\''.implode('\', \'', $props).'\'],\'relation\'],'."\n";
            ?>
        ];
    }

<?php foreach($generator->getConstrains($prefix.$tableName) as $constraint): ?>
    public function get<?= ucwords($constraint->methodName) ?>()
    {
        return parent::hasMany(<?= $constraint->Type; ?>::className(), ['<?= $constraint->localProp; ?>' => '<?= $constraint->foreignProp; ?>']);
    }
<?php endforeach; ?>

    function beforeSave()
    {
        return true;
    }

    function afterFind()
    {

    }
}