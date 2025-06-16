<?php 

namespace Moonspot\Component\Test;

use PHPUnit\Framework\TestCase;
use Moonspot\Component\ComponentAbstract;

class ComponentAbstractTest extends TestCase {

    public function testOutput(): void {
        ob_start();
        TestComponent::render(
            [
                'size' => 'large',
            ],
            [
                'id'   => 'mytest',
                'data' => [
                    'foo' => 'bar'
                ]
            ]
        );
        TestComponent::render(
            [],
            [
                'id'   => 'mytest2',
                'disabled' => false,
                'data' => [
                    'foo' => 'bar'
                ]
            ]
        );
        $html = ob_get_clean();

        $expect = trim(file_get_contents(__DIR__ . '/fixtures/my_component.html'));
        $this->assertEquals($expect, trim($html));
    }

    public function testSettingsExceptions(): void {
        $this->expectException(\LogicException::class);
        $this->expectExceptionCode(1);
        TestComponent::render(
            [
                'foo' => 'bar',
            ]
        );
    }

    public function testAttributeExceptions(): void {

        $this->expectException(\LogicException::class);
        $this->expectExceptionCode(2);
        TestComponent::render(
            [],
            [
                'foo' => 'bar',
            ]
        );
    }

    public function testAutoId(): void {
        ob_start();
        TestComponent::render();
        $html = ob_get_clean();

        $this->assertEquals(1, preg_match('/id="auto-id-.+?"/', $html));
    }
}

class TestComponent extends ComponentAbstract {

    protected string $size = 'small';

    public bool $disabled = true;

    public function setDefaults() {
        $this->class .= " test";
    }

    public function markup() {
        ?>
        <div <?=$this->attributes()?>>
        </div>
        <?php
    }

    public static function css() {
        ?>
        <style>
            .test {
                font-weight: bold;
            }
        </style>
        <?php
    }

    public static function script() {
        ?>
        <script>
            var foo = 1;
        </script>
        <?php
    }
}
