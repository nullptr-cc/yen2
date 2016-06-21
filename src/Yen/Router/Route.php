<?php

namespace Yen\Router;

class Route
{
    protected $location;
    protected $result;
    protected $location_rx;
    protected $defaults;
    protected $vmap;

    public function __construct($location, $result)
    {
        $this->location = rtrim($location, '*+');
        $this->result = $result;

        $lrx = self::loc2rx($location);
        $this->location_rx = $lrx['rx'];
        $this->defaults = $lrx['defaults'];
        $this->vmap = $lrx['vmap'];
    }

    public function match($uri)
    {
        if (!preg_match($this->location_rx, $uri, $matches)) {
            return MatchResult::fail();
        };

        $vars = $this->defaults;
        foreach ($this->vmap as $index => $vname) {
            if (isset($matches[$index + 1])) {
                $vars[$vname] = $matches[$index + 1];
            } elseif ($vname == 'suffix') {
                $vars['suffix'] = '';
            };
        };
        $vars['uri'] = $uri;

        $result = preg_replace_callback(
            '~\$([a-z0-9_]+)~',
            function ($m) use (&$vars)
            {
                $value = $vars[$m[1]];
                unset($vars[$m[1]]);
                return $value;
            },
            $this->result
        );

        unset($vars['uri'], $vars['suffix']);
        $path = trim(preg_replace('~/{2,}~', '/', $result), '/');

        return MatchResult::success(new RoutePoint($path, $vars));
    }

    public function apply($args)
    {
        $used_args = [];

        $uri = preg_replace_callback(
            '~\([^)]+\)~',
            function ($m) use ($args, &$used_args)
            {
                $str = substr($m[0], 1, -1);
                if ($str[0] != ':') {
                    return $str;
                };
                $nv = array_map('trim', explode('=', substr($str, 1)));
                $used_args[$nv[0]] = true;
                return isset($args[$nv[0]]) ? $args[$nv[0]] : ((count($nv) == 1) ? '' : $nv[1]);
            },
            $this->location
        );

        $uri = preg_replace_callback(
            '~:[a-z0-9_]+~',
            function ($m) use ($args, &$used_args)
            {
                $str = substr($m[0], 1);
                $used_args[$str] = true;
                return $args[$str];
            },
            $uri
        );

        $point_args = array_diff_key($args, $used_args);

        return new RoutePoint($uri, $point_args);
    }

    private static function loc2rx($location)
    {
        $loc_parts = array_filter(explode('/', rtrim($location, '*+')));
        $rx_parts = [];
        $vmap = [];
        $defaults = [];
        $vmi = 0;

        foreach ($loc_parts as $part) {
            if ($part[0] == ':') {
                $rx_parts[] = '/([a-z0-9_]+)';
                $vmap[$vmi++] = substr($part, 1);
                continue;
            };

            if ($part[0] == '(') {
                if ($part[1] != ':') {
                    $rx_parts[] = '(/' . substr($part, 1, -1) . ')?';
                    continue;
                };

                $str = substr($part, 2, -1);
                $nv = explode('=', $str);
                if (count($nv) == 1) {
                    $rx_parts[] = '(/[a-z0-9_]+)?';
                    $vmap[$vmi++] = $str;
                } else {
                    $rx_parts[] = '(/[a-z0-9_]+)?';
                    $vmap[$vmi++] = trim($nv[0]);
                    $defaults[trim($nv[0])] = trim($nv[1]);
                };
                continue;
            };

            $rx_parts[] = '/' . $part;
        };

        $suffix = '';
        if (substr($location, -1) == '*') {
            $suffix = '(/.+)?';
        } elseif (substr($location, -1) == '+') {
            $suffix = '(/.+)';
        };
        if ($suffix != '') {
            $vmap[$vmi++] = 'suffix';
        };

        if (!count($rx_parts)) {
            $rx_main = (strlen($suffix) == 6) ? '/|' : ($suffix ? '' : '/');
        } else {
            $rx_main = implode($rx_parts);
        };

        return [
            'rx' => '~^' . $rx_main . $suffix . '$~',
            'defaults' => $defaults,
            'vmap' => $vmap
        ];
    }
}
