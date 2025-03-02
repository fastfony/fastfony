# Coding style

If you want a project with homogeneous code, you can use the following coding style which have used in Fastfony.

## Fixer with GrumPHP

In order to install the git pre-commit hook, execute the command `vendor/bin/grumphp git:pre-commit`

## PHP

We try to have line length of 120 characters maximum.

In class :

- Constants in first
- Properties in second
- Public methods in first, after constants and properties
- Protected methods in second, after public methods
- Private methods in third, after protected methods
- If multiple arguments in a declaration or usage of method, we put each argument on a new line with a comma at the end of the line

### Naming

### No suffix for class name

In order to evict repetition (and long name) when the directory name is included in the class name, we don't repeat it in the class name.

For example, if the class is in the `Entity` directory, we don't repeat `Entity` in the class name.

It's obvious for the entities class, so we apply this rule for all classes.

For example, controller class `src/Controller/LoginController.php` will be named `Login` without `Controller` suffix.
