<?php

namespace Solr\ValueFormatter;

/**
 * ValueFormatter to for Contributor field specifically for facets. Will ignore URI values and also values that include "Fashion Institute of Technology, State University of New York" which isn't particularly helpful.
 */
class Contributor implements ValueFormatterInterface
{
    public function getLabel()
    {
        return 'Contributor';
    }

    public function format($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return null;
        } elseif (str_contains(strtolower($value), strtolower("Fashion Institute of Technology"))) {
            return null;
        } else {
            return $value;
        }
    }
}