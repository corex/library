<?php

use CoRex\Support\Obj;
use CoRex\Support\StrList;
use PHPUnit\Framework\TestCase;

class StrListTest extends TestCase
{
    private $item1 = 'Item 1';
    private $item2 = 'Item 2';
    private $item3 = 'Item 3';
    private $item4 = 'Item 4';
    private $items;

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->items = $this->item1 . '|' . $this->item2 . '|' . $this->item3;
    }

    /**
     * Test count.
     */
    public function testCount()
    {
        $this->assertEquals(3, StrList::count($this->items, '|'));
    }

    /**
     * Test count empty.
     */
    public function testCountEmpty()
    {
        $this->assertEquals(0, StrList::count('', '|'));
    }

    /**
     * Test add.
     */
    public function testAdd()
    {
        $this->assertEquals(3, StrList::count($this->items, '|'));
        $items = StrList::add($this->items, $this->item4, '|');
        $this->assertEquals(4, StrList::count($items, '|'));
        $this->assertEquals($this->items . '|' . $this->item4, $items);
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $this->assertEquals($this->item3, StrList::get($this->items, 2, '|'));
    }

    /**
     * Test get tag.
     */
    public function testGetTag()
    {
        $this->assertEquals('3', StrList::get('-1-|-2-|-3-|-4-', 2, '|', '-'));
    }

    /**
     * Test get empty.
     */
    public function testGetEmpty()
    {
        $this->assertEquals('', StrList::get('', 2, '|'));
    }

    /**
     * Test pos.
     */
    public function testPos()
    {
        $this->assertEquals(2, StrList::pos($this->items, $this->item3, '|'));
    }

    /**
     * Test pos empty.
     */
    public function testPosEmpty()
    {
        $this->assertEquals(-1, StrList::pos('', $this->item3, '|'));
    }

    /**
     * Test remove.
     */
    public function testRemove()
    {
        $items = $this->items . '|' . $this->item4;
        $this->assertEquals($this->items, StrList::remove($items, $this->item4, '|'));
    }

    /**
     * Test remove index.
     */
    public function testRemoveIndex()
    {
        $items = $this->items . '|' . $this->item4;
        $this->assertEquals($this->items, StrList::removeIndex($items, 3, '|'));
    }

    /**
     * Test exist.
     */
    public function testExist()
    {
        $this->assertTrue(StrList::exist($this->items, $this->item2, '|'));
        $this->assertFalse(StrList::exist($this->items, $this->item4, '|'));
    }

    /**
     * Test merge.
     */
    public function testMerge()
    {
        $items1 = $this->item2 . '|' . $this->item1;
        $items2 = $this->item4 . '|' . $this->item3;
        $items = StrList::merge($items1, $items2, false, '|');
        $this->assertNotEquals($this->items . '|' . $this->item4, $items);
        $items = StrList::merge($items1, $items2, true, '|');
        $this->assertEquals($this->items . '|' . $this->item4, $items);
    }

    /**
     * Test sortCompare asc.
     */
    public function testSortCompareAsc()
    {
        $params = ['item1' => 'a', 'item2' => 'b'];
        $check = Obj::callMethod('sortCompare', null, $params, StrList::class);
        $this->assertEquals(-1, $check);
    }

    /**
     * Test sortCompare desc.
     */
    public function testSortCompareDesc()
    {
        $params = ['item1' => 'b', 'item2' => 'a'];
        $check = Obj::callMethod('sortCompare', null, $params, StrList::class);
        $this->assertEquals(1, $check);
    }

    /**
     * Test sortCompare equal.
     */
    public function testSortCompareEqual()
    {
        $params = ['item1' => 'a', 'item2' => 'a'];
        $check = Obj::callMethod('sortCompare', null, $params, StrList::class);
        $this->assertEquals(0, $check);
    }

    /**
     * Test sortCompare tag.
     */
    public function testSortCompareTag()
    {
        Obj::setProperty('usortTag', null, '-', StrList::class);
        $params = ['item1' => '-a-', 'item2' => '-b-'];
        $check = Obj::callMethod('sortCompare', null, $params, StrList::class);
        $this->assertEquals(-1, $check);
    }
}
