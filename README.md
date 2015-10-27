# Vinnia Database tools
Various tools for querying a mysql database.

Some examples:
```
$dsn = 'mysql:host=127.0.0.1;dbname=my_db';
$db = new \Vinnia\DbTools\PDODatabase::build($dsn, 'user', 'pass');

$cars = $db->queryAll('select * from cars');

$car = $db->query('select * from cars');

$db->execute(
    'insert into car(make, model) values (:make, :model)',
    [':make' => 'volvo', ':model' => 'xc90']
);

$helper = new \Vinnia\DbTools\DbHelper($db);

$helper->insert('car', ['make' => 'volvo', 'model' => 'xc90']);

$helper->update('car', ['model' => 'v70'], $predicate = ['make' => 'volvo']);

$allCars = $helper->select('car');

$oneCar = $helper->selectOne('car');

$volvos = $helper->select('car', ['*'], $predicate = ['make' => 'volvo']);

$helper->insertOrUpdate(
    'car',
    ['make' => 'volvo', 'model' => 'xc60'],
    $predicate = ['make' => 'xc90']
)

```
