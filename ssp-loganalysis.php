<?php

/*
 * ssp-loganalysis
 *
 * This tool operates on STAT logs from a SimpleSAMLphp IdP (both compressed and uncompressed) and is used to collect usage statistics."
 */

/*
 * simplesamlphp.stat - Example log lines parsed by this PHP script:
 *
 * Feb  7 12:32:12 ssp-idp simplesamlphp[27612]: 5 STAT [46ff6971c4] saml20-idp-SSO https://sp.aai-test.garr.it/shibboleth https://ssp-idp.aai-test.garr.it/simplesaml-212/module.php/saml/idp/metadata NA
 * Feb  7 12:32:12 ssp-idp simplesamlphp[27612]: 5 STAT [46ff6971c4] consent nostorage
 * Feb  7 12:32:19 ssp-idp simplesamlphp[27636]: 5 STAT [46ff6971c4] consentResponse rememberNot
 * Feb  9 10:27:19 ssp-idp simplesamlphp[62995]: 5 STAT [2d3387ea6f] User 'admin' successfully authenticated from 90.147.163.3
 */

/* SSP Variables */
$ssp_home_dir = "/var/simplesamlphp";
$version = null;
/* ************* */

function readLinesFromFile($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    if ($extension === 'gz') {
        $file = gzopen($filename, 'r');
    } else {
        $file = fopen($filename, 'r');
    }
    
    $lines = array();
    
    if ($file) {
        while (($line = ($extension === 'gz') ? gzgets($file) : fgets($file)) !== false) {
            if (strpos($line, 'ustar') === false and trim($line) != '') {
                $lines[] = $line;
            }
        }
        if ($extension === 'gz') {
            gzclose($file);
        } else {
            fclose($file);
        }
    }
    return $lines;
}

/* Get SimpleSAMLphp IdP Version */
$ssp_config_lib_file = $ssp_home_dir."/vendor/simplesamlphp/simplesamlphp/lib/SimpleSAML/Configuration.php";
$file_lines = readLinesFromFile($ssp_config_lib_file);
if ($file_lines !== false) {
    foreach ($file_lines as $line) {
      if (str_contains($line, 'public const VERSION =')){
         $version = explode(' ',$line)[8];
         $version = str_replace("'", "", $version);
         $version = str_replace(";", "", $version);
         $version = trim($version);
      }
    }
} else {
    echo "Unable to read $ssp_config_lib_file.\n";
}

/* Get SimpleSAMLphp IdP stats */
if ($argc != 2) {
    echo "Usage: php ssp-loganalysis.php <file_path>\n";
    exit(1);
}

$ssp_stat_file = $argv[1];

if (!file_exists($ssp_stat_file) || !is_readable($ssp_stat_file)) {
    echo "File not found or not readable.\n";
    exit(1);
}

$logins = 0;
$idem_stats = [
   "stats" => [
      "logins" => 0,
      "rps" => 0,
      "ssp-version" => $version,
   ],
   "logins_per_rp" => [],
];

$file_lines = readLinesFromFile($ssp_stat_file);
if ($file_lines !== false) {
    foreach ($file_lines as $line) {
      //print("LINE:".$line);
      $array = explode(' ',$line);
      if ($array[9] == 'saml20-idp-SSO') {
         $idem_stats["stats"]["logins"] += 1;
         $rp = $array[10];   
   
         if (isset($idem_stats["logins_per_rp"][$rp])) {
            $idem_stats["logins_per_rp"][$rp] += 1;
         } else {
   	        $idem_stats["logins_per_rp"][$rp] = 1;
         }
   	 
         $idem_stats["stats"]["rps"] = count($idem_stats["logins_per_rp"]);
      }
    }
} else {
    echo "Unable to read $ssp_config_lib_file.\n";
}

echo json_encode($idem_stats, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

?>
