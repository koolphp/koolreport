# Unit test

### Create a test

```
vendor/bin/codecept generate:test unit koolreport\amazing\AmazingTheme
```

### Run all tests

```
vendor/bin/codecept run unit
```

### Run a test

```
vendor/bin/codecept run unit koolreport\amazing\AmazingThemeTest
```

# Acceptance Test

### Run phantomjs

```
vendor/bin/phantomjs
```

It will run the phantomjs at port 4444

### Create a test

```
vendor/bin/codecept generate:cest amazing\Theme
```

### Create a test sample

The phantomjs will run at https://localhost/Reporting/koolreport/tests/web

Place a folder containing sample report there to test

### Run an acceptance test

```
vendor/bin/codecept run acceptance amazing\ThemeCest
```

### Run all acceptance tests

```
vendor/bin/codecept run acceptance
```
