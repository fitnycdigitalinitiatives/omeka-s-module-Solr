<?php

namespace Solr\Transformation;

class DateYear extends AbstractTransformation
{
    public function getLabel(): string
    {
        return 'Convert Date to just the Year'; // @translate
    }

    public function transform(array $values, array $transformationData): array
    {
        $transformedValues = [];
        foreach ($values as $value) {
            $stringValue = (string) $value;
            $start = $end = null;
            $matches = [];
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $stringValue)) {
                $transformedValues[] = substr($stringValue, 0, 4);
            } elseif (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $stringValue)) {
                $transformedValues[] = substr($stringValue, 0, 4);
            } elseif (preg_match('|^\s*(\d+)\s*[-/]\s*(\d+)\s*$|', $stringValue, $matches)) {
                $start = $matches[1];
                $end = $matches[2];
                if ($start > $end) {
                    return null;
                }
            } elseif (preg_match('|^\s*(\d+)\s*$|', $stringValue)) {
                $transformedValues[] = $stringValue;
            }

            if (isset($start) && isset($end) && ($start <= $end)) {
                array_push($transformedValues, $start, $end);
            }
        }
        return $transformedValues;
    }
}
