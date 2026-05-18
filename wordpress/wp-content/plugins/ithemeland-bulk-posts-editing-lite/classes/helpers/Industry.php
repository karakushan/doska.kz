<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Industry
{
    public static function get()
    {
        return [
            'Automotive and Transportation' => esc_html__('Automotive', 'ithemeland-bulk-posts-editing-lite'),
            'AdTech and AdNetwork' => esc_html__('AdTech and AdNetwork', 'ithemeland-bulk-posts-editing-lite'),
            'Agency' => esc_html__('Agency', 'ithemeland-bulk-posts-editing-lite'),
            'B2B Software' => esc_html__('B2B Software', 'ithemeland-bulk-posts-editing-lite'),
            'B2C Internet Services' => esc_html__('B2C Internet Services', 'ithemeland-bulk-posts-editing-lite'),
            'Classifieds' => esc_html__('Classifieds', 'ithemeland-bulk-posts-editing-lite'),
            'Consulting and Market Research' => esc_html__('Consulting and Market Research', 'ithemeland-bulk-posts-editing-lite'),
            'CPG, Food and Beverages' => esc_html__('CPG', 'ithemeland-bulk-posts-editing-lite'),
            'Education' => esc_html__('Education', 'ithemeland-bulk-posts-editing-lite'),
            'Education (student)' => esc_html__('Education (Student)', 'ithemeland-bulk-posts-editing-lite'),
            'Equity Research' => esc_html__('Equity Research', 'ithemeland-bulk-posts-editing-lite'),
            'Financial services' => esc_html__('Financial Services', 'ithemeland-bulk-posts-editing-lite'),
            'Gambling / Gaming' => esc_html__('Gambling and Gaming', 'ithemeland-bulk-posts-editing-lite'),
            'Hedge Funds and Asset Management' => esc_html__('Hedge Funds and Asset Management', 'ithemeland-bulk-posts-editing-lite'),
            'Investment Banking' => esc_html__('Investment Banking', 'ithemeland-bulk-posts-editing-lite'),
            'Logistics and Shipping' => esc_html__('Logistics and Shipping', 'ithemeland-bulk-posts-editing-lite'),
            'Payments' => esc_html__('Payments', 'ithemeland-bulk-posts-editing-lite'),
            'Pharma and Healthcare' => esc_html__('Pharma and Healthcare', 'ithemeland-bulk-posts-editing-lite'),
            'Private Equity and Venture Capital' => esc_html__('Private Equity and Venture Capital', 'ithemeland-bulk-posts-editing-lite'),
            'Media and Entertainment' => esc_html__('Publishers and Media', 'ithemeland-bulk-posts-editing-lite'),
            'Government Public Sector & Non Profit' => esc_html__('Public Sector, Non Profit, Fraud and Compliance', 'ithemeland-bulk-posts-editing-lite'),
            'Retail / eCommerce' => esc_html__('Retail and eCommerce', 'ithemeland-bulk-posts-editing-lite'),
            'Telecom and Hardware' => esc_html__('Telecom', 'ithemeland-bulk-posts-editing-lite'),
            'Travel and Hospitality' => esc_html__('Travel', 'ithemeland-bulk-posts-editing-lite'),
            'Other' => esc_html__('Other', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }
}
