<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/3/14
 * Time: 12:07 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Sql\QueryBuilder\Manipulation;

use NilPortugues\Sql\QueryBuilder\Syntax\SQLFunction;
use NilPortugues\Sql\QueryBuilder\Syntax\SyntaxFactory;

/**
 * Class Insert.
 */
class Insert extends AbstractCreationalQuery
{
    /**
     * @var Update
     */
    private $onDuplicateKeyUpdateValues;

    /**
     * @return string
     */
    public function partName()
    {
        return 'INSERT';
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $columns = \array_keys($this->values);

        return SyntaxFactory::createColumns($columns, $this->getTable());
    }

    public function onDuplicateKeyUpdate($values)
    {
        array_walk( $values, function(&$value, $key){
            if(is_null($value)) {
                $nameAndAlias = [$key, null];
                $value = new SQLFunction('VALUES', SyntaxFactory::createColumn($nameAndAlias, $this->getTable()));
            }
        });

        $this->onDuplicateKeyUpdateValues = new Update($this->table, $values);
    }

    /**
     * @return Update
     */
    public function getOnDuplicateKeyUpdate()
    {
        return $this->onDuplicateKeyUpdateValues;
    }

    /**
     * @return array
     */
    public function getOnDuplicateKeyUpdateValues()
    {
        return $this->onDuplicateKeyUpdateValues->getValues();
    }

    /**
     * @return array
     */
    public function getOnDuplicateKeyUpdateValuesWithFunctions()
    {
        return $this->onDuplicateKeyUpdateValues->getValuesWithFunctions();
    }


}
