<?php

namespace Solr\ValueFormatter;

/**
 * ValueFormatter to ignore URI values. Useful for indexing for facets, subject with a label and a uri so only the label is indexed.
 */
class RemoveURI implements ValueFormatterInterface
{
    public function getLabel()
    {
        return 'Remove URI';
    }

    public function format($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return null;
        } else {
            return $value;
        }
    }
}
