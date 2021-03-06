<?php
/**
 * Created by PhpStorm.
 * User: johan
 * Date: 15-10-27
 * Time: 13:12
 */

namespace Vinnia\DbTools\Test;

use PDO;
use Vinnia\DbTools\PDODatabase;
use Vinnia\DbTools\DbHelper;

class DbHelperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var PDODatabase
     */
    public $db;

    /**
     * @var DbHelper
     */
    public $helper;

    public function setUp() {
        parent::setUp();

        $host = '127.0.0.1';
        $dbname = 'dbtools_test';
        $user = 'root';
        $pass = '';

        $dsn = sprintf('mysql:host=%s;dbname=%s', $host, $dbname);

        $this->db = PDODatabase::build($dsn, $user, $pass);
        $this->helper = new DbHelper($this->db);
    }

    public function tearDown() {
        $this->db->execute('delete from car');

        parent::tearDown();
    }

    public function testInsert() {
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);

        $cars = $this->db->queryAll('select * from car');

        var_dump($cars);

        $this->assertCount(1, $cars);
        $this->assertEquals('volvo', $cars[0]['make']);
        $this->assertEquals('xc90', $cars[0]['model']);
    }

    public function testUpdate() {
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);
        $this->helper->update('car', ['model' => 'v70'], ['make' => 'volvo']);

        $cars = $this->db->queryAll('select * from car');

        var_dump($cars);

        $this->assertCount(1, $cars);
        $this->assertEquals('volvo', $cars[0]['make']);
        $this->assertEquals('v70', $cars[0]['model']);
    }

    public function testExists() {
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);

        $this->assertTrue($this->helper->exists('car', ['make' => 'volvo', 'model' => 'xc90']));
        $this->assertFalse($this->helper->exists('car', ['make' => 'toyota']));
    }

    public function testSelectOne() {
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);
        $car = $this->helper->selectOne('car');

        $this->assertEquals('volvo', $car['make']);
        $this->assertEquals('xc90', $car['model']);
    }

    public function testSelect() {
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'v70']);
        $cars = $this->helper->select('car');

        $this->assertCount(2, $cars);
        $this->assertEquals('volvo', $cars[0]['make']);
        $this->assertEquals('xc90', $cars[0]['model']);
        $this->assertEquals('volvo', $cars[1]['make']);
        $this->assertEquals('v70', $cars[1]['model']);
    }

    public function testSelectWithPredicate() {
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'v70']);
        $cars = $this->helper->select('car', ['*'], ['model' => 'v70']);

        $this->assertCount(1, $cars);
        $this->assertEquals('volvo', $cars[0]['make']);
        $this->assertEquals('v70', $cars[0]['model']);
    }

    public function testInsertOrUpdateWithoutPreviousValue() {
        $this->helper->insertOrUpdate(
            'car',
            ['make' => 'volvo', 'model' => 'xc90'],
            ['make' => 'volvo']
        );

        $cars = $this->helper->select('car');

        $this->assertCount(1, $cars);
        $this->assertEquals('volvo', $cars[0]['make']);
        $this->assertEquals('xc90', $cars[0]['model']);
    }

    public function testInsertOrUpdateWithPreviousValue() {
        $this->helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);
        $this->helper->insertOrUpdate(
            'car',
            ['make' => 'volvo', 'model' => 'v70'],
            ['make' => 'volvo']
        );

        $cars = $this->helper->select('car');

        $this->assertCount(1, $cars);
        $this->assertEquals('volvo', $cars[0]['make']);
        $this->assertEquals('v70', $cars[0]['model']);
    }

}
