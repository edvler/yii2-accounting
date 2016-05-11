<?php

namespace edvlerblog\accounting\widgets\autonumeric;

use edvlerblog\accounting\tools\StaticEb;
/**
 * ContactForm is the model behind the contact form.
 */
class AutoNumericLocalized extends \extead\autonumeric\AutoNumeric
{
        public function init()
        {
            $localeenv = StaticEb::getLoacleconv();

            $pSign = null;
            if ($localeenv['p_cs_precedes']) {
                $pSign = 'p';
            } else {
                $pSign = 's';
            }

            $this->pluginOptions = [
                'aSep' => $localeenv['mon_thousands_sep'],
                'aDec' => $localeenv['mon_decimal_point'],
                'aSign' => " " . $localeenv['currency_symbol'],
                'pSign' => $pSign,
                'mDec' => $localeenv['frac_digits']
            ];
            parent::init();
        }
}
