# Contributing to Plume

Contributions are **welcome** via Pull Requests on [GitHub](https://github.com/PHLAK/Plume).

**Before contributing** we encourage you to discuss the change in
[GitHub Discussion](https://github.com/PHLAK/Plume/discussions)
to verify fit with the overall direction and goals of Plume.

## Pull Requests Requirements

- **One feature per pull request.** If you want to change more than one thing,
  send multiple pull requests.

- **[PSR-2 Coding Standard.](https://www.php-fig.org/psr/psr-2/)** The easiest
  way to apply the conventions is to install and run
  [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

- **Test all the tings!** Your patch won't be accepted if it doesn't have tests.

- **Document changes in behaviour.** Make sure relevant documentation is kept
  up to date.

## Checking Your Work

Please check your work before submitting. Your branch must meet our coding
standards and pass static analysis and tests before it will be accepted.

### Coding Standards

You can check your code formatting without applying any fixes by running

    $ composer exec php-cs-fixer fix --diff --dry-run

To automatically apply any fixes run the same command without the flags.

    $ composer exec php-cs-fixer fix

### Static Analysis

[PHPStan](https://phpstan.org) is used to generate a report of static analysis errors.

    $ composer exec phpstan

### Run Tests

Application tests can be ran with phpunit.

    $ composer exec phpunit

---

*Thank you and happy coding!*
