# ssp-statistics-parser

**The script is applicable to those who have enabled the Authentication Process Filter "[statistics:StatisticsWithAttribute](https://simplesamlphp.org/docs/contrib_modules/statistics/authproc_statisticswithattribute.html)" but not the [SimpleSAMLphp statistics module](https://simplesamlphp.org/docs/contrib_modules/statistics/statistics.html) on the Identity Provider.**

It converts the `simplesamlphp.log` log file into the format compatible with the `statistics` module.

## Instructions

1. Enable `statistics` module `simplesamlphp/config/config.php`:
   * `sudo vim /var/simplesamlphp/config/config.php`
   
     ```php
     'module.enable' => [
        'statistics' => true,
     ],
     ```

2. Configure it:
   * `cp simplesamlphp/modules/statistics/config-templates/*.php simplesamlphp/config/`
   * `vim simplesamlphp/config/module_statistics.php`
   
     ```php
     'inputfile' => '/var/log/simplesamlphp.stat',
     'statdir' => '/var/simplesamlphp/stats/',
     ```

3. Create the `stats` directory and assign the ownership to the apache user:
   * `sudo mkdir /var/simplesamlphp/stats`
   * `sudo chown www-data /var/simplesamlphp/stats`
   
4. Insert `ssp-statistics-parser.py` into the same directory of the `simplesamlphp.log`:
   * `wget "https://raw.githubusercontent.com/ConsortiumGARR/ssp-statistics-parser/main/ssp-statistics-parser.py" -O /var/simplesamlphp/log/ssp-statistics-parser.py`

5. Create the input file `simplesamlphp.stat`:
   * Python 2: `python ssp-statistics-parser.py > /var/log/simplesamlphp.stat`
   * Python 3: `python3 ssp-statistics-parser.py > /var/log/simplesamlphp.stat`

6. Configuring the syntax of the logfile as explained in the [SimpleSAMLphp statistics module documentation](https://simplesamlphp.org/docs/contrib_modules/statistics/statistics.html):
   * `cd simplesamlphp/modules/statistics/bin/`
   * `loganalyzer.php --debug`

7. If check is OK, create statistics with:
   * `sudo loganalyzer.php`

8. Find statistical data on the 'statistics' web page of your SSP IdP administrative panel.

## Authors
 * Marco Malavolti (marco.malavolti@garr.it)
