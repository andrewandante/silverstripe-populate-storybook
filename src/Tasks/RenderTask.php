<?php

namespace AndrewAndante\PopulateStoryBook\Tasks;

use AndrewAndante\PopulateStoryBook\Factory\RenderablePopulateFactory;
use DNADesign\Populate\Populate;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\YamlFixture;
use Symfony\Component\Yaml\Parser;

class RenderTask extends BuildTask
{

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function run($request)
    {
        $class = $request->getVar('className');
        $identifier = $request->getVar('id');
        $blueprintData = $request->getVar('data') ?: [];

        if ($class && $identifier) {
            $parser = new Parser();
            $factory = Injector::inst()->create(RenderablePopulateFactory::class);
            foreach (Populate::config()->get('include_yaml_fixtures') as $fixtureFile) {
                $fixture = new YamlFixture($fixtureFile);
                if (!empty($fixture->getFixtureString())) {
                    $fixtureContent = $parser->parse($fixture->getFixtureString());
                } else {
                    if (!file_exists($fixture->getFixtureFile()) || is_dir($fixture->getFixtureFile())) {
                        return;
                    }

                    $contents = file_get_contents($fixture->getFixtureFile());
                    $fixtureContent = $parser->parse($contents);

                    if (!$fixtureContent) {
                        return;
                    }
                }

                foreach ($fixtureContent as $candidateClass => $items) {
                    if ($class !== $candidateClass) {
                        continue;
                    }
                    foreach ($items as $candidateIdentifier => $data) {
                        if ($identifier === $candidateIdentifier) {
                            $blueprintData = array_merge($data, $blueprintData);
                            break 3;
                        }
                    }
                }
            }
            echo $factory->renderObject($class, $identifier, $blueprintData);
        } else {
            echo "Needs at least ?className= and ?id=" . PHP_EOL;
        }
    }
}
