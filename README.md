# Charaka_CurrencyConverter

A Magento 2 module that adds a storefront **Currency Exchange Converter** page powered by the free [Frankfurter API](https://frankfurter.dev). Visitors can pick any supported currency pair, see the converted amount, swap the two currencies, and view a rate-history chart. The page is rendered fully server-side, so it works without JavaScript.

---

## Features

- Convert an amount between any two currencies supported by Frankfurter
- Current exchange rate and converted amount
- Historical rate chart (inline SVG, no JS library) with High / Low values and start / end dates
- **Swap** button to reverse the From / To currencies
- Admin configuration for defaults, API URL, timeout, history period and debug logging
- Option to hide crypto / metal assets and list real currencies only
- Optional API request / response logging to a dedicated log file
- Admin menu entry and ACL resource to control who can edit the settings

## Requirements

| Item | Requirement |
|------|-------------|
| Magento | 2.4.x (Open Source / Adobe Commerce) |
| PHP | 8.3+ |

## Installation

The module code is namespaced `Charaka\CurrencyConverter`, so it must live at `app/code/Charaka/CurrencyConverter`.

1. Create the vendor folder (if missing):
   ```bash
   mkdir -p app/code/Charaka
   ```
2. Copy the module (the `CurrencyConverter` folder from the zip) into it:
   ```bash
   cp -r CurrencyConverter app/code/Charaka/CurrencyConverter
   ```
3. Enable the module and run setup:
   ```bash
   bin/magento module:enable Charaka_CurrencyConverter
   bin/magento setup:upgrade
   ```
4. Compile and deploy static content (required in production mode):
   ```bash
   bin/magento setup:di:compile
   bin/magento setup:static-content:deploy -f
   ```
5. Flush the cache:
   ```bash
   bin/magento cache:flush
   ```
6. Verify the module is enabled:
   ```bash
   bin/magento module:status Charaka_CurrencyConverter
   ```

## How to navigate to the Configuration (Admin)

There are two ways to reach the settings.

### Option A: Through the Admin menu

1. Log in to the Magento Admin panel.
2. In the left sidebar, click **Charaka**.
3. Click **Currency Converter Configs**.
4. The **Currency Converter Configuration** page opens, with the **Default Configs** group.

### Option B: Through Stores > Configuration

1. Log in to the Magento Admin panel.
2. Go to **Stores > Settings > Configuration**.
3. In the left tab list, find the **Charaka** tab.
4. Click **Currency Converter Configuration**.
5. Expand **Default Configs**.

### Direct URL

```
https://<your-domain>/<admin-path>/admin/system_config/edit/section/currency_converter/
```

### Configuration fields

| Field | Default | Description |
|-------|---------|-------------|
| Enable Currency Conversion Page | Yes | Turns the storefront page on or off. When off, the page returns a 404. |
| Frankfurter API URL | `https://api.frankfurter.dev/v2/` | Base URL of the API. **Must end with a trailing `/`.** |
| Default Currency "From" | `AED` | Currency preselected in the "From" dropdown (e.g. `AED`). |
| Default Currency "To" | `LKR` | Currency preselected in the "To" dropdown (e.g. `LKR`). |
| Curl Time Out | `30` | API request timeout in seconds. |
| Show Only "currency" Asset Type | Yes | If Yes, crypto and metal assets are excluded from the dropdowns. |
| Rate History Show Period | `90` | Number of days (up to today) shown in the history chart. |
| Debug API Call's | Yes | Logs API calls and payloads to `var/log/frankfurter-currency-converter.log`. |

All fields except the first are only shown when **Enable Currency Conversion Page** is set to **Yes**.

Config path prefix: `currency_converter/general/*`

## How to navigate to the Front End page

1. Make sure **Enable Currency Conversion Page = Yes** (see [Configuration fields](#configuration-fields)) and the cache is flushed.
2. Open your storefront in a browser.
3. Visit the page URL:
   ```
   https://<your-domain>/currency_converter
   ```

4. The **Currency Converter** page loads with the default From / To currencies and an amount of `1`.
5. Use the page:
   1. Enter an **Amount**.
   2. Choose **From** and **To** currencies.
   3. Click **Convert** to see the converted value and the rate-history chart.
   4. Click **Swap** to reverse the two currencies.

The page is not added to any storefront menu automatically; link to it from a CMS block or the navigation if needed.

### URL parameters

The form uses `GET`, so results can be bookmarked or shared:

| Parameter | Example | Meaning |
|-----------|---------|---------|
| `amount` | `100` | Amount to convert (default `1`) |
| `from` | `USD` | Source currency code |
| `to` | `LKR` | Target currency code |
| `action` | `swap` | Swaps `from` and `to` |


## How it works

1. A visitor opens `/currency_converter`.
2. `Controller/Index/Index` checks the **Enable** setting. If disabled, it forwards to `noroute` (404). Otherwise it renders the page titled **Currency Converter**.
3. The layout file `currencyconverter_index_index.xml` places the `CurrencyConverter` block and the `converter.phtml` template into the `content` container (1-column layout).
4. The block reads the request params (`amount`, `from`, `to`, `action`) and falls back to the configured defaults.
5. `Model/ApiCall` calls Frankfurter through Magento's Curl client:

   | Purpose | Request |
   |---------|---------|
   | Currency list | `GET {api_url}currencies` |
   | Latest rate | `GET {api_url}rate/{from}/{to}` |
   | History | `GET {api_url}rates?base={from}&quotes={to}&from={start}&to={end}` |

6. The template renders the form, the result, and the SVG chart (rate points are scaled into a 600 x 160 polyline).


## Admin permissions (ACL)

Access to the settings is controlled by the resource **Currency Converter** (`Charaka_CurrencyConverter::config`).

To grant it to a role:

1. Go to **System > Permissions > User Roles**.
2. Edit the role and open the **Role Resources** tab.
3. Tick **Currency Converter** and save.

## Logging and debugging

- With **Debug API Call's = Yes**, every API request, response body and failure is written to:
  ```
  var/log/frankfurter-currency-converter.log
  ```
- Use it to check the exact endpoint called, HTTP status codes and payloads.
- Turn it off in production once testing is done.

## Uninstall / Disable

```bash
bin/magento module:disable Charaka_CurrencyConverter
bin/magento setup:upgrade
bin/magento cache:flush
```

To remove completely, delete `app/code/Charaka/CurrencyConverter` afterwards. The module creates no database tables.

## Author

Charaka Gamlath — sasangagamlath@gmail.com
