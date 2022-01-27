<?php

namespace AndrewAndante\PopulateStoryBook\Factory;

use DNADesign\Populate\PopulateFactory;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBHTMLText;

class RenderablePopulateFactory extends PopulateFactory
{
    public function renderObject($class, $identifier, $data = null): DBHTMLText
    {
        $object = $this->get($class, $identifier);
        if ($object === null) {
            DB::alteration_message('Item not found for render, creating...', 'created');
            $object = $this->createObject($class, $identifier, $data);
        }

        return $object->renderWith($object->getViewerTemplates(), $data);
    }
}
