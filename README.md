# ssp-loganalysis.php

**The script is applicable to those who have enabled the Authentication Process Filter "[statistics:StatisticsWithAttribute](https://simplesamlphp.org/docs/contrib_modules/statistics/authproc_statisticswithattribute.html)" on the Identity Provider.**

```php
45 => [
    'class' => 'core:StatisticsWithAttribute',
    'attributename' => 'realm',
    'type' => 'saml20-idp-SSO',
],
```

Example of a log line considered:

```bash
Feb  7 12:32:12 ssp-idp simplesamlphp[27612]: 5 STAT [46ff6971c4] saml20-idp-SSO https://sp.aai-test.garr.it/shibboleth https://ssp-idp.aai-test.garr.it/simplesaml-212/module.php/saml/idp/metadata NA
```

What remains to be done to produce the required statistics is:

  1. Split the log lines present in the single file `/var/log/simplesamlphp.log` or `/var/log/simplesamlphp.stat` into individual files, one for each month (e.g., `simplesamlphp-YYYY-MM.log` or `simplesamlphp-YYYY-MM.stat`).

  2. Set the constant `SSP_HOME_DIR` within the script.

At this point, executing the script for each month will yield:

```bash
php ssp-loganalysis.php simplesamlphp-2023-01.log > /tmp/idp-$(dnsdomainname)-2023-01-sso-stats.json

php ssp-loganalysis.php simplesamlphp-2023-02.log > /tmp/idp-$(dnsdomainname)-2023-02-sso-stats.json
```

This will produce files like `idp-garr.it-2023-01-sso-stats.json` in the following JSON format:

```json
{
    "stats": {
        "logins": 17,
        "rps": 1,
        "ssp-version": "1.19.8"
    },
    "logins_per_rp": {
        "https://sp.aai-test.garr.it/shibboleth": 17
    }
}
```

These files can be sent to IDEM as an attachment.

## Authors
 * Marco Malavolti (marco.malavolti@garr.it)
