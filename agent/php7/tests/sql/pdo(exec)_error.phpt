--TEST--
hook PDO::exec error
--SKIPIF--
<?php
$plugin = <<<EOF
plugin.register('sql_exception', params => {
    assert(params.server == 'mysql')
    assert(params.query == 'select GeometryCollection((select 1 from (select * from (select user())a)b))')
    assert(params.error_code == '1367')
    return block
})
EOF;
$conf = <<<CONF
security.enforce_policy: false
CONF;
include(__DIR__.'/../skipif.inc');
if (!extension_loaded("mysqli")) die("Skipped: mysqli extension required.");
if (!extension_loaded("pdo")) die("Skipped: pdo extension required.");
@$con = mysqli_connect('127.0.0.1', 'root', 'rasp#2019');
if (mysqli_connect_errno()) die("Skipped: can not connect to MySQL " . mysqli_connect_error());
mysqli_close($con);
?>
--INI--
openrasp.root_dir=/tmp/openrasp
--FILE--
<?php
include('pdo_mysql.inc');
$con->exec("select GeometryCollection((select 1 from (select * from (select user())a)b))");
?>
--EXPECTREGEX--
<\/script><script>location.href="http[s]?:\/\/.*?request_id=[0-9a-f]{32}"<\/script>