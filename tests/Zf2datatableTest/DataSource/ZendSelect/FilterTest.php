<?php
namespace Zf2datatableTest\DataSource\ZendSelect;

use PHPUnit_Framework_TestCase;
use Zf2datatable\DataSource\ZendSelect\Filter as FilterSelect;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\Operator;

/**
 * @group DataSource
 * @covers Zf2datatable\DataSource\ZendSelect\Filter
 */
class FilterTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var \Zf2datatable\Column\AbstractColumn
     */
    private $column;

    /**
     *
     * @var \Zf2datatable\Column\AbstractColumn
     */
    private $column2;

    /**
     *
     * @var FilterSelect
     */
    private $filterSelect;

    public function setUp()
    {
        $this->column = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $this->column->setUniqueId('myCol');
        $this->column->setSelect('myCol');

        $this->column2 = $this->getMockForAbstractClass('Zf2datatable\Column\AbstractColumn');
        $this->column2->setUniqueId('myCol2');
        $this->column2->setSelect('myCol2');

        $this->mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $this->mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
        $this->mockDriver->expects($this->any())
            ->method('checkEnvironment')
            ->will($this->returnValue(true));
        $this->mockDriver->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($this->mockConnection));
        $this->mockPlatform = $this->getMock('Zend\Db\Adapter\Platform\PlatformInterface');
        $this->mockStatement = $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
        $this->mockDriver->expects($this->any())
            ->method('createStatement')
            ->will($this->returnValue($this->mockStatement));

        $this->adapter = new Adapter($this->mockDriver, $this->mockPlatform);

        $sql = new Sql($this->adapter, 'foo');

        $select = new Select('myTable');
        $select->columns(array(
            'myCol',
            'myCol2'
        ));

        $this->filterSelect = new FilterSelect($sql, $select);
    }

    public function testBasic()
    {
        $this->assertInstanceOf('Zend\Db\Sql\Select', $this->filterSelect->getSelect());
        $this->assertInstanceOf('Zend\Db\Sql\Sql', $this->filterSelect->getSql());

        // Test two filters
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '~myValue,123');

        $filter2 = new \Zf2datatable\Filter();
        $filter2->setFromColumn($this->column2, '~myValue,123');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);
        $filterSelect->applyFilter($filter2);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();
        $this->assertEquals(2, count($predicates));
    }

    /**
     *
     * @param unknown $predicates
     * @param number  $part
     *
     * @return \Zend\Db\Sql\Predicate\Expression
     */
    private function getWherePart($predicates, $part = 0)
    {
        /* @var $predicateSet \Zend\Db\Sql\Predicate\PredicateSet */
        $predicateSet = $predicates[0][1];

        $pred = $predicateSet->getPredicates();
        $where = $pred[$part][1];
        $wherePred = $where->getPredicates();

        return $wherePred[0][1];
    }

    public function testLike()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '~myValue,123');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();
        $this->assertEquals(1, count($predicates));

        $like = $this->getWherePart($predicates, 0);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Like', $like);
        $this->assertEquals('%myValue%', $like->getLike());

        $like = $this->getWherePart($predicates, 1);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Like', $like);
        $this->assertEquals('%123%', $like->getLike());
    }

    public function testLikeLeft()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '~%myValue,123');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $like = $this->getWherePart($predicates, 0);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Like', $like);
        $this->assertEquals('%myValue', $like->getLike());

        $like = $this->getWherePart($predicates, 1);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Like', $like);
        $this->assertEquals('%123', $like->getLike());
    }

    public function testLikeRight()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '~myValue%');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $like = $this->getWherePart($predicates, 0);
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Like', $like);
        $this->assertEquals('myValue%', $like->getLike());
    }

    public function testNotLike()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '!~myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $notLike = $this->getWherePart($predicates, 0);
        $parameters = $notLike->getParameters();

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Expression', $notLike);
        $this->assertEquals('NOT LIKE ?', $notLike->getExpression());
        $this->assertEquals('%myValue%', $parameters[0]);
    }

    public function testNotLikeLeft()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '!~%myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $notLike = $this->getWherePart($predicates, 0);
        $parameters = $notLike->getParameters();

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Expression', $notLike);
        $this->assertEquals('NOT LIKE ?', $notLike->getExpression());
        $this->assertEquals('%myValue', $parameters[0]);
    }

    public function testNotLikeRight()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '!~myValue%');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $notLike = $this->getWherePart($predicates, 0);
        $parameters = $notLike->getParameters();

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Expression', $notLike);
        $this->assertEquals('NOT LIKE ?', $notLike->getExpression());
        $this->assertEquals('myValue%', $parameters[0]);
    }

    public function testEqual()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '=myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $operator = $this->getWherePart($predicates, 0);

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Operator', $operator);
        $this->assertEquals(Operator::OP_EQ, $operator->getOperator());
        $this->assertEquals('myCol', $operator->getLeft());
        $this->assertEquals('myValue', $operator->getRight());
    }

    public function testNotEqual()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '!=myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $operator = $this->getWherePart($predicates, 0);

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Operator', $operator);
        $this->assertEquals(Operator::OP_NE, $operator->getOperator());
        $this->assertEquals('myCol', $operator->getLeft());
        $this->assertEquals('myValue', $operator->getRight());
    }

    public function testGreaterEqual()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '>=myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $operator = $this->getWherePart($predicates, 0);

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Operator', $operator);
        $this->assertEquals(Operator::OP_GTE, $operator->getOperator());
        $this->assertEquals('myCol', $operator->getLeft());
        $this->assertEquals('myValue', $operator->getRight());
    }

    public function testGreater()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '>myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $operator = $this->getWherePart($predicates, 0);

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Operator', $operator);
        $this->assertEquals(Operator::OP_GT, $operator->getOperator());
        $this->assertEquals('myCol', $operator->getLeft());
        $this->assertEquals('myValue', $operator->getRight());
    }

    public function testLessEqual()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '<=myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $operator = $this->getWherePart($predicates, 0);

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Operator', $operator);
        $this->assertEquals(Operator::OP_LTE, $operator->getOperator());
        $this->assertEquals('myCol', $operator->getLeft());
        $this->assertEquals('myValue', $operator->getRight());
    }

    public function testLess()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '<myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $operator = $this->getWherePart($predicates, 0);

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Operator', $operator);
        $this->assertEquals(Operator::OP_LT, $operator->getOperator());
        $this->assertEquals('myCol', $operator->getLeft());
        $this->assertEquals('myValue', $operator->getRight());
    }

    public function testBetween()
    {
        $filter = new \Zf2datatable\Filter();
        $filter->setFromColumn($this->column, '3 <> myValue');

        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);

        $select = $filterSelect->getSelect();
        /* @var $where \Zend\Db\Sql\Where */
        $where = $select->getRawState('where');

        $predicates = $where->getPredicates();

        $operator = $this->getWherePart($predicates, 0);

        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Between', $operator);
        $this->assertEquals('myCol', $operator->getIdentifier());
        $this->assertEquals('3', $operator->getMinValue());
        $this->assertEquals('myValue', $operator->getMaxValue());
    }

    public function testException()
    {
        $filter = $this->getMock('ZfcDatagrid\Filter');
        $filter->expects($this->any())
            ->method('getColumn')
            ->will($this->returnValue($this->column));
        $filter->expects($this->any())
            ->method('getValues')
            ->will($this->returnValue(array(
            1
        )));
        $filter->expects($this->any())
            ->method('getOperator')
            ->will($this->returnValue(' () '));

        $this->setExpectedException('InvalidArgumentException');
        $filterSelect = clone $this->filterSelect;
        $filterSelect->applyFilter($filter);
    }
}
