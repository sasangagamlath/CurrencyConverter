<?php
/**
 * Copyright © 2026 All rights reserved
 * @author Charaka Gamlath <sasangagamlath@gmail.com>
 */

declare(strict_types=1);

namespace Charaka\CurrencyConverter\Model;

class Constants
{

    // System Configuration Fields
    public const string XML_PATH_API_URL = 'currency_converter/general/api_url';
    public const string XML_PATH_DEFAULT_CURRENCY_FROM = 'currency_converter/general/default_currency_from';
    public const string XML_PATH_DEFAULT_CURRENCY_TO = 'currency_converter/general/default_currency_to';
    public const string XML_PATH_CURL_TIME_OUT = 'currency_converter/general/curl_time_out';
    public const string XML_PATH_ASSET_TYPE = 'currency_converter/general/asset_type';
    public const string XML_PATH_RATE_HISTORY_DATE_RANGE = 'currency_converter/general/history_show_period';
    public const string XML_PATH_ENABLED = 'currency_converter/general/enable_currency_conversion_page';
    public const string XML_PATH_DEBUG_LOG = 'currency_converter/general/debug_log';

    // API Endpoints
    public const string CURRENCIES_ENDPOINT = 'currencies';
    public const string CONVERSION_RATE_ENDPOINT = 'rate';
    public const string CONVERSION_RATE_HISTORY_ENDPOINT = 'rates?base=';

    // Data Fields
    public const string FIELD_RATE = 'rate';
    public const string FIELD_DATE = 'date';
    public const string FIELD_NAME = 'name';
    public const string FIELD_ISO_CODE = 'iso_code';
    public const string FIELD_TYPE = 'type';
    public const string FIELD_ASSET_TYPE_CURRENCY = 'currency';

    //Param Fields
    public const string PARAM_FIELD_FROM = 'from';
    public const string PARAM_FIELD_TO = 'to';
    public const string PARAM_FIELD_DAYS = 'days';
    public const string PARAM_FIELD_AMOUNT = 'amount';
    public const string PARAM_FIELD_ACTION = 'action';
    public const string PARAM_FIELD_SWAP = 'swap';
}
