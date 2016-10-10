<?php

use PHPUnit\Framework\TestCase;

use SaneXml\Serializer;

use Test\Fixtures\RootElement;
use Test\Fixtures\Lion;
use Test\Fixtures\Tiger;
use Test\Fixtures\Whale;
use Test\Fixtures\EW1;
use Test\Fixtures\EW2;
use Test\Fixtures\EW3;
use Test\Fixtures\EW4;
use Test\Fixtures\Dingo;
use Test\Fixtures\Bingo;

class SerializerTest extends TestCase
{
    public function testWrapsSabreSerializer()
    {
        $xml = file_get_contents(__DIR__ . "/fixtures/Test.xml");
        $ns = '{http://www.w3.org/2001/XMLSchema-instance}';

        $serializer = new Serializer($ns);
        $serializer->map(new RootElement);
        $serializer->map(new Lion);
        $serializer->map(new Tiger);
        $serializer->map(new Whale);
        $serializer->map(new EW2);
        $serializer->map(new EW1);
        $serializer->map(new EW3);
        $serializer->map(new EW4);
        $serializer->map(new Dingo);
        $serializer->map(new Bingo);

        $parsedElements = $serializer->parse($xml);

        $this->assertEquals($parsedElements->Lion->E1, "195.201.0.78");
        $this->assertEquals($parsedElements->Lion->E2, "2001-12-31T12:00:00-06:00");

        $this->assertEquals($parsedElements->Tiger->E1, "30044");
        $this->assertEquals($parsedElements->Tiger->E2, "Lawrenceville");

        $this->assertEquals($parsedElements->Whale->EW1->At1, "3");
        $this->assertEquals($parsedElements->Whale->EW1->At2, "4");
        $this->assertEquals($parsedElements->Whale->EW1->EW1, "Own");

        $this->assertEquals($parsedElements->Whale->EW2->EW3->At3, "true");
        $this->assertEquals($parsedElements->Whale->EW2->EW3->At4, "0");

        $this->assertEquals($parsedElements->Whale->EW2->EW3->EW4->At5, "5000");
        $this->assertEquals($parsedElements->Whale->EW2->EW3->EW4->At6, "5");
        $this->assertEquals($parsedElements->Whale->EW2->EW3->EW4->At7, "0");

        $this->assertEquals($parsedElements->Whale->EW2->EW3->EW4->Dingo->Egret, "shutup");
        $this->assertEquals($parsedElements->Whale->EW2->EW3->EW4->Dingo->Bingo->Singo, "eyyyy");
        $this->assertEquals($parsedElements->Whale->EW2->EW3->EW4->Dingo->Bingo->Bingo, "BamBam");
    }
}
