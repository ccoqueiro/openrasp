--TEST--
hook mysqli::__construct error
--SKIPIF--
<?php
$plugin = <<<EOF
plugin.register('sql_exception', params => {
    assert(params.hostname == '127.0.0.1')
    assert(params.username == 'nonexistentusername')
    assert(params.error_code == '1045')
    return block
})
EOF;
$conf = <<<CONF
security.enforce_policy: false
CONF;
include(__DIR__.'/../skipif.inc');
if (!extension_loaded("mysqli")) die("Skipped: mysqli extension required.");
?>
--INI--
openrasp.root_dir=/tmp/openrasp
--FILE--
<?php
new mysqli('127.0.0.1', 'nonexistentusername');
?>
--EXPECTREGEX--
<\/script><script>location.href="http[s]?:\/\/.*?request_id=[0-9a-f]{32}"<\/script>