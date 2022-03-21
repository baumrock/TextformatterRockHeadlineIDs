# TextformatterRockHeadlineIDs

## A message to Russian 🇷🇺 people

If you currently live in Russia, please read [this message](https://github.com/Roave/SecurityAdvisories/blob/latest/ToRussianPeople.md).

[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

---

Textformatter that applies id attributes to all headlines (h1-h6) in the markup field.

```html
// input
<h1>This is my headline</h1>
// output
<h1 id='this-is-my-headline'>This is my headline</h1>
```

## What if an id already exists?

If an id already exists in the input it will leave this id as is:

```html
// input
<h1 id='foo'>foo</h1>
<h2>bar</h2>
// output
<h1 id='foo'>foo</h1>
<h2 id='bar'>bar</h2>
```

## What if an id is used twice?

It will automatically apply dashes until the id is unique:

```html
// input
<h1>foo</h1>
<h2>foo</h2>
<h3>foo</h3>
// output
<h1 id='foo'>foo</h1>
<h2 id='foo-'>foo</h2>
<h3 id='foo--'>foo</h3>
```

## What about special characters?

Special characters will be sanitized according to your transliterate settings:

```html
// input
<h1>Sehr schön</h1>
// output
<h1 id='sehr-schoen'>Sehr schön</h1>
```

---

## Customize

You can customize the generated ID via hooking into `getID`:

```php
$wire->addHookAfter("TextformatterRockHeadlineIDs::getID", function($event) {
  $event->return .= "-hooked";
});
```

```html
// input
<h1>foo</h1>
// output
<h1 id='foo-hooked'>foo</h1>
```

---

## Debugging

You can use tracy to debug this formatter:

![img](https://i.imgur.com/WFnG7aJ.png)
