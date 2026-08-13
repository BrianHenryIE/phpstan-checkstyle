# phpstan-checkstyle

Reads a PHPStan generated checkstyle report and prints it using PHPStan's own output formatters.

Designed to sit at the end of a [kamazee/pr-filter](https://github.com/kamazee/pr-filter) pipeline, restoring the familiar PHPStan table format after filtering errors down to only those on changed lines.

```
git fetch origin; git diff $(git rev-parse origin/master) > tmp.diff; phpstan --error-format=checkstyle | sponge phpstan-checkstyle.xml | vendor/bin/prf filter-checkstyle tmp.diff phpstan-checkstyle.xml phpstan-filtered-checkstyle.xml; vendor/bin/phpstan-checkstyle phpstan-filtered-checkstyle.xml
```

## Installation

```bash
composer require brianhenryie/phpstan-checkstyle --dev
```

## Usage

Read from a file:

```bash
vendor/bin/phpstan-checkstyle report.xml
```

Read from stdin:

```bash
cat report.xml | vendor/bin/phpstan-checkstyle
```

### Error identifiers

Pass `-v` to include the PHPStan error identifier (e.g. `argument.type`) alongside each error message:

```bash
vendor/bin/phpstan-checkstyle -v report.xml
```

The identifier is read from the checkstyle `source` attribute, which PHPStan populates when generating checkstyle output.

### Output format

The default output format is `table` (PHPStan's standard human-readable output). Use `--format` to select any format PHPStan supports:

```bash
vendor/bin/phpstan-checkstyle --format=json report.xml
vendor/bin/phpstan-checkstyle --format=github report.xml   # GitHub Actions annotations
vendor/bin/phpstan-checkstyle --format=gitlab report.xml
vendor/bin/phpstan-checkstyle --format=teamcity report.xml
vendor/bin/phpstan-checkstyle --format=checkstyle report.xml
vendor/bin/phpstan-checkstyle --format=junit report.xml
vendor/bin/phpstan-checkstyle --format=raw report.xml
```

### Full pipeline example

```bash
# Run PHPStan, filter to PR diff, display as table
phpstan analyse --error-format=checkstyle \
  | vendor/bin/prf pr.diff \
  | vendor/bin/phpstan-checkstyle

# Same, but output GitHub Actions annotations
phpstan analyse --error-format=checkstyle \
  | vendor/bin/prf pr.diff \
  | vendor/bin/phpstan-checkstyle --format=github
```

## Limitations

The checkstyle format does not carry PHPStan tip messages. Output will match PHPStan's table format exactly except for those fields.

## Development

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse
vendor/bin/phpcs --standard=PSR12 src bin tests
```
