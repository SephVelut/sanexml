<?php

namespace SaneXml;

use Sabre\Xml\Service;

class Serializer extends Service
{
    private $clarkN;

    public function __construct($clarkN)
    {
        $this->clarkN = $clarkN;
    }

    public function map($object)
    {
         $parser = $this->getParser($object);
         $elementMap = $this->clarkN . $this->getElementNameFromNamespace($object);

         $this->elementMap[$elementMap] = $parser;
    }

    private function setAttributesForElement($reader, $obj)
    {
        foreach($reader->parseAttributes() as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = $value;
            }
        }
    }

    private function setInnerElements($reader, $obj)
    {
        $elements = $reader->parseInnerTree();
        if(is_array($elements) && count($elements) > 0) {
            $this->setTagElements($elements, $obj);
        }

        $elementName = $this->getElementNameFromNamespace($obj);
        if(is_string($elements)) {
            $this->setIfPropertyExistsOnElement($elementName, $elements, $obj);
        }
    }

    private function setIfPropertyExistsOnElement($elementName, $element, $obj)
    {
        if(property_exists($obj, $elementName)) {
            $obj->$elementName = $element;
        }
    }

    private function setTagElements($elements, $obj)
    {
        foreach ($elements as $key => $value) {
            $property = $this->getElementNameFromClarkNotation($value["name"]);

            if (property_exists($obj, $property)) {
                $obj->$property = $value["value"];
            }
        }
    }

    private function getElementNameFromClarkNotation($namespace)
    {
        $fromLastCurlyBrace = strstr($namespace, "}");

        return ltrim($fromLastCurlyBrace, "}");
    }

    private function getElementNameFromNamespace($obj)
    {
        $fromLastBackQuote = strrchr(get_class($obj), "\\");

        return ltrim($fromLastBackQuote, "\\");
    }

    private function getParser($obj)
    {
        return function ($reader) use ($obj) {
            $this->setAttributesForElement($reader, $obj);

            $this->setInnerElements($reader, $obj);

            $reader->next();
            return $obj;
        };
    }
}
