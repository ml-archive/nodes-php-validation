<?php

namespace Nodes\Validation\Rules;

/**
 * Class International.
 *
 * @trait
 */
trait International
{
    /**
     * Validate that attribute is a valid Swift/BIC (Bank Identifier Code).
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return bool
     */
    protected function validateBic($attribute, $value, $parameters)
    {
        // Use provided regex or use fallback
        $pattern = ! empty($parameters[0]) ? $parameters[0] : '/^[A-Za-z]{4,} ?[A-Za-z]{2,} ?[A-Za-z0-9]{2,} ?([A-Za-z0-9]{3,})?$/';

        return (bool) preg_match($pattern, $value);
    }

    /**
     * Validate that attribute is a valid IBAN number (International Bank Account Number).
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return bool
     */
    protected function validateIban($attribute, $value, $parameters)
    {
        // Since IBAN's has different lengths depending on which country
        // the IBAN it comes from. We unfortunately needs this rather ugly
        // array with country codes and a value which corrosponds to the length
        // of the IBAN in that given country
        $countryIbanLengths = [
            'AL' => 28, 'AD' => 24, 'AT' => 20, 'AZ' => 28, 'BH' => 22, 'BE' => 16, 'BA' => 20, 'BR' => 29,
            'BG' => 22, 'CR' => 21, 'HR' => 21, 'CY' => 28, 'CZ' => 24, 'DK' => 18, 'DO' => 28, 'TL' => 23,
            'EE' => 20, 'FO' => 18, 'FI' => 18, 'FR' => 27, 'GE' => 22, 'DE' => 22, 'GI' => 23, 'GR' => 27,
            'GL' => 18, 'GT' => 28, 'HU' => 28, 'IS' => 26, 'IE' => 22, 'IL' => 23, 'IT' => 27, 'JO' => 30,
            'KZ' => 20, 'XK' => 20, 'KW' => 30, 'LV' => 21, 'LB' => 28, 'LI' => 21, 'LT' => 20, 'LU' => 20,
            'MK' => 19, 'MT' => 31, 'MR' => 27, 'MU' => 30, 'MC' => 27, 'MD' => 24, 'ME' => 22, 'NL' => 18,
            'NO' => 15, 'PK' => 24, 'PS' => 29, 'PL' => 28, 'PT' => 25, 'QA' => 29, 'RO' => 24, 'SM' => 27,
            'SA' => 24, 'RS' => 22, 'SK' => 24, 'SI' => 19, 'ES' => 24, 'SE' => 24, 'CH' => 21, 'TN' => 24,
            'TR' => 26, 'AE' => 23, 'GB' => 22, 'VG' => 24, 'DZ' => 24, 'AO' => 25, 'BJ' => 28, 'BF' => 27,
            'BI' => 16, 'CM' => 27, 'CV' => 25, 'IR' => 26, 'CI' => 28, 'MG' => 27, 'ML' => 28, 'MZ' => 25,
            'SN' => 28, 'UA' => 29,
        ];

        // Prepare string for validation
        $value = str_replace(' ', '', strtoupper($value));

        // Grab country of IBAN
        $countryCode = substr($value, 0, 2);

        // Retrieve IBAN length
        $countryIbanLength = ! empty($countryIbanLengths[$countryCode]) ? $countryIbanLengths[$countryCode] : 0;

        // Validate IBAN length
        if ($countryIbanLength == 0 || strlen($value) != $countryIbanLength) {
            return false;
        }

        // Generate data for later checksum calculation
        $checksumData = [];
        foreach (range('A', 'Z') as $letter) {
            $checksumData[$letter] = strval($letter);
        }

        // Prepare for generation of checksum
        $value = substr($value, 4).substr($value, 0, 4);
        $value = str_replace(array_keys($checksumData), array_values($checksumData), $value);

        // Generate checksum
        $checksum = intval($value[0]);
        for ($i = 1; $i < strlen($value); $i++) {
            $checksum *= 10;
            $checksum += intval($value[$i]);
            $checksum %= 97;
        }

        // Verify checksum
        return $checksum == 1;
    }

    /**
     * Validate that attribute is a valid ISBN10 or ISBN13 number (International Standard Book Number).
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return bool
     */
    protected function validateIsbn($attribute, $value, $parameters)
    {
        // Prepare value for validation
        $value = str_replace([' ', '-', '‐', '.'], '', $value);

        // Length of value
        $length = strlen($value);

        // Validate either ISBN10 or ISBN13
        if ($length == 10) {
            return $this->validateIsbn10($attribute, $value, $parameters);
        } elseif ($length == 13) {
            return $this->validateIsbn13($attribute, $value, $parameters);
        }

        return false;
    }

    /**
     * Validate ISBN10 number (International Standard Book Number).
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return bool
     */
    protected function validateIsbn10($attribute, $value, $parameters)
    {
        // Prepare value for validation
        $value = str_replace([' ', '-', '‐', '.'], '', $value);

        // Make sure value is exactly 10 characters
        //
        // Sometimes an ISBN10 number can end on an "X". Therefore we
        // only validate the first 9 characeters as numeric.
        if (strlen($value) != 10 || ! is_numeric(substr($value, -10, 9))) {
            return false;
        }

        // Grab "check digit" for later use
        // Also handle when "X" is the "check digit" instead of an integer
        $checkDigit = substr($value, -1);
        $checkDigit = (strtoupper($checkDigit) == 'X') ? 10 : $checkDigit;

        // Generate checksum
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ($value[$i] * (10 - $i));
        }

        // Add "check digit" to checksum
        $sum += $checkDigit;

        // Verify checksum
        return ($sum % 11) == 0;
    }

    /**
     * Validate ISBN13 number (International Standard Book Number).
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return bool
     */
    protected function validateIsbn13($attribute, $value, $parameters)
    {
        // Prepare value for validation
        $value = str_replace([' ', '-', '‐', '.'], '', $value);

        // Make sure value is exactly 13 characters
        // and that all the characters are numeric
        if (strlen($value) != 13 || ! is_numeric($value)) {
            return false;
        }

        // Grab "check digit" for later use
        $checkDigit = substr($value, -1);

        // Generate checksum
        $sum = $value[0] + ($value[1] * 3) + $value[2] + ($value[3] * 3) +
                $value[4] + ($value[5] * 3) + $value[6] + ($value[7] * 3) +
                $value[8] + ($value[9] * 3) + $value[10] + ($value[11] * 3);

        // Grab "check digit" from checksum for later verification
        $checksumCheckDigit = 10 - ($sum % 10);
        $checksumCheckDigit = ($checksumCheckDigit == 10) ? 0 : $checksumCheckDigit;

        return $checkDigit == $checksumCheckDigit;
    }
}
