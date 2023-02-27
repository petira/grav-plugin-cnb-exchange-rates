# CNB Exchange Rates Plugin

The **CNB Exchange Rates Plugin** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). The CNB Exchange Rates plugin obtains information about current exchange rates from source files published by the Czech National Bank (CNB) and subsequently provides their outputs.

The dependency on Grav v1.6.0 is now set, but it is being developed on Grav v1.7 and Admin Panel v1.10.

You can always find the latest version of this [documentation](https://github.com/petira/grav-plugin-cnb-exchange-rates/blob/develop/README.md) on the project [homepage](https://github.com/petira/grav-plugin-cnb-exchange-rates).

If you find a problem or have a suggestion for improvement, please send me an [issue](https://github.com/petira/grav-plugin-cnb-exchange-rates/issues).

If you translate the CNB Exchange Rates Plugin into another language, please send me the [strings](https://github.com/petira/grav-plugin-cnb-exchange-rates/blob/develop/languages.yaml) via [pull request](https://github.com/petira/grav-plugin-cnb-exchange-rates/pulls) or [issue](https://github.com/petira/grav-plugin-cnb-exchange-rates/issues).

The [demo](https://www.grav.cz/demo/cnb-exchange-rates) is available on the [Grav.cz](https://www.grav.cz) website.

## Installation

Installing the CNB Exchange Rates Plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](https://learn.getgrav.org/cli-console/grav-cli-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav installation, and enter:

    bin/gpm install cnb-exchange-rates

This will install the CNB Exchange Rates Plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/cnb-exchange-rates`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `cnb-exchange-rates`. You can find these files on [GitHub](https://github.com/petira/grav-plugin-cnb-exchange-rates) or via [GetGrav.org](https://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/cnb-exchange-rates

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml file on GitHub](https://github.com/petira/grav-plugin-cnb-exchange-rates/blob/develop/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins` menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `/user/plugins/cnb-exchange-rates/cnb-exchange-rates.yaml` to `/user/config/plugins/cnb-exchange-rates.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named `cnb-exchange-rates.yaml` will be saved in the `/user/config/plugins` folder once the configuration is saved in the Admin.

## Usage

The data is obtained from two source files, one in English, the other in Czech. Depending on the plugin settings, both files are processed in the desired priority, or only one of the selected files.

The data is stored in the `cnb()` array, the key for each currency is its `code`, for example `USD`. Each currency array contains the following `country_xx`, `currency_xx`, `amount_xx`, `code_xx` and `rate_xx` variables, where `xx` indicates the `en` code for English and the `cs` code for Czech. If only one language is processed, the other is not included.

At the same time, the data is stored in the following `country`, `currency`, `amount`, `code` and `rate` variables. In the case of processing one language, they contain values for that language. In the case of processing both languages, they contain the values for the language that is selected as the **major** one.

### Sample values for `AUD` currency at different processing sequence settings

| sequence | ==> | EN/CS | <== | ==> | CS/EN | <== | ==> | EN | <== | ==> | CS | <== |
| - | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: |
| **variable/suffix** | * | en | cs | * | cs | en | * | en | cs | * | cs | en |
| country | Australia | Australia | Austrálie | Austrálie | Austrálie | Australia | Australia | Australia | null | Austrálie | Austrálie | null |
| currency | dollar | dollar | dolar | dolar | dolar | dollar | dollar | dollar | null | dolar | dolar | null |
| amount | 1 | 1 | 1 | 1 | 1 | 1 | 1 | 1 | null | 1 | 1 | null |
| code | AUD | AUD | AUD | AUD | AUD | AUD | AUD | AUD | null | AUD | AUD | null |
| rate | 00.000 | 00.000 | 00,000 | 00,000 | 00,000 | 00.000 | 00.000 | 00.000 | null | 00,000 | 00,000 | null |

> NOTE: `English major, Czech minor` is `EN/CS` etc. The `*` sign indicates main variables without a suffix. In the line of the `rate` variable, the currency mask is symbolically represented.

### Current currency codes

`AUD`, `BGN`, `BRL`, `CAD`, `CNY`, `DKK`, `EUR`, `GBP`, `HKD`, `HUF`, `CHF`, `IDR`, `ILS`, `INR`, `ISK`, `JPY`, `KRW`, `MXN`, `MYR`, `NOK`, `NZD`, `PHP`, `PLN`, `RON`, `SEK`, `SGD`, `THB`, `TRY`, `USD`, `XDR`, `ZAR`

### Twig processing

The `cnb()` is a function that returns an array, must contain `()`. The `code` is case sensitive, must contain capital letters, for example `['USD']`. Everything else must be lowercase, for example `['country']`.

#### Separate output

    {{ cnb()['USD']['country'] }} ({{ cnb()['USD']['currency'] }}) - {{ cnb()['USD']['amount'] }} {{ cnb()['USD']['code'] }} = {{ cnb()['USD']['rate'] }} CZK

#### Complete output

* **Basic output**

```yaml
{% for cnb in cnb() %}
    {{ cnb.country_cs|e }} ({{ cnb.currency_cs|e }}) - {{ cnb.amount_cs|e }} {{ cnb.code_cs|e }} = {{ cnb.rate_cs|e }} CZK<br />
{% endfor %}
```

* **Basic output with key**

```yaml
{% for code, cnb in cnb() %}
    {{ cnb.country_cs|e }} ({{ cnb.currency_cs|e }}) - {{ cnb.amount_cs|e }} {{ code|e }} = {{ cnb.rate_cs|e }} CZK<br />
{% endfor %}
```

> NOTE: `code` key equals `cnb.code_en` equals `cnb.code_en` equals `cnb.code_cs`.

* **Sorting by country name**

```yaml
{% for code, cnb in cnb()|sort %}
...
```

> NOTE: Sorting is carried out according to the country name stored in the `country` item, that is, for the main language.

* **Sorting by currency code**

```yaml
{% for code, cnb in cnb()|ksort %}
...
```

> NOTE: Sorting is carried out according to the currency `code` set as the key.

* **Header of the listing**

It is possible to use translated strings of available languages from the plugin for the header of the listing, for example:

    {{ 'PLUGIN_CNB_EXCHANGE_RATES.COUNTRY'|t }}

> NOTE: `COUNTRY`, `CURRENCY`, `AMOUNT`, `CODE` and `RATE` strings are now available.

## Credits

[Grav.cz](https://www.grav.cz) - Czech portal about [Grav CMS](https://github.com/getgrav/grav) containing lots of instructions and tips

[Vít Petira](https://github.com/petira) - GitHub repository with additional plugins for [Grav CMS](https://github.com/getgrav/grav) from the same author

## To Do

- [ ] Local cache of source files
- [ ] Twig variables support
- [ ] Shortcode support
