# SilverStripe Populate Storybook

This module adds command-line help for rendering arbitrary populate data
for easy copy-paste in Storybook Stories

## Requirements

* SilverStripe ^4.0
* DNADesign/SilverStripe-Populate

## Installation

Add the following to your `composer.json`:

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:andrewandante/silverstripe-populate-storybook.git"
        }
    ]
```

then run:

```shell
composer require andrewandante/silverstripe-populate-storybook 4.x-dev
```

## License
See [License](license.md)

## Documentation
 * [Documentation readme](docs/en/readme.md)

Add links into your docs/<language> folder here unless your module only requires minimal documentation
in that case, add here and remove the docs folder. You might use this as a quick table of content if you
mhave multiple documentation pages.

## Maintainers
 * Andrew Paxley <andrew.paxley@silverstripe.com>

## Bugtracker
Bugs are tracked in the issues section of this repository. Before submitting an issue please read over
existing issues to ensure yours is unique.

If the issue does look like a new bug:

 - Create a new issue
 - Describe the steps required to reproduce your issue, and the expected outcome. Unit tests, screenshots
 and screencasts can help here.
 - Describe your environment as detailed as possible: SilverStripe version, Browser, PHP version,
 Operating System, any installed SilverStripe modules.

Please report security issues to the module maintainers directly. Please don't file security issues in the bugtracker.

## Development and contribution
If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.
