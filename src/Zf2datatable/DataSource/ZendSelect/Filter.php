<?php
namespace Zf2datatable\DataSource\ZendSelect;

use Zf2datatable\Filter as DatagridFilter;
use Zf2datatable\Column;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\PredicateSet;

class Filter
{

    /**
     *
     * @var Sql
     */
    private $sql;

    /**
     *
     * @var Select
     */
    private $select;


    protected static $where;

    public function __construct(Sql $sql, Select $select)
    {
        $this->sql = $sql;
        $this->select = $select;
    }

    /**
     *
     * @return \Zend\Db\Sql\Sql
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     *
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param  DatagridFilter            $filter
     * @throws \InvalidArgumentException
     */
    public function applyFilter(DatagridFilter $filter)
    {
        $select = $this->getSelect();

        $adapter = $this->getSql()->getAdapter();
        $qi = function ($name) use ($adapter) {
            return $adapter->getPlatform()->quoteIdentifier($name);
        };

        $column = $filter->getColumn();
        $colString = $column->getSelectPart1();
        if ($column->getSelectPart2() != '') {
            $colString .= '.' . $column->getSelectPart2();
        }
        if ($column instanceof Column\Select && $column->hasFilterSelectExpression()) {
            $colString = sprintf($column->getFilterSelectExpression(), $colString);
        }
        $values = $filter->getValues();

        $filterSelectOptions = $column->getFilterSelectOptions();


        $wheres = array();

        if($filter->getColumn()->getType()instanceof Column\Type\DateTime && $filter->getColumn()
            ->getType()
            ->isDaterangePickerEnabled() === true){
            $where = new Where();
            $wheres[] = $where->between($colString, $values[0], $values[1]);

            if (count($wheres) > 0) {
                $set = new PredicateSet($wheres, PredicateSet::OP_AND);
                $select->where->andPredicate($set);
            }
        }
        else{
            foreach ($values as $value) {
                $where = new Where();
                 switch ($filter->getOperator()) {

                    case DatagridFilter::LIKE:
                        $wheres[] = $where->like($colString, '%' . $value . '%');
                        break;

                    case DatagridFilter::LIKE_LEFT:
                        $wheres[] = $where->like($colString, '%' . $value);
                        break;

                    case DatagridFilter::LIKE_RIGHT:
                        $wheres[] = $where->like($colString, $value . '%');
                        break;

                    case DatagridFilter::NOT_LIKE:
                        $wheres[] = $where->literal($qi($colString) . 'NOT LIKE ?', array(
                            '%' . $value . '%'
                        ));
                        break;

                    case DatagridFilter::NOT_LIKE_LEFT:
                        $wheres[] = $where->literal($qi($colString) . 'NOT LIKE ?', array(
                            '%' . $value
                        ));
                        break;

                    case DatagridFilter::NOT_LIKE_RIGHT:
                        $wheres[] = $where->literal($qi($colString) . 'NOT LIKE ?', array(
                            $value . '%'
                        ));
                        break;
                    case DatagridFilter::EQUAL:
                        $wheres[] = $where->equalTo($colString, $value);
                        break;

                    case DatagridFilter::NOT_EQUAL:
                        $wheres[] = $where->notEqualTo($colString, $value);
                        break;

                    case DatagridFilter::GREATER_EQUAL:
                        $wheres[] = $where->greaterThanOrEqualTo($colString, $value);
                        break;

                    case DatagridFilter::GREATER:
                        $wheres[] = $where->greaterThan($colString, $value);
                        break;

                    case DatagridFilter::LESS_EQUAL:
                        $wheres[] = $where->lessThanOrEqualTo($colString, $value);
                        break;

                    case DatagridFilter::LESS:
                        $wheres[] = $where->lessThan($colString, $value);
                        break;

                    case DatagridFilter::BETWEEN:
                        $wheres[] = $where->between($colString, $values[0], $values[1]);
                        break ;
                    case DatagridFilter::IN:
                            $wheres[] = $where->in($colString, (array) $value);
                        break ;
                     case DatagridFilter::NOT_IN:
                            $wheres[] = $where->notin($colString, (array) $value);
                        break ;
                    default:
                        throw new \InvalidArgumentException('This operator is currently not supported: ' . $filter->getOperator());
                        break;
                }
            }

            if (count($wheres) > 0) {
                $set = new PredicateSet($wheres, PredicateSet::OP_OR);
                $select->where->andPredicate($set);
            }
        }

    }


    public static function applyStaticFilter(DatagridFilter $filter,$colString, $adapter)
    {
        if(!isset(self::$where)){
            self::$where = new Where();
        }

        $qi = function ($name) use ($adapter) {
            return $adapter->getPlatform()->quoteIdentifier($name);
        };

        $values = $filter->getValues();
        $wheres = array();
        foreach ($values as $value) {

            switch ($filter->getOperator()) {

                case DatagridFilter::LIKE:
                    $wheres[] = self::$where->like($colString, '%' . $value . '%');
                    break;

                case DatagridFilter::LIKE_LEFT:
                    $wheres[] = self::$where->like($colString, '%' . $value);
                    break;

                case DatagridFilter::LIKE_RIGHT:
                    $wheres[] = self::$where->like($colString, $value . '%');
                    break;

                case DatagridFilter::NOT_LIKE:
                    $wheres[] = self::$where->literal($qi($colString) . 'NOT LIKE ?', array(
                    '%' . $value . '%'
                        ));
                        break;

                case DatagridFilter::NOT_LIKE_LEFT:
                    $wheres[] = self::$where->literal($qi($colString) . 'NOT LIKE ?', array(
                    '%' . $value
                    ));
                    break;

                case DatagridFilter::NOT_LIKE_RIGHT:
                    $wheres[] = self::$where->literal($qi($colString) . 'NOT LIKE ?', array(
                    $value . '%'
                        ));
                        break;
                case DatagridFilter::EQUAL:
                    $wheres[] = self::$where->equalTo($colString, $value);
                    break;

                case DatagridFilter::NOT_EQUAL:
                    $wheres[] = self::$where->notEqualTo($colString, $value);
                    break;

                case DatagridFilter::GREATER_EQUAL:
                    $wheres[] = self::$where->greaterThanOrEqualTo($colString, $value);
                    break;

                case DatagridFilter::GREATER:
                    $wheres[] = self::$where->greaterThan($colString, $value);
                    break;

                case DatagridFilter::LESS_EQUAL:
                    $wheres[] = self::$where->lessThanOrEqualTo($colString, $value);
                    break;

                case DatagridFilter::LESS:
                    $wheres[] = self::$where->lessThan($colString, $value);
                    break;

                case DatagridFilter::BETWEEN:
                    $wheres[] = self::$where->between($colString, $values[0], $values[1]);
                    break ;
                case DatagridFilter::IN:
                    $wheres[] = self::$where->in($colString, (array) $value);
                    break ;
                case DatagridFilter::NOT_IN:
                    $wheres[] = self::$where->notin($colString, (array) $value);
                    break ;
                default:
                    throw new \InvalidArgumentException('This operator is currently not supported: ' . $filter->getOperator());
                    break;
            }
        }

        return self::$where;
    }
}
