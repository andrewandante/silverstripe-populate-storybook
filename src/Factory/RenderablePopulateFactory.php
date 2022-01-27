<?php

namespace AndrewAndante\PopulateStoryBook\Factory;

use DNADesign\Populate\PopulateFactory;
use SilverStripe\ORM\FieldType\DBHTMLText;

class RenderablePopulateFactory extends PopulateFactory
{
    public function renderObject($class, $identifier, $data = null): DBHTMLText
    {
        $object = $this->get($class, $identifier);
        if ($object === null) {
            $object = $this->createObject($class, $identifier, $data);
        }

        return $object->renderWith($object->getViewerTemplates(), $data);
    }
}
