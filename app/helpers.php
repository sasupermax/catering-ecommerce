<?php

if (!function_exists('csp_nonce')) {
    /**
     * Get the CSP nonce for the current request
     *
     * @return string
     */
    function csp_nonce(): string
    {
        return request()->attributes->get('csp_nonce', '');
    }
}
