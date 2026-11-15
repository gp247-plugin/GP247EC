<?php
// Add new helper function here

if (! function_exists('gp247ec_is_rtl')) {
    /**
     * Determine whether the active locale's admin-configured language is
     * flagged RTL (`admin_language.rtl`).
     *
     * WHY a local helper: no core helper exposes the `rtl` column, and a
     * template keeps its PHP inside its own folder, so RTL is resolved here
     * with the public AdminLanguage::getListActive() method.
     *
     * @return bool True when the active language's `rtl` flag is set.
     */
    function gp247ec_is_rtl(): bool
    {
        $locale = app()->getLocale();
        $language = \GP247\Core\Models\AdminLanguage::getListActive()->get($locale);

        return (bool) ($language->rtl ?? false);
    }
}
