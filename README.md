# ssp-statistics-parser
The script is needed to those SimpleSAMLphp IdP that did not install 'statistics' module before. It converts the simplesamlphp.log log into a format compatible with the code provided by the module.

## Instructions

1. Enable '`statistics`' module '`simplesamlphp/config/config.php`':
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

3. Create the '`stats`' directory and assign the ownership to the apache user:
   * `sudo mkdir /var/simplesamlphp/stats`
   * `sudo chown www-data /var/simplesamlphp/stats`
   
4. Insert `ssp-statistics-parser.py`' into the same directory of the '`simplesamlphp.log`':
   * `wget "https://raw.githubusercontent.com/ConsortiumGARR/ssp-statistics-parser/main/ssp-statistics-parser.py" -O /var/simplesamlphp/log/ssp-statistics-parser.py`

5. Create the input file "`simplesamlphp.stat`":
   * Python 2: `python ssp-statistics-parser.py > /var/log/simplesamlphp.stat`
   * Python 3: `python3 ssp-statistics-parser.py > /var/log/simplesamlphp.stat`

6. Run the following command and check if the "STAT" word is on the position [3]:
   * `cd simplesamlphp/module/statistics/bin/ ; loganalyzer.php --debug`

7. If check is OK, create statistics with:
   * `sudo loganalyzer.php`

8. Find statistics on the following page of the SSP IdP:
   * `https://<SERVER-NAME>/simplesaml/module.php/statistics/showstats.php`

## Authors
 * Marco Malavolti (marco.malavolti@garr.it)
