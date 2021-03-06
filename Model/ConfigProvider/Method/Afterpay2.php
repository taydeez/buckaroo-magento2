<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright Copyright (c) 2016 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 * @license   http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */

namespace TIG\Buckaroo\Model\ConfigProvider\Method;

/**
 * @method getDueDate()
 * @method getSendEmail()
 */
class Afterpay2 extends AbstractConfigProvider
{
    const XPATH_ALLOWED_CURRENCIES               = 'buckaroo/tig_buckaroo_afterpay2/allowed_currencies';

    const XPATH_AFTERPAY2_ACTIVE                 = 'payment/tig_buckaroo_afterpay2/active';
    const XPATH_AFTERPAY2_PAYMENT_FEE            = 'payment/tig_buckaroo_afterpay2/payment_fee';
    const XPATH_AFTERPAY2_PAYMENT_FEE_LABEL      = 'payment/tig_buckaroo_afterpay2/payment_fee_label';
    const XPATH_AFTERPAY2_SEND_EMAIL             = 'payment/tig_buckaroo_afterpay2/send_email';
    const XPATH_AFTERPAY2_ACTIVE_STATUS          = 'payment/tig_buckaroo_afterpay2/active_status';
    const XPATH_AFTERPAY2_ORDER_STATUS_SUCCESS   = 'payment/tig_buckaroo_afterpay2/order_status_success';
    const XPATH_AFTERPAY2_ORDER_STATUS_FAILED    = 'payment/tig_buckaroo_afterpay2/order_status_failed';
    const XPATH_AFTERPAY2_AVAILABLE_IN_BACKEND   = 'payment/tig_buckaroo_afterpay2/available_in_backend';
    const XPATH_AFTERPAY2_DUE_DATE               = 'payment/tig_buckaroo_afterpay2/due_date';
    const XPATH_AFTERPAY2_ALLOWED_CURRENCIES     = 'payment/tig_buckaroo_afterpay2/allowed_currencies';
    const XPATH_AFTERPAY2_BUSINESS               = 'payment/tig_buckaroo_afterpay2/business';
    const XPATH_AFTERPAY2_PAYMENT_METHODS        = 'payment/tig_buckaroo_afterpay2/payment_method';
    const XPATH_AFTERPAY2_HIGH_TAX               = 'payment/tig_buckaroo_afterpay2/high_tax';
    const XPATH_AFTERPAY2_MIDDLE_TAX             = 'payment/tig_buckaroo_afterpay2/middle_tax';
    const XPATH_AFTERPAY2_LOW_TAX                = 'payment/tig_buckaroo_afterpay2/low_tax';
    const XPATH_AFTERPAY2_ZERO_TAX               = 'payment/tig_buckaroo_afterpay2/zero_tax';
    const XPATH_AFTERPAY2_NO_TAX                 = 'payment/tig_buckaroo_afterpay2/no_tax';

    /**
     * @return array
     */
    public function getConfig()
    {
        if (!$this->scopeConfig->getValue(self::XPATH_AFTERPAY2_ACTIVE)) {
            return [];
        }

        $paymentFeeLabel = $this->getBuckarooPaymentFeeLabel(\TIG\Buckaroo\Model\Method\Afterpay2::PAYMENT_METHOD_CODE);

        return [
            'payment' => [
                'buckaroo' => [
                    'afterpay2' => [
                        'sendEmail'         => (bool) $this->getSendEmail(),
                        'paymentFeeLabel'   => $paymentFeeLabel,
                        'allowedCurrencies' => $this->getAllowedCurrencies(),
                        'businessMethod'    => $this->getBusiness(),
                        'paymentMethod'     => $this->getPaymentMethod(),
                    ],
                    'response' => [],
                ],
            ],
        ];
    }

    /**
     * businessMethod 1 = B2C
     * businessMethod 2 = B2B
     * businessMethod 3 = Both
     *
     * @return bool|int
     */
    public function getBusiness()
    {
        $business = (int) $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_BUSINESS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $business ? $business : false;
    }

    /**
     * paymentMethod 1 = afterpayacceptgiro
     * paymentMethod 2 = afterpaydigiaccept
     *
     * @return bool|int
     */
    public function getPaymentMethod()
    {
        $paymentMethod = (int) $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_PAYMENT_METHODS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $paymentMethod ? $paymentMethod : false;
    }

    /**
     * Get the config values for the high tax classes.
     *
     * @return bool|mixed
     */
    public function getHighTaxClasses()
    {
        $taxClasses = $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_HIGH_TAX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $taxClasses ? $taxClasses : false;
    }

    /**
     * Get the config values for the middle tax classes
     *
     * @return bool|mixed
     */
    public function getMiddleTaxClasses()
    {
        $taxClasses = $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_MIDDLE_TAX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $taxClasses ? $taxClasses : false;
    }

    /**
     * Get the config values for the low tax classes
     *
     * @return bool|mixed
     */
    public function getLowTaxClasses()
    {
        $taxClasses = $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_LOW_TAX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $taxClasses ? $taxClasses : false;
    }

    /**
     * Get the config values for the zero tax classes
     *
     * @return bool|mixed
     */
    public function getZeroTaxClasses()
    {
        $taxClasses = $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_ZERO_TAX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $taxClasses ? $taxClasses : false;
    }

    /**
     * Get the config values for the no tax classes
     *
     * @return bool|mixed
     */
    public function getNoTaxClasses()
    {
        $taxClasses = $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_NO_TAX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $taxClasses ? $taxClasses : false;
    }

    /**
     * Get the methods name
     *
     * @param int $method
     *
     * @return bool|string
     */
    public function getPaymentMethodName($method = null)
    {
        $paymentMethodName = false;

        if (!$method) {
            $method = $this->getPaymentMethod();
        }

        if ($method) {
            switch ($method) {
                case '1':
                    $paymentMethodName = 'afterpayacceptgiro';
                    break;
                case '2':
                    $paymentMethodName = 'afterpaydigiaccept';
            }
        }

        return $paymentMethodName;
    }

    /**
     * @return float
     */
    public function getPaymentFee()
    {
        $paymentFee = $this->scopeConfig->getValue(
            self::XPATH_AFTERPAY2_PAYMENT_FEE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $paymentFee ? $paymentFee : false;
    }
}
