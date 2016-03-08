<?php

namespace YenTest\Settings;

use Yen\Settings\Contract\ISettings;
use Yen\Settings\SettingsArray;

class SettingsArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPlain()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'bam',
            'boo' => [
                'bor' => 321
            ]
        ];

        $settings = new SettingsArray($array);

        $this->assertEquals('bar', $settings->get('foo'));
        $this->assertEquals('bam', $settings->get('baz'));
        $this->assertEquals(321, $settings->get('boo.bor'));
    }

    public function testGetSettings()
    {
        $array = [
            'foo' => 'bar',
            'boo' => [
                'bor' => 321
            ]
        ];

        $settings = new SettingsArray($array);

        $this->assertInstanceOf(ISettings::class, $settings->get('boo'));
        $this->assertEquals(321, $settings->get('boo')->get('bor'));
    }

    public function testInvalidKeyException()
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Invalid settings key');

        $array = [
            'foo' => 'bar'
        ];

        $settings = new SettingsArray($array);
        $settings->get('baz');
    }

    public function testLookup()
    {
        $array = [
            'foo' => 'bar',
            'boo' => [
                'bor' => 321
            ]
        ];

        $settings = new SettingsArray($array);

        $this->assertEquals('bar', $settings->lookup('foo'));
        $this->assertEquals(321, $settings->lookup('boo.bor'));
        $this->assertEquals('fall', $settings->lookup('noone', 'fall'));
    }
}
