# silverstripe-list-items
This module adds functionality to create a set of list items in a collection, which can be used 
in various grid elements. Also a custom set of list items can be made on the fly in a grid element itself.

## Requirements
* See `composer.json` requirements

## Installation
* `composer require wedevelopnl/silverstripe-elemental-list-items`

## License
See [License](LICENSE)

## Maintainers
* [WeDevelop](https://www.wedevelop.nl/) <development@wedevelop.nl>

## Development and contribution
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
See read our [contributing](CONTRIBUTING.md) document for more information.

### Getting started
We advise to use [Docker](https://docker.com)/[Docker compose](https://docs.docker.com/compose/) for development.\
We also included a [Makefile](https://www.gnu.org/software/make/) to simplify some commands

Our development container contains some built-in tools like `PHPCSFixer`.

#### Getting development container up
`make build` to build the Docker container and then run detached.\
If you want to only get the container up, you can simply type `make up`.

You can SSH into the container using `make sh`.

#### All make commands
You can run `make help` to get a list with all available `make` commands.
