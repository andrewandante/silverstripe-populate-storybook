<?php

namespace AndrewAndante\PopulateStoryBook\Tasks;

use DNADesign\Populate\Populate;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\YamlFixture;
use SilverStripe\View\Parsers\TidyHTMLCleaner;
use Symfony\Component\Yaml\Parser;

class RenderPopulateTask extends BuildTask
{

    private static $segment = 'RenderPopulateTask';

    protected $title = 'Render Populate for Storybook Task';

    protected $description = <<<TXT
Pass in a class name and an identifier to return HTML for any given populate data so that you can easily copy-paste
it into your Storybook Stories. Optionally pass data in via an array if needed.
TXT;


    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function run($request)
    {
        $class = $request->getVar('className');
        $identifier = $request->getVar('id');
        $blueprintData = $request->getVar('data') ?: [];
        $found = false;

        if ($class && $identifier) {
            $parser = new Parser();
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
                            $found = true;
                            break 3;
                        }
                    }
                }
            }
            if (!$found) {
                self::log('No entry found for that class + id combination');
            } else {
                $object = Injector::inst()->create($class);
                $tidy = new TidyHTMLCleaner();
                $defaultConfig = $tidy->getConfig();
                $tidy->setConfig(array_merge($defaultConfig, ['indent' => 2]));
                self::log($tidy->cleanHTML($object->renderWith($object->getViewerTemplates(), $blueprintData)));
            }
        } else {
            self::log('Needs at least ?className= and ?id=');
        }
    }

    protected static function log(string $message)
    {
        if (Director::is_cli()) {
            $newline = PHP_EOL;
        } else {
            $newline = "<br />";
        }
        echo $message . $newline;
    }
}
