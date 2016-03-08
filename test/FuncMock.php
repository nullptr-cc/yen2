<?php

namespace FuncMock;

class Mocker
{
    private static $mocks;

    public static function call($name, ...$args)
    {
        $mock = static::$mocks[$name];
        return $mock(...$args);
    }

    protected $back_prefix;
    protected $old_funcs;

    public function __construct()
    {
        $this->back_prefix = uniqid('fm_');
        $this->old_funcs = [];
    }

    public function mockFunc($name, callable $mock)
    {
        $this->redefineFunc($name, $mock, $this->backupFunc($name));
        return $this;
    }

    public function __destruct()
    {
        foreach ($this->old_funcs as $old_name => $new_name) {
            $this->restoreFunc($old_name, $new_name);
        };
    }

    protected function backupFunc($name)
    {
        $new_name = sprintf('%s_%s', $this->back_prefix, $name);
        if (!runkit_function_copy($name, $new_name)) {
            throw new \RuntimeException('Cannot backup function ' . $name);
        };
        $this->old_funcs[$name] = $new_name;
        return $new_name;
    }

    protected function redefineFunc($name, callable $mock, $new_name)
    {
        $code = sprintf('return %s::call("%s", ...$args);', static::class, $new_name);
        if (!runkit_function_redefine($name, '...$args', $code)) {
            throw new \RuntimeException('Cannot redefine function ' . $name);
        };
        static::$mocks[$new_name] = $mock;
    }

    protected function restoreFunc($old_name, $new_name)
    {
        runkit_function_remove($old_name);
        runkit_function_copy($new_name, $old_name);
        runkit_function_remove($new_name);
        unset(static::$mocks[$new_name]);
    }
}
